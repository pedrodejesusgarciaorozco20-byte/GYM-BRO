
<?php
session_start();
require_once __DIR__ . '/conexion.php';

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

if ($mensaje === '' || mb_strlen($mensaje) > 1000) {
    header('Location: /gymbro/loged/index.php?contacto=error#contacto');
    exit;
}

$st = $conn->prepare("
    SELECT name, Lname, email
    FROM usuarios
    WHERE id = ? AND activo = 1
");

$st->bind_param('i', $uid);
$st->execute();

$usuario = $st->get_result()->fetch_assoc();
$st->close();

if (!$usuario) {
    session_destroy();
    header('Location: /gymbro/login.php');
    exit;
}

$nombre = trim($usuario['name'] . ' ' . $usuario['Lname']);
$correo = $usuario['email'];

$lim = $conn->prepare("
    SELECT id
    FROM contacto
    WHERE usuario_id = ?
    AND fecha_envio > DATE_SUB(NOW(), INTERVAL 6 HOUR)
    LIMIT 1
");

$lim->bind_param('i', $uid);
$lim->execute();

if ($lim->get_result()->num_rows > 0) {
    $lim->close();
    header('Location: /gymbro/loged/index.php?contacto=espera#contacto');
    exit;
}

$lim->close();

$ins = $conn->prepare("
    INSERT INTO contacto (usuario_id, mensaje)
    VALUES (?, ?)
");

$ins->bind_param('is', $uid, $mensaje);

if (!$ins->execute()) {
    $ins->close();
    header('Location: /gymbro/loged/index.php?contacto=error#contacto');
    exit;
}

$ins->close();

header('Location: /gymbro/loged/index.php?contacto=ok#contacto');
exit;
