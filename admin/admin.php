<?php
require_once __DIR__.'/_auth.php'; require_once __DIR__.'/_header.php';
function countTable($conn,$table){$allowed=['usuarios','suscripciones','contacto','planes'];if(!in_array($table,$allowed,true))return 0;return (int)$conn->query("SELECT COUNT(*) total FROM `$table`")->fetch_assoc()['total'];}
$activos=(int)$conn->query("SELECT COUNT(*) total FROM suscripciones WHERE estado=1 AND fecha_fin>=NOW()")->fetch_assoc()['total'];
$planes=$conn->query("SELECT nombre_plan,descripcion,precio,duracion_dias,activo FROM planes ORDER BY activo DESC,id");
$mensajes=$conn->query("SELECT c.mensaje,c.fecha_envio,u.name,u.Lname,u.email FROM contacto c JOIN usuarios u ON u.id=c.usuario_id ORDER BY c.fecha_envio DESC LIMIT 6");
adminHeader('Inicio','inicio');
?>
<p class="text-secondary mb-4">Este es el resumen general de la actividad de GYM-BRO.</p>
<div class="row g-3">
<?php foreach([['Usuarios registrados',countTable($conn,'usuarios'),'bi-people'],['Suscripciones registradas',countTable($conn,'suscripciones'),'bi-card-checklist'],['Suscripciones activas',$activos,'bi-person-check']] as $s): ?>
<div class="col-md-4"><div class="stat-card"><div class="stat-icon"><i class="bi <?= $s[2] ?>"></i></div><div><small><?= $s[0] ?></small><strong><?= $s[1] ?></strong></div></div></div>
<?php endforeach; ?></div>
<section class="content-card"><h2 class="section-title">Planes disponibles</h2><div class="row g-3">
<?php while($p=$planes->fetch_assoc()): ?><div class="col-sm-6 col-xl-4"><article class="plan-card"><div class="d-flex justify-content-between gap-2"><h3><?= htmlspecialchars($p['nombre_plan']) ?></h3><span class="badge <?= $p['activo']?'badge-soft text-success':'text-bg-secondary' ?>"><?= $p['activo']?'Activo':'Inactivo' ?></span></div><div class="plan-price">$<?= number_format((float)$p['precio'],2) ?><small class="fs-6 text-secondary"> MXN</small></div><p class="text-secondary small mb-0"><?= htmlspecialchars($p['descripcion']??'') ?></p><small class="text-secondary"><?= (int)$p['duracion_dias'] ?> días</small></article></div><?php endwhile; ?>
</div></section>
<section class="content-card"><div class="d-flex justify-content-between align-items-center mb-3"><h2 class="section-title mb-0">Mensajes de contacto recientes</h2><a href="usuarios.php" class="small">Ver usuarios</a></div><div class="row g-3">
<?php if($mensajes->num_rows): while($m=$mensajes->fetch_assoc()): ?><div class="col-md-6 col-xl-4"><article class="message-card"><div class="fw-bold"><?= htmlspecialchars($m['name'].' '.$m['Lname']) ?></div><small class="text-secondary"><?= htmlspecialchars($m['email']) ?> · <?= htmlspecialchars($m['fecha_envio']) ?></small><hr><p class="mb-0"><?= nl2br(htmlspecialchars($m['mensaje'])) ?></p></article></div><?php endwhile; else: ?><div class="empty-state">Todavía no hay mensajes de contacto.</div><?php endif; ?>
</div></section>
<?php adminFooter(); ?>
