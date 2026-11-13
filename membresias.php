
<?php
require_once __DIR__ . '/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Plan Acceso Total</title>
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
    <a href="/gymbro/login.php">Iniciar sesión</a>
    <a href="/gymbro/signin.php">Regístrate</a>
  </div>
</header>

<main>
  <h1 class="text-center">Membresias</h1>

  <div class="table-container">
    <table>
      <tr>
        <th>Beneficio</th>
        <th>basico</th>
        <th>medio</th>
        <th>premium</th>
      </tr>

      <tr>
        <td class="benefit">Acceso a clases grupales</td>
        <td>✔️</td>
        <td>✔️</td>
        <td>✔️</td>
      </tr>

      <tr>
        <td class="benefit">Asesoría inicial gratuita</td>
        <td>✔️</td>
        <td>✔️</td>
        <td>✔️</td>
      </tr>

      <tr>
        <td class="benefit">Acceso por 1 mes completo</td>
        <td>❌</td>
        <td>✔️</td>
        <td>✔️</td>
      </tr>

      <tr>
        <td class="benefit">2 sesiones de entrenamiento personalizado</td>
        <td>❌</td>
        <td>✔️</td>
        <td>✔️</td>
      </tr>

      <tr>
        <td class="benefit">Acceso a cualquier sucursal</td>
        <td>❌</td>
        <td>❌</td>
        <td>✔️</td>
      </tr>

      <tr>
        <td class="benefit">Entrenamientos ilimitados</td>
        <td>❌</td>
        <td>❌</td>
        <td>✔️</td>
      </tr>

      <tr>
        <td class="benefit">Descuentos en productos</td>
        <td>❌</td>
        <td>❌</td>
        <td>✔️</td>
      </tr>

      <tr>
        <td class="benefit">Precios</td>
        <td>$299</td>
        <td>$499</td>
        <td>$799</td>
      </tr>

      <tr>
        <td></td>

        <td class="text-center">
          <a href="/gymbro/login.php">
            <button class="btn">Iniciar sesión</button>
          </a>
        </td>

        <td class="text-center">
          <a href="/gymbro/login.php">
            <button class="btn">Iniciar sesión</button>
          </a>
        </td>

        <td class="text-center">
          <a href="/gymbro/login.php">
            <button class="btn">Iniciar sesión</button>
          </a>
        </td>
      </tr>
    </table>
  </div>

  <div class="mt-30 text-center">
    <a href="/gymbro/index.php" class="mr-15">
      <button class="btn btn--secondary">← Regresar</button>
    </a>
  </div>

</main>

</body>
</html>
