<?php
require 'db.php';

$error = '';
$username = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = $_POST["password"];
  $password_confirm = $_POST["password_confirm"];

  // Server-side validacija
  if (strlen($username) < 3) {
    $error = "Korisničko ime mora imati barem 3 znaka.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Unesite valjani email.";
  } elseif (strlen($password) < 6) {
    $error = "Lozinka mora imati barem 6 znakova.";
  } elseif ($password !== $password_confirm) {
    $error = "Lozinke se ne podudaraju.";
  }

  if (!$error) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    try {
      $stmt->execute([$username, $email, $passwordHash]);
      header("Location: login.php");
      exit;
    } catch (PDOException $e) {
      $error = "Korisničko ime ili email već postoji.";
    }
  }
}

// Output buffering za sadržaj stranice
ob_start();
?>

<style>
  /* Centrirana i sužena forma */
  .form-container {
    max-width: 400px;
    margin: 40px auto;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  /* Dugme za prikaz lozinke bez okvira */
  .password-toggle {
    background: transparent;
    border: none;
    padding: 0;
    cursor: pointer;
    color: #495057;
    font-size: 1.25rem;
    line-height: 1;
  }
  .password-toggle:focus {
    outline: none;
    box-shadow: none;
  }
</style>

<div class="form-container">
  <h2>Registracija</h2>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" onsubmit="return validateForm()">
    <div class="mb-3">
      <label for="username" class="form-label">Korisničko ime</label>
      <input type="text" id="username" name="username" class="form-control" required minlength="3" value="<?= htmlspecialchars($username) ?>">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($email) ?>">
    </div>

    <div class="mb-3 position-relative">
      <label for="password" class="form-label">Lozinka</label>
      <input type="password" id="password" name="password" class="form-control pe-5" required minlength="6">
      <button type="button" class="password-toggle position-absolute end-0 me-2" style="top: 57%;" onclick="togglePassword('password', 'toggleIcon1')" tabindex="-1">
        <i id="toggleIcon1" class="bi bi-eye"></i>
      </button>
    </div>

    <div class="mb-3 position-relative">
      <label for="password_confirm" class="form-label">Ponovi lozinku</label>
      <input type="password" id="password_confirm" name="password_confirm" class="form-control pe-5" required minlength="6">
      <button type="button" class="password-toggle position-absolute end-0 me-2" style="top: 57%;" onclick="togglePassword('password_confirm', 'toggleIcon2')" tabindex="-1">
        <i id="toggleIcon2" class="bi bi-eye"></i>
      </button>
    </div>

    <button type="submit" class="btn btn-primary">Registriraj se</button>
  </form>
</div>

<script>
function togglePassword(fieldId, iconId) {
  const input = document.getElementById(fieldId);
  const icon = document.getElementById(iconId);
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
  }
}

function validateForm() {
  const username = document.getElementById('username').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const password_confirm = document.getElementById('password_confirm').value;

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (username.length < 3) {
    alert('Korisničko ime mora imati barem 3 znaka.');
    return false;
  }
  if (!emailPattern.test(email)) {
    alert('Unesite valjani email.');
    return false;
  }
  if (password.length < 6) {
    alert('Lozinka mora imati barem 6 znakova.');
    return false;
  }
  if (password !== password_confirm) {
    alert('Lozinke se ne podudaraju.');
    return false;
  }
  return true;
}
</script>

<?php
$content = ob_get_clean();
$title = "Registracija - Daft Punk Fan";
include 'layout.php';
?>