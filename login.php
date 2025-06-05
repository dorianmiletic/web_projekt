<?php
session_start();
require 'db.php';



$error = '';
$email = '';

// Ako je forma poslana
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST["email"]);
  $password = $_POST["password"];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Unesite valjani email.";
  } else {
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
      session_start();
      // Spremaš samo što treba:
     $_SESSION["user"] = [
  "id" => $user["id"],
  "username" => $user["username"]
];

      header("Location: index.php");
      exit;
    } else {
      $error = "Neispravna email adresa ili lozinka.";
    }
  }
}

// Output buffering
ob_start();
?>

<style>
  .form-container {
    max-width: 400px;
    margin: 40px auto;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
</style>

<div class="form-container">
  <h2>Prijava</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label for="email" class="form-label">Email adresa</label>
      <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($email) ?>">
    </div>

 <div class="mb-3 position-relative">
  <label for="password" class="form-label">Lozinka</label>
  <input type="password" id="password" name="password" class="form-control pe-5" required>
  <button type="button" class="position-absolute end-0 me-2 border-0 bg-transparent p-0" style="top: 57%;" onclick="togglePassword('password', 'toggleIcon1')" tabindex="-1">
  <i id="toggleIcon1" class="bi bi-eye"></i>
</button>
</div>

    <button type="submit" class="btn btn-primary">Prijavi se</button>
  </form>

  <p class="mt-3">Nemate račun? <a href="register.php">Registrirajte se</a></p>
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
</script>

<?php
$content = ob_get_clean();
$title = "Prijava - Daft Punk Fan";
include 'layout.php';
?>