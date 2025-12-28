<?php
session_start();
require_once __DIR__ . '/config.php';
require_login();

$db = get_db();
$isAdmin = $_SESSION['user']['role'] === 'admin';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Maintenance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<?php include __DIR__ . '/inc_nav.php'; ?>

<header class="bg-white border-bottom py-4 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 mb-0">Maintenance Tickets</h1>
      <div class="text-muted">Report and track maintenance issues</div>
    </div>
    <?php if ($isAdmin): ?>
      <button class="btn btn-cta" data-bs-toggle="modal" data-bs-target="#createTicketModal">Create Ticket</button>
    <?php endif; ?>
  </div>
</header>

<div class="container">
  <div class="row g-3 mb-4">
    <?php
    $stats = $db->query('SELECT COUNT(*) AS total, SUM(CASE WHEN status="completed" THEN 1 ELSE 0 END) AS completed, SUM(CASE WHEN priority="high" THEN 1 ELSE 0 END) AS high FROM maintenance_tickets')->fetch();
    ?>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Total Tickets</h6>
          <div class="h4 mb-0"><?=$stats['total']?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Completed</h6>
          <div class="h4 mb-0"><?=$stats['completed']?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">High Priority</h6>
          <div class="h4 mb-0"><?=$stats['high']?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Tickets</h5>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead><tr><th>ID</th><th>Title</th><th>Priority</th><th>Status</th><th>Reported By</th><th>Created</th><?php if ($isAdmin): ?><th>Actions</th><?php endif; ?></tr></thead>
          <tbody>
            <?php
            $tickets = $db->query('SELECT mt.id, mt.title, mt.priority, mt.status, u.fullname, mt.created_at FROM maintenance_tickets mt JOIN users u ON mt.reported_by = u.id ORDER BY mt.created_at DESC')->fetchAll();
            foreach ($tickets as $t):
              $priorityColor = $t['priority'] === 'high' ? 'danger' : ($t['priority'] === 'medium' ? 'warning' : 'info');
              $statusColor = $t['status'] === 'completed' ? 'success' : ($t['status'] === 'in-progress' ? 'primary' : 'secondary');
            ?>
              <tr>
                <td><?=$t['id']?></td>
                <td><?=htmlspecialchars($t['title'])?></td>
                <td><span class="badge bg-<?=$priorityColor?>"><?=ucfirst($t['priority'])?></span></td>
                <td><span class="badge bg-<?=$statusColor?>"><?=ucfirst(str_replace('-', ' ', $t['status']))?></span></td>
                <td><?=htmlspecialchars($t['fullname'])?></td>
                <td><?=$t['created_at']?></td>
                <?php if ($isAdmin): ?>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <button class="btn btn-outline-primary change-status" data-id="<?=$t['id']?>" data-bs-toggle="dropdown">Change</button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item set-status" href="#" data-id="<?=$t['id']?>" data-status="open">Open</a></li>
                        <li><a class="dropdown-item set-status" href="#" data-id="<?=$t['id']?>" data-status="in-progress">In Progress</a></li>
                        <li><a class="dropdown-item set-status" href="#" data-id="<?=$t['id']?>" data-status="completed">Completed</a></li>
                      </ul>
                    </div>
                    <button class="btn btn-sm btn-outline-warning change-priority" data-id="<?=$t['id']?>" data-priority="<?=$t['priority']?>">
                      Priority
                    </button>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Create Ticket Modal -->
<?php if ($isAdmin): ?>
<div class="modal fade" id="createTicketModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Maintenance Ticket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="createTicketForm">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select">
              <option>low</option>
              <option selected>medium</option>
              <option>high</option>
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
<?php endif; ?>

<!-- Priority Modal -->
<div class="modal fade" id="priorityModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Priority</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="btn-group-vertical w-100" role="group">
          <button type="button" class="btn btn-outline-info text-start set-priority" data-priority="low">Low</button>
          <button type="button" class="btn btn-outline-warning text-start set-priority" data-priority="medium">Medium</button>
          <button type="button" class="btn btn-outline-danger text-start set-priority" data-priority="high">High</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('createTicketForm')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const res = await fetch('api/create_maintenance.php', {method: 'POST', body: formData});
  const data = await res.json();
  if (data.success) location.reload();
  else alert('Error');
});

let currentTicketId = null;

document.querySelectorAll('.change-priority').forEach(btn => {
  btn.addEventListener('click', (e) => {
    currentTicketId = e.target.dataset.id;
    new bootstrap.Modal(document.getElementById('priorityModal')).show();
  });
});

document.querySelectorAll('.set-priority').forEach(btn => {
  btn.addEventListener('click', async (e) => {
    e.preventDefault();
    const priority = e.target.dataset.priority;
    const res = await fetch('api/update_maintenance.php', {
      method: 'POST',
      body: new URLSearchParams({id: currentTicketId, priority})
    });
    if (res.ok) location.reload();
  });
});

document.querySelectorAll('.set-status').forEach(link => {
  link.addEventListener('click', async (e) => {
    e.preventDefault();
    const id = e.target.dataset.id;
    const status = e.target.dataset.status;
    const res = await fetch('api/update_maintenance.php', {
      method: 'POST',
      body: new URLSearchParams({id, status})
    });
    if (res.ok) location.reload();
  });
});
</script>
</body>
</html>
