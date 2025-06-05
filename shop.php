<?php
session_start();
require 'db.php';

// Dohvati proizvode iz baze
$products = [];
$stmt = $pdo->query("SELECT * FROM products");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $products[$row['id']] = $row;
}

// Inicijalizacija košarice
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Dodavanje u košaricu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];
    if (isset($products[$productId])) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]++;
        } else {
            $_SESSION['cart'][$productId] = 1;
        }
        $message = "Dodano u košaricu: " . htmlspecialchars($products[$productId]['name']);
    }
}

// Uklanjanje iz košarice
if (isset($_GET['remove'])) {
    $removeId = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$removeId])) {
        unset($_SESSION['cart'][$removeId]);
        $message = "Proizvod uklonjen iz košarice.";
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <title>Shop - Daft Punk Merch</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
<?php include 'header.php'; ?>

<div class="container py-4 flex-grow-1">
    <h1>Shop - Daft Punk Merch</h1>

    <?php if (!empty($message)): ?>
      <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Proizvodi -->
        <div class="col-md-8">
            <h2>Proizvodi</h2>
            <div class="row">
                <?php foreach ($products as $id => $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($product['image'])): ?>
                          <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text">Cijena: <?= number_format($product['price'], 2) ?> €</p>
                            <form method="post" class="mt-auto">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <button type="submit" class="btn btn-primary w-100">Dodaj u košaricu</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Košarica -->
        <div class="col-md-4">
            <h2>Košarica</h2>
            <?php if (empty($_SESSION['cart'])): ?>
                <p>Košarica je prazna.</p>
            <?php else: ?>
                <ul class="list-group mb-3">
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $prodId => $qty):
                        if (!isset($products[$prodId])) continue;
                        $prod = $products[$prodId];
                        $subtotal = $prod['price'] * $qty;
                        $total += $subtotal;
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <?= htmlspecialchars($prod['name']) ?> x <?= $qty ?>
                        </div>
                        <div>
                            <span><?= number_format($subtotal, 2) ?> €</span>
                            <a href="?remove=<?= $prodId ?>" class="btn btn-sm btn-danger ms-2">X</a>
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
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
