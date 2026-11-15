<?php
session_start();

require_once __DIR__ . '/../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

$usuario_id = (int)$_SESSION['user_id'];

$plan_id = filter_input(INPUT_POST, 'plan_id', FILTER_VALIDATE_INT);

$nombre = trim($_POST['nombre'] ?? '');

$email = trim($_POST['email'] ?? '');

$tarjeta = preg_replace('/\D/', '', $_POST['tarjeta'] ?? '');

$cvv = preg_replace('/\D/', '', $_POST['cvv'] ?? '');

$vencimiento = trim($_POST['vencimiento'] ?? '');

if (!$plan_id || $nombre === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($tarjeta) !== 16 || strlen($cvv) !== 3 || !preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $vencimiento)) { header('Location: checkout.php?plan_id='.(int)$plan_id.'&error=datos'); exit; }

$active=$conn->prepare("SELECT id FROM suscripciones WHERE usuario_id=? AND estado=1 AND fecha_fin>=NOW() LIMIT 1");

$active->bind_param('i',$usuario_id);$active->execute();

if($active->get_result()->num_rows>0){header('Location: cuenta.php?mensaje=suscripcion_activa');exit;}

$stmt=$conn->prepare('SELECT precio,duracion_dias FROM planes WHERE id=? AND activo=1');

$stmt->bind_param('i',$plan_id);$stmt->execute();$plan=$stmt->get_result()->fetch_assoc();

if(!$plan){http_response_code(404);exit('Plan inválido o inactivo.');}

$total=round((float)$plan['precio']*1.16,2);

$inicio=date('Y-m-d H:i:s');$fin=date('Y-m-d H:i:s',strtotime('+'.(int)$plan['duracion_dias'].' days'));

$ref='GYM-'.strtoupper(bin2hex(random_bytes(5)));

try{

$conn->begin_transaction();

$estado=1;$renovar=0;

$ins=$conn->prepare('INSERT INTO suscripciones (usuario_id,plan_id,fecha_compra,fecha_inicio,fecha_fin,estado,auto_renovar,total_pagado) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)');

$ins->bind_param('iissiid',$usuario_id,$plan_id,$inicio,$fin,$estado,$renovar,$total);$ins->execute();$sub_id=$conn->insert_id;

$metodo='tarjeta';$pago_estado='aprobado';$nota='Pago de demostración. No se realizó un cobro real.';

$pay=$conn->prepare('INSERT INTO pagos (suscripcion_id,fecha_pago,metodo_pago,monto,estado,referencia,notas) VALUES (?,NOW(),?,?,?,?,?)');

$pay->bind_param('isdsss',$sub_id,$metodo,$total,$pago_estado,$ref,$nota);$pay->execute();

$conn->commit();header('Location: gracias.php');exit;

}catch(Throwable $e){$conn->rollback();http_response_code(500);exit('No fue posible registrar la compra. Inténtalo nuevamente.');}
