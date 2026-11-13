
<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM-BRO | Iniciar sesión</title>
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
            padding: 60px 20px;
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
        <h1>¡Bienvenido de nuevo!</h1>
        <p class="auth-description">Inicia sesión para continuar con GYM-BRO.</p>

        <form action="/gymbro/login.php" method="POST">
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

            <button type="submit" class="auth-button">
                Iniciar sesión
            </button>
        </form>

        <p class="auth-switch">
            ¿No tienes cuenta?
            <a href="/gymbro/signin.php">Regístrate aquí</a>
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
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/conexion.php';

function loginUser(string $email, string $plainPass, mysqli $conn): array {

    $sql = "SELECT id, name, lname, email, password, rol, activo
            FROM usuarios
            WHERE email = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return [false, "Error preparando consulta: " . $conn->error];
    }

    $stmt->bind_param("s", $email);

    if (!$stmt->execute()) {
        $stmt->close();
        return [false, "Error ejecutando consulta: " . $conn->error];
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        return [false, "Usuario no encontrado"];
    }

    $row = $result->fetch_assoc();
    $stmt->close();

    if ((int)$row['activo'] !== 1) {
        return [false, 'Esta cuenta se encuentra desactivada. Contacta al administrador.'];
    }

    if (!password_verify($plainPass, $row['password'])) {
        return [false, "Contraseña incorrecta"];
    }

    $_SESSION['user_id']       = (int)$row['id'];
    $_SESSION['user_name']     = $row['name'];
    $_SESSION['user_lastname'] = $row['lname'];
    $_SESSION['user_email']    = $row['email'];
    $_SESSION['user_rol']      = isset($row['rol']) ? (int)$row['rol'] : 0;
    $_SESSION['logged_in']     = true;
    $_SESSION['last_activity'] = time();

    return [true, "Login exitoso"];
}

$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password'] ?? '';

if ($email === '' || $pass === '') {
    echo "<script>alert('Completa todos los campos'); history.back();</script>";
    exit;
}

[$ok, $msg] = loginUser($email, $pass, $conn);
$conn->close();

if ($ok) {

    if (!empty($_SESSION['user_rol']) && $_SESSION['user_rol'] === 1) {
        header('Location: /gymbro/admin/admin.php');
    } else {
        header('Location: /gymbro/loged/index.php');
    }

    exit;

} else {

    echo "<script>alert('" . addslashes($msg) . "'); history.back();</script>";
    exit;
}
?>

