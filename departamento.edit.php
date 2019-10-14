<?php
include('inc/config.php');
$page['title'] = 'Editar Unidad CMDS';

# table
$table['name'] = 'departamento';
$table['id'] = 'id';
$id = ($data = form_check()) ? $_GET['id'] : false;


# fields array[name](required, filter, min, max, discard, equal)
$fields['nombre'] = array(true, false, 2, 100);
$fields['activo'] = array(false, true, 'checkbox');

# save data
form_save();
?>
<?php include('header.php')?>

<?php
	if($id){form_header('<h4>Actualizar Departamento</h4>',false,false);}
	else{form_header('<h4>Agregar Nuevo Departamento</h4>',false,false);}
?>


<div class="row">
	<div class="form-group col-sm-6"><?php form_input('nombre', $data['nombre'])?></div>		
</div>

<div class="row">
	<div class="form-group col-md-6"><?php form_checkbox('activo', $data['activo'],false, $data['activo'])?><?php form_label2('activo', false);?></div>
</div>

<?php form_footer()?>

<?php include('footer.php')?>