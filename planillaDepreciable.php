<?php session_start();?>
<?php
include('inc/config.php');
$page['title'] = 'Administración Planilla';

# delete handler
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_POST['action'] == 'delete') {
		# delete some records
		$sql = 'DELETE FROM planilla WHERE id = :id';
		$stmt = cnn()->prepare($sql);
		$stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
		$stmt->execute();
		exit(true);
	}
}

# table
//$sql['table'] = 'user INNER JOIN company ON company.id_company = user.id_company';
$sql['table'] = 'planilla 
					INNER JOIN vida_util ON vida_util.id = planilla.vida_util';


# cols array(column, alias, asc/desc, type)
$sql['cols'][] = array('planilla.id', 'id');
//$sql['cols'][] = array('user.flg_access', 'flg_access', false, 'boolean');
$sql['cols'][] = array('planilla.correlativo', 'CORR','desc');
$sql['cols'][] = array('planilla.especie', 'ESPECIE');
$sql['cols'][] = array('planilla.serie', 'SERIE');
$sql['cols'][] = array('vida_util.categoria', 'CATEGORIA');
$sql['cols'][] = array('vida_util.item', 'ITEM');
$sql['cols'][] = array('planilla.unitario', 'UNITARIO',null,'money');
$sql['cols'][] = array('(planilla.unitario / vida_util.adquirido_desde)', 'DEPRECIACION_AÑO',null,'money');
$sql['cols'][] = array('DATE_FORMAT(planilla.fecha_pedido,"%d-%m-%Y")', 'PEDIDO');



/*
# SPECIFIC sql options

# filters
$sql['filters'][] = array('user.name LIKE :name', ':name', "%Veronica%", PDO::PARAM_STR);
$sql['filters'][] = array('user.id_level = :id_level', ':id_level', 2, PDO::PARAM_INT);

# group
$sql['groupby'] = 'exchange.id_exchange';
*/
$sql['filters'][] = array('planilla.depreciable = :id', ':id', "1", PDO::PARAM_STR);

$m['record_list'] = '<h4>Planilla</h4>';
?>

<?php include('header.php')?>
<?php grid($sql)?>
<?php include('footer.php')?>