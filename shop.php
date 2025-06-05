<?php
session_start();

// Definiraj proizvode (obično dolaze iz baze, ali za početak hardkodirano)
$products = [
    1 => ['name' => 'Daft Punk Majica', 'price' => 25.00],
    2 => ['name' => 'Daft Punk Kapa', 'price' => 15.00],
    3 => ['name' => 'Random Access Memories Album', 'price' => 40.00],
];

// Inicijaliziraj košaricu ako ne postoji
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Dodavanje proizvoda u košaricu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];
    if (isset($products[$productId])) {
        // Ako proizvod već postoji u košarici, povećaj količinu
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]++;
        } else {
            $_SESSION['cart'][$productId] = 1;
        }
        $message = "Dodano u košaricu: " . htmlspecialchars($products[$productId]['name']);
    }
}

// Brisanje proizvoda iz košarice
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
        <div class="col-md-8">
            <h2>Proizvodi</h2>
            <div class="row">
                <?php foreach ($products as $id => $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text">Cijena: <?= number_format($product['price'], 2) ?> €</p>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <button type="submit" class="btn btn-primary">Dodaj u košaricu</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-md-4">
            <h2>Košarica</h2>
            <?php if (empty($_SESSION['cart'])): ?>
                <p>Košarica je prazna.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $prodId => $qty):
                        $prod = $products[$prodId];
                        $subtotal = $prod['price'] * $qty;
                        $total += $subtotal;
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($prod['name']) ?> x <?= $qty ?>
                        <span><?= number_format($subtotal, 2) ?> €</span>
                        <a href="?remove=<?= $prodId ?>" class="btn btn-sm btn-danger ms-2">X</a>
                    </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Ukupno:</strong>
                        <strong><?= number_format($total, 2) ?> €</strong>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
