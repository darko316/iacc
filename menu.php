<?php session_start();?>
<?php include("../utils/cache/noCache.php");?>
<?php include_once("../clases/Controlador.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SGOM - CMDS</title>


<link rel="stylesheet" href="css/sgom.css">
<link rel="stylesheet" href="css/font.awesome.css">

</head>

<body>


<table border="0">
	<tr><td colspan="10"><hr></td></tr>
	<tr cellspacing="6">
		<td align="center"><a href="proyecto.php" target="prueba1" id="link" title="Proyecto"><img src="images/proyecto.png" width="45px"/><BR>Proyecto</a></td>
		<?php if($_SESSION['tipo']== 1){?>			
			<td align="center"><a href="unidad.php" target="prueba1" id="link" title="BANCO"><img src="images/unidad.png" width="45px"/><BR>Unidad</a></td>
			<td align="center"><a href="banco.php" target="prueba1" id="link" title="BANCO"><img src="images/banco.png" width="45px"/><BR>Banco</a></td>
			<td align="center"><a href="contratista.php" target="prueba1" id="link" title="Contratista"><img src="images/contratista.png" width="45px"/><BR>Contratista</a></td>
			<td align="center"><a href="financiamiento.php" target="prueba1" id="link" title="BANCO"><img src="images/financiamiento.png" width="45px"/><BR>Fondos</a></td>
			<td align="center"><a href="usuario.php" target="prueba1" id="link" title="BANCO"><img src="images/usuario.png" width="45px"/><BR>Usuario</a></td>
			<td align="center"><a href="tipo.contrato.php" target="prueba1" id="link" title="TIPO CONTRATO"><img src="images/tipo_adjudicacion.png" width="45px"/><BR>Tipo Adj.</a></td>
			<td align="center"><a href="boleta.garantia.tipo.php" target="prueba1" id="link" title="BOLETA GARANTIA TIPO"><img src="images/tipo_boleta.png" width="45px"/><BR>Tipo Boleta</a></td>
		<?php }?>
	</tr>
	<tr><td colspan="10"><hr></td></tr>
</table>

