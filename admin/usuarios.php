<?php
require_once __DIR__.'/_auth.php';require_once __DIR__.'/_header.php';
$res=$conn->query("SELECT id,name,Lname,email,singdate,activo FROM usuarios WHERE rol=0 ORDER BY id DESC");
adminHeader('Usuarios','usuarios');
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"><p class="text-secondary mb-0">Administra las cuentas normales. Las cuentas de administrador están protegidas.</p><a class="btn btn-accent" href="usuario_form.php"><i class="bi bi-plus-lg"></i> Nuevo usuario</a></div>

<?php if(!empty($_SESSION['admin_error'])): ?><div class="alert alert-warning"><?= htmlspecialchars($_SESSION['admin_error']); unset($_SESSION['admin_error']); ?></div><?php endif; ?>

    <section class="content-card"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Registro</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>

    <?php if($res->num_rows):while($u=$res->fetch_assoc()): ?><tr><td><?= (int)$u['id'] ?></td><td class="fw-semibold"><?= htmlspecialchars($u['name'].' '.$u['Lname']) ?></td><td><?= htmlspecialchars($u['email']) ?></td><td><?= htmlspecialchars($u['singdate']) ?></td><td><span class="badge <?= $u['activo']?'badge-soft':'text-bg-secondary' ?>"><?= $u['activo']?'Activo':'Inactivo' ?></span></td><td><div class="d-flex gap-2"><a class="btn btn-sm btn-outline-primary" href="usuario_form.php?id=<?= (int)$u['id'] ?>" title="Editar"><i class="bi bi-pencil"></i></a><form method="post" action="usuario_eliminar.php" onsubmit="return confirm('¿Eliminar este usuario? Esta acción puede estar limitada por compras o suscripciones asociadas.');"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button></form></div></td></tr><?php endwhile;else:?><tr><td colspan="6" class="text-center text-secondary py-4">No hay usuarios registrados.</td></tr><?php endif; ?>

    </tbody></table></div></section><?php adminFooter(); ?>
