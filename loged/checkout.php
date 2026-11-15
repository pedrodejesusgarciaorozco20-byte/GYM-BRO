<?php

session_start();

require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

$usuario_id = (int)$_SESSION['user_id'];

$active = $conn->prepare("SELECT id FROM suscripciones WHERE usuario_id = ? AND estado = 1 AND fecha_fin >= NOW() LIMIT 1");

$active->bind_param('i', $usuario_id);

$active->execute();

if ($active->get_result()->num_rows > 0) { header('Location: cuenta.php?mensaje=suscripcion_activa'); exit; }

$plan_id = filter_input(INPUT_GET, 'plan_id', FILTER_VALIDATE_INT);

if (!$plan_id) { http_response_code(400); exit('No se seleccionó un plan válido.'); }

$stmt = $conn->prepare('SELECT id, nombre_plan, precio, duracion_dias FROM planes WHERE id = ? AND activo = 1');

$stmt->bind_param('i', $plan_id); $stmt->execute(); $plan = $stmt->get_result()->fetch_assoc();

if (!$plan) { http_response_code(404); exit('Plan no encontrado o inactivo.'); }

$precio = (float)$plan['precio']; $iva = round($precio * .16, 2); $total = $precio + $iva;

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout - <?= htmlspecialchars($plan['nombre_plan']) ?></title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

<header><div class="logo"><img src="/gymbro/Imagenes/LogoGymBro.png" alt="GYM-BRO"></div><nav class="nav-links"><a href="index.php">Inicio</a><a href="membresias.php">Membresías</a><a href="cuenta.php">Mi Cuenta</a><a href="logout.php">Cerrar sesión</a></nav></header>

<main><div class="checkout-card"><h2 class="text-center">Completa tu compra</h2><p class="text-center">Plan seleccionado: <strong><?= htmlspecialchars($plan['nombre_plan']) ?></strong></p>

<form class="form" action="pagos.php" method="POST" id="checkout-form">

<input type="hidden" name="plan_id" value="<?= (int)$plan['id'] ?>">

<label>Nombre completo</label><input class="input" type="text" name="nombre" maxlength="100" required>

<label>Correo electrónico</label><input class="input" type="email" name="email" maxlength="254" required>

<label>Datos de transacción (simulación)</label>

<input class="input" type="text" name="tarjeta" inputmode="numeric" maxlength="19" placeholder="Número de tarjeta (16 dígitos)" required>

<div class="row"><div style="flex:1"><input class="input" type="text" name="vencimiento" maxlength="5" placeholder="MM/AA" required></div><div style="flex:1"><input class="input" type="password" name="cvv" inputmode="numeric" maxlength="3" placeholder="CVV" required></div></div>

<p style="font-size:12px">* Simulación. No se realizan cobros reales. Los datos de tarjeta no se almacenan.</p>

<div class="payment-summary"><div><span>Subtotal</span><strong>$<?= number_format($precio,2) ?></strong></div><div><span>IVA (16%)</span><strong>$<?= number_format($iva,2) ?></strong></div><div><strong>Total</strong><strong>$<?= number_format($total,2) ?> MXN</strong></div></div>

<label><input type="checkbox" required> Acepto los términos y condiciones</label><button class="btn btn--block" type="submit">Pagar ahora</button></form></div></main>

<script>

const form=document.getElementById('checkout-form');

form.addEventListener('submit',e=>{const c=form.tarjeta.value.replace(/\D/g,'');const v=form.cvv.value.replace(/\D/g,'');if(c.length!==16||v.length!==3||! /^(0[1-9]|1[0-2])\/\d{2}$/.test(form.vencimiento.value)){e.preventDefault();alert('Verifica la tarjeta de 16 dígitos, vencimiento MM/AA y CVV de 3 dígitos.');}});

form.tarjeta.addEventListener('input',e=>e.target.value=e.target.value.replace(/\D/g,'').slice(0,16).replace(/(.{4})/g,'$1 ').trim());

form.cvv.addEventListener('input',e=>e.target.value=e.target.value.replace(/\D/g,'').slice(0,3));

form.vencimiento.addEventListener('input',e=>{let v=e.target.value.replace(/\D/g,'').slice(0,4);if(v.length>2)v=v.slice(0,2)+'/'+v.slice(2);e.target.value=v;});

</script>
</body>
</html>