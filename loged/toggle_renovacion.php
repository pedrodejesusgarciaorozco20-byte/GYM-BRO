<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cuenta.php");
    exit;
}

$usuario_id      = (int)$_SESSION['user_id'];
$suscripcion_id  = isset($_POST['suscripcion_id']) ? (int)$_POST['suscripcion_id'] : 0;
$nuevo_estado    = isset($_POST['nuevo_estado']) ? (int)$_POST['nuevo_estado'] : 0;
$nuevo_estado    = $nuevo_estado ? 1 : 0; // normalizar a 0/1

if ($suscripcion_id <= 0) {
    header("Location: cuenta.php");
    exit;
}

$sql = "UPDATE suscripciones 
        SET auto_renovar = ?
        WHERE id = ? AND usuario_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $nuevo_estado, $suscripcion_id, $usuario_id);
$stmt->execute();
$stmt->close();

header("Location: cuenta.php");
exit;
