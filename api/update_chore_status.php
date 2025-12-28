<?php
session_start();
require_once __DIR__ . '/../config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$status = trim($_POST['status'] ?? '');

if (!$id || !in_array($status, ['pending', 'completed'])) {
    http_response_code(400);
    exit;
}

$db = get_db();
$stmt = $db->prepare('UPDATE chores SET status = ? WHERE id = ? LIMIT 1');
$stmt->execute([$status, $id]);

echo json_encode(['success' => true]);
?>
