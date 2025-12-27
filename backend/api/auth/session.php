<?php
/**
 * Session Check API Endpoint
 * GET /backend/api/auth/session.php
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/security.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendMethodNotAllowed(['GET']);
}

try {
    // Check if user is authenticated
    $user = optionalAuth();
    
    if (!$user) {
        sendSuccess([
            'authenticated' => false,
            'user' => null
        ], 'Not authenticated');
    }
    
    // Prepare user response (exclude password)
    $userResponse = [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'fullName' => $user['full_name'],
        'role' => $user['role'],
        'avatar' => $user['avatar'],
        'phone' => $user['phone'],
        'leaseStart' => $user['lease_start'],
        'leaseEnd' => $user['lease_end']
    ];
    
    // Generate CSRF token
    $csrfToken = generateCsrfToken();
    
    sendSuccess([
        'authenticated' => true,
        'user' => $userResponse,
        'csrfToken' => $csrfToken
    ], 'Session valid');
    
} catch (Exception $e) {
    if (APP_DEBUG) {
        sendServerError($e->getMessage());
    } else {
        sendServerError('An error occurred checking session');
    }
}
