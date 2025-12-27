<?php
/**
 * API Response Utilities
 * Consistent JSON response format for all API endpoints
 */

/**
 * Send JSON response
 */
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Send success response
 */
function sendSuccess($data = null, $message = 'Success', $statusCode = 200) {
    sendResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ], $statusCode);
}

/**
 * Send error response
 */
function sendError($message = 'An error occurred', $errors = [], $statusCode = 400) {
    sendResponse([
        'success' => false,
        'message' => $message,
        'errors' => $errors
    ], $statusCode);
}

/**
 * Send validation error response
 */
function sendValidationError($errors, $message = 'Validation failed') {
    sendError($message, $errors, 422);
}

/**
 * Send unauthorized response
 */
function sendUnauthorized($message = 'Unauthorized access') {
    sendError($message, [], 401);
}

/**
 * Send forbidden response
 */
function sendForbidden($message = 'Access denied') {
    sendError($message, [], 403);
}

/**
 * Send not found response
 */
function sendNotFound($message = 'Resource not found') {
    sendError($message, [], 404);
}

/**
 * Send method not allowed response
 */
function sendMethodNotAllowed($allowedMethods = []) {
    header('Allow: ' . implode(', ', $allowedMethods));
    sendError('Method not allowed', [], 405);
}

/**
 * Send internal server error
 */
function sendServerError($message = 'Internal server error') {
    sendError($message, [], 500);
}

/**
 * Set CORS headers
 */
function setCorsHeaders() {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    
    // Allow from same origin or localhost during development
    if (APP_DEBUG || strpos($origin, APP_URL) === 0) {
        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    }
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}
