<?php
function adminHeader(string $title, string $active = 'inicio'): void {
$links = ['inicio'=>['admin.php','Inicio','bi-house'], 'usuarios'=>['usuarios.php','Usuarios','bi-people'], 'compras'=>['compras.php','Compras','bi-bag-check']];
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title) ?> | GYM-BRO</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css"></head><body>
<div class="admin-shell"><aside class="sidebar">
<a class="brand" href="admin.php"><img src="../Imagenes/LogoGymBro.png" alt="GYM-BRO"><span>GYM-BRO<small>ADMINISTRACIÓN</small></span></a>
<div class="nav-caption">MENÚ PRINCIPAL</div><nav class="side-nav">
<?php foreach($links as $key=>$item): ?><a class="<?= $active===$key?'active':'' ?>" href="<?= $item[0] ?>"><i class="bi <?= $item[2] ?>"></i><?= $item[1] ?></a><?php endforeach; ?>
</nav><div class="sidebar-bottom"><a href="../index.php" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Ver sitio</a><a href="../loged/logout.php" class="logout"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a></div>
</aside><main class="admin-content"><header class="page-head"><div><span class="eyebrow">PANEL DE CONTROL</span><h1><?= htmlspecialchars($title) ?></h1></div><div class="admin-avatar"><i class="bi bi-person-fill"></i></div></header>
<?php } function adminFooter(): void { ?></main></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php } ?>
