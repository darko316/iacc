<?php
include('inc/config.php');
$page['title'] = 'Editar Proyecto';

# table
$table['name'] = 'planilla';
$table['id'] = 'id';
$id = ($data = form_check()) ? $_GET['id'] : false;   



# fields array[name](required, filter, min, max, discard, equal)
$fields['correlativo'] = array(true, false, 2, 100);//
$fields['especie'] = array(true, false, 2, 600);//
$fields['serie'] = array(false, false, 0, 200);//
$fields['num_pieza'] = array(false, false, 0, 100);//
$fields['unitario'] = array(false, false, 0, 100);//
$fields['orden_compra'] = array(false, false, 0, 100);//
$fields['observacion'] = array(false, false);//
$fields['fecha_pedido'] = array(false, 'date');



$fields['departamento'] = array(true, 'idcheck');//
$fields['estado'] = array(true, 'idcheck');//
$fields['sector'] = array(true, 'idcheck');//
$fields['depreciable'] = array(false, 'idcheck');//
$fields['vida_util'] = array(false,false);

	



# save data
form_save();
?>

<?php include('header.php')?>
<?php
	if($id){form_header('<h4>Actualizar Planilla</h4>',false,false);}
	else{form_header('<h4>Agregar Nueva Especie Planilla</h4>',false,false);}
?>

<div class="row">
	<div class="form-group col-md-2"><?php form_label2('CORRELATIVO', TRUE);?><?php form_input('correlativo', $data['correlativo'],false)?></div>
	<div class="form-group col-md-6"><?php form_label2('ESPECIE', TRUE);?><?php form_input('especie', $data['especie'],false)?></div>	
</div>		

<div class="row">
	<div class="form-group col-md-1"><?php form_label2('SERIE', false);?><?php form_input('serie', $data['serie'],false)?></div>
	<div class="form-group col-md-1"><?php form_label2('N° PIEZA', false);?><?php form_input('num_pieza', $data['num_pieza'],false)?></div>	
	<div class="form-group col-md-1"><?php form_label2('UNITARIO', false);?><?php form_input('unitario', $data['unitario'],false)?></div>
	<div class="form-group col-md-2"><?php form_label2('FECHA PEDIDO', false);?><?php form_date('fecha_pedido', $data['fecha_pedido'],false)?></div>
	<div class="form-group col-md-2"><?php form_label2('ORDEN COMPRA', false);?><?php form_input('orden_compra', $data['orden_compra'],false)?></div>
</div>
	

<div class="row">

	<?php
	$sql = 'SELECT id, nombre FROM departamento ORDER BY nombre';
	$stmt = cnn()->prepare($sql);
	$stmt->execute();
	$departamento = $stmt->fetchAll();
	?>
	<div class="form-group col-sm-3"><?php form_label2('DEPARTAMENTO', true);?><?php form_select('departamento',$departamento, $data['departamento'], !$id,FALSE)?></div>
	
	<?php
	$sql = 'SELECT id, nombre FROM estado ORDER BY nombre';
	$stmt = cnn()->prepare($sql);
	$stmt->execute();
	$estado = $stmt->fetchAll();
	?>
	<div class="form-group col-sm-2"><?php form_label2('ESTADO', true);?><?php form_select('estado',$estado, $data['estado'], !$id,FALSE)?></div>
	
	<?php
	$sql = 'SELECT id, nombre FROM sector ORDER BY nombre';
	$stmt = cnn()->prepare($sql);
	$stmt->execute();
	$sector = $stmt->fetchAll();
	?>
	<div class="form-group col-sm-4"><?php form_label2('SECTOR', true);?><?php form_select('sector',$sector, $data['sector'], !$id,FALSE)?></div>
</div>

<div class="row">
	<div class="form-group col-sm-9"><?php form_label2('OBSERVACION', false);?><?php form_textarea('observacion', $data['observacion'],FALSE)?></div>
</div>

<?php
	$sql = 'SELECT id, concat(categoria, " ", item) categoria FROM `vida_util`';
	$stmt = cnn()->prepare($sql);
	$stmt->execute();
	$vida_util = $stmt->fetchAll();
	?>
<div class="row">
	<div class="form-group col-sm-3"><?php form_label2('CATEGORIA VIDA UTIL', false);?><?php form_select('vida_util', $vida_util, $id, !$id,FALSE)?></div>
</div>

<div class="row">
	<div class="form-group col-md-6"><?php form_checkbox('depreciable', $data['depreciable'],false, $data['depreciable'])?><?php form_label2('BIEN DEPRECIABLE', false);?></div>
</div>


<?php form_footer()?>

<?php include('footer.php')?>