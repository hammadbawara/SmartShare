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
  <title>Shopping</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-app">
<?php include __DIR__ . '/inc_nav.php'; ?>

<header class="bg-white border-bottom py-4 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h3 mb-0">Shopping List</h1>
      <div class="text-muted">Track household shopping</div>
    </div>
    <button class="btn btn-cta" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Item</button>
  </div>
</header>

<div class="container">
  <div class="row g-3 mb-4">
    <?php
    $stats = $db->query('SELECT COUNT(*) AS total, SUM(CASE WHEN is_purchased=1 THEN 1 ELSE 0 END) AS purchased FROM shopping_items')->fetch();
    ?>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Total Items</h6>
          <div class="h4 mb-0"><?=$stats['total']?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Purchased</h6>
          <div class="h4 mb-0"><?=$stats['purchased']?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted">Pending</h6>
          <div class="h4 mb-0"><?=($stats['total'] - $stats['purchased'])?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Items</h5>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead><tr><th>Item</th><th>Category</th><th>Qty</th><th>Added By</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            <?php
            $items = $db->query('SELECT si.id, si.item_name, si.category, si.quantity, si.is_purchased, u.fullname FROM shopping_items si JOIN users u ON si.added_by = u.id ORDER BY si.is_purchased ASC, si.created_at DESC')->fetchAll();
            foreach ($items as $i):
            ?>
              <tr class="<?=$i['is_purchased'] ? 'table-success' : ''?>">
                <td><?=htmlspecialchars($i['item_name'])?></td>
                <td><?=htmlspecialchars($i['category'])?></td>
                <td><?=$i['quantity']?></td>
                <td><?=htmlspecialchars($i['fullname'])?></td>
                <td>
                  <span class="badge <?=$i['is_purchased'] ? 'bg-success' : 'bg-warning'?>">
                    <?=$i['is_purchased'] ? 'Purchased' : 'Pending'?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-primary toggle-item" data-id="<?=$i['id']?>" data-purchased="<?=$i['is_purchased']?>">
                    <?=$i['is_purchased'] ? 'Undo' : 'Mark Bought'?>
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

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Shopping Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addItemForm">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
              <option>Groceries</option>
              <option>Household</option>
              <option>Personal Care</option>
              <option>Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-cta">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('addItemForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const res = await fetch('api/create_shopping_item.php', {method: 'POST', body: formData});
  const data = await res.json();
  if (data.success) location.reload();
  else alert('Error');
});

document.querySelectorAll('.toggle-item').forEach(btn => {
  btn.addEventListener('click', async (e) => {
    const id = e.target.dataset.id;
    const isPurchased = e.target.dataset.purchased === '0' ? 1 : 0;
    const res = await fetch('api/update_shopping_status.php', {
      method: 'POST',
      body: new URLSearchParams({id, is_purchased: isPurchased})
    });
    if (res.ok) location.reload();
  });
});
</script>
</body>
</html>
?>
