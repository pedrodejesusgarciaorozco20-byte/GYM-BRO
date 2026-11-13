<?php
require_once __DIR__ . '/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GYM-BRO</title>
  <link rel="stylesheet" href="/gymbro/loged/style.css">
</head>
<body>

  <header>
    <div class="logo">
      <img src="/gymbro/Imagenes/LogoGymBro.png" height="100px" width="100px" alt="GYM-BRO">
    </div>

    <div class="nav-links">
      <a href="/gymbro/index.php">Inicio</a>
      <a href="/gymbro/membresias.php">Membresías</a>
      <a href="/gymbro/login.php">Inicia sesión<br>Regístrate</a>
    </div>
  </header>

  <main>

    <div class="carousel">
      <img src="/gymbro/Imagenes/ScrollUno.png" class="active" alt="Imagen 1">
      <img src="https://picsum.photos/id/1023/1200/400" alt="Imagen 2">
      <img src="https://picsum.photos/id/1035/1200/400" alt="Imagen 3">
    </div>

    <section class="section center">
      <h1>¡Bienvenido a GYM-BRO!</h1>

      <p>En GYM-BRO somos más que un gimnasio: somos una comunidad enfocada en el bienestar físico y mental.</p>

      <p>Nuestro objetivo es ayudarte a alcanzar tus metas de salud y condición física en un ambiente motivador, accesible y con la mejor atención personalizada.</p>

      <br><br>

      <p>Ofrecemos entrenamientos guiados, planes de membresía adaptados a cada necesidad, clases dinámicas y un espacio equipado con tecnología de vanguardia.</p>

      <p>Nuestro equipo de entrenadores profesionales te acompaña en cada paso, asegurando que logres resultados de forma segura, eficiente y motivadora.</p>
    </section>

    <section class="section">
      <h2 class="center">Nuestros Planes</h2>

      <div class="row mt-20">

        <a href="/gymbro/membresias.php" style="text-decoration: none; color: inherit;">
          <div class="card">
            <img src="imagenes/basico.png" alt="Plan Básico">
            <h3>BASICO</h3>
            <p><br></p>
            <p>Entrada a la sucursal, clases grupales y asesoria inicial gratuita.</p>
            <p><br></p>
            <p>$299</p>
          </div>
        </a>

        <a href="/gymbro/membresias.php" style="text-decoration: none; color: inherit;">
          <div class="card">
            <img src="/gymbro/Imagenes/medio.png" alt="Plan Estándar">
            <h3>PLAN MEDIO</h3>
            <p><br></p>
            <p>plan basico + 2 sesiones personalizadas.</p>
            <p><br></p>
            <p>$499</p>
          </div>
        </a>

        <a href="/gymbro/membresias.php" style="text-decoration: none; color: inherit;">
          <div class="card">
            <img src="/gymbro/Imagenes/premiumm.webp" alt="Plan Premium">
            <h3>DISFRUTA LO MEJOR</h3>
            <p><br></p>
            <p>Plan Premium.</p>
            <p><br></p>
            <p>$799</p>
          </div>
        </a>

      </div>
    </section>

    <section class="section">
      <h2 class="center">Nuestras Instalaciones</h2>

      <div class="row mt-20">

        <div class="card">
          <img src="/gymbro/Imagenes/Centro.png" alt="Gimnasio">
          <p class="mt-10">Area Fitnes</p>
        </div>

        <div class="card">
          <img src="/gymbro/Imagenes/SanPedro.png" alt="Yoga">
          <p class="mt-10">Area de Caminadoras</p>
        </div>

        <div class="card">
          <img src="/gymbro/Imagenes/SanNicolas.png" alt="Piscina">
          <p class="mt-10">Area de Pesas.</p>
        </div>

      </div>
    </section>

    <section class="section section--muted" id="testimonios">
      <h2 style="text-align: center;">Lo Que Dicen Nuestros Clientes</h2>

      <div class="row mt-20">

        <?php
        $testimonios = $conn->query("
          SELECT nombre, mensaje
          FROM testimonios
          WHERE publicado = 1
          ORDER BY fecha_publicacion DESC
          LIMIT 6
        ");

        if ($testimonios && $testimonios->num_rows > 0):
          while ($testimonio = $testimonios->fetch_assoc()):
        ?>

          <div class="card">
            <p><?= htmlspecialchars($testimonio['mensaje'], ENT_QUOTES, 'UTF-8') ?></p>
            <strong>- <?= htmlspecialchars($testimonio['nombre'], ENT_QUOTES, 'UTF-8') ?></strong>
          </div>

        <?php
          endwhile;
        else:
        ?>

          <div class="card">
            <p>"Me encanta el equipo de máquinas, siempre están en buen estado y el lugar está muy limpio. Además, las clases grupales son muy dinámicas."</p>
            <strong>- Sofía Ramírez</strong>
          </div>

          <div class="card">
            <p>"Excelente ambiente y entrenadores muy preparados. Desde que me inscribí he visto resultados y lo mejor es la motivación que recibo cada día."</p>
            <strong>- Juan Martínez</strong>
          </div>

          <div class="card">
            <p>"Un gimnasio con todo lo necesario: buena ubicación, precios accesibles y atención personalizada. ¡Recomendado al 100%!"</p>
            <strong>- Carlos Hernández</strong>
          </div>

        <?php endif; ?>

      </div>

      <div class="form center">
        <h3>¿Quieres compartir tu experiencia?</h3>
        <p>Inicia sesión para poder publicar tu propio testimonio.</p>
        <a href="/gymbro/login.php" class="btn">Iniciar sesión</a>
      </div>

    </section>

    <section class="section" id="contacto">
      <h2 class="center">Contáctanos</h2>

      <div class="form center">
        <p>Para enviar un mensaje al equipo de GYM-BRO, necesitas iniciar sesión.</p>
        <a href="/gymbro/login.php" class="btn">Iniciar sesión</a>
      </div>
    </section>

  </main>

  <footer>
    <div class="footer-content">

      <div class="footer-section">
        <h3>Contáctanos</h3>
        <p>correo</p>
        <p>Telefono</p>
      </div>

      <div class="footer-section">
        <h3>Redes Sociales</h3>
        <a href="#">Facebook</a>
        <a href="#">Instagram</a>
        <a href="#">Twitter</a>
      </div>

      <div class="footer-section">
        <h3>Ubicación</h3>
        <p>MONTERREY NUEVO LEON</p>
      </div>

    </div>
  </footer>

  <script src="/gymbro/index.js"></script>

</body>
</html>
