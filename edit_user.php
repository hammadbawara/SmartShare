<?php
session_start();
require_once __DIR__ . '/config.php';
require_role('admin');
$db = get_db();

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'roommate';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    if ($fullname && $email) {
        $stmt = $db->prepare('UPDATE users SET fullname = ?, email = ?, role = ?, is_active = ? WHERE id = ? LIMIT 1');
        $stmt->execute([$fullname, $email, $role, $is_active, $id]);
        header('Location: users.php');
        exit;
    }
}

$user = $db->prepare('SELECT id, username, fullname, email, role, is_active FROM users WHERE id = ? LIMIT 1');
$user->execute([$id]);
$user = $user->fetch();
if (!$user) {
    header('Location: users.php');
    exit;
}
// Ensure fields exist to avoid undefined index notices
$user_username = $user['username'] ?? '';
$user_fullname = $user['fullname'] ?? '';
$user_email = $user['email'] ?? '';
$user_role = $user['role'] ?? 'roommate';
$user_is_active = isset($user['is_active']) ? (int)$user['is_active'] : 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<?php include __DIR__ . '/inc_nav.php'; ?>
<div class="container py-5">
  <div class="card shadow-sm mx-auto" style="max-width:720px">
    <div class="card-body">
      <h3 class="mb-3">Edit User: <?=htmlspecialchars($user_username)?></h3>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Full name</label>
          <input name="fullname" class="form-control" value="<?=htmlspecialchars($user_fullname)?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control" value="<?=htmlspecialchars($user_email)?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select">
            <option value="admin" <?= $user_role==='admin'?'selected':''?>>admin</option>
            <option value="roommate" <?= $user_role==='roommate'?'selected':''?>>roommate</option>
            <option value="landlord" <?= $user_role==='landlord'?'selected':''?>>landlord</option>
            <option value="maintenance" <?= $user_role==='maintenance'?'selected':''?>>maintenance</option>
          </select>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?= $user_is_active ? 'checked' : ''?> />
          <label class="form-check-label" for="is_active">Active</label>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-cta">Save</button>
          <a href="users.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>