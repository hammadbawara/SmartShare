<?php
/**
 * Validation Utilities
 * Input validation and sanitization helpers
 */

/**
 * Validate required fields
 */
function validateRequired($data, $requiredFields) {
    $errors = [];
    
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }
    
    return $errors;
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate number
 */
function validateNumber($value, $min = null, $max = null) {
    if (!is_numeric($value)) {
        return false;
    }
    
    $num = floatval($value);
    
    if ($min !== null && $num < $min) {
        return false;
    }
    
    if ($max !== null && $num > $max) {
        return false;
    }
    
    return true;
}

/**
 * Validate date
 */
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Validate enum (value in list)
 */
function validateEnum($value, $allowedValues) {
    return in_array($value, $allowedValues, true);
}

/**
 * Sanitize string
 */
function sanitizeString($value) {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize integer
 */
function sanitizeInt($value) {
    return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Sanitize float
 */
function sanitizeFloat($value) {
    return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Sanitize email
 */
function sanitizeEmail($email) {
    return filter_var($email, FILTER_SANITIZE_EMAIL);
}

/**
 * Get request data (handles JSON and form data)
 */
function getRequestData() {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    
    if (strpos($contentType, 'application/json') !== false) {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        return $data ?? [];
    }
    
    return array_merge($_GET, $_POST);
}

/**
 * Validate password strength
 */
function validatePassword($password, $minLength = 8) {
    if (strlen($password) < $minLength) {
        return "Password must be at least $minLength characters long";
    }
    
    return null; // Valid
}
