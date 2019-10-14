<?php
include('inc/config.php');
$page['title'] = 'Administración USUARIO';

# delete handler
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_POST['action'] == 'delete') {
		# delete some records
		$sql = 'DELETE FROM usuario WHERE id = :id';
		$stmt = cnn()->prepare($sql);
		$stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
		$stmt->execute();
		exit(true);
	}
}

# table
//$sql['table'] = 'user INNER JOIN company ON company.id_company = user.id_company';
//$sql['table'] = 'usuario';

$sql['table'] = 'usuario 
					INNER JOIN tipo_usuario ON tipo_usuario.id = usuario.tipo_usuario';


# cols array(column, alias, asc/desc, type)
$sql['cols'][] = array('usuario.id', 'id');
$sql['cols'][] = array('usuario.nombre_completo', 'NOMBRE');
$sql['cols'][] = array('usuario.cuenta', 'CUENTA');
$sql['cols'][] = array('tipo_usuario.nombre', 'TIPO');


$sql['cols'][] = array('usuario.activo','A', false, 'boolean');
$sql['cols'][] = array('usuario.id', 'action_edit');
$sql['cols'][] = array('usuario.id', 'action_pass');
$sql['cols'][] = array('usuario.id', 'action_delete');

$m['record_list'] = '<h4>Usuarios</h4>';
?>

<?php include('header.php')?>
<?php grid($sql)?>
<?php include('footer.php')?>