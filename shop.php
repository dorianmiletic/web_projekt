<?php
session_start();
require 'db.php';

// Dohvati proizvode za prikaz u košarici
$products = [];
$stmt = $pdo->query("SELECT * FROM products");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $products[$row['id']] = $row;
}

// Inicijalizacija košarice
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
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

    <div class="row">
        <!-- Proizvodi -->
        <div class="col-md-8">
            <h2>Proizvodi</h2>
            <div class="row" id="products-container">
                <!-- Proizvodi će se učitati putem JavaScript-a -->
            </div>
        </div>

        <!-- Košarica -->
        <div class="col-md-4">
            <h2>Košarica</h2>
            <div id="cart-container">
              <!-- Košarica će se učitati AJAX-om -->
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Učitaj proizvode i prikaži
  fetch('api/products.php')
    .then(response => response.json())
    .then(products => {
      const container = document.getElementById('products-container');
      container.innerHTML = '';

      products.forEach(product => {
        const col = document.createElement('div');
        col.className = 'col-md-4 mb-4';

        col.innerHTML = `
          <div class="card h-100">
            ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}">` : ''}
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">${product.name}</h5>
              <p class="card-text">Cijena: ${parseFloat(product.price).toFixed(2)} €</p>
              <button class="btn btn-primary w-100 add-to-cart-btn" data-product-id="${product.id}">
                Dodaj u košaricu
              </button>
            </div>
          </div>
        `;
        container.appendChild(col);
      });

      // Poveži evente za dodavanje u košaricu
      setupAddToCartButtons();
    })
    .catch(error => {
      console.error('Greška pri dohvaćanju proizvoda:', error);
      document.getElementById('products-container').innerHTML = '<p class="text-danger">Greška pri učitavanju proizvoda.</p>';
    });

  // Učitaj košaricu i prikaži
  updateCart();
});

// Funkcija za učitavanje i prikaz košarice
function updateCart() {
  fetch('api/cart-view.php')
    .then(res => res.text())
    .then(html => {
      document.getElementById('cart-container').innerHTML = html;
      setupRemoveButtons(); // Poveži evente za uklanjanje
    })
    .catch(err => {
      console.error('Greška pri osvježavanju košarice:', err);
    });
}

// Event handler za dodavanje u košaricu
function setupAddToCartButtons() {
  document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const productId = btn.getAttribute('data-product-id');

      fetch('api/add-to-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          // Mjesto za lijepu notifikaciju, npr. alert ili toast
          // alert(data.message);
          updateCart();
        } else {
          alert("Greška: " + data.message);
        }
      })
      .catch(err => {
        console.error('Greška:', err);
        alert('Došlo je do pogreške.');
      });
    });
  });
}

// Event handler za uklanjanje iz košarice
function setupRemoveButtons() {
  document.querySelectorAll('.remove-from-cart-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const productId = btn.getAttribute('data-product-id');

      fetch('api/remove-from-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          updateCart();
        } else {
          alert("Greška: " + data.message);
        }
      })
      .catch(err => {
        console.error('Greška:', err);
        alert('Došlo je do pogreške.');
      });
    });
  });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
