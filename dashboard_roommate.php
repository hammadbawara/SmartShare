<?php
session_start();
require_once __DIR__ . '/config.php';
require_role('roommate');
$db = get_db();
$userId = $_SESSION['user']['id'];
$stmt = $db->prepare('SELECT id, title, due_date, status FROM chores WHERE assigned_to = ? ORDER BY due_date');
$stmt->execute([$userId]);
$chores = $stmt->fetchAll();
$bills = $db->query('SELECT COUNT(*) AS c FROM bills WHERE status="unpaid"')->fetch()['c'];
$shoppingItems = $db->query('SELECT COUNT(*) AS c FROM shopping_items WHERE is_purchased=0')->fetch()['c'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Roommate Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<?php include __DIR__ . '/inc_nav.php'; ?>

<header class="bg-white border-bottom py-4 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 mb-0">Welcome, <?=htmlspecialchars($_SESSION['user']['fullname'] ?? $_SESSION['user']['username'])?></h1>
      <div class="text-muted">Roommate Dashboard</div>
    </div>
  </div>
</header>

<div class="container">
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Your Chores</h6>
          <div class="h4 mb-0"><?=count($chores)?></div>
          <small class="text-muted">This month</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Unpaid Bills</h6>
          <div class="h4 mb-0"><?=$bills?></div>
          <small class="text-muted">Awaiting payment</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Shopping Items</h6>
          <div class="h4 mb-0"><?=$shoppingItems?></div>
          <small class="text-muted">Pending purchase</small>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Your Chores</h5>
          <ul class="list-group list-group-flush">
            <?php if (empty($chores)): ?>
              <li class="list-group-item">No chores assigned.</li>
            <?php else: ?>
              <?php foreach ($chores as $c): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong><?=htmlspecialchars($c['title'])?></strong>
                    <div class="text-muted small">Due: <?=$c['due_date']?></div>
                  </div>
                  <span class="badge <?=$c['status'] === 'completed' ? 'bg-success' : 'bg-warning'?>">
                    <?=ucfirst($c['status'])?>
                  </span>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
          <a href="chores.php" class="btn btn-outline-primary btn-sm mt-3 w-100">View All Chores</a>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Quick Actions</h5>
          <div class="d-grid gap-2">
            <a href="finances.php" class="btn btn-outline-primary">📊 View Bills</a>
            <a href="chores.php" class="btn btn-outline-primary">✓ View All Chores</a>
            <a href="shopping.php" class="btn btn-outline-primary">🛒 Shopping List</a>
            <a href="maintenance.php" class="btn btn-outline-primary">🔧 Report Issue</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
