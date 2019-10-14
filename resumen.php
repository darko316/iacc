<?php /*session_start();*/?>
<?php
include('inc/config.php');


?>

<?php include('header.php')?>
<form action="/sit/reporte2.php">


								<div class="row">					
									<div class="form-group col-md-2"><?php form_label2('FECHA INFORME', false);?><?php form_date('fechaReporte', null,false)?></div>
								</div>
								
								<div class="row">
								
									<?php
									$sql = 'SELECT id, nombre FROM estado ORDER BY nombre';
									$stmt = cnn()->prepare($sql);	
									$stmt->execute();
									$estado = $stmt->fetchAll();
									?>
									<div class="form-group col-sm-2"><?php form_label2('ESTADO', false);?><?php form_select('estado',$estado, null, !$id,FALSE)?></div>
									
								</div>			
								<div class="row">	
									<?php
									$sql = 'SELECT id, nombre FROM sector ORDER BY nombre';
									$stmt = cnn()->prepare($sql);
									$stmt->execute();
									$sector = $stmt->fetchAll();
									?>
									<div class="form-group col-sm-4"><?php form_label2('SECTOR', false);?><?php form_select('sector',$sector, null, !$id,FALSE)?></div>
								</div>				
								<br>
								<div class="row">
									<div class="form-group col-sm-1"><?php form_label2('CORRELATIVO', false);?></div>						
								
									<div class="form-group col-md-1"><?php form_label2('DESDE', false);?><?php form_input('desde', null,false)?></div>
									<div class="form-group col-md-1"><?php form_label2('HASTA', false);?><?php form_input('hasta', null,false)?></div>
								</div>

				
							
							
												
<hr><input type="submit" value="GENERAR INFORME">

</form>