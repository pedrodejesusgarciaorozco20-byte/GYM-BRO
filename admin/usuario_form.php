<?php
require_once __DIR__.'/_auth.php';
$id=(int)($_GET['id']??0);$u=['name'=>'','Lname'=>'','email'=>'','activo'=>1];$error='';
if($id){$st=$conn->prepare('SELECT id,name,Lname,email,activo FROM usuarios WHERE id=? AND rol=0');$st->bind_param('i',$id);$st->execute();$u=$st->get_result()->fetch_assoc();if(!$u){http_response_code(404);exit('Usuario no encontrado o protegido.');}}
if($_SERVER['REQUEST_METHOD']==='POST'){
$name=trim($_POST['name']??'');$lname=trim($_POST['lname']??'');$email=trim($_POST['email']??'');$password=$_POST['password']??'';$activo=(int)($_POST['activo']??1);
if($name===''||$lname===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||!in_array($activo,[0,1],true))$error='Verifica los datos del formulario.';
else try{
if($id){if($password!==''){$hash=password_hash($password,PASSWORD_DEFAULT);$st=$conn->prepare('UPDATE usuarios SET name=?,Lname=?,email=?,password=?,activo=? WHERE id=? AND rol=0');$st->bind_param('ssssii',$name,$lname,$email,$hash,$activo,$id);}else{$st=$conn->prepare('UPDATE usuarios SET name=?,Lname=?,email=?,activo=? WHERE id=? AND rol=0');$st->bind_param('sssii',$name,$lname,$email,$activo,$id);}$st->execute();}
else{if($password==='')$error='La contraseña es obligatoria para crear un usuario.';else{$hash=password_hash($password,PASSWORD_DEFAULT);$st=$conn->prepare('INSERT INTO usuarios(name,Lname,email,password,rol,activo) VALUES(?,?,?,?,0,?)');$st->bind_param('ssssi',$name,$lname,$email,$hash,$activo);$st->execute();}}
if($error===''){header('Location: usuarios.php');exit;}
}catch(mysqli_sql_exception $e){$error=$e->getCode()===1062?'Ese correo ya está registrado.':'No se pudo guardar el usuario.';}
$u=array_merge($u,['name'=>$name,'Lname'=>$lname,'email'=>$email,'activo'=>$activo]);
}
require_once __DIR__.'/_header.php';adminHeader($id?'Editar usuario':'Nuevo usuario','usuarios');
?>
<section class="content-card"><h2 class="section-title"><?= $id?'Editar datos':'Registrar cuenta' ?></h2><?php if($error):?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif;?>
<form method="post" class="row g-3"><div class="col-md-6"><label class="form-label">Nombre</label><input class="form-control" name="name" maxlength="50" required value="<?= htmlspecialchars($u['name']) ?>"></div><div class="col-md-6"><label class="form-label">Apellido</label><input class="form-control" name="lname" maxlength="50" required value="<?= htmlspecialchars($u['Lname']) ?>"></div><div class="col-md-7"><label class="form-label">Correo</label><input class="form-control" type="email" name="email" maxlength="254" required value="<?= htmlspecialchars($u['email']) ?>"></div><div class="col-md-5"><label class="form-label">Contraseña <?= $id?'(dejar vacía para conservar)':'' ?></label><input class="form-control" type="password" name="password" <?= $id?'':'required' ?> minlength="6"></div><div class="col-md-4"><label class="form-label">Estado</label><select class="form-select" name="activo"><option value="1" <?= $u['activo']?'selected':'' ?>>Activo</option><option value="0" <?= !$u['activo']?'selected':'' ?>>Inactivo</option></select></div><div class="col-12 d-flex gap-2"><button class="btn btn-accent">Guardar</button><a href="usuarios.php" class="btn btn-outline-secondary">Cancelar</a></div></form></section>
<?php adminFooter(); ?>
