<?php
include('inc/config.php');
$page['title'] = 'Editar Estado';

# table
$table['name'] = 'estado';
$table['id'] = 'id';
$id = ($data = form_check()) ? $_GET['id'] : false;


# fields array[name](required, filter, min, max, discard, equal)
$fields['nombre'] = array(requerido, false, 2, 50);
$fields['activo'] = array(false, true, 'checkbox');

# save data
form_save();
?>
<?php include('header.php')?>

<?php
	if($id){form_header('<h4>Actualizar Estado</h4>',false,false);}
	else{form_header('<h4>Agregar Nuevo Estado</h4>',false,false);}
?>

<div class="row">
	<div class="form-group col-md-6"><?php form_input('nombre', $data['nombre'])?></div>
	
</div>
<div class="row">
	<div class="form-group col-md-6"><?php form_checkbox('activo', $data['activo'],false, $data['activo'])?><?php form_label2('ACTIVO', false);?></div>
</div>


<?php form_footer()?>

<?php include('footer.php')?>