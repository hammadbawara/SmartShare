<?php
session_start();
require_once __DIR__ . '/config.php';
require_role('admin');
$db = get_db();
$usersCount = $db->query('SELECT COUNT(*) AS c FROM users')->fetch()['c'] ?? 0;
$billsCount = $db->query('SELECT COUNT(*) AS c FROM bills')->fetch()['c'] ?? 0;
$choresCount = $db->query('SELECT COUNT(*) AS c FROM chores')->fetch()['c'] ?? 0;
$ticketsCount = $db->query('SELECT COUNT(*) AS c FROM maintenance_tickets')->fetch()['c'] ?? 0;

$statusRows = $db->query('SELECT IFNULL(status, "unknown") AS status, COUNT(*) AS c FROM bills GROUP BY status')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<?php include __DIR__ . '/inc_nav.php'; ?>

<header class="bg-white border-bottom py-4 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 mb-0">Admin Dashboard</h1>
      <div class="text-muted">Overview & quick actions</div>
    </div>
    <div>
      <a href="finances.php" class="btn btn-cta">Create Bill</a>
      <a href="users.php" class="btn btn-outline-secondary ms-2">Manage Users</a>
    </div>
  </div>
</header>

<div class="container">
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card shadow-sm"><div class="card-body">
        <h6 class="text-muted">Users</h6>
        <div class="h4 mb-0"><?=$usersCount?></div>
      </div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm"><div class="card-body">
        <h6 class="text-muted">Bills</h6>
        <div class="h4 mb-0"><?=$billsCount?></div>
      </div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm"><div class="card-body">
        <h6 class="text-muted">Chores</h6>
        <div class="h4 mb-0"><?=$choresCount?></div>
      </div></div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm"><div class="card-body">
        <h6 class="text-muted">Tickets</h6>
        <div class="h4 mb-0"><?=$ticketsCount?></div>
      </div></div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">System Overview</h5>
          <div class="chart-wrap"><canvas id="overviewBar"></canvas></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Bills by Status</h5>
          <div class="chart-wrap"><canvas id="billsDoughnut"></canvas></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  // Overview Bar Chart
  const ctxBar = document.getElementById('overviewBar');
  if(ctxBar) {
    new Chart(ctxBar, {
      type: 'bar',
      data: {
        labels: ['Users','Bills','Chores','Tickets'],
        datasets: [{
          label: 'Total Count',
          data: [<?=$usersCount?>, <?=$billsCount?>, <?=$choresCount?>, <?=$ticketsCount?>],
          backgroundColor: ['#3b82f6','#bf0000','#10b981','#f59e0b']
        }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });
  }

  // Bills Doughnut Chart
  const ctxDoc = document.getElementById('billsDoughnut');
  if(ctxDoc) {
    let bLabels = <?php echo json_encode(array_column($statusRows,'status')); ?>;
    let bData = <?php echo json_encode(array_column($statusRows,'c')); ?>;
    
    if(bLabels.length === 0) {
        bLabels = ['No Data']; bData = [1];
    }

    new Chart(ctxDoc, {
      type: 'doughnut',
      data: {
        labels: bLabels,
        datasets: [{ data: bData, backgroundColor: ['#bf0000','#f97316','#10b981','#94a3b8'] }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  }
});
</script>
</body>
</html>