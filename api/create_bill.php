<?php
session_start();
require_once __DIR__ . '/../config.php';
require_role('admin');

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$db = get_db();
$title = trim($_POST['title'] ?? '');
$amount_raw = $_POST['amount'] ?? '';
$amount = is_numeric($amount_raw) ? floatval($amount_raw) : 0;
$category = trim($_POST['category'] ?? '');
$due_date = trim($_POST['due_date'] ?? '');
$created_by = $_SESSION['user']['id'];

$errors = [];
if ($title === '') $errors[] = 'Title is required.';
if ($amount <= 0) $errors[] = 'Amount must be greater than 0.';
if ($category === '') $errors[] = 'Category is required.';
if ($due_date === '') $errors[] = 'Due date is required.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

try {
    $stmt = $db->prepare('INSERT INTO bills (title, amount, category, due_date, created_by) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$title, $amount, $category, $due_date, $created_by]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
