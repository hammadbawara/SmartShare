<?php
session_start();
require_once __DIR__ . '/../config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit;
}

$item_name = trim($_POST['item_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$quantity = intval($_POST['quantity'] ?? 1);
$added_by = $_SESSION['user']['id'];

if (!$item_name || !$category) {
    http_response_code(400);
    exit;
}

$db = get_db();
$stmt = $db->prepare('INSERT INTO shopping_items (item_name, category, quantity, added_by) VALUES (?, ?, ?, ?)');
$stmt->execute([$item_name, $category, $quantity, $added_by]);

echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
?>
