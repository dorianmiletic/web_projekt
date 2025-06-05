<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Daft Punk</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : '' ?>" href="index.php">Početna</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'biografija.php' ? ' active' : '' ?>" href="biografija.php">Biografija</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'diskografija.php' ? ' active' : '' ?>" href="diskografija.php">Diskografija</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'shop.php' ? ' active' : '' ?>" href="shop.php">Web Shop</a>
        </li>

        <?php if (isset($_SESSION['username'])): ?>
          <li class="nav-item">
            <span class="nav-link">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Odjava</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'login.php' ? ' active' : '' ?>" href="login.php">Prijava</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>