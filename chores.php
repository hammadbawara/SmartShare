<?php
session_start();
require_once __DIR__ . '/config.php';
require_role(['admin', 'roommate']);

$db = get_db();
$userId = $_SESSION['user']['id'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Chores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<?php include __DIR__ . '/inc_nav.php'; ?>

<header class="bg-white border-bottom py-4 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 mb-0">Chores</h1>
      <div class="text-muted">Manage household chores</div>
    </div>
    <?php if ($user['role'] === 'admin'): ?>
      <button class="btn btn-cta" data-bs-toggle="modal" data-bs-target="#createChoreModal">Create Chore</button>
    <?php endif; ?>
  </div>
</header>

<div class="container">
  <div class="row g-3 mb-4">
    <?php
    $stats = $db->query('SELECT COUNT(*) AS total, SUM(CASE WHEN status="completed" THEN 1 ELSE 0 END) AS completed FROM chores')->fetch();
    ?>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Total Chores</h6>
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
          <h6 class="text-muted">Pending</h6>
          <div class="h4 mb-0"><?=($stats['total'] - $stats['completed'])?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Chores</h5>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead><tr><th>ID</th><th>Title</th><th>Assigned To</th><th>Due Date</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
            <?php
            $chores = $db->query('SELECT c.id, c.title, c.due_date, c.status, u.fullname FROM chores c JOIN users u ON c.assigned_to = u.id ORDER BY c.due_date')->fetchAll();
            foreach ($chores as $c):
            ?>
              <tr>
                <td><?=$c['id']?></td>
                <td><?=htmlspecialchars($c['title'])?></td>
                <td><?=htmlspecialchars($c['fullname'])?></td>
                <td><?=$c['due_date']?></td>
                <td>
                  <span class="badge <?=$c['status'] === 'completed' ? 'bg-success' : 'bg-warning'?>">
                    <?=ucfirst($c['status'])?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-primary toggle-chore" data-id="<?=$c['id']?>" data-status="<?=$c['status']?>">
                    <?=$c['status'] === 'completed' ? 'Mark Pending' : 'Mark Done'?>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Create Chore Modal -->
<div class="modal fade" id="createChoreModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Chore</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="createChoreForm">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Assign To</label>
            <select name="assigned_to" class="form-select" required>
              <?php foreach ($db->query('SELECT id, fullname FROM users WHERE role IN ("admin","roommate")') as $u): ?>
                <option value="<?=$u['id']?>"><?=htmlspecialchars($u['fullname'])?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
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
<script>
document.getElementById('createChoreForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const res = await fetch('api/create_chore.php', {method: 'POST', body: formData});
  const data = await res.json();
  if (data.success) location.reload();
  else alert('Error');
});

document.querySelectorAll('.toggle-chore').forEach(btn => {
  btn.addEventListener('click', async (e) => {
    const id = e.target.dataset.id;
    const status = e.target.dataset.status === 'completed' ? 'pending' : 'completed';
    const res = await fetch('api/update_chore_status.php', {
      method: 'POST',
      body: new URLSearchParams({id, status})
    });
    if (res.ok) location.reload();
  });
});
</script>
</body>
</html>
?>
