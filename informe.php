<?php session_start();?>
<?php
include('inc/config.php');
$page['title'] = 'Administración Proyecto';

# delete handler
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_POST['action'] == 'delete') {
		# delete some records
		$sql = 'DELETE FROM proyecto WHERE id = :id';
		$stmt = cnn()->prepare($sql);
		$stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
		$stmt->execute();
		exit(true);
	}
}

# table
//$sql['table'] = 'user INNER JOIN company ON company.id_company = user.id_company';
$sql['table'] = 'proyecto 
					INNER JOIN unidad_cmds ON unidad_cmds.id = proyecto.unidad_cmds
					INNER JOIN usuario ON usuario.id = proyecto.usuario
					INNER JOIN tipo_contrato ON tipo_contrato.id = proyecto.tipo_contrato
					INNER JOIN contratista ON contratista.id = proyecto.contratista
					INNER JOIN financiamiento ON financiamiento.id = proyecto.financiamiento ';


# cols array(column, alias, asc/desc, type)
$sql['cols'][] = array('proyecto.id', 'id');
//$sql['cols'][] = array('user.flg_access', 'flg_access', false, 'boolean');
$sql['cols'][] = array('proyecto.nombre', 'NOMBRE');
$sql['cols'][] = array('usuario.nombre_completo', 'ITO');
$sql['cols'][] = array('CONCAT(unidad_cmds.numero," ",unidad_cmds.nombre)', 'UNIDAD');
$sql['cols'][] = array('DATE_FORMAT(proyecto.fecha_acta_entrega_terreno,"%m-%Y")', 'FECHA', false, 'date');
$sql['cols'][] = array('contratista.nombre', 'CONTRATISTA');
//$sql['cols'][] = array('financiamiento.nombre', 'FINANCIAMIENTO');

//$sql['cols'][] = array('proyecto.id', 'action_boton_accion');
$sql['cols'][] = array('proyecto.id', 'action_estadistica');

//$sql['cols'][] = array('proyecto.id', 'action_delete');
$sql['cols'][] = array('proyecto.id', 'action_estado_pago');

/*
# SPECIFIC sql options

# filters
$sql['filters'][] = array('user.name LIKE :name', ':name', "%Veronica%", PDO::PARAM_STR);
$sql['filters'][] = array('user.id_level = :id_level', ':id_level', 2, PDO::PARAM_INT);

# group
$sql['groupby'] = 'exchange.id_exchange';
*/
$m['record_list'] = '<h4>Proyectos</h4>';
?>

<?php include('header.php')?>
<?php grid($sql,FALSE)?>
<?php include('footer.php')?>