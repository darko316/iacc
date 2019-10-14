<?php
		
	include('inc/config.php');
	
	
	$sql = "SELECT * FROM usuario WHERE tipo_usuario = 5";
						
	$stmt = cnn()->prepare($sql);
	$stmt->execute();
	$usuario = $stmt->fetchAll();
	
		
	
	// Create connection
	$conn2 = new mysqli("localhost", "root", "", "municip4_remuneracion");
	// Check connection
	if ($conn2->connect_error) {
	    die("Connection failed: " . $conn2->connect_error);
		return "error conexion";
	}

	foreach($usuario as $row){
		$encrypted = password_hash ( md5($row['clave']) , PASSWORD_DEFAULT );
		//actualizar registro
		$sqlUp = "UPDATE usuario SET clave = '".$encrypted."' WHERE id = ".$row['id'];
		//echo $sqlUp."</br>"; 
		$r = $conn2->query($sqlUp);
		/*if ($conn2->query($sqlUp) === TRUE) {
		    return "Nuevo registro actualizado "."\n";
		} else {
		   return "Error: " . $sqlUp . "\n" . $conn2->error;
		   
		}*/
		
	}

		
	$conn2->close();	



		

		
?>