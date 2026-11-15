
<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$usuario_id = (int)$_SESSION['user_id'];

$sqlUser = "SELECT name, Lname, email, singdate FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sqlUser);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resUser = $stmt->get_result();

if ($resUser->num_rows === 0) {
    die("Usuario no encontrado.");
}

$user = $resUser->fetch_assoc();
$stmt->close();

$sqlSub = "
    SELECT s.*, p.nombre_plan
    FROM suscripciones s
    JOIN planes p ON s.plan_id = p.id
    WHERE s.usuario_id = ?
    ORDER BY s.fecha_inicio DESC
    LIMIT 1
";

$stmt = $conn->prepare($sqlSub);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resSub = $stmt->get_result();

$tieneSub = ($resSub->num_rows > 0);
$subs = null;
$activa = false;

if ($tieneSub) {
    $subs = $resSub->fetch_assoc();

    $hoy = date("Y-m-d");
    $estadoBD = (int)$subs['estado'];
    $fechaFinCorta = substr($subs['fecha_fin'], 0, 10);

    $activa = ($estadoBD === 1 && $fechaFinCorta >= $hoy);
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta | GYM-BRO</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <div class="logo">
        <img src="/gymbro/Imagenes/LogoGymBro.png"
             height="100px" width="100px" alt="GYM-BRO">
    </div>

    <div class="nav-links">
        <a href="/gymbro/loged/index.php">Inicio</a>
        <a href="/gymbro/loged/membresias.php">Membresías</a>
        <a href="/gymbro/loged/cuenta.php">Mi Cuenta</a>
        <a href="/gymbro/loged/logout.php">Cerrar sesión</a>
    </div>
</header>

<main>

    <h1 class="text-center">Mi Cuenta</h1>

    <h2 class="text-center">Datos personales</h2>

    <div class="table-container">
        <table>
            <tr>
                <th>Información</th>
                <th>Datos</th>
            </tr>

            <tr>
                <td class="benefit">Nombre</td>
                <td>
                    <?php echo htmlspecialchars($user['name'] . ' ' . $user['Lname']); ?>
                </td>
            </tr>

            <tr>
                <td class="benefit">Correo electrónico</td>
                <td>
                    <?php echo htmlspecialchars($user['email']); ?>
                </td>
            </tr>

            <tr>
                <td class="benefit">Miembro desde</td>
                <td>
                    <?php echo htmlspecialchars($user['singdate']); ?>
                </td>
            </tr>
        </table>
    </div>

    <h2 class="text-center">Mi suscripción</h2>

    <div class="table-container">
        <table>
            <tr>
                <th>Información</th>
                <th>Detalles</th>
            </tr>

            <?php if (!$tieneSub): ?>

                <tr>
                    <td colspan="2" class="text-center">
                        Actualmente no tienes ninguna suscripción registrada.
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="text-center">
                        <a href="/gymbro/loged/membresias.php">
                            <button class="btn">Ver planes disponibles</button>
                        </a>
                    </td>
                </tr>

            <?php else: ?>

                <?php
                    $fecha_inicio = substr($subs['fecha_inicio'], 0, 10);
                    $fecha_fin = substr($subs['fecha_fin'], 0, 10);
                    $total = number_format($subs['total_pagado'], 2);
                    $renovAuto = (int)$subs['auto_renovar'];
                ?>

                <tr>
                    <td class="benefit">Estado</td>
                    <td>
                        <?php echo $activa ? '✔️ Activa' : '❌ Inactiva'; ?>
                    </td>
                </tr>

                <tr>
                    <td class="benefit">Plan contratado</td>
                    <td>
                        <?php echo htmlspecialchars($subs['nombre_plan']); ?>
                    </td>
                </tr>

                <tr>
                    <td class="benefit">Fecha de inicio</td>
                    <td><?php echo htmlspecialchars($fecha_inicio); ?></td>
                </tr>

                <tr>
                    <td class="benefit">Fecha de vencimiento</td>
                    <td><?php echo htmlspecialchars($fecha_fin); ?></td>
                </tr>

                <tr>
                    <td class="benefit">Total pagado</td>
                    <td>$<?php echo $total; ?> MXN</td>
                </tr>

                <?php if ($activa): ?>

                    <tr>
                        <td class="benefit">Renovación automática</td>
                        <td>
                            <?php echo $renovAuto ? 'Activada' : 'Desactivada'; ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-center">
                            <form method="POST" action="toggle_renovacion.php">
                                <input type="hidden" name="suscripcion_id"
                                       value="<?php echo (int)$subs['id']; ?>">

                                <input type="hidden" name="nuevo_estado"
                                       value="<?php echo $renovAuto ? 0 : 1; ?>">

                                <button class="btn" type="submit">
                                    <?php
                                    echo $renovAuto
                                        ? 'Desactivar renovación automática'
                                        : 'Activar renovación automática';
                                    ?>
                                </button>
                            </form>
                        </td>
                    </tr>

                <?php else: ?>

                    <tr>
                        <td colspan="2" class="text-center">
                            Tu suscripción ya venció. Puedes adquirir un nuevo plan cuando desees.
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-center">
                            <a href="/gymbro/loged/membresias.php">
                                <button class="btn">Ver planes disponibles</button>
                            </a>
                        </td>
                    </tr>

                <?php endif; ?>

            <?php endif; ?>

        </table>
    </div>

    <div class="mt-30 text-center">
        <a href="/gymbro/loged/index.php" class="mr-15">
            <button class="btn btn--secondary">← Regresar</button>
        </a>
    </div>

</main>

</body>
</html>


