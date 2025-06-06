<?php
require '../db.php'; // Povezivanje na bazu (putanja prema tvojoj strukturi)

header('Content-Type: application/json');

// Dohvati sve proizvode s potrebnim poljima uključujući sliku
$stmt = $pdo->query("SELECT id, name, price, image FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vrati kao JSON
echo json_encode($products);
?>