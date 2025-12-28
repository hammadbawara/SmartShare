<?php
session_start();
require_once __DIR__ . '/config.php';
require_role('landlord');
$db = get_db();
$tickets = $db->query('SELECT id, title, status, priority, created_at FROM maintenance_tickets ORDER BY created_at DESC LIMIT 20')->fetchAll();
$stats = $db->query('SELECT COUNT(*) AS total, SUM(CASE WHEN status="completed" THEN 1 ELSE 0 END) AS completed FROM maintenance_tickets')->fetch();

// Fetch chart data
$prio = $db->query('SELECT IFNULL(priority,"unknown") AS p, COUNT(*) AS c FROM maintenance_tickets GROUP BY p')->fetchAll(PDO::FETCH_ASSOC);
$chartLabels = array_column($prio, 'p');
$chartValues = array_column($prio, 'c');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Landlord Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<?php include __DIR__ . '/inc_nav.php'; ?>

<header class="bg-white border-bottom py-4 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 mb-0">Landlord View</h1>
      <div class="text-muted">Property maintenance overview</div>
    </div>
  </div>
</header>

<div class="container">
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Total Tickets</h6>
          <div class="h4 mb-0"><?=$stats['total'] ?? 0?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Completed</h6>
          <div class="h4 mb-0"><?=$stats['completed'] ?? 0?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">In Progress</h6>
          <div class="h4 mb-0"><?=(($stats['total'] ?? 0) - ($stats['completed'] ?? 0))?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Recent Maintenance Tickets</h5>
      <div class="mb-3 chart-wrap">
        <canvas id="ticketsPriorityLandlord"></canvas>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead><tr><th>ID</th><th>Title</th><th>Priority</th><th>Status</th><th>Created</th></tr></thead>
          <tbody>
            <?php if (empty($tickets)): ?>
              <tr><td colspan="5" class="text-center text-muted">No tickets found.</td></tr>
            <?php else: ?>
              <?php foreach ($tickets as $t):
                $priorityColor = $t['priority'] === 'high' ? 'danger' : ($t['priority'] === 'medium' ? 'warning' : 'info');
                $statusColor = $t['status'] === 'completed' ? 'success' : ($t['status'] === 'in-progress' ? 'primary' : 'secondary');
              ?>
                <tr>
                  <td><?=$t['id']?></td>
                  <td><?=htmlspecialchars($t['title'])?></td>
                  <td><span class="badge bg-<?=$priorityColor?>"><?=ucfirst($t['priority'])?></span></td>
                  <td><span class="badge bg-<?=$statusColor?>"><?=ucfirst(str_replace('-', ' ', $t['status']))?></span></td>
                  <td><?=$t['created_at']?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <a href="maintenance.php" class="btn btn-outline-primary btn-sm mt-3">View All Tickets</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const ctx = document.getElementById('ticketsPriorityLandlord');
  if (!ctx) return;

  let labels = <?php echo json_encode($chartLabels); ?>;
  let data = <?php echo json_encode($chartValues); ?>;

  // Fallback if no data
  if (labels.length === 0) {
    labels = ['No Data'];
    data = [1];
    var colors = ['#e2e8f0'];
  } else {
    var colors = ['#ef4444','#f59e0b','#60a5fa','#94a3b8'];
  }

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: colors
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });
});
</script>
</body>
</html>