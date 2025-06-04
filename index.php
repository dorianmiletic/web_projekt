<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daft Punk - Fan Stranica</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    h1, h2 {
      margin-top: 1rem;
    }
    footer {
      position: relative;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<body>

  <?php include 'header.php'; ?>

  <div class="container py-4">
    <div class="row g-4">
      <main class="col-lg-8 col-md-7 col-sm-12">
        <h1>Dobrodošli na fan stranicu Daft Punka!</h1>
        <p>Daft Punk je legendarni francuski elektronski duo poznat po svojim inovativnim zvukovima i prepoznatljivim kacigama. Duo su činili Thomas Bangalter i Guy-Manuel de Homem-Christo.</p>

        <h2>Top 10 pjesama</h2>
        <ol class="list-group list-group-numbered">
          <li class="list-group-item">Get Lucky</li>
          <li class="list-group-item">One More Time</li>
          <li class="list-group-item">Around the World</li>
          <li class="list-group-item">Harder, Better, Faster, Stronger</li>
          <li class="list-group-item">Instant Crush</li>
          <li class="list-group-item">Lose Yourself to Dance</li>
          <li class="list-group-item">Digital Love</li>
          <li class="list-group-item">Da Funk</li>
          <li class="list-group-item">Technologic</li>
          <li class="list-group-item">Something About Us</li>
        </ol>
      </main>

      <aside class="col-lg-4 col-md-5 col-sm-12">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">O izvođaču</h5>
            <p class="card-text">Daft Punk djelovao je od 1993. do 2021. godine, ostavljajući neizbrisiv trag u elektronskoj i popularnoj glazbi.</p>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Spotify Player</h5>
            <iframe style="border-radius:12px" src="https://open.spotify.com/embed/artist/4tZwfgrHOc3mvqYlEYSvVi" width="100%" height="152" frameborder="0" allowfullscreen=""
              allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
          </div>
        </div>
      </aside>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
