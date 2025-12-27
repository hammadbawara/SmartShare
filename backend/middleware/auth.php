<?php
/**
 * Authentication Middleware
 * Session validation and role-based access control
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/response.php';
require_once __DIR__ . '/../utils/security.php';

/**
 * Initialize and validate session
 */
function requireAuth() {
    secureSessionStart();
    
    if (!isset($_SESSION['user_id'])) {
        sendUnauthorized('Please log in to access this resource');
    }
    
    // Validate session in database
    $db = getDB();
    $stmt = $db->prepare("
        SELECT u.*, s.last_activity 
        FROM users u
        JOIN sessions s ON u.id = s.user_id
        WHERE u.id = ? AND s.id = ? AND u.is_active = TRUE
    ");
    
    $stmt->execute([$_SESSION['user_id'], session_id()]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Invalid session
        destroySession();
        sendUnauthorized('Invalid session. Please log in again');
    }
    
    // Check session timeout
    $lastActivity = strtotime($user['last_activity']);
    $timeout = SESSION_LIFETIME;
    
    if ((time() - $lastActivity) > $timeout) {
        destroySession();
        sendUnauthorized('Session expired. Please log in again');
    }
    
    // Update last activity
    $stmt = $db->prepare("UPDATE sessions SET last_activity = NOW() WHERE id = ?");
    $stmt->execute([session_id()]);
    
    // Store user in global for easy access
    $GLOBALS['current_user'] = $user;
    
    return $user;
}

/**
 * Require specific role(s)
 */
function requireRole($allowedRoles) {
    $user = requireAuth();
    
    if (!is_array($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }
    
    if (!in_array($user['role'], $allowedRoles)) {
        sendForbidden('You do not have permission to access this resource');
    }
    
    return $user;
}

/**
 * Require admin role
 */
function requireAdmin() {
    return requireRole('admin');
}

/**
 * Get current authenticated user
 */
function getCurrentUser() {
    if (!isset($GLOBALS['current_user'])) {
        return null;
    }
    return $GLOBALS['current_user'];
}

/**
 * Check if user has role
 */
function hasRole($role) {
    $user = getCurrentUser();
    if (!$user) {
        return false;
    }
    return $user['role'] === $role;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return hasRole('admin');
}

/**
 * Log user activity
 */
function logActivity($action, $entityType = null, $entityId = null, $description = null) {
    $user = getCurrentUser();
    if (!$user) {
        return;
    }
    
    try {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO activity_log 
            (user_id, action, entity_type, entity_id, description, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $user['id'],
            $action,
            $entityType,
            $entityId,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    } catch (Exception $e) {
        // Log activity failure shouldn't break the request
        error_log("Failed to log activity: " . $e->getMessage());
    }
}

/**
 * Check if request is from authenticated user
 */
function isAuthenticated() {
    secureSessionStart();
    return isset($_SESSION['user_id']);
}

/**
 * Optional auth - doesn't fail if not authenticated
 */
function optionalAuth() {
    if (!isAuthenticated()) {
        return null;
    }
    
    try {
        return requireAuth();
    } catch (Exception $e) {
        return null;
    }
}
