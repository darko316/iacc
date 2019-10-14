
<?php 
	include('inc/config.php');
	$sql = "SELECT usuario.id,usuario.cuenta,usuario.nombre_completo,usuario.clave, tipo_usuario.nombre as tipo_cuenta 
			FROM usuario, tipo_usuario 
			WHERE usuario.tipo_usuario = tipo_usuario.id 
			AND usuario.activo = 1  
			AND usuario.cuenta = '".$_GET['cuenta']."'";			
	$stmt = cnn()->prepare($sql);
	$stmt->execute();
	$usuario = $stmt->fetchAll();
	
	if($usuario && validarClave($_GET['clave'],$usuario[0]['clave'])){
		session_start();
		$_SESSION['id'] = $usuario[0]['id'];$_SESSION['cuenta'] = $usuario[0]['cuenta'];$_SESSION['tipo'] = $usuario[0]['tipo_cuenta'];	$_SESSION['nombre'] = $usuario[0]['nombre_completo'];
		//header('Location: principal2.php');
		echo "<script language=Javascript> location.href=\"principal2.php\"; </script>"; 
	    
	    
	}
	else{
		echo("<script>var r = alert('Las siguientes situaciones puede causar este mensaje: \\n 1.- Cuenta deshabilitada  \\n 2.- Cuenta incorrecta  \\n 3.- Clave incorrecta');	if (!r) {window.location = 'index.php';} </script>");
		}
	
	 ?>
 