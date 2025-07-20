<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Imágenes - Soo Bahk Do Argentina</title>
  <link rel="icon" href="Imagenes/LogoPin.webp" type="image/webp" />
  <link rel="stylesheet" href="styles.css" />
  <link rel="canonical" href="https://www.worldmoodukkwan.ar/imagenes.php">
  <!-- Tailwind con plugin aspect-ratio -->
  <script src="https://cdn.tailwindcss.com?plugins=aspect-ratio"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    body, html { font-family:'Roboto',sans-serif; background-color:#0c1a3c; }
    img { cursor:pointer; }
    #lightbox {
      position:fixed; top:0; left:0; right:0; bottom:0;
      background:rgba(0,0,0,0.85);
      display:none; flex-direction:column;
      justify-content:center; align-items:center;
      z-index:9999;
    }
    #lightbox img {
      max-width:90%; max-height:80%;
      border-radius:8px; margin-bottom:1rem;
      box-shadow:0 0 20px rgba(255,255,255,0.2);
    }
    #lightbox .controls {
      display:flex; gap:2rem;
    }
    #lightbox .controls button,
    #lightbox .close {
      background:none; border:none; color:white;
      font-size:2rem; cursor:pointer; user-select:none;
    }
    #lightbox .close {
      position:absolute; top:20px; right:30px; font-size:2.5rem;
    }
  </style>
</head>
<body class="bg-blue-950 text-white min-h-screen flex flex-col items-center">

  <!-- Menú -->
  <div id="menu-container" class="w-full"></div>
  <script>
    fetch("menu.html")
      .then(r => r.text())
      .then(html => {
        document.getElementById("menu-container").innerHTML = html;
        const t = document.getElementById('menu-toggle'),
              m = document.getElementById('mobile-menu');
        if (t && m) t.addEventListener('click', () => m.classList.toggle('hidden'));
      });
  </script>

  <main class="flex-grow w-full px-2 py-4 sm:px-4 sm:py-8">
    <h1 class="text-2xl font-bold mb-8 text-center text-white">Galerías de Imágenes</h1>

    <?php
    $directorio = "Galerias";

    function buscarImagenPortada($dir) {
      foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = "$dir/$item";
        if (is_dir($path)) {
          if ($res = buscarImagenPortada($path)) return $res;
        } elseif (preg_match('/\.(jpe?g|png|webp|gif)$/i', $item)) {
          return $path;
        }
      }
      return null;
    }

    function listarTodasImagenes($dir) {
      $imgs = [];
      foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = "$dir/$item";
        if (is_dir($path)) {
          $imgs = array_merge($imgs, listarTodasImagenes($path));
        } elseif (preg_match('/\.(jpe?g|png|webp|gif)$/i', $item)) {
          $imgs[] = $path;
        }
      }
      return $imgs;
    }

    $lightboxImages = [];

    if (!isset($_GET['album'])) {
      // Vista general: portadas de álbumes
      $dirs = array_filter(glob(__DIR__ . "/$directorio/*"), 'is_dir');
      echo "<div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4'>";
      foreach ($dirs as $dir) {
        $name = basename($dir);
        $fixed = "$dir/portada.webp";
        if (file_exists($fixed)) {
          $url = "$directorio/$name/portada.webp";
        }
        elseif ($p = buscarImagenPortada($dir)) {
          $url = str_replace('\\','/', substr($p, strlen(__DIR__)+1));
        }
        else {
          $url = "Imagenes/LogoPin.webp";
        }
        echo "<a href='?album=" . urlencode($name) . "' class='block text-center'>";
        echo "<div class='aspect-w-4 aspect-h-3 overflow-hidden rounded shadow'>";
        echo "<img src='" . htmlspecialchars($url) . "' class='w-full h-full object-cover transition-transform hover:scale-105'>";
        echo "</div>";
        echo "<p class='mt-2 text-lg font-semibold text-white'>" . htmlspecialchars($name) . "</p>";
        echo "</a>";
      }
      echo "</div>";
    }
    else {
      // Hicieron click en un álbum
      $album = basename($_GET['album']);
      $ruta_album = realpath(__DIR__ . "/$directorio/$album");
      if (!$ruta_album || strpos($ruta_album, __DIR__ . "/$directorio") !== 0) {
        echo "<p class='text-red-500 text-center'>Álbum no encontrado.</p>";
      }
      elseif (!isset($_GET['sub'])) {
        // Vista álbum: portadas de subcarpetas
        echo "<h2 class='text-xl font-semibold mb-4 text-center'>Álbum: " . htmlspecialchars($album) . "</h2>";
        $subs = array_filter(glob("$ruta_album/*"), 'is_dir');
        echo "<div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4'>";
        foreach ($subs as $sub) {
          $subn = basename($sub);
          if ($p = buscarImagenPortada($sub)) {
            $url = str_replace('\\','/', substr($p, strlen(__DIR__)+1));
            echo "<a href='?album=" . urlencode($album) . "&sub=" . urlencode($subn) . "' class='block text-center'>";
            echo "<div class='aspect-w-4 aspect-h-3 overflow-hidden rounded shadow'>";
            echo "<img src='" . htmlspecialchars($url) . "' class='w-full h-full object-cover transition-transform hover:scale-105'>";
            echo "</div>";
            echo "<p class='mt-2 font-semibold text-white'>" . htmlspecialchars($subn) . "</p>";
            echo "</a>";
          }
        }
        echo "</div>";
        echo "<div class='mt-6 text-center'><a href='imagenes.php' class='text-blue-300 underline'>Volver a álbumes</a></div>";
      }
      else {
        // Vista subcarpeta: todas las imágenes con navegación
        $sub = basename($_GET['sub']);
        $ruta_sub = realpath("$ruta_album/$sub");
        if (!$ruta_sub || strpos($ruta_sub, $ruta_album) !== 0) {
          echo "<p class='text-red-500 text-center'>Subcarpeta no encontrada.</p>";
        } else {
          echo "<h2 class='text-xl font-semibold mb-4 text-center'>" .
               htmlspecialchars("$album / $sub") . "</h2>";
          $imgs = listarTodasImagenes($ruta_sub);
          if (empty($imgs)) {
            echo "<p class='text-center'>No hay imágenes en esta carpeta.</p>";
          } else {
            echo "<div class='grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4'>";
            foreach ($imgs as $i => $img) {
              $url = str_replace('\\','/', substr($img, strlen(__DIR__)+1));
              $lightboxImages[] = $url;
              echo "<img src='" . htmlspecialchars($url) . "' class='w-full rounded shadow cursor-pointer lb-item' data-idx='$i'>";
            }
            echo "</div>";
          }
          echo "<div class='mt-6 text-center'><a href='?album=" . urlencode($album) . "' class='text-blue-300 underline'>Volver a subcarpetas</a></div>";
        }
      }
    }
    ?>
  </main>

  <!-- Lightbox -->
  <div id="lightbox">
    <span class="close" onclick="cerrarLightbox()">×</span>
    <img id="lightbox-img" src="" alt="Imagen ampliada" />
    <div class="controls">
      <button onclick="prevImg()">◀</button>
      <button onclick="nextImg()">▶</button>
    </div>
  </div>

  <script>
    const images = <?php echo json_encode($lightboxImages, JSON_UNESCAPED_SLASHES); ?>;
    let current = 0;
    document.querySelectorAll('.lb-item').forEach(el => {
      el.addEventListener('click', () => {
        current = parseInt(el.dataset.idx);
        abrirLightbox(images[current]);
      });
    });
    function abrirLightbox(src) {
      document.getElementById('lightbox-img').src = src;
      document.getElementById('lightbox').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
    function cerrarLightbox() {
      document.getElementById('lightbox').style.display = 'none';
      document.body.style.overflow = '';
    }
    function prevImg() {
      current = (current - 1 + images.length) % images.length;
      abrirLightbox(images[current]);
    }
    function nextImg() {
      current = (current + 1) % images.length;
      abrirLightbox(images[current]);
    }
  </script>

</body>
</html>
