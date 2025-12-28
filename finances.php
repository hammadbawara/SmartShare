<?php
session_start();
require_once __DIR__ . '/config.php';
require_role(['admin', 'roommate']);

$db = get_db();
$user = $_SESSION['user'] ?? [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Finances</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<?php include __DIR__ . '/inc_nav.php'; ?>

<header class="bg-white border-bottom py-4 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 mb-0">Finances</h1>
      <div class="text-muted">Track and manage bills</div>
    </div>
    <?php if ($user['role'] === 'admin'): ?>
      <button class="btn btn-cta" data-bs-toggle="modal" data-bs-target="#createBillModal">Create Bill</button>
    <?php endif; ?>
  </div>
</header>

<div class="container">
  <div class="row g-3 mb-4">
    <?php
    $stats = $db->query('SELECT COUNT(*) AS total, SUM(amount) AS sum_amount, SUM(CASE WHEN status="paid" THEN amount ELSE 0 END) AS paid FROM bills')->fetch();
    ?>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Total Bills</h6>
          <div class="h4 mb-0"><?=$stats['total']?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Total Amount</h6>
          <div class="h4 mb-0">$<?=number_format($stats['sum_amount'] ?? 0, 2)?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Paid</h6>
          <div class="h4 mb-0">$<?=number_format($stats['paid'] ?? 0, 2)?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Outstanding</h6>
          <div class="h4 mb-0">$<?=number_format(($stats['sum_amount'] ?? 0) - ($stats['paid'] ?? 0), 2)?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Bills</h5>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead><tr><th>ID</th><th>Title</th><th>Amount</th><th>Category</th><th>Due Date</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
            <?php
            $bills = $db->query('SELECT id, title, amount, category, due_date, status FROM bills ORDER BY due_date DESC')->fetchAll();
            foreach ($bills as $b):
            ?>
              <tr>
                <td><?=$b['id']?></td>
                <td><?=htmlspecialchars($b['title'])?></td>
                <td>$<?=number_format($b['amount'], 2)?></td>
                <td><?=htmlspecialchars($b['category'])?></td>
                <td><?=$b['due_date']?></td>
                <td>
                  <span class="badge <?=$b['status'] === 'paid' ? 'bg-success' : 'bg-warning'?>">
                    <?=ucfirst($b['status'])?>
                  </span>
                </td>
                <td>
                  <?php if ($user['role'] === 'admin'): ?>
                    <button class="btn btn-sm btn-outline-primary toggle-status" data-id="<?=$b['id']?>" data-status="<?=$b['status']?>">
                      <?=$b['status'] === 'paid' ? 'Mark Unpaid' : 'Mark Paid'?>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-bill" data-id="<?=$b['id']?>">Delete</button>
                  <?php else: ?>
                    <span class="text-muted small">View Only</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Create Bill Modal -->
<div class="modal fade" id="createBillModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Bill</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="createBillForm">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" step="0.01" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
              <option>Rent</option>
              <option>Utilities</option>
              <option>Internet</option>
              <option>Groceries</option>
              <option>Other</option>
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
document.getElementById('createBillForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const res = await fetch('api/create_bill.php', {method: 'POST', body: formData});
  const data = await res.json();
  if (data.success) {
    location.reload();
  } else {
    alert('Error: ' + (data.error || 'Unknown error'));
  }
});

document.querySelectorAll('.toggle-status').forEach(btn => {
  btn.addEventListener('click', async (e) => {
    const id = e.target.dataset.id;
    const currentStatus = e.target.dataset.status;
    const newStatus = currentStatus === 'paid' ? 'unpaid' : 'paid';
    const res = await fetch('api/update_bill_status.php', {
      method: 'POST',
      body: new URLSearchParams({id, status: newStatus})
    });
    if (res.ok) location.reload();
  });
});

document.querySelectorAll('.delete-bill').forEach(btn => {
  btn.addEventListener('click', async (e) => {
    if (!confirm('Delete this bill?')) return;
    const id = e.target.dataset.id;
    const res = await fetch('api/delete_bill.php?id=' + id);
    if (res.ok) location.reload();
  });
});
</script>
</body>
</html>
?>
