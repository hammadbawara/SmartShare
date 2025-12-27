<?php
/**
 * Chores API Endpoint
 * GET    /backend/api/chores/chores.php - Get all chores
 * POST   /backend/api/chores/chores.php - Create new chore
 * PUT    /backend/api/chores/chores.php?id={id} - Update chore (mark complete/incomplete)
 * DELETE /backend/api/chores/chores.php?id={id} - Delete chore
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/validation.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $user = requireRole(['admin', 'roommate']);
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            handleGetChores($db, $user);
            break;
        case 'POST':
            handleCreateChore($db, $user);
            break;
        case 'PUT':
            handleUpdateChore($db, $user);
            break;
        case 'DELETE':
            handleDeleteChore($db, $user);
            break;
        default:
            sendMethodNotAllowed(['GET', 'POST', 'PUT', 'DELETE']);
    }
    
} catch (Exception $e) {
    if (APP_DEBUG) {
        sendServerError($e->getMessage());
    } else {
        sendServerError('An error occurred processing your request');
    }
}

function handleGetChores($db, $user) {
    $status = $_GET['status'] ?? 'all';
    $week = $_GET['week'] ?? date('Y-W');
    
    // Calculate week date range
    $date = new DateTime();
    $date->setISODate(substr($week, 0, 4), substr($week, 5));
    $startDate = $date->format('Y-m-d');
    $date->modify('+6 days');
    $endDate = $date->format('Y-m-d');
    
    $sql = "
        SELECT 
            c.*,
            u.full_name as assigned_to_name,
            u.avatar as assigned_to_avatar,
            cr.full_name as created_by_name
        FROM chores c
        JOIN users u ON c.assigned_to = u.id
        JOIN users cr ON c.created_by = cr.id
        WHERE c.assigned_date BETWEEN ? AND ?
    ";
    
    if ($status === 'completed') {
        $sql .= " AND c.is_completed = TRUE";
    } elseif ($status === 'pending') {
        $sql .= " AND c.is_completed = FALSE";
    }
    
    $sql .= " ORDER BY c.due_date ASC, c.created_at ASC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$startDate, $endDate]);
    $chores = $stmt->fetchAll();
    
    // Calculate completion rate
    $totalChores = count($chores);
    $completedChores = count(array_filter($chores, fn($c) => $c['is_completed']));
    $completionRate = $totalChores > 0 ? ($completedChores / $totalChores) * 100 : 0;
    
    sendSuccess([
        'chores' => $chores,
        'stats' => [
            'total' => $totalChores,
            'completed' => $completedChores,
            'pending' => $totalChores - $completedChores,
            'completionRate' => round($completionRate, 1)
        ],
        'period' => [
            'week' => $week,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]
    ]);
}

function handleCreateChore($db, $user) {
    if ($user['role'] !== 'admin') {
        sendForbidden('Only administrators can create chores');
    }
    
    $data = getRequestData();
    $errors = validateRequired($data, ['title', 'assigned_to', 'due_date']);
    
    if (!empty($errors)) {
        sendValidationError($errors);
    }
    
    $stmt = $db->prepare("
        INSERT INTO chores (title, description, assigned_to, assigned_date, due_date, recurrence, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        sanitizeString($data['title']),
        sanitizeString($data['description'] ?? ''),
        (int)$data['assigned_to'],
        $data['assigned_date'] ?? date('Y-m-d'),
        $data['due_date'],
        $data['recurrence'] ?? 'weekly',
        $user['id']
    ]);
    
    $choreId = $db->lastInsertId();
    logActivity('create_chore', 'chore', $choreId, "Created chore: {$data['title']}");
    
    sendSuccess(['id' => $choreId], 'Chore created successfully', 201);
}

function handleUpdateChore($db, $user) {
    if (!isset($_GET['id'])) {
        sendError('Chore ID is required');
    }
    
    $choreId = (int)$_GET['id'];
    $data = getRequestData();
    
    $stmt = $db->prepare("SELECT * FROM chores WHERE id = ?");
    $stmt->execute([$choreId]);
    $chore = $stmt->fetch();
    
    if (!$chore) {
        sendNotFound('Chore not found');
    }
    
    if (isset($data['is_completed'])) {
        $isCompleted = (bool)$data['is_completed'];
        
        $stmt = $db->prepare("
            UPDATE chores 
            SET is_completed = ?, completed_date = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $isCompleted,
            $isCompleted ? date('Y-m-d') : null,
            $choreId
        ]);
        
        logActivity(
            $isCompleted ? 'complete_chore' : 'uncomplete_chore',
            'chore',
            $choreId,
            "Marked chore as " . ($isCompleted ? 'completed' : 'incomplete')
        );
        
        sendSuccess(null, 'Chore updated successfully');
    }
    
    sendError('No valid updates provided');
}

function handleDeleteChore($db, $user) {
    if ($user['role'] !== 'admin') {
        sendForbidden('Only administrators can delete chores');
    }
    
    if (!isset($_GET['id'])) {
        sendError('Chore ID is required');
    }
    
    $choreId = (int)$_GET['id'];
    
    $stmt = $db->prepare("SELECT title FROM chores WHERE id = ?");
    $stmt->execute([$choreId]);
    $chore = $stmt->fetch();
    
    if (!$chore) {
        sendNotFound('Chore not found');
    }
    
    $stmt = $db->prepare("DELETE FROM chores WHERE id = ?");
    $stmt->execute([$choreId]);
    
    logActivity('delete_chore', 'chore', $choreId, "Deleted chore: {$chore['title']}");
    
    sendSuccess(null, 'Chore deleted successfully');
}
