<?php
require_once __DIR__.'/_auth.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: usuarios.php');exit;}
$id=(int)($_POST['id']??0);
if($id>0){$st=$conn->prepare('DELETE FROM usuarios WHERE id=? AND rol=0');$st->bind_param('i',$id);try{$st->execute();}catch(mysqli_sql_exception $e){$_SESSION['admin_error']='No se puede eliminar: el usuario tiene compras, suscripciones o mensajes relacionados.';}}
header('Location: usuarios.php');exit;
