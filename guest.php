<?php
session_start();
// Prevent logged-in users from using guest mode directly
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'] ?? '';
    header('Location: dashboard_' . ($role ? $role : 'roommate') . '.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Guest View</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">SmartShare</a>
    <a class="btn btn-outline-secondary" href="index.php">Login</a>
  </div>
</nav>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="card-title">Guest View</h3>
          <p class="lead">Welcome! This view is for visiting guests — non-sensitive, read-only info.</p>
          <hr>
          <h5>WiFi</h5>
          <div class="mb-3">
            <div class="fw-bold">Network:</div>
            <div>SmartShare-Guest</div>
            <div class="fw-bold mt-2">Password:</div>
            <div>welcome123</div>
          </div>
          <h5>House Rules</h5>
          <ul>
            <li>Be respectful</li>
            <li>Keep shared spaces tidy</li>
            <li>Report maintenance issues</li>
          </ul>
          <div class="mt-3">
            <a href="index.php" class="btn btn-cta">Sign in</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
