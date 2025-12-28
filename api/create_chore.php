<?php
session_start();
require_once __DIR__ . '/../config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit;
}

$title = trim($_POST['title'] ?? '');
$assigned_to = intval($_POST['assigned_to'] ?? 0);
$due_date = trim($_POST['due_date'] ?? '');

if (!$title || !$assigned_to || !$due_date) {
    http_response_code(400);
    exit;
}

$db = get_db();
$stmt = $db->prepare('INSERT INTO chores (title, assigned_to, due_date) VALUES (?, ?, ?)');
$stmt->execute([$title, $assigned_to, $due_date]);

echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
?>
