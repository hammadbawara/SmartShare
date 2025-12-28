<?php
session_start();
require_once __DIR__ . '/../config.php';
require_role('admin');

header('Content-Type: application/json');
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing id']);
    exit;
}

try {
    $db = get_db();
    $stmt = $db->prepare('DELETE FROM bills WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
