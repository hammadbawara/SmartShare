<?php
/**
 * Login API Endpoint
 * POST /backend/api/auth/login.php
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/validation.php';
require_once __DIR__ . '/../../utils/security.php';

setCorsHeaders();

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendMethodNotAllowed(['POST']);
}

try {
    // Get request data
    $data = getRequestData();
    
    // Validate required fields
    $errors = validateRequired($data, ['username', 'password']);
    if (!empty($errors)) {
        sendValidationError($errors);
    }
    
    $username = sanitizeString($data['username']);
    $password = $data['password'];
    
    // Rate limiting
    if (!checkRateLimit('login_' . $username, 5, 300)) {
        $resetTime = getRateLimitReset('login_' . $username, 300);
        sendError(
            'Too many login attempts. Please try again in ' . ceil($resetTime / 60) . ' minutes',
            [],
            429
        );
    }
    
    // Find user by username or email
    $db = getDB();
    $stmt = $db->prepare("
        SELECT * FROM users 
        WHERE (username = ? OR email = ?) AND is_active = TRUE
        LIMIT 1
    ");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    // Verify user exists and password is correct
    if (!$user || !verifyPassword($password, $user['password'])) {
        sendError('Invalid username or password', [], 401);
    }
    
    // Start secure session
    secureSessionStart();
    
    // Regenerate session ID for security
    regenerateSession();
    
    // Store user info in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['login_time'] = time();
    
    // Store session in database
    $sessionId = session_id();
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    // Delete any existing sessions for this user (single session per user)
    $stmt = $db->prepare("DELETE FROM sessions WHERE user_id = ?");
    $stmt->execute([$user['id']]);
    
    // Insert new session
    $stmt = $db->prepare("
        INSERT INTO sessions (id, user_id, ip_address, user_agent, payload, last_activity)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $sessionId,
        $user['id'],
        $ipAddress,
        substr($userAgent, 0, 255),
        json_encode($_SESSION)
    ]);
    
    // Log activity
    $stmt = $db->prepare("
        INSERT INTO activity_log (user_id, action, description, ip_address, user_agent)
        VALUES (?, 'login', 'User logged in', ?, ?)
    ");
    $stmt->execute([$user['id'], $ipAddress, substr($userAgent, 0, 255)]);
    
    // Generate CSRF token
    $csrfToken = generateCsrfToken();
    
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
    
    sendSuccess([
        'user' => $userResponse,
        'csrfToken' => $csrfToken,
        'sessionId' => $sessionId
    ], 'Login successful');
    
} catch (Exception $e) {
    if (APP_DEBUG) {
        sendServerError($e->getMessage());
    } else {
        sendServerError('An error occurred during login');
    }
}
