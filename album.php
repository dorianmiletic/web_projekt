<?php
session_start();


$clientId = '5d4ad03ed0ab4ad38f1d18c277a62950';
$clientSecret = '7b396f69f97c4e819fae1228cef4f621';


$album_images = [
    'homework' => 'homework.jpg',
    'discovery' => 'discovery.jpg',
    'human_after_all' => 'human_after_all.jpg',
    'random_access_memories' => 'random_access_memories.jpg',
    'alive_2007' => 'alive_2007.jpg',
    'tron_legacy' => 'tron_legacy.jpg'
];


$albums = [
    'homework' => [
        'title' => 'Homework',
        'year' => 1997,
        'songs' => [
            'Da Funk',
            'Around the World',
            'Revolution 909',
            'Phoenix',
            'Rollin\' & Scratchin\'',
            'Teachers',
            'High Fidelity',
            'Rock\'n Roll',
            'Burnin\'',
            'Alive'
        ]
    ],
    'discovery' => [
        'title' => 'Discovery',
        'year' => 2001,
        'songs' => [
            'One More Time',
            'Aerodynamic',
            'Digital Love',
            'Harder, Better, Faster, Stronger',
            'Crescendolls',
            'Nightvision',
            'Superheroes',
            'High Life',
            'Something About Us',
            'Voyager',
            'Veridis Quo',
            'Short Circuit',
            'Face to Face',
            'Too Long'
        ]
    ],
    'human_after_all' => [
        'title' => 'Human After All',
        'year' => 2005,
        'songs' => [
            'Human After All',
            'The Prime Time of Your Life',
            'Robot Rock',
            'Steam Machine',
            'Make Love',
            'The Brainwasher',
            'On/Off',
            'Television Rules the Nation',
            'Technologic',
            'Emotion'
        ]
    ],
    'random_access_memories' => [
        'title' => 'Random Access Memories',
        'year' => 2013,
        'songs' => [
            'Give Life Back to Music',
            'The Game of Love',
            'Giorgio by Moroder',
            'Within',
            'Instant Crush',
            'Lose Yourself to Dance',
            'Touch',
            'Get Lucky',
            'Beyond',
            'Motherboard',
            'Fragments of Time',
            'Doin\' It Right',
            'Contact'
        ]
    ],
    'alive_2007' => [
        'title' => 'Alive 2007',
        'year' => 2007,
        'songs' => [
            'Robot Rock / Oh Yeah',
            'Touch It / Technologic',
            'Television Rules the Nation / Crescendolls',
            'Too Long / Steam Machine',
            'Around the World / Harder, Better, Faster, Stronger',
            'Burnin\' / Too Long',
            'Face to Face / Short Circuit',
            'One More Time / Aerodynamic',
            'Aerodynamic Beats / Forget About the World',
            'Prime Time of Your Life / Brainwasher / Rollin\' & Scratchin\' / Alive',
            'Da Funk / Daftendirekt',
            'Superheroes / Human After All / Rock\'n Roll'
        ]
    ],
    'tron_legacy' => [
        'title' => 'Tron: Legacy',
        'year' => 2010,
        'songs' => [
            'Overture',
            'The Grid',
            'The Son of Flynn',
            'Recognizer',
            'Armory',
            'Arena',
            'Rinzler',
            'The Game Has Changed',
            'Outlands',
            'Adagio for Tron',
            'Noir',
            'Disc Wars',
            'C.L.U.',
            'Arrival',
            'Flynn Lives',
            'Tron Legacy (End Titles)',
            'Finale'
        ]
    ]
];


function getSpotifyAccessToken($clientId, $clientSecret) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode($clientId . ':' . $clientSecret),
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return isset($data['access_token']) ? $data['access_token'] : null;
}


function getSpotifyTrackId($trackName, $albumName, $accessToken) {
    $query = urlencode("track:$trackName album:$albumName artist:Daft Punk");
    $url = "https://api.spotify.com/v1/search?q=$query&type=track&limit=1";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return isset($data['tracks']['items'][0]['id']) ? $data['tracks']['items'][0]['id'] : null;
}


$album_key = isset($_GET['album']) ? strtolower(trim($_GET['album'])) : '';

if (!array_key_exists($album_key, $albums)) {
    $album = null;
    $error = "Album nije pronađen. Provjerite URL parametar: '$album_key'";
} else {
    $album = $albums[$album_key];
    $error = null;

  
    if (!isset($_SESSION['spotify_track_ids'][$album_key])) {
        $accessToken = getSpotifyAccessToken($clientId, $clientSecret);
        if ($accessToken) {
            $_SESSION['spotify_track_ids'][$album_key] = [];
            foreach ($album['songs'] as $song) {
                $trackId = getSpotifyTrackId($song, $album['title'], $accessToken);
                $_SESSION['spotify_track_ids'][$album_key][$song] = $trackId;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $album ? htmlspecialchars($album['title']) : 'Album'; ?> - Daft Punk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .spotify-embed iframe {
            width: 100%;
            height: 80px;
            border: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container py-5">
    <?php if ($album): ?>
        <h1 class="mb-4 text-center"><?php echo htmlspecialchars($album['title']); ?> (<?php echo $album['year']; ?>)</h1>
        <div class="row">
            <div class="col-md-4">
                <img src="images/<?php echo isset($album_images[$album_key]) ? $album_images[$album_key] : 'placeholder.jpg'; ?>" 
                     class="img-fluid mb-3" 
                     alt="<?php echo htmlspecialchars($album['title']); ?>">
            </div>
            <div class="col-md-8">
                <h3>Pjesme:</h3>
                <ul class="list-group">
                    <?php foreach ($album['songs'] as $song): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?php echo htmlspecialchars($song); ?></span>
                            <?php
                            $trackId = isset($_SESSION['spotify_track_ids'][$album_key][$song]) ? $_SESSION['spotify_track_ids'][$album_key][$song] : null;
                            if ($trackId):
                            ?>
                                <div class="spotify-embed">
                                    <iframe src="https://open.spotify.com/embed/track/<?php echo htmlspecialchars($trackId); ?>" 
                                            allow="encrypted-media" 
                                            allowfullscreen></iframe>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">Nije dostupno na Spotifyju</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php else: ?>
        <h1 class="mb-4 text-center">Greška</h1>
        <p class="text-center"><?php echo htmlspecialchars($error); ?></p>
        <p class="text-center"><a href="diskografija.php" class="btn btn-primary">Povratak na diskografiju</a></p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>