<?php session_start();?>
<?php include_once("revisaSesion.php");?>
<!DOCTYPE html>
<html lang="en">
  <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" value="notranslate">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="stylesheet" href="css/sbn.css">
    
    
    
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800" rel="stylesheet">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">  
    <link href="assets/css/linearicons.css" rel="stylesheet">
	<link href="assets/css/simple-line-icons.css" rel="stylesheet">
	<link href="assets/css/ionicons.css" rel="stylesheet"> 
	<link href="assets/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="assets/css/scoop-vertical.css" rel="stylesheet">
    <link href="assets/css/jquery.mCustomScrollbar.css" rel="stylesheet">
	
	
	<script src="assets/js/jquery.1.11.3.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/scoop.min.js"></script>     
	<script src="assets/js/lib/jquery.mCustomScrollbar.concat.min.js"></script> 
	<script src="assets/js/lib/jquery.mousewheel.min.js"></script> 
	
	<script>
		$( document ).ready(function() {
			$( "#scoop" ).scoopmenu({
				themelayout: 'vertical',
				HeaderBackground: 'theme8',
				LHeaderBackground : 'theme8',
				NavbarBackground: 'theme8',
				collapseVerticalLeftHeader: true,
			});
		});
	</script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	</head>
	<body> 
		<div id="scoop" class="scoop">
			<div class="scoop-overlay-box"></div>
			<div class="scoop-container">  
				<header class="scoop-header">
					<div class="scoop-wrapper"> 
						<div class="scoop-left-header" style="color: #FFFFFF"> 
							<div class="scoop-logo"> 
								<!-- <a href="#"><span class="logo-icon"><i class="ion-stats-bars"></i></span> -->
								<span class="logo-text"><img src="images/logo.png" width="60px" /><br>TEST IACC<BR></span><!--</a>-->								
							</div> 
						</div>
						<div class="scoop-right-header" > 
							<div class="sidebar_toggle"><a href="javascript:void(0)"><i class="icon-menu"></i></a></div>
							<strong>SISTEMA DE INVENTARIO EN TRANSITO (S.I.T.)</strong> | <strong>Usuario:</strong> <?php echo $_SESSION['cuenta'];?> | <strong>Tipo:</strong> <?php echo $_SESSION['tipo'];?> | <strong>Nombre:</strong> <?php echo $_SESSION['nombre'];?>   
							<div class="scoop-rl-header"></div>
							<div class="scoop-rr-header"></div>
						</div>
					</div>
				</header>
				<div class="scoop-main-container">
					<div class="scoop-wrapper">
						<nav class="scoop-navbar">  
							<div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
							<div class="scoop-inner-navbar"> 
								<div class="scoop-navigatio-lavel"></div>
								<div class="scoop-navigatio-lavel"></div>
								<div class="scoop-navigatio-lavel"></div>
								<div class="scoop-navigatio-lavel"></div>
								 
								<ul class="scoop-item scoop-brand">
									<li class=" ">
										<a href="resumen.php" target="prueba1">
											<span class="scoop-micon"><i class="fa fa-book"></i></span>
											<span class="scoop-mtext">Reporte</span>
											<span class="scoop-mcaret"></span>
										</a>
									</li>
								</ul>
								
								<ul class="scoop-item scoop-left-item"> 
									<?php if($_SESSION['tipo'] == "Administrador"){?>   
									<li class="scoop-hasmenu">
										<a href="javascript:void(0)">
											<span class="scoop-micon"><i class="fa fa-toggle-on"></i></span>
											<span class="scoop-mtext">Administración</span>
											<span class="scoop-mcaret"></span>
										</a>
										<ul class="scoop-submenu">											
											<li class=" ">
												<a href="planilla.php" target="prueba1">
													<span><i class="fa fa-object-group"></i></span>
													<span>Inventario</span>
													<span class="scoop-mcaret"></span>
												</a> 
											</li>
											<li class=" ">
												<a href="planillaDepreciable.php" target="prueba1">
													<span><i class="fa fa-object-group"></i></span>
													<span>Inventario Depreciable</span>
													<span class="scoop-mcaret"></span>
												</a> 
											</li>
											<li class=" ">
												<a href="estado.php" target="prueba1">
													<span><i class="fa fa-arrows-alt"></i></span>
													<span>Estado</span>
													<span class="scoop-mcaret"></span>
												</a> 
											</li>
											<li class="  ">
												<a href="sector.php" target="prueba1">
													<span><i class="fa fa-venus-mars"></i></span>
													<span>Sector</span>
													<span class="scoop-mcaret"></span>
												</a> 	
											</li>
											<li class=" ">
												<a href="departamento.php" target="prueba1">
													<span><i class="fa fa-bullhorn"></i></span>
													<span>Departamento</span>
													<span class="scoop-mcaret"></span>
												</a> 
											</li>
																					
											<li class=" ">
												<a href="usuario.php" target="prueba1">
													<span><i class="fa fa-user"></i></span>
													<span>Usuario</span>
													<span class="scoop-mcaret"></span>
												</a> 
											</li>
												<li class=" ">
												<a href="backupp.html" target="prueba1">
													<span><i class="fa fa-object-group"></i></span>
													<span>Backup</span>
													<span class="scoop-mcaret"></span>
												</a> 
											</li>
												<li class=" ">
												<a href="restaura.php" target="prueba1">
													<span><i class="fa fa-object-group"></i></span>
													<span>RestaurarBD</span>
													<span class="scoop-mcaret"></span>
												</a> 
											</li>
										</ul>
									</li>
											<li class=" ">
												<a href="manual.pdf" target="prueba1">
													<span><i class="fa fa-object-group"></i></span>
													<span>Manual de Usuario</span>
													<span class="scoop-mcaret"></span>
												</a> 
											</li>
										</ul>
									</li>
									<?php }?> 
									<li class=" ">
										<a href="index.php">
											<span class="scoop-micon"><i class="fa fa-hand-o-left"></i></span>
											<span class="scoop-mtext">Cerrar Sesión</span>
											<span class="scoop-mcaret"></span>
										</a> 
									</li> 
									
								</ul> 
						<br>							 
						</nav> 
						
						<div class="scoop-content"> 
							<div class="scoop-inner-content" >
								<!-- <iframe name="menu2" width="80%" height="100%" border=0 STYLE="position:absolute; left:200px; top:100px; border: 0px"> --> 
								<?php /*if($_REQUEST['page']){include_once($_REQUEST['page']);}else{echo "hola bienvenido";}*/?>
								<!-- </iframe>  -->
								<iframe name="prueba1" src="blanco.php" border="0" STYLE="border:0px; height: 800px; width: 100%;"></iframe>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</body>
</html>