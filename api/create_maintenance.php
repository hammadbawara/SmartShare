<?php
session_start();
require_once __DIR__ . '/../config.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit;
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$priority = trim($_POST['priority'] ?? 'medium');
$reported_by = $_SESSION['user']['id'];

if (!$title || !$description) {
    http_response_code(400);
    exit;
}

$db = get_db();
$stmt = $db->prepare('INSERT INTO maintenance_tickets (title, description, priority, reported_by) VALUES (?, ?, ?, ?)');
$stmt->execute([$title, $description, $priority, $reported_by]);

echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
?>
