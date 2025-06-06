<?php
// spotify-top10.php

header('Content-Type: application/json');

// 🔒 Spotify API pristupni podaci
$clientId = '5d4ad03ed0ab4ad38f1d18c277a62950'; // zamijeni ako je drugačiji
$clientSecret = '7b396f69f97c4e819fae1228cef4f621'; // ⚠️ Unesi pravi secret

// 1. Dohvati access token (Client Credentials Flow)
$authUrl = 'https://accounts.spotify.com/api/token';
$credentials = base64_encode("$clientId:$clientSecret");

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $authUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
    CURLOPT_HTTPHEADER => [
        "Authorization: Basic $credentials",
        "Content-Type: application/x-www-form-urlencoded"
    ]
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
if (!isset($data['access_token'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Neuspješno dohvaćanje pristupnog tokena.']);
    exit;
}

$accessToken = $data['access_token'];

// 2. Dohvati top pjesme izvođača (Daft Punk ID: 4tZwfgrHOc3mvqYlEYSvVi)
$artistId = '4tZwfgrHOc3mvqYlEYSvVi';
$apiUrl = "https://api.spotify.com/v1/artists/$artistId/top-tracks?market=HR";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $accessToken"
    ]
]);

$response = curl_exec($ch);
curl_close($ch);

$tracksData = json_decode($response, true);
$tracks = [];

if (isset($tracksData['tracks'])) {
    foreach (array_slice($tracksData['tracks'], 0, 10) as $track) {
    $tracks[] = [
        'id' => $track['id'],
        'name' => $track['name'],
        'artists' => array_map(fn($a) => $a['name'], $track['artists']),
        'image' => $track['album']['images'][0]['url'] ?? null,
        'preview_url' => $track['preview_url'], // opcionalno
        'external_url' => $track['external_urls']['spotify'] ?? null
    ];
}
    echo json_encode(['tracks' => $tracks]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Nije moguće dohvatiti pjesme.']);
}
