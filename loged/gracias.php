<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gracias por tu compra | GYM-BRO</title>
<link rel="stylesheet" href="../style.css">

<style>
.page-center {
    min-height: calc(100vh - 120px);
    display: flex;
    justify-content: center;
    align-items: center;
}
.checkmark {
    font-size: 60px;
    color: #4CAF50;
    margin-bottom: 15px;
}
</style>

</head>
<body>

<header>
  <div class="logo"><img src="/gymbro/Imagenes/LogoGymBro.png" height="100px" width="100px" alt="GYM-BRO"></div>
  <div class="nav-links">
    <a href="/gymbro/loged/index.php">Inicio</a>
    <a href="/gymbro/loged/membresias.php">Membresías</a>
    <a href="/gymbro/loged/cuenta.php">Mi Cuenta</a>
    <a href="/gymbro/loged/logout.php">Cerrar sesión</a>
  </div>
</header>

<main class="page-center">

<div class="login-card text-center">

    <div class="checkmark">✔</div>

    <h1>¡Pago exitoso!</h1>

    <p class="mt-10">
        Tu suscripción ha sido activada correctamente.<br>
        Gracias por unirte a <strong>GYM-BRO</strong>.
    </p>

    <a href="/gymbro/loged/index.php">
        <button class="btn mt-30">Volver al inicio</button>
    </a>

</div>

</main>

</body>
</html>
