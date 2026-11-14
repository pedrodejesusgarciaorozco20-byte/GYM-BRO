<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "gymbro";
$port = 3307;
try {
    $conn = new mysqli($servername, $username, $password, $database, $port);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit("No fue posible conectar con la base de datos. Revisa los datos de conexion en conexion.php.");
}
