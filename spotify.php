<?php
session_start();
require 'db.php';

// Spotify API podaci
$client_id = '5d4ad03ed0ab4ad38f1d18c277a62950';
$client_secret = '7b396f69f97c4e819fae1228cef4f621';

// Dohvati access token
$auth = base64_encode("$client_id:$client_secret");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Content-Type: application/x-www-form-urlencoded"
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$access_token = $data['access_token'] ?? null;

$tracks = [];

if ($access_token) {
    // Pretraži top pjesme od Daft Punk
    $artist_id = '4tZwfgrHOc3mvqYlEYSvVi'; // Daft Punk Spotify ID
    $country = 'HR'; // ili 'US', 'GB'...

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/artists/$artist_id/top-tracks?country=$country");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $access_token"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    $tracks = $result['tracks'] ?? [];
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <title>Spotify - Daft Punk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
<?php include 'header.php'; ?>

<div class="container py-4 flex-grow-1">
    <h1 class="mb-4">Top 10 Daft Punk pjesama na Spotifyju</h1>

    <?php if (empty($tracks)): ?>
        <div class="alert alert-warning">Nije moguće dohvatiti podatke sa Spotify API-ja.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach (array_slice($tracks, 0, 10) as $track): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <?php if (!empty($track['album']['images'][0]['url'])): ?>
                            <img src="<?= htmlspecialchars($track['album']['images'][0]['url']) ?>" class="card-img-top" alt="Album cover">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($track['name']) ?></h5>
                            <p class="card-text">Album: <?= htmlspecialchars($track['album']['name']) ?></p>
                            <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/<?= htmlspecialchars($track['id']) ?>" width="100%" height="80" frameborder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
