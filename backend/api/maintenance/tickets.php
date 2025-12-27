<?php
/**
 * Maintenance Tickets API Endpoint
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/validation.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $user = requireAuth(); // All authenticated users can access
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            handleGetTickets($db, $user);
            break;
        case 'POST':
            handleCreateTicket($db, $user);
            break;
        case 'PUT':
            handleUpdateTicket($db, $user);
            break;
        case 'DELETE':
            handleDeleteTicket($db, $user);
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

function handleGetTickets($db, $user) {
    $status = $_GET['status'] ?? 'all';
    
    $sql = "
        SELECT 
            t.*,
            u.full_name as reported_by_name,
            u.avatar as reported_by_avatar,
            a.full_name as assigned_to_name
        FROM maintenance_tickets t
        JOIN users u ON t.reported_by = u.id
        LEFT JOIN users a ON t.assigned_to = a.id
    ";
    
    $params = [];
    
    if ($status !== 'all') {
        $sql .= " WHERE t.status = ?";
        $params[] = $status;
    }
    
    $sql .= " ORDER BY t.priority DESC, t.created_at DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $tickets = $stmt->fetchAll();
    
    sendSuccess(['tickets' => $tickets]);
}

function handleCreateTicket($db, $user) {
    $data = getRequestData();
    $errors = validateRequired($data, ['title', 'description', 'category', 'priority']);
    
    if (!empty($errors)) {
        sendValidationError($errors);
    }
    
    $stmt = $db->prepare("
        INSERT INTO maintenance_tickets (title, description, category, priority, location, reported_by)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        sanitizeString($data['title']),
        sanitizeString($data['description']),
        $data['category'],
        $data['priority'],
        sanitizeString($data['location'] ?? ''),
        $user['id']
    ]);
    
    $ticketId = $db->lastInsertId();
    logActivity('create_ticket', 'maintenance_ticket', $ticketId, "Created ticket: {$data['title']}");
    
    sendSuccess(['id' => $ticketId], 'Maintenance ticket created successfully', 201);
}

function handleUpdateTicket($db, $user) {
    if (!isset($_GET['id'])) {
        sendError('Ticket ID is required');
    }
    
    $ticketId = (int)$_GET['id'];
    $data = getRequestData();
    
    $stmt = $db->prepare("SELECT * FROM maintenance_tickets WHERE id = ?");
    $stmt->execute([$ticketId]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) {
        sendNotFound('Ticket not found');
    }
    
    if (isset($data['status'])) {
        $stmt = $db->prepare("
            UPDATE maintenance_tickets 
            SET status = ?, resolved_date = ?, notes = ?
            WHERE id = ?
        ");
        
        $resolvedDate = ($data['status'] === 'completed') ? date('Y-m-d H:i:s') : null;
        
        $stmt->execute([
            $data['status'],
            $resolvedDate,
            sanitizeString($data['notes'] ?? ''),
            $ticketId
        ]);
        
        logActivity('update_ticket', 'maintenance_ticket', $ticketId, "Updated ticket status to: {$data['status']}");
        
        sendSuccess(null, 'Ticket updated successfully');
    }
    
    if (isset($data['assigned_to'])) {
        if ($user['role'] !== 'admin') {
            sendForbidden('Only administrators can assign tickets');
        }
        
        $stmt = $db->prepare("UPDATE maintenance_tickets SET assigned_to = ? WHERE id = ?");
        $stmt->execute([(int)$data['assigned_to'], $ticketId]);
        
        logActivity('assign_ticket', 'maintenance_ticket', $ticketId, "Assigned ticket to user ID: {$data['assigned_to']}");
        
        sendSuccess(null, 'Ticket assigned successfully');
    }
    
    sendError('No valid updates provided');
}

function handleDeleteTicket($db, $user) {
    if ($user['role'] !== 'admin') {
        sendForbidden('Only administrators can delete tickets');
    }
    
    if (!isset($_GET['id'])) {
        sendError('Ticket ID is required');
    }
    
    $ticketId = (int)$_GET['id'];
    
    $stmt = $db->prepare("SELECT title FROM maintenance_tickets WHERE id = ?");
    $stmt->execute([$ticketId]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) {
        sendNotFound('Ticket not found');
    }
    
    $stmt = $db->prepare("DELETE FROM maintenance_tickets WHERE id = ?");
    $stmt->execute([$ticketId]);
    
    logActivity('delete_ticket', 'maintenance_ticket', $ticketId, "Deleted ticket: {$ticket['title']}");
    
    sendSuccess(null, 'Ticket deleted successfully');
}
