<?php
session_start();
require_once __DIR__ . '/../config.php';
require_role('admin');

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: ../users.php');
    exit;
}
$db = get_db();
$stmt = $db->prepare('DELETE FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
header('Location: ../users.php');
exit;
?>