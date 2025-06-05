<?php
// Spotify API credentials - zamijeni svojim podacima
$clientId = 'TVOJ_CLIENT_ID';
$clientSecret = 'TVOJ_CLIENT_SECRET';

// Dohvati Access Token koristeći Client Credentials Flow
function getAccessToken($clientId, $clientSecret) {
    $url = "https://accounts.spotify.com/api/token";
    $headers = [
        "Authorization: Basic " . base64_encode("$clientId:$clientSecret"),
        "Content-Type: application/x-www-form-urlencoded"
    ];
    $data = "grant_type=client_credentials";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response, true);
    return $json['access_token'] ?? null;
}

// Dohvati top pjesme za Daft Punk
function getTopTracks($accessToken, $artistId = '4tZwfgrHOc3mvqYlEYSvVi', $market = 'US') {
    $url = "https://api.spotify.com/v1/artists/$artistId/top-tracks?market=$market";

    $headers = [
        "Authorization: Bearer $accessToken"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

$token = getAccessToken($clientId, $clientSecret);
$topTracks = $token ? getTopTracks($token) : null;
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8" />
    <title>Spotify - Daft Punk Top 10</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include 'header.php'; ?>

<div class="container py-4">
    <h1>Top 10 trenutno slušani Daft Punk pjesama</h1>

    <?php if (!$token): ?>
        <div class="alert alert-danger">Neuspjelo dohvaćanje Spotify tokena. Provjerite API ključ.</div>
    <?php elseif (!$topTracks || empty($topTracks['tracks'])): ?>
        <div class="alert alert-warning">Nema dostupnih podataka za pjesme.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($topTracks['tracks'] as $track): ?>
                <a href="<?= htmlspecialchars($track['external_urls']['spotify']) ?>" target="_blank" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= htmlspecialchars($track['name']) ?></h5>
                        <small>Poput: <?= number_format($track['popularity']) ?></small>
                    </div>
                    <p class="mb-1">Album: <?= htmlspecialchars($track['album']['name']) ?></p>
                    <small>Trajanje: <?= floor($track['duration_ms'] / 60000) . ':' . str_pad(floor(($track['duration_ms'] % 60000) / 1000), 2, '0', STR_PAD_LEFT) ?></small>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>