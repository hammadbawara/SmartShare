<?php
session_start();
require_once __DIR__ . '/config.php';
require_role('admin');
$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create user
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'roommate';
    if ($username && $fullname && $email) {
        $stmt = $db->prepare('INSERT INTO users (username, fullname, email, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $fullname, $email, $role]);
        header('Location: users.php');
        exit;
    }
}

$users = $db->query('SELECT id, username, fullname, email, role, is_active, created_at FROM users ORDER BY id DESC')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<?php include __DIR__ . '/inc_nav.php'; ?>

<header class="bg-white border-bottom py-4 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 mb-0">Manage Users</h1>
      <div class="text-muted">Create, edit, and remove users</div>
    </div>
    <div>
      <button class="btn btn-cta" data-bs-toggle="modal" data-bs-target="#createUserModal">Create User</button>
    </div>
  </div>
</header>

<div class="container">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Users</h5>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead><tr><th>ID</th><th>Username</th><th>Full name</th><th>Email</th><th>Role</th><th>Active</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td><?=htmlspecialchars($u['id'])?></td>
                <td><?=htmlspecialchars($u['username'])?></td>
                <td><?=htmlspecialchars($u['fullname'])?></td>
                <td><?=htmlspecialchars($u['email'])?></td>
                <td><?=htmlspecialchars($u['role'])?></td>
                <td><?= $u['is_active'] ? 'Yes' : 'No' ?></td>
                <td>
                  <a href="edit_user.php?id=<?=$u['id']?>" class="btn btn-sm btn-outline-primary">Edit</a>
                  <a href="api/delete_user.php?id=<?=$u['id']?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete user?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="fullname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
              <option value="admin">admin</option>
              <option value="roommate">roommate</option>
              <option value="landlord">landlord</option>
              <option value="maintenance">maintenance</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-cta">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
