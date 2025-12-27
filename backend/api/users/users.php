<?php
/**
 * Users API Endpoint (Admin Only)
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../utils/validation.php';
require_once __DIR__ . '/../../utils/security.php';
require_once __DIR__ . '/../../middleware/auth.php';

setCorsHeaders();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $user = requireAdmin(); // Only admins
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            handleGetUsers($db, $user);
            break;
        case 'POST':
            handleCreateUser($db, $user);
            break;
        case 'PUT':
            handleUpdateUser($db, $user);
            break;
        case 'DELETE':
            handleDeleteUser($db, $user);
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

function handleGetUsers($db, $user) {
    $includeInactive = $_GET['include_inactive'] ?? false;
    
    $sql = "SELECT id, username, email, full_name, role, avatar, phone, lease_start, lease_end, is_active, created_at FROM users";
    
    if (!$includeInactive) {
        $sql .= " WHERE is_active = TRUE";
    }
    
    $sql .= " ORDER BY role, full_name";
    
    $stmt = $db->query($sql);
    $users = $stmt->fetchAll();
    
    // Get activity count for each user
    foreach ($users as &$u) {
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM activity_log WHERE user_id = ?");
        $stmt->execute([$u['id']]);
        $u['activity_count'] = $stmt->fetch()['count'];
    }
    
    sendSuccess(['users' => $users]);
}

function handleCreateUser($db, $user) {
    $data = getRequestData();
    $errors = validateRequired($data, ['username', 'email', 'password', 'full_name', 'role']);
    
    if (!empty($errors)) {
        sendValidationError($errors);
    }
    
    if (!validateEmail($data['email'])) {
        sendValidationError(['email' => 'Invalid email address']);
    }
    
    $passwordError = validatePassword($data['password']);
    if ($passwordError) {
        sendValidationError(['password' => $passwordError]);
    }
    
    // Check if username or email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$data['username'], $data['email']]);
    if ($stmt->fetch()) {
        sendError('Username or email already exists', [], 409);
    }
    
    $stmt = $db->prepare("
        INSERT INTO users (username, email, password, full_name, role, avatar, phone, lease_start, lease_end)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Generate avatar initials
    $nameParts = explode(' ', $data['full_name']);
    $avatar = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
    
    $stmt->execute([
        sanitizeString($data['username']),
        sanitizeEmail($data['email']),
        hashPassword($data['password']),
        sanitizeString($data['full_name']),
        $data['role'],
        $avatar,
        sanitizeString($data['phone'] ?? ''),
        $data['lease_start'] ?? null,
        $data['lease_end'] ?? null
    ]);
    
    $userId = $db->lastInsertId();
    logActivity('create_user', 'user', $userId, "Created user: {$data['username']}");
    
    sendSuccess(['id' => $userId], 'User created successfully', 201);
}

function handleUpdateUser($db, $user) {
    if (!isset($_GET['id'])) {
        sendError('User ID is required');
    }
    
    $userId = (int)$_GET['id'];
    $data = getRequestData();
    
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $targetUser = $stmt->fetch();
    
    if (!$targetUser) {
        sendNotFound('User not found');
    }
    
    $updates = [];
    $params = [];
    
    if (isset($data['full_name'])) {
        $updates[] = "full_name = ?";
        $params[] = sanitizeString($data['full_name']);
    }
    
    if (isset($data['email'])) {
        if (!validateEmail($data['email'])) {
            sendValidationError(['email' => 'Invalid email address']);
        }
        $updates[] = "email = ?";
        $params[] = sanitizeEmail($data['email']);
    }
    
    if (isset($data['phone'])) {
        $updates[] = "phone = ?";
        $params[] = sanitizeString($data['phone']);
    }
    
    if (isset($data['role'])) {
        $updates[] = "role = ?";
        $params[] = $data['role'];
    }
    
    if (isset($data['is_active'])) {
        $updates[] = "is_active = ?";
        $params[] = (bool)$data['is_active'];
    }
    
    if (isset($data['lease_start'])) {
        $updates[] = "lease_start = ?";
        $params[] = $data['lease_start'];
    }
    
    if (isset($data['lease_end'])) {
        $updates[] = "lease_end = ?";
        $params[] = $data['lease_end'];
    }
    
    if (empty($updates)) {
        sendError('No valid updates provided');
    }
    
    $params[] = $userId;
    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    logActivity('update_user', 'user', $userId, "Updated user information");
    
    sendSuccess(null, 'User updated successfully');
}

function handleDeleteUser($db, $user) {
    if (!isset($_GET['id'])) {
        sendError('User ID is required');
    }
    
    $userId = (int)$_GET['id'];
    
    if ($userId === $user['id']) {
        sendError('Cannot delete your own account');
    }
    
    $stmt = $db->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $targetUser = $stmt->fetch();
    
    if (!$targetUser) {
        sendNotFound('User not found');
    }
    
    // Soft delete by setting is_active = FALSE
    $stmt = $db->prepare("UPDATE users SET is_active = FALSE WHERE id = ?");
    $stmt->execute([$userId]);
    
    // Alternatively, hard delete (uncomment if preferred):
    // $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    // $stmt->execute([$userId]);
    
    logActivity('delete_user', 'user', $userId, "Deactivated user: {$targetUser['username']}");
    
    sendSuccess(null, 'User deleted successfully');
}
