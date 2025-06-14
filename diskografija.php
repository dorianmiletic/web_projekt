<?php
session_start();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <title>Diskografija - Daft Punk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container py-5">
  <h1 class="mb-4 text-center">Diskografija Daft Punka</h1>

  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

    <!-- Homework -->
    <div class="col">
      <a href="album.php?album=homework" class="text-decoration-none">
        <div class="card h-100">
          <img src="images/homework.jpg" class="card-img-top" alt="Homework">
          <div class="card-body">
            <h5 class="card-title">Homework</h5>
            <p class="card-text">1997 – Sadrži hitove "Around the World", "Da Funk".</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Discovery -->
    <div class="col">
      <a href="album.php?album=discovery" class="text-decoration-none">
        <div class="card h-100">
          <img src="images/discovery.jpg" class="card-img-top" alt="Discovery">
          <div class="card-body">
            <h5 class="card-title">Discovery</h5>
            <p class="card-text">2001 – "One More Time", "Digital Love", "Harder, Better, Faster, Stronger".</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Human After All -->
    <div class="col">
      <a href="album.php?album=human_after_all" class="text-decoration-none">
        <div class="card h-100">
          <img src="images/human_after_all.jpg" class="card-img-top" alt="Human After All">
          <div class="card-body">
            <h5 class="card-title">Human After All</h5>
            <p class="card-text">2005 – "Robot Rock", "Technologic", "Human After All".</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Random Access Memories -->
    <div class="col">
      <a href="album.php?album=random_access_memories" class="text-decoration-none">
        <div class="card h-100">
          <img src="images/random_access_memories.jpg" class="card-img-top" alt="Random Access Memories">
          <div class="card-body">
            <h5 class="card-title">Random Access Memories</h5>
            <p class="card-text">2013 – "Get Lucky", "Instant Crush", "Touch".</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Alive 2007 -->
    <div class="col">
      <a href="album.php?album=alive_2007" class="text-decoration-none">
        <div class="card h-100">
          <img src="images/alive_2007.jpg" class="card-img-top" alt="Alive 2007">
          <div class="card-body">
            <h5 class="card-title">Alive 2007</h5>
            <p class="card-text">2007 – Kultni live nastup iz Pariza, miks klasičnih hitova.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Tron: Legacy -->
    <div class="col">
      <a href="album.php?album=tron_legacy" class="text-decoration-none">
        <div class="card h-100">
          <img src="images/tron_legacy.jpg" class="card-img-top" alt="Tron: Legacy Soundtrack">
          <div class="card-body">
            <h5 class="card-title">Tron: Legacy</h5>
            <p class="card-text">2010 – Filmski soundtrack za Disneyjev Tron: Legacy.</p>
          </div>
        </div>
      </a>
    </div>

  </div>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>