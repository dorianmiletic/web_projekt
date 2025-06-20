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
  <link href="style.css" rel="stylesheet" />
</head>
<body>

  <?php include 'header.php'; ?>

  <div class="main-section">
   
    <div class="side-image left">
      <img src="images/guy.jpg" alt="Guy-Manuel de Homem-Christo">
    </div>

    
    <div class="center-content">
      <h1>Dobrodošli na stranicu Daft Punka!</h1>
      <div class="video-container mb-4">
        <video autoplay muted loop playsinline controls>
          <source src="videos/DaftPunkVideo.mp4" type="video/mp4">
          Vaš preglednik ne podržava video tag.
        </video>
      </div>
      <a href="diskografija.php" class="btn btn-primary btn-lg">Istraži diskografiju</a>
    </div>

    
    <div class="side-image right">
      <img src="images/thomas.jpg" alt="Thomas Bangalter">
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
