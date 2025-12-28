<?php
session_start();
require_once __DIR__ . '/../config.php';
require_role(['admin', 'maintenance']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$status = trim($_POST['status'] ?? '');
$priority = trim($_POST['priority'] ?? '');

if (!$id) {
    http_response_code(400);
    exit;
}

$db = get_db();
$updates = [];
$params = [];

if (in_array($status, ['open', 'in-progress', 'completed'])) {
    $updates[] = 'status = ?';
    $params[] = $status;
}

if (in_array($priority, ['low', 'medium', 'high'])) {
    $updates[] = 'priority = ?';
    $params[] = $priority;
}

if (empty($updates)) {
    http_response_code(400);
    exit;
}

$params[] = $id;
$sql = 'UPDATE maintenance_tickets SET ' . implode(', ', $updates) . ' WHERE id = ? LIMIT 1';
$stmt = $db->prepare($sql);
$stmt->execute($params);

echo json_encode(['success' => true]);
?>
