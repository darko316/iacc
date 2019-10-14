<?php 
	include('inc/config.php');
	include_once("combo.php");
?>
<br />

<form id="form1" name="form1" method="post" action="javascript:doc()">
	<table>
		<tr><td>Fecha Reporte<br><input type="text" id="fecha_reporte" name="fecha_reporte" value="" maxlength="8" size="10" onclick="displayCalendar(document.getElementById('fecha_reporte'),'dd-mm-yyyy',this)" readonly/></td></tr>
		<tr><td>Sector<br><?php echo comboSector();?></td></tr>
		<tr><td>Estado<br><?php echo comboEstado();?></td></tr>
		<tr><td>
			<table><tr>
				<td>Desde<br><input type="text" id="desde" name="desde" value="" maxlength="8" size="10" onclick="displayCalendar(document.getElementById('desde'),'dd-mm-yyyy',this)" readonly/></td>
				<td>Hasta<br><input type="text" id="hasta" name="hasta" value="" maxlength="8" size="10" onclick="displayCalendar(document.getElementById('hasta'),'dd-mm-yyyy',this)" readonly/></td>
				
			</tr></table>
			
			 </td></tr>
		<tr><td><input type="submit" name="button" id="button" value="REPORTE" class="buttonS" /></td></tr>
		
	</table>
	
	
</form>
</body>
 

