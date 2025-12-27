<?php
/**
 * Logout API Endpoint
 * POST /backend/api/auth/logout.php
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/security.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendMethodNotAllowed(['POST']);
}

try {
    // Require authentication
    $user = requireAuth();
    
    // Log activity
    logActivity('logout', null, null, 'User logged out');
    
    // Delete session from database
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM sessions WHERE id = ?");
    $stmt->execute([session_id()]);
    
    // Destroy PHP session
    destroySession();
    
    sendSuccess(null, 'Logout successful');
    
} catch (Exception $e) {
    if (APP_DEBUG) {
        sendServerError($e->getMessage());
    } else {
        sendServerError('An error occurred during logout');
    }
}
