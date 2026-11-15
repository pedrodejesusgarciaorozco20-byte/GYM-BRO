<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../conexion.php';
if (!isset($_SESSION['user_id']) || (int)($_SESSION['user_rol'] ?? 0) !== 1) {
    header('Location: ../login.php'); exit;
}
