<?php
/**
 * Security Utilities
 * CSRF protection, password hashing, and security helpers
 */

/**
 * Generate CSRF token
 */
function generateCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF token
 */
function verifyCsrfToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        return false;
    }
    
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_HASH_ALGO, [
        'cost' => PASSWORD_HASH_COST
    ]);
}

/**
 * Verify password
 * Using plain text comparison for assignment purposes
 */
function verifyPassword($password, $hash) {
    // For assignment: using plain text password comparison
    return $password === $hash;
}

/**
 * Generate random string
 */
function generateRandomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Secure session start
 */
function secureSessionStart() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return true;
    }
    
    // Configure session settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Lax');
    
    // Use HTTPS in production
    if (!APP_DEBUG) {
        ini_set('session.cookie_secure', 1);
    }
    
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'domain' => '',
        'secure' => !APP_DEBUG,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    return session_start();
}

/**
 * Regenerate session ID
 */
function regenerateSession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

/**
 * Destroy session
 */
function destroySession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = [];
        
        // Destroy session cookie
        if (isset($_COOKIE[session_name()])) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
    }
}

/**
 * Rate limiting (simple implementation)
 */
function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 300) {
    if (session_status() === PHP_SESSION_NONE) {
        secureSessionStart();
    }
    
    $key = 'rate_limit_' . $identifier;
    $now = time();
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 1, 'start' => $now];
        return true;
    }
    
    $data = $_SESSION[$key];
    
    // Reset if time window has passed
    if ($now - $data['start'] > $timeWindow) {
        $_SESSION[$key] = ['count' => 1, 'start' => $now];
        return true;
    }
    
    // Check if limit exceeded
    if ($data['count'] >= $maxAttempts) {
        return false;
    }
    
    // Increment counter
    $_SESSION[$key]['count']++;
    return true;
}

/**
 * Get remaining rate limit time
 */
function getRateLimitReset($identifier, $timeWindow = 300) {
    if (session_status() === PHP_SESSION_NONE) {
        secureSessionStart();
    }
    
    $key = 'rate_limit_' . $identifier;
    
    if (!isset($_SESSION[$key])) {
        return 0;
    }
    
    $data = $_SESSION[$key];
    $elapsed = time() - $data['start'];
    $remaining = $timeWindow - $elapsed;
    
    return max(0, $remaining);
}
