<?php
include('inc/config.php');
$page['title'] = 'Editar USUARIO';

# table
$table['name'] = 'usuario';
$table['id'] = 'id';
$id = ($data = form_check()) ? $_GET['id'] : false;


# fields array[name](required, filter, min, max, discard, equal)
$fields['nombre_completo'] = array(true, false, 2, 50);
$fields['cuenta'] = array(false, false, 2, 50);
if(!$id){$fields['clave'] = array(false, 'password');}

$fields['tipo_usuario'] = array(false,false);
$fields['activo'] = array(false, true, 'checkbox');

# save data
form_save();
?>
<?php include('header.php')?>

<?php if($id){form_header('<h4>Actualizar Usuario</h4>',false,false);}else{form_header('<h4>Agregar Nuevo Usuario</h4>',false,false);}?>

<div class="row">
	<div class="form-group col-md-6"><?php form_input('nombre_completo', $data['nombre_completo'])?></div>	
</div>
<div class="row">
	<div class="form-group col-md-3"><?php form_input('cuenta', $data['cuenta'])?></div>
	<?php if(!$id){?>
	<div class="form-group col-md-4"><?php form_input('clave', $data['clave'])?></div>	
	<?php }?>
</div>

<?php
	$sql = 'SELECT id, nombre FROM tipo_usuario';
	$stmt = cnn()->prepare($sql);
	$stmt->execute();
	$tipo_usuario = $stmt->fetchAll();
	?>
<div class="row">
	<div class="form-group col-sm-3"><?php form_select('tipo_usuario', $tipo_usuario, $_SESSION['tipo_usuario'], !$id)?></div>
</div>

<div class="row">
	<div class="form-group col-sm-2"><?php form_checkbox('activo', $data['activo'],false,$data['activo'])?><?php form_label2('ACTIVO', false);?></div>
</div>


<?php form_footer()?>

<?php include('footer.php')?>