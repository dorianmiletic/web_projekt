<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: shop.php");
    exit;
}

// Dohvati korisnika i košaricu
$userId = $_SESSION['user']['id'];
$cart = $_SESSION['cart'];

// Dohvati proizvode iz baze
$products = [];
$stmt = $pdo->query("SELECT * FROM products");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $products[$row['id']] = $row;
}

try {
    $pdo->beginTransaction();

    // 1. Unesi novu narudžbu
    $stmt = $pdo->prepare("INSERT INTO orders (user_id) VALUES (:user_id)");
    $stmt->execute(['user_id' => $userId]);
    $orderId = $pdo->lastInsertId();

    // 2. Unesi stavke
   $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (:order_id, :product_name, :qty, :price)");

foreach ($cart as $prodId => $qty) {
    if (!isset($products[$prodId])) continue;

    $price = $products[$prodId]['price'];
    $name = $products[$prodId]['name'];

    $stmtItem->execute([
        'order_id' => $orderId,
        'product_name' => $name,
        'qty' => $qty,
        'price' => $price
    ]);
}

    $pdo->commit();

    // Očisti košaricu
    $_SESSION['cart'] = [];

    $success = true;
} catch (Exception $e) {
    $pdo->rollBack();
    $error = "Došlo je do pogreške: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <title>Narudžba</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
<?php include 'header.php'; ?>

<div class="container py-5 flex-grow-1">
    <h1>Narudžba</h1>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">Vaša narudžba je uspješno zaprimljena! Hvala na kupnji.</div>
        <a href="shop.php" class="btn btn-primary">Natrag u shop</a>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <a href="shop.php" class="btn btn-secondary">Natrag</a>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
