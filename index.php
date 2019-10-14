<?php
session_start(); 
session_destroy();
?>
<?php
include('inc/config.php');
$page['title'] = 'Acceso sistema SCCT';


# fields array[name](required, filter, min, max, discard, equal)
$fields['cuenta'] = array(true, false, 2, 100);
$fields['clave'] = array(true, 'password');




$m['save_changes'] = 'ACCESAR';
?>

<?php include('header.php')?>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/crypt.js"></script>

<form id="form1" name="form1" method="post" action="javascript:identidad()">
<br><br>
<center>
	<img align="center" src="images/logo.png" border="0" width="100px" /></br></br>	
<table>
	<tr><td>
		<?php form_header('<center><h4>ACCESO A SISTEMA <br>Sistema de Inventario en Tránsito</H4></center>',null,false)?>
		<?php form_label2('CUENTA', true);?><?php form_input('cuenta', '',FALSE)?><br>
		<?php form_label2('CLAVE', true);?><?php form_input('clave', null,FALSE)?>
		<br>
		<button type="submit" class="btn btn-primary"><i class="fa fa-user" aria-hidden="true"></i> Accesar</button>
		
		</td></tr>
</table>
</center>
<?php include('footer.php')?>

<div id="divPrimario"></div>
</form>
