<?php
session_start();

if (!isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

require '../db.php';

$error = '';
$name = '';
$price = '';
$image = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $image = trim($_POST['image']);

    if ($name === '') {
        $error = 'Naziv proizvoda je obavezan.';
    } elseif (!is_numeric($price) || $price < 0) {
        $error = 'Cijena mora biti pozitivan broj.';
    }

    if (!$error) {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $image]);

        header('Location: dashboard.php');
        exit;
    }
}

ob_start();
?>

<div class="container py-4">
    <h1>Dodaj novi proizvod</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Naziv proizvoda</label>
            <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Cijena (€)</label>
            <input type="number" step="0.01" min="0" id="price" name="price" class="form-control" required value="<?= htmlspecialchars($price) ?>">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">URL slike (opcionalno)</label>
            <input type="text" id="image" name="image" class="form-control" value="<?= htmlspecialchars($image) ?>">
        </div>

        <button type="submit" class="btn btn-success">Spremi</button>
        <a href="dashboard.php" class="btn btn-secondary">Odustani</a>
    </form>
</div>

<?php
$content = ob_get_clean();
$title = "Dodaj proizvod";
include '../layout.php';
?>
