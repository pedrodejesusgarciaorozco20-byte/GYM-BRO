
<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM-BRO | Crear cuenta</title>
    <link rel="stylesheet" href="/gymbro/loged/style.css">

    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 20px;
        }

        .auth-card {
    width: 100%;
    max-width: 440px; 
    padding: 40px;
    background: #123456;
    border: 1px solid #234b70;
    border-radius: 15px;
    box-shadow: 0 10px 35px rgba(0,0,0,0.45);
    text-align: center;
}

.auth-card h1 {
    color: #ffffff;
    margin-bottom: 12px;
    font-size: 30px;
}

.auth-description {
    color: #ffffff;
    margin-bottom: 30px;
}

.auth-card input {
    width: 100%;
    box-sizing: border-box;
    padding: 15px;
    border: 1px solid #52718f;
    border-radius: 8px;
    background: #0c2945;
    color: #ffffff;
    font-size: 15px;
    outline: none;
}

.auth-card input:focus {
    border-color: #f5c518;
}

.auth-card input::placeholder {
    color: #cccccc;
}

.auth-button {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 8px;
    background: #f5c518;
    color: #111111;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.auth-switch {
    color: #ffffff;
    margin-top: 25px;
}

.auth-switch a {
    color: #f5c518;
    text-decoration: none;
    font-weight: bold;
}

        .auth-switch a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .auth-card {
                padding: 28px 20px;
            }

            .auth-card h1 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body class="auth-page">

<header>
    <div class="logo">
        <a href="/gymbro/index.php">
            <img src="/gymbro/Imagenes/LogoGymBro.png" height="100" width="100" alt="Logo GYM-BRO">
        </a>
    </div>

    <div class="nav-links">
        <a href="/gymbro/index.php">Inicio</a>
        <a href="/gymbro/membresias.php">Membresías</a>
        <a href="/gymbro/login.php">Iniciar sesión</a>
        <a href="/gymbro/signin.php">Regístrate</a>
    </div>
</header>

<main class="auth-main">
    <div class="auth-card">
        <h1>Únete a GYM-BRO</h1>
        <p class="auth-description">Crea tu cuenta y comienza tu entrenamiento.</p>

        <form action="/gymbro/signin.php" method="POST">

            <input
                type="text"
                name="name"
                placeholder="Nombre"
                required
            >

            <input
                type="text"
                name="lname"
                placeholder="Apellidos"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Correo electrónico"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Contraseña"
                required
            >

            <input
                type="password"
                name="vpassword"
                placeholder="Confirmar contraseña"
                required
            >

            <button type="submit" class="auth-button">
                Crear cuenta
            </button>

        </form>

        <p class="auth-switch">
            ¿Ya tienes cuenta?
            <a href="/gymbro/login.php">Inicia sesión aquí</a>
        </p>
    </div>
</main>

</body>
</html>
<?php
    exit;
}
?>

<?php
require_once __DIR__ . '/conexion.php';

$nombre    = trim($_POST['name'] ?? '');
$apellido  = trim($_POST['lname'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password'] ?? '';
$vpassword = $_POST['vpassword'] ?? '';

if ($nombre === '' || $apellido === '' || $email === '' || $password === '' || $vpassword === '') {
    exit("<script>alert('Faltan datos'); history.back();</script>");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("<script>alert('Correo inválido'); history.back();</script>");
}

if ($password !== $vpassword) {
    exit("<script>alert('Las contraseñas no coinciden'); history.back();</script>");
}

$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    exit("<script>alert('Ese correo ya está registrado'); history.back();</script>");
}

$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (name, lname, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $apellido, $email, $hash);

if ($stmt->execute()) {

    $stmt->close();
    $conn->close();

    echo "<script>alert('Registro exitoso ✅'); window.location='/gymbro/login.php';</script>";
    exit;

} else {

    $error = $conn->error;

    $stmt->close();
    $conn->close();

    exit("<script>alert('Error al registrar: " . addslashes($error) . "'); history.back();</script>");
}
?>
