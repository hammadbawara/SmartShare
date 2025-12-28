<?php
session_start();
require_once __DIR__ . '/../config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$is_purchased = intval($_POST['is_purchased'] ?? 0);

if (!$id) {
    http_response_code(400);
    exit;
}

$db = get_db();
$stmt = $db->prepare('UPDATE shopping_items SET is_purchased = ? WHERE id = ? LIMIT 1');
$stmt->execute([$is_purchased, $id]);

echo json_encode(['success' => true]);
?>
