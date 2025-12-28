<?php
// Database configuration - update these values to match your XAMPP/phpMyAdmin setup
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'smartshare_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function get_db()
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    return $pdo;
}

function is_logged_in()
{
    return isset($_SESSION['user']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function require_role($roles = [])
{
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
    $role = $_SESSION['user']['role'] ?? '';
    if (!in_array($role, (array)$roles, true)) {
        header('Location: index.php');
        exit;
    }
}

?>
