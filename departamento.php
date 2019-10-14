<?php
include('inc/config.php');
$page['title'] = 'Administración departamento';

# delete handler
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_POST['action'] == 'delete') {
		# delete some records
		$sql = 'DELETE FROM departamento WHERE id = :id';
		$stmt = cnn()->prepare($sql);
		$stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
		$stmt->execute();
		exit(true);
	}
}

# table
//$sql['table'] = 'user INNER JOIN company ON company.id_company = user.id_company';
$sql['table'] = 'departamento';



# cols array(column, alias, asc/desc, type)
$sql['cols'][] = array('departamento.id', 'id');
//$sql['cols'][] = array('user.flg_access', 'flg_access', false, 'boolean');
$sql['cols'][] = array('departamento.nombre', 'NOMBRE');
$sql['cols'][] = array('departamento.activo', 'flg_access', false, 'boolean');
$sql['cols'][] = array('departamento.id', 'action_edit');
$sql['cols'][] = array('departamento.id', 'action_delete');

/*
# SPECIFIC sql options

# filters
$sql['filters'][] = array('user.name LIKE :name', ':name', "%Veronica%", PDO::PARAM_STR);
$sql['filters'][] = array('user.id_level = :id_level', ':id_level', 2, PDO::PARAM_INT);

# group
$sql['groupby'] = 'exchange.id_exchange';
*/

$m['record_list'] = '<h4>DEPARTAMENTOS</h4>';
?>

<?php include('header.php')?>
<?php grid($sql)?>
<?php include('footer.php')?>