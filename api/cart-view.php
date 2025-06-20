<?php
session_start();
require '../db.php';

$products = [];
$stmt = $pdo->query("SELECT * FROM products");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $products[$row['id']] = $row;
}

if (empty($_SESSION['cart'])) {
    echo '<p>Košarica je prazna.</p>';
    exit;
}

$total = 0;
?>
<ul class="list-group mb-3">
<?php foreach ($_SESSION['cart'] as $prodId => $qty):
    if (!isset($products[$prodId])) continue;
    $prod = $products[$prodId];
    $subtotal = $prod['price'] * $qty;
    $total += $subtotal;
?>
<li class="list-group-item d-flex justify-content-between align-items-center">
    <div><?= htmlspecialchars($prod['name']) ?> x <?= $qty ?></div>
    <div>
        <span><?= number_format($subtotal, 2) ?> €</span>
        <a href="#" class="btn btn-sm btn-danger ms-2 remove-from-cart-btn" data-product-id="<?= $prodId ?>">X</a>
    </div>
</li>
<?php endforeach; ?>
<li class="list-group-item d-flex justify-content-between">
    <strong>Ukupno:</strong>
    <strong><?= number_format($total, 2) ?> €</strong>
</li>
</ul>

<?php if (isset($_SESSION['user'])): ?>
<form action="narudzba.php" method="post">
    <button type="submit" class="btn btn-success w-100">Završi narudžbu</button>
</form>
<?php else: ?>
<div class="alert alert-warning">Prijavite se kako biste završili narudžbu.</div>
<?php endif; ?>

<script>

document.querySelectorAll('.remove-from-cart').forEach(btn => {
  btn.addEventListener('click', (e) => {
    e.preventDefault();
    const prodId = btn.getAttribute('data-id');
    fetch('api/remove-from-cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ product_id: prodId })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        updateCart(); 
      } else {
        alert('Greška pri uklanjanju proizvoda.');
      }
    });
  });
});
</script>