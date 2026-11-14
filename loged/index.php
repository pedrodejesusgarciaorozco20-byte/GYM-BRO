
<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /gymbro/login.php");
    exit;
}

$usuario_id = (int)$_SESSION['user_id'];

$u = $conn->prepare('SELECT name, Lname, email FROM usuarios WHERE id = ? AND activo = 1');
$u->bind_param('i', $usuario_id);
$u->execute();
$usuario = $u->get_result()->fetch_assoc();
$u->close();

if (!$usuario) {
    session_destroy();
    header('Location: /gymbro/login.php');
    exit;
}

$nombreUsuario = $usuario['name'] . ' ' . $usuario['Lname'];
$correoUsuario = $usuario['email'];

$lastTest = $conn->prepare("
    SELECT fecha_publicacion
    FROM testimonios
    WHERE usuario_id = ?
    AND fecha_publicacion > DATE_SUB(NOW(), INTERVAL 6 HOUR)
    ORDER BY fecha_publicacion DESC
    LIMIT 1
");

$lastTest->bind_param('i', $usuario_id);
$lastTest->execute();
$testWait = $lastTest->get_result()->fetch_assoc();
$lastTest->close();

$lastContact = $conn->prepare("
    SELECT fecha_envio
    FROM contacto
    WHERE usuario_id = ?
    AND fecha_envio > DATE_SUB(NOW(), INTERVAL 6 HOUR)
    ORDER BY fecha_envio DESC
    LIMIT 1
");

$lastContact->bind_param('i', $usuario_id);
$lastContact->execute();
$contactWait = $lastContact->get_result()->fetch_assoc();
$lastContact->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GYM-BRO</title>

    <link rel="stylesheet" href="/gymbro/loged/style.css">
</head>

<body>

    <header>

        <div class="logo">
            <img src="/gymbro/Imagenes/LogoGymBro.png"
                 height="100px"
                 width="100px"
                 alt="GYM-BRO">
        </div>

        <div class="nav-links">
            <a href="/gymbro/loged/index.php">Inicio</a>
            <a href="/gymbro/loged/membresias.php">Membresías</a>
            <a href="/gymbro/loged/cuenta.php">Mi Cuenta</a>
            <a href="/gymbro/loged/logout.php">Cerrar sesión</a>
        </div>

    </header>

    <main>

        <!-- CARRUSEL -->
        <div class="carousel">

            <img src="/gymbro/Imagenes/ScrollUno.png"
                 class="active"
                 alt="Imagen 1">

            <img src="https://picsum.photos/id/1023/1200/400"
                 alt="Imagen 2">

            <img src="https://picsum.photos/id/1035/1200/400"
                 alt="Imagen 3">

        </div>

        <section class="section center">

            <h1>¡Bienvenido a GYM-BRO!</h1>

            <p>
                En GYM-BRO somos más que un gimnasio:
                somos una comunidad enfocada en el bienestar físico y mental.
            </p>

            <p>
                Nuestro objetivo es ayudarte a alcanzar tus metas de salud
                y condición física en un ambiente motivador, accesible
                y con la mejor atención personalizada.
            </p>

            <br><br>

            <p>
                Ofrecemos entrenamientos guiados, planes de membresía
                adaptados a cada necesidad, clases dinámicas y un espacio
                equipado con tecnología de vanguardia.
            </p>

            <p>
                Nuestro equipo de entrenadores profesionales te acompaña
                en cada paso, asegurando que logres resultados de forma
                segura, eficiente y motivadora.
            </p>

        </section>

        <section class="section">

            <h2 class="center">Nuestros Planes</h2>

            <div class="row mt-20">

                <a href="/gymbro/loged/membresias.php"
                   style="text-decoration: none; color: inherit;">

                    <div class="card">

                        <img src="/gymbro/imagenes/basico.png"
                             alt="Plan Básico">

                        <h3>BÁSICO</h3>

                        <p><br></p>

                        <p>
                            Entrada a la sucursal, clases grupales
                            y asesoría inicial gratuita.
                        </p>

                        <p><br></p>

                        <p>$299</p>

                    </div>

                </a>

                <a href="/gymbro/loged/membresias.php"
                   style="text-decoration: none; color: inherit;">

                    <div class="card">

                        <img src="/gymbro/Imagenes/medio.png"
                             alt="Plan Medio">

                        <h3>PLAN MEDIO</h3>

                        <p><br></p>

                        <p>
                            Plan básico + 2 sesiones personalizadas.
                        </p>

                        <p><br></p>

                        <p>$499</p>

                    </div>

                </a>

                <a href="/gymbro/loged/membresias.php"
                   style="text-decoration: none; color: inherit;">

                    <div class="card">

                        <img src="/gymbro/Imagenes/premiumm.webp"
                             alt="Plan Premium">

                        <h3>DISFRUTA LO MEJOR</h3>

                        <p><br></p>

                        <p>Plan Premium.</p>

                        <p><br></p>

                        <p>$799</p>

                    </div>

                </a>

            </div>

        </section>

        <section class="section">

            <h2 class="center">Nuestras Instalaciones</h2>

            <div class="row mt-20">

                <div class="card">

                    <img src="/gymbro/Imagenes/Centro.png"
                         alt="Gimnasio">

                    <p class="mt-10">Área Fitness</p>

                </div>

                <div class="card">

                    <img src="/gymbro/Imagenes/SanPedro.png"
                         alt="Área de caminadoras">

                    <p class="mt-10">Área de Caminadoras</p>

                </div>

                <div class="card">

                    <img src="/gymbro/Imagenes/SanNicolas.png"
                         alt="Área de pesas">

                    <p class="mt-10">Área de Pesas</p>

                </div>

            </div>

        </section>

        <section class="section section--muted" id="testimonios">

            <h2 style="text-align: center;">
                Lo Que Dicen Nuestros Clientes
            </h2>

            <div class="row mt-20">

                <?php
                $testimonios = $conn->query("
                    SELECT nombre, mensaje
                    FROM testimonios
                    WHERE publicado = 1
                    ORDER BY fecha_publicacion DESC
                    LIMIT 6
                ");

                if ($testimonios && $testimonios->num_rows > 0):

                    while ($testimonio = $testimonios->fetch_assoc()):
                ?>

                    <div class="card">

                        <p>
                            <?= htmlspecialchars($testimonio['mensaje']) ?>
                        </p>

                        <strong>
                            - <?= htmlspecialchars($testimonio['nombre']) ?>
                        </strong>

                    </div>

                <?php
                    endwhile;

                else:
                ?>

                    <div class="card">

                        <p>
                            "Me encanta el equipo de máquinas,
                            siempre están en buen estado y el lugar
                            está muy limpio. Además, las clases grupales
                            son muy dinámicas."
                        </p>

                        <strong>- Sofía Ramírez</strong>

                    </div>

                    <div class="card">

                        <p>
                            "Excelente ambiente y entrenadores muy preparados.
                            Desde que me inscribí he visto resultados
                            y lo mejor es la motivación que recibo cada día."
                        </p>

                        <strong>- Juan Martínez</strong>

                    </div>

                    <div class="card">

                        <p>
                            "Un gimnasio con todo lo necesario:
                            buena ubicación, precios accesibles
                            y atención personalizada. ¡Recomendado al 100%!"
                        </p>

                        <strong>- Carlos Hernández</strong>

                    </div>

                <?php endif; ?>

            </div>

            <form class="form" action="../testimonio.php" method="POST">

                <h3>Comparte tu experiencia</h3>

                <p>
                    Publicando como:
                    <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
                </p>

                <?php if ($testWait): ?>

                    <p class="center">
                        Ya publicaste un testimonio recientemente.
                        Podrás publicar otro después de que transcurran
                        las 6 horas.
                    </p>

                    <button class="btn" type="button" disabled>
                        Espera para publicar
                    </button>

                <?php else: ?>

                    <textarea
                        class="textarea"
                        name="mensaje"
                        maxlength="500"
                        placeholder="Escribe tu opinión..."
                        required
                    ></textarea>

                    <button class="btn" type="submit">
                        Publicar testimonio
                    </button>

                <?php endif; ?>

            </form>

        </section>

        <section class="section" id="contacto">

            <h2 class="center">Contáctanos</h2>

            <?php if (($_GET['contacto'] ?? '') === 'ok'): ?>

                <p class="center">
                    Tu mensaje fue enviado correctamente.
                </p>

            <?php elseif (($_GET['contacto'] ?? '') === 'error'): ?>

                <p class="center">
                    Revisa tu mensaje e inténtalo nuevamente.
                </p>

            <?php endif; ?>

            <form class="form" action="../contacto.php" method="POST">

                <p>
                    Enviando mensaje como:
                    <strong><?= htmlspecialchars($nombreUsuario) ?></strong>
                </p>

                <p>
                    Correo:
                    <strong><?= htmlspecialchars($correoUsuario) ?></strong>
                </p>

                <?php if ($contactWait): ?>

                    <p class="center">
                        Ya enviaste un mensaje recientemente.
                        Podrás enviar otro después de que transcurran
                        las 6 horas.
                    </p>

                    <button class="btn" type="button" disabled>
                        Espera para enviar otro mensaje
                    </button>

                <?php else: ?>

                    <textarea
                        class="textarea"
                        name="mensaje"
                        maxlength="1000"
                        placeholder="Escribe tu mensaje aquí..."
                        rows="5"
                        required
                    ></textarea>

                    <button type="submit" class="btn">
                        Enviar Mensaje
                    </button>

                <?php endif; ?>

            </form>

        </section>

    </main>

    <footer>

        <div class="footer-content">

            <div class="footer-section">

                <h3>Contáctanos</h3>

                <p>correo</p>
                <p>Teléfono</p>

            </div>

            <div class="footer-section">

                <h3>Redes Sociales</h3>

                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Twitter</a>

            </div>

            <div class="footer-section">

                <h3>Ubicación</h3>

                <p>MONTERREY NUEVO LEÓN</p>

            </div>

        </div>

    </footer>

    <script src="index.js"></script>

    <script>
        const track = document.getElementById('equipmentTrack');

        document.getElementById('equipmentNext')?.addEventListener('click', () => {
            track?.scrollBy({
                left: track.clientWidth * 0.8,
                behavior: 'smooth'
            });
        });

        document.getElementById('equipmentPrev')?.addEventListener('click', () => {
            track?.scrollBy({
                left: -track.clientWidth * 0.8,
                behavior: 'smooth'
            });
        });
    </script>

</body>
</html>
