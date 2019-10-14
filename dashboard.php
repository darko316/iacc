<?php
include('inc/config.php');
$page['title'] = 'Editar Proyecto';

# table
$table['name'] = 'proyecto';
$table['id'] = 'id';
$id = ($data = form_check()) ? $_GET['id'] : false;   


$sql = 'SELECT sum(valor) as "modif_monto",SUM(plazo) as "modif_plazo"
		FROM modificacion_contrato where proyecto = '.$id;
	$stmt = cnn()->prepare($sql);$stmt->execute();$modificacion = $stmt->fetchAll();
	//echo "Modificacion monto "."$".number_format($modificacion[0]['modif_monto'],0,'','.')."<br>";	
	//echo "Modificacion plazo ".$modificacion[0]['modif_plazo']."<br>";
	$modificacionPlazo = $modificacion[0]['modif_plazo'];
	$modificacionMonto = $modificacion[0]['modif_monto'];
	
	
$sql = 'SELECT proyecto.nombre as "nombreProyecto", contratista.nombre as "nombreContratista", 
			unidad_cmds.nombre as "nombreUnidad"
		FROM contratista, unidad_cmds, proyecto 
		WHERE proyecto.unidad_cmds = unidad_cmds.id
		AND proyecto.contratista = contratista.id 
		AND proyecto.id = '.$id;
	$stmt = cnn()->prepare($sql);$stmt->execute();$infoProyecto = $stmt->fetchAll();
	//$infoProyecto[0]['nombreProyecto']



//echo "acta entrega ".$data['fecha_acta_entrega_terreno']."<br>";

$fechaEntregaTerreno = $data['fecha_acta_entrega_terreno'];
//$addDias = '+'.$data['plazo'].' day';
$addDias = '+'.$data['plazo'] + $modificacionPlazo.' day';
//echo $addDias."<br>"; 
$fechaPlazo = strtotime ( $addDias , strtotime ( $fechaEntregaTerreno ) ) ;
$fechaPlazo = date ( 'Y-m-d' , $fechaPlazo );
 
//echo $fechaPlazo."<br>";

//echo "Plazo ".$data['plazo']." dias"."<br>";
$dias	= (strtotime(date('Y-m-d'))-strtotime($fechaPlazo))/86400;
$dias 	= abs($dias); $dias = floor($dias);	
//echo "Quedan ".$dias." dias"."<br>";

//echo "Monto Proyecto "."$".number_format($data['costo_total'],0,'','.')."<br>";
	$sql = 'SELECT SUM(estado_pago - (estado_pago*(porcentaje_retencion/100))) as valor_pago
			 FROM estado_pago where proyecto = '.$id;
	$stmt = cnn()->prepare($sql);$stmt->execute();$estadoPago = $stmt->fetchAll();
	$consumido = $estadoPago[0]['valor_pago'];
	//echo "Consumido a la fecha "."$".number_format($consumido,0,'','.')."<br>";
	$porConsumir = $data['costo_total']-$estadoPago[0]['valor_pago'];
	//echo "Resta por consumir "."$".number_format($porConsumir,0,'','.')."<br>";
	$porcentajeConsumido =  $consumido * 100 / $data['costo_total'];
	//echo "% avance monto proyecto ".number_format($porcentajeConsumido,1,',','.')." %"."<br>";
	
	$sql = 'SELECT estado_pago.estado_pago - (estado_pago.estado_pago*(estado_pago.porcentaje_retencion/100)) as "valor_pago",estado_pago.estado_pago as "estado_pago", financiamiento.nombre as "financiamiento",
			estado_pago.fecha_factura as "fecha"
			 FROM estado_pago,financiamiento 
			 WHERE  estado_pago.financiamiento = financiamiento.id
			 AND estado_pago.proyecto = '.$id;
	$stmt = cnn()->prepare($sql);$stmt->execute();$estadoPagoList = $stmt->fetchAll();
	
	

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Vertical Scoop Navigation</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800" rel="stylesheet">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet"> 
    <link href="assets/css/linearicons.css" rel="stylesheet">
	<link href="assets/css/simple-line-icons.css" rel="stylesheet">
	<link href="assets/css/ionicons.css" rel="stylesheet">
	<link href="assets/css/flag-icon.min.css" rel="stylesheet">
	<link href="assets/css/fakeLoader.css" rel="stylesheet">
	<link href="assets/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="assets/css/scoop-vertical.css" rel="stylesheet">
    <link href="assets/css/jquery.mCustomScrollbar.css" rel="stylesheet">
	
	
	<script src="assets/js/jquery.1.11.3.min.js"></script>
	<script src="assets/js/lib/fakeLoader.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/scoop.min.js"></script>
	<script src="assets/js/vertical-demo.js"></script> 	
	<script src="assets/js/lib/echarts/echarts-all.js"></script>
	<script src="assets/js/lib/echarts/theme/echarts-theme.js"></script>
	<script src="assets/js/lib/echarts/echartsConfig.js"></script>
	<script src="assets/js/lib/sparkline.min.js"></script>

	<script src="assets/js/lib/jquery.mCustomScrollbar.concat.min.js"></script> 
	<script src="assets/js/lib/jquery.mousewheel.min.js"></script> 
 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    

    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/chartli.js"></script>
    
	</head>
	
	<body>
			
		
		<div id="scoop" class="scoop">
			<div class="scoop-overlay-box"></div>
			<div class="scoop-container">  
				
				<div class="scoop-main-container">
					<div class="scoop-wrapper">
						<div class="scoop-content"> 
							<div class="scoop-inner-content">
								
								<div class="row">
								<div class="col-md-5">
												<div class="pageview-statistics-panel">
													<div class="list-group">  
														<div class="list-group-item">
															<h3 class ="pageview-statistics-title">Proyecto: <?php echo $infoProyecto[0]['nombreProyecto'];?></h3>
														</div>
														<div class="list-group-item"> 
															<span class="statistics-name">Unidad</span>
															<span class="value"><?php echo $infoProyecto[0]['nombreUnidad'];?></span>
														</div>
														<div class="list-group-item"> 
															<span class="statistics-name">Contratista</span>
															<span class="value"><?php echo $infoProyecto[0]['nombreContratista'];?></span>
														</div>										
													</div>
												</div>
											</div>
								</div>
								
								
								 <div class="row">
									<div class="col-md-4 col-sm-6">
										<div class="info-widget">
											<div class="panel panel-default balance-panel-theme">											 
												<div class="panel-body">
													<div class="info-box-stats">
														<p class="counter"><?php echo "$".number_format($data['costo_total'],0,'','.');?></p>
														<span class="info-box-title">Monto Original Proyecto</span>
													</div>
													<div class="info-box-icon">
														<i class="fa fa-money" aria-hidden="true"></i>
													</div>
													
												</div> 
											</div> 
										</div>
									</div>
									<div class="col-md-3 col-sm-6">
										<div class="info-widget">
											<div class="panel panel-default balance-panel-theme">											 
												<div class="panel-body">
													<div class="info-box-stats">
														<p class="counter"><?php echo $data['plazo'];?> días</p>
														<span class="info-box-title">Duración Proyecto</span>
													</div>
													<div class="info-box-icon">
														<i class="fa fa-clock-o" aria-hidden="true"></i>
													</div>
													
												</div> 
											</div> 
										</div>
									</div>
									
									<div class="col-md-3 col-sm-6">
										<div class="info-widget">
											<div class="panel panel-default balance-panel-theme">											 
												<div class="panel-body">
													<div class="info-box-stats">
														<p class="counter"><?php echo $dias;?> días</p>
														<span class="info-box-title">Días restantes</span>
													</div>
													<div class="info-box-icon">
														<i class="fa fa-clock-o" aria-hidden="true"></i>
													</div>
													
												</div> 
											</div> 
										</div>
									</div>
								
									
								</div>
								
								
								
								
								<div class="row">
									<div class="col-md-3 col-sm-6">
										
										<div class="info-widget">
											<div class="panel panel-default earnings-panel-theme">											 
												<div class="panel-body">
													<div id="chartli3" style="height:130px;width:210px;"></div>
													<div class="info-box-stats" align="center">
														<h4>Avance Financiero <?php echo number_format($porcentajeConsumido,1,',','.')." %";?></h4>
													</div>
																										
												</div> 
											</div> 
										</div>
									</div>
								 
									<div class="col-md-6">  
										 
										<div class="panel panel-default">
											<div class="panel-heading">
												<h3 class="panel-title">
													Estados de Pago
												</h3>
											</div>
											<div class="panel-body">
												<div class="table-container">
													<table class="table table-striped table-condensed table-hover">
														<thead>
															<tr>
																<th>Fecha</th>
																<th>Valor Pago</th>
																<th>Estado Pago</th> 
																<th>Financiamiento</th>
															</tr>
														</thead>
														<tbody> 
															<?php foreach($estadoPagoList as $row) { ?>
															<tr>
																<td><?php echo $row['fecha'];?></td>
																<td><?php echo get_money($row['valor_pago'],true);?></td>
																<td><?php echo get_money($row['estado_pago'],true);?></td>
																<td><?php echo $row['financiamiento'];?></td> 
																<?php 
																	$sumaValorPago += $row['valor_pago'];
																	$sumaEstadoPago += $row['estado_pago'];
																	?>
																
															</tr>
															<?php }?>
															<tr class="total">
																<td>Total</td>
																<td><?php echo get_money($sumaValorPago,true);?></td>
																<td><?php echo get_money($sumaEstadoPago,true);?></td> 
															</tr>
														</tbody>
													</table>
												</div>
											</div> 
										</div>
									</div>
									
								</div>
								
								
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
		<?php include('graficoGauged.php');?>
	</body>
</html>