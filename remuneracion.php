<?php session_start();?>
<?php include_once("revisaSesion.php");?>
<?php
include('inc/config.php');
$page['title'] = 'Remuneracion';

$anho = $_REQUEST['anho'];

# table
$table['name'] = 'personal_general';
$table['id'] = 'id';
$id = ($data = form_check()) ? $_GET['id'] : false;   

$sql = "SELECT DISTINCT anho, mes
			FROM personal_general
			WHERE anho = ".$anho."
			AND rut = '".$_SESSION['cuenta']."'"." ORDER BY anho,mes asc";
$stmt = cnn()->prepare($sql);$stmt->execute();$listRemuneracion = $stmt->fetchAll();
	

$sqlSEP = "SELECT DISTINCT anho, mes
			FROM personal_sep
			WHERE anho = ".$anho."
			AND rut = '".$_SESSION['cuenta']."'"." ORDER BY anho,mes  asc";
$stmtSEP = cnn()->prepare($sqlSEP);$stmtSEP->execute();$listRemuneracionSEP = $stmtSEP->fetchAll();


$sqlPIE = "SELECT DISTINCT anho, mes
			FROM personal_pie
			WHERE anho = ".$anho."
			AND rut = '".$_SESSION['cuenta']."'"." ORDER BY anho,mes asc";
$stmtPIE = cnn()->prepare($sqlPIE);$stmtPIE->execute();$listRemuneracionPIE = $stmtPIE->fetchAll();


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
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
									<div class="col-md-4">  
										<div class="panel panel-default">
											<div class="panel-heading">
												<h3 class="panel-title">
													Liquidaciones de Sueldo 2017
												</h3>
											</div>
											<div class="panel-body">
												<div class="table-container">
													<?php if($listRemuneracion){?>
													<table class="table table-striped table-condensed table-hover">
														<thead>
															<tr>
																<th colspan="3"><h4 align="center">GENERAL</h4></th>																
															</tr>
															<tr>
																<th>AÑO</th>
																<th>MES</th>
																<th>VER</th>
															</tr>
														</thead>
														<tbody> 
															<?php foreach($listRemuneracion as $row) { ?>
															<tr>
																<td><?php echo $row['anho'];?></td>
																<td><?php echo getMes($row['mes']);?></td>
																<td><a href="generaLiquidacion.php?mes=<?php echo $row['mes'];?>&anho=<?php echo $row['anho'];?>" ><i class="fa fa-clock-o" aria-hidden="true"></i></a></td>
																
																
															</tr>
															<?php }?>
															
														</tbody>
													</table>
													<br>
													<?php }?>
													
													<?php if($listRemuneracionSEP){?>
													<table class="table table-striped table-condensed table-hover">
														<thead>
															<tr>
																<th colspan="3"><h4 align="center">S.E.P.</h4></th>																
															</tr>
															<tr>
																<th>AÑO</th>
																<th>MES</th>
																<th>VER</th>
															</tr>
														</thead>
														<tbody> 
															<?php foreach($listRemuneracionSEP as $rowSEP) { ?>
															<tr>
																<td><?php echo $rowSEP['anho'];?></td>
																<td><?php echo getMes($rowSEP['mes']);?></td>
																<td><a href="generaLiquidacionSEP.php?mes=<?php echo $rowSEP['mes'];?>&anho=<?php echo $rowSEP['anho'];?>" ><i class="fa fa-clock-o" aria-hidden="true"></i></a></td>
																
																
															</tr>
															<?php }?>
															
														</tbody>
													</table>
													<br>
													<?php }?>
													
													<?php if($listRemuneracionPIE){?>
													<table class="table table-striped table-condensed table-hover">
														<thead>
															<tr>
																<th colspan="3"><h4 align="center">P.I.E.</h4></th>																
															</tr>
															<tr>
																<th>AÑO</th>
																<th>MES</th>
																<th>VER</th>
															</tr>
														</thead>
														<tbody> 
															<?php foreach($listRemuneracionPIE as $rowPIE) { ?>
															<tr>
																<td><?php echo $rowPIE['anho'];?></td>
																<td><?php echo getMes($rowPIE['mes']);?></td>
																<td><a href="generaLiquidacionPIE.php?mes=<?php echo $rowPIE['mes'];?>&anho=<?php echo $rowPIE['anho'];?>" ><i class="fa fa-clock-o" aria-hidden="true"></i></a></td>
																
																
															</tr>
															<?php }?>
															
														</tbody>
													</table>
													<?php }?>
													<?php if($listRemuneracion || $listRemuneracionSEP || $listRemuneracionPIE){}else{echo 'NO TIENE LIQUIDACIONES ASOCIADAS';}?>
														
													
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
	</body>
</html>