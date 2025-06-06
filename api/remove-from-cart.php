<?php
session_start();

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nedostaje product_id']);
    exit;
}

$productId = (int)$input['product_id'];

if (!isset($_SESSION['cart'][$productId])) {
    echo json_encode(['success' => false, 'message' => 'Proizvod nije u košarici']);
    exit;
}

unset($_SESSION['cart'][$productId]);

echo json_encode(['success' => true, 'message' => 'Proizvod uklonjen iz košarice']);
?>