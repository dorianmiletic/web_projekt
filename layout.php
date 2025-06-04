<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= isset($title) ? htmlspecialchars($title) : 'Daft Punk - Fan Stranica' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh; /* Full viewport height */
      background-color: #f8f9fa;
    }

    #page-container {
      flex: 1 0 auto; /* raste i zauzima prostor između header i footer */
      padding-top: 1rem;
      padding-bottom: 1rem;
    }

    footer {
      flex-shrink: 0; /* ne smanjuj footer */
      background-color: #212529; /* možeš promijeniti po želji */
      color: white;
      padding: 1rem 0;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <main id="page-container" class="container">
    <?= $content ?>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
