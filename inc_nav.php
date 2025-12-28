<?php
// Simple navigation include. Assumes session_start() has been called and config.php loaded.
$user = $_SESSION['user'] ?? null;
?>
<nav class="navbar navbar-expand-lg" style="background: linear-gradient(90deg, rgba(255,255,255,0.98), rgba(255,255,255,0.95));">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="<?php echo $user ? 'dashboard_'.htmlspecialchars($user['role']).'.php' : 'index.php'; ?>">
      <strong>SmartShare</strong>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (!$user): ?>
          <li class="nav-item"><a class="nav-link" href="index.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="guest.php">Guest View</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="dashboard_<?=htmlspecialchars($user['role'])?>.php">Dashboard</a></li>
          <?php if (in_array($user['role'], ['admin','roommate'])): ?>
            <li class="nav-item"><a class="nav-link" href="finances.php">Finances</a></li>
            <li class="nav-item"><a class="nav-link" href="chores.php">Chores</a></li>
            <li class="nav-item"><a class="nav-link" href="shopping.php">Shopping</a></li>
          <?php endif; ?>
          <?php if (in_array($user['role'], ['admin','maintenance','landlord'])): ?>
            <li class="nav-item"><a class="nav-link" href="maintenance.php">Maintenance</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center">
        <?php if ($user): ?>
          <div class="me-3 text-end">
            <div class="small text-muted">Signed in as</div>
            <div><strong><?=htmlspecialchars($user['fullname'] ?? $user['username'])?></strong></div>
            <div class="small text-muted">Role: <?=htmlspecialchars($user['role'])?></div>
          </div>
          <div class="btn-group">
            <a class="btn btn-outline-secondary" href="logout.php">Logout</a>
          </div>
        <?php else: ?>
          <a class="btn btn-cta" href="index.php">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
