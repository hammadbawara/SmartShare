<?php
session_start();
require_once __DIR__ . '/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $error = 'Please enter a username.';
    } else {
        $db = get_db();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user) {
            // Demo password check: default demo password is 'password123'
            if ($password === 'password123') {
                // Successful login
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'fullname' => $user['fullname'] ?? $user['username'],
                    'role' => $user['role'],
                ];
                // Redirect to role dashboard
                switch ($user['role']) {
                    case 'admin':
                        header('Location: dashboard_admin.php');
                        break;
                    case 'roommate':
                        header('Location: dashboard_roommate.php');
                        break;
                    case 'landlord':
                        header('Location: dashboard_landlord.php');
                        break;
                    case 'maintenance':
                        header('Location: dashboard_maintenance.php');
                        break;
                    default:
                        header('Location: index.php');
                }
                exit;
            } else {
                $error = 'Invalid password (demo password is password123).';
            }
        } else {
            $error = 'User not found.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SmartShare — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3">SmartShare</h3>
                    <p class="text-muted">Sign in with demo credentials. Password: <strong>password123</strong></p>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
                    <?php endif; ?>
                    <form method="post" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-cta">Sign in</button>
                        </div>
                    </form>
                    <hr>
                    <a href="guest.php" class="btn btn-outline-secondary w-100">Continue as Guest</a>
                </div>
            </div>
            <p class="text-center text-muted mt-3">Demo users: admin, roommate1, landlord, maintenance</p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
