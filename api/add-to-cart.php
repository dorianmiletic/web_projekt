<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nedostaje product_id']);
    exit;
}

$productId = (int)$data['product_id'];

// Dohvati proizvod iz baze
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Proizvod nije pronađen']);
    exit;
}

// Inicijaliziraj košaricu ako ne postoji
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]++;
} else {
    $_SESSION['cart'][$productId] = 1;
}

echo json_encode(['success' => true, 'message' => 'Dodano u košaricu']);
