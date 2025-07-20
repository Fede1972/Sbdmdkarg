<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Videos - Soo Bahk Do Argentina</title>
  <link rel="icon" href="Imagenes/LogoPin.webp" type="image/webp" />
  <link rel="stylesheet" href="styles.css" />
  <link rel="canonical" href="https://www.worldmoodukkwan.ar/videos.php">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

  <style>
    body, html {
      font-family: 'Roboto', sans-serif;
      background-image: url('Imagenes/logoDBu.webp');
      background-size: cover;
      background-position: top;
      background-repeat: repeat-y;
      background-color: #0c1a3c;
    }
    video {
      max-height: 400px;
    }
  </style>
</head>

<body class="bg-blue-950 text-white min-h-screen flex flex-col items-center">

  <!-- Menú -->
  <div id="menu-container" class="w-full"></div>
  <script>
    fetch("menu.html")
      .then(res => res.text())
      .then(html => {
        document.getElementById("menu-container").innerHTML = html;
        const toggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('mobile-menu');
        if (toggle && menu) {
          toggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
          });
        }
      });
  </script>

  <!-- Contenido principal -->
  <main class="flex-grow w-full px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-center">Galería de Videos</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
        $videoDir = 'Videos/';
        $videos = glob($videoDir . '*.{mp4,webm,ogg}', GLOB_BRACE);

        if ($videos) {
          foreach ($videos as $video) {
            echo '<div class="bg-blue-900 bg-opacity-60 p-2 rounded-lg shadow-lg w-72 mx-auto">';
            echo '<video controls class="w-full rounded max-h-60">';
            echo '<source src="'.$video.'" type="video/mp4">';
            echo 'Tu navegador no soporta la reproducción de videos.';
            echo '</video>';
            echo '<p class="mt-2 text-sm text-center text-gray-200">'.basename($video).'</p>';
            echo '</div>';
          }
        } else {
          echo '<p class="col-span-3 text-center text-gray-300">No hay videos disponibles.</p>';
        }
      ?>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-blue-900 bg-opacity-80 p-4 text-center text-sm w-full">
    Asociación Argentina de Soo Bahk Do - Moo Duk Kwan – World Moo Duk Kwan ©
  </footer>

</body>
</html>
