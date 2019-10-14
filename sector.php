<?php
include('inc/config.php');
$page['title'] = 'Administración Contratista';

# delete handler
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_POST['action'] == 'delete') {
		# delete some records
		$sql = 'DELETE FROM sector WHERE id = :id';
		$stmt = cnn()->prepare($sql);
		$stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
		$stmt->execute();
		exit(true);
	}
}

# table
//$sql['table'] = 'user INNER JOIN company ON company.id_company = user.id_company';
$sql['table'] = 'sector';

# cols array(column, alias, asc/desc, type)
$sql['cols'][] = array('sector.id', 'id');
//$sql['cols'][] = array('user.flg_access', 'flg_access', false, 'boolean');
$sql['cols'][] = array('sector.nombre', 'NOMBRE', 'asc');
$sql['cols'][] = array('sector.activo', 'flg_access', false, 'boolean');

/*$sql['cols'][] = array('company.name', 'id_company');
$sql['cols'][] = array('user.birthday', 'birthday', false, 'date');*/
$sql['cols'][] = array('sector.id', 'action_edit');
$sql['cols'][] = array('sector.id', 'action_delete');
/*$sql['cols'][] = array('contratista.id', 'action_boton_accion');*/

/*
# SPECIFIC sql options

# filters
$sql['filters'][] = array('user.name LIKE :name', ':name', "%Veronica%", PDO::PARAM_STR);
$sql['filters'][] = array('user.id_level = :id_level', ':id_level', 2, PDO::PARAM_INT);

# group
$sql['groupby'] = 'exchange.id_exchange';
*/
$m['record_list'] = '<h4>Sectores</h4>';
?>

<?php include('header.php')?>
<?php grid($sql)?>
<?php include('footer.php')?>
