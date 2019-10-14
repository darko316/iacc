<?php
include('inc/config.php');
$page['title'] = 'Editar USUARIO';

# table
$table['name'] = 'usuario';
$table['id'] = 'id';
$id = ($data = form_check()) ? $_GET['id'] : false;


# fields array[name](required, filter, min, max, discard, equal)
$fields['clave'] = array(true, 'password');

# save data
form_save();
?>
<?php include('header.php')?>


<?php form_header('<h4>Cambiar Clave</h4>',true,true)?>

<div class="row">
	<div class="form-group col-md-3"><?php form_label2('NOMBRE COMPLETO', false);?>: <?php echo $data['nombre_completo']?></div>
	<div class="form-group col-md-3"><?php form_label2('CUENTA', false);?>: <?php echo $data['cuenta']?></div>
</div>	
<div class="row">	
	
</di
<div class="row">	
	<div class="form-group col-md-4"><?php form_label2('NUEVA CLAVE', true);?><?php form_input('clave', null,false)?></div>	
</div>


<?php form_footer(false)?>

<?php include('footer.php')?>