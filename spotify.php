<?php
// spotify.php
session_start();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <title>Spotify Top 10 – Daft Punk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .spotify-track {
      margin-bottom: 20px;
    }
    .spotify-embed iframe {
      width: 100%;
      height: 80px;
      border: none;
    }
    .album-cover {
      max-width: 100%;
      height: auto;
      border-radius: 5px;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include 'header.php'; ?>

<div class="container py-4 flex-grow-1">
  <h1 class="mb-4">Top 10 Spotify pjesama – Daft Punk</h1>
  <div id="track-list" class="row"></div>
</div>

<?php include 'footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  fetch('spotify-top10.php')
    .then(response => response.json())
    .then(data => {
      const trackList = document.getElementById('track-list');

      if (data.tracks && data.tracks.length > 0) {
        data.tracks.forEach(track => {
          const col = document.createElement('div');
          col.className = 'col-md-6 spotify-track';

          const card = `
            <div class="card h-100 shadow-sm">
              <div class="row g-0">
                <div class="col-md-4 d-flex align-items-center justify-content-center p-2">
                  <img src="${track.image}" alt="Album cover" class="album-cover img-fluid" />
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">${track.name}</h5>
                    <p class="card-text">🎤 ${track.artists.join(', ')}</p>
                    <div class="spotify-embed">
                      <iframe src="https://open.spotify.com/embed/track/${track.id}" allow="encrypted-media"></iframe>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
          col.innerHTML = card;
          trackList.appendChild(col);
        });
      } else {
        trackList.innerHTML = '<p class="text-muted">Nema dostupnih pjesama.</p>';
      }
    })
    .catch(error => {
      console.error('Greška:', error);
      document.getElementById('track-list').innerHTML = '<p class="text-danger">Greška pri dohvaćanju podataka sa Spotifyja.</p>';
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
