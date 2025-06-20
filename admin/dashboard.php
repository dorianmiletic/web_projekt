<?php
session_start();


if (!isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] !== true) {
    header('Location: ../login.php');
    exit;
}

require '../db.php';


$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="container py-4">
    <h1>Admin Panel - Proizvodi</h1>
    <a href="add_product.php" class="btn btn-success mb-3">Dodaj novi proizvod</a>

    <?php if (count($products) === 0): ?>
        <p>Nema proizvoda u bazi.</p>
    <?php else: ?>
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                   <th>Slika</th>
                    <th>Naziv</th>
                    <th>Cijena (€)</th>
                    <th>Opcije</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $prod): ?>
                <tr>
                    <td><?= htmlspecialchars($prod['id']) ?></td>
                    <td>
                        <?php if (!empty($prod['image'])): ?>
                          <img src="/web_projekt/<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" style="height: 80px; object-fit: contain;">

                        <?php else: ?>
                            Nema slike
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($prod['name']) ?></td>
                    <td><?= number_format($prod['price'], 2) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $prod['id'] ?>" class="btn btn-primary btn-sm">Uredi</a>
                        <a href="delete_product.php?id=<?= $prod['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Jesi li siguran da želiš obrisati proizvod?');">Obriši</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
$title = "Admin Panel - Proizvodi";
include '../layout.php';
?>