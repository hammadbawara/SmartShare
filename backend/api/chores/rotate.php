<?php
/**
 * Chore Rotation API Endpoint
 * POST /backend/api/chores/rotate.php
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendMethodNotAllowed(['POST']);
}

try {
    $user = requireRole('admin');
    $db = getDB();
    
    // Get all active roommates and admin
    $stmt = $db->query("
        SELECT id FROM users 
        WHERE role IN ('admin', 'roommate') AND is_active = TRUE
        ORDER BY id
    ");
    $users = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($users) < 2) {
        sendError('Need at least 2 active users to rotate chores');
    }
    
    // Get upcoming week's chores
    $nextWeekStart = date('Y-m-d', strtotime('next monday'));
    $nextWeekEnd = date('Y-m-d', strtotime($nextWeekStart . ' +6 days'));
    
    $stmt = $db->prepare("
        SELECT * FROM chores 
        WHERE assigned_date BETWEEN ? AND ? AND recurrence = 'weekly'
        ORDER BY assigned_to
    ");
    $stmt->execute([$nextWeekStart, $nextWeekEnd]);
    $chores = $stmt->fetchAll();
    
    // Rotate assignments
    foreach ($chores as $chore) {
        $currentIndex = array_search($chore['assigned_to'], $users);
        $nextIndex = ($currentIndex + 1) % count($users);
        $nextUserId = $users[$nextIndex];
        
        $stmt = $db->prepare("
            UPDATE chores 
            SET assigned_to = ?
            WHERE id = ?
        ");
        $stmt->execute([$nextUserId, $chore['id']]);
    }
    
    logActivity('rotate_chores', null, null, 'Rotated chore assignments');
    
    sendSuccess(null, 'Chores rotated successfully');
    
} catch (Exception $e) {
    if (APP_DEBUG) {
        sendServerError($e->getMessage());
    } else {
        sendServerError('An error occurred rotating chores');
    }
}
