
<?php
session_start();
require_once __DIR__ . '/conexion.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: /gymbro/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /gymbro/loged/index.php');
    exit;
}

$uid = (int)$_SESSION['user_id'];
$mensaje = trim($_POST['mensaje'] ?? '');

if ($mensaje === '' || mb_strlen($mensaje) > 500) {
    header('Location: /gymbro/loged/index.php?testimonio=error#testimonios');
    exit;
}

$st = $conn->prepare("
    SELECT name, Lname
    FROM usuarios
    WHERE id = ? AND activo = 1
");

$st->bind_param('i', $uid);
$st->execute();

$user = $st->get_result()->fetch_assoc();
$st->close();

// Verificar usuario
if (!$user) {
    session_destroy();
    header('Location: /gymbro/login.php');
    exit;
}

$nombre = trim($user['name'] . ' ' . $user['Lname']);

$lim = $conn->prepare("
    SELECT id
    FROM testimonios
    WHERE usuario_id = ?
    AND fecha_publicacion > DATE_SUB(NOW(), INTERVAL 6 HOUR)
    LIMIT 1
");

$lim->bind_param('i', $uid);
$lim->execute();

if ($lim->get_result()->num_rows > 0) {
    $lim->close();
    header('Location: /gymbro/loged/index.php?testimonio=espera#testimonios');
    exit;
}

$lim->close();

$ins = $conn->prepare("
    INSERT INTO testimonios (usuario_id, nombre, mensaje, publicado)
    VALUES (?, ?, ?, 1)
");

$ins->bind_param('iss', $uid, $nombre, $mensaje);

if (!$ins->execute()) {
    $ins->close();
    header('Location: /gymbro/loged/index.php?testimonio=error#testimonios');
    exit;
}

$ins->close();

header('Location: /gymbro/loged/index.php?testimonio=ok#testimonios');
exit;
