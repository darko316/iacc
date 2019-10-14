<?php
	session_start();
 
	//include our function
	include 'functionrestaurar.php';
 
	if(isset($_POST['restore'])){
		
		//get post data
		$server = $_POST['server'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$dbname = $_POST['dbname'];
 
		//moving the uploaded sql file
		$filename = $_FILES['sql']['name'];
		move_uploaded_file($_FILES['sql']['tmp_name'],'upload/' . $filename);
		$file_location = 'upload/' . $filename;
 
		//restore database using our function
		$restore = restore($server, $username, $password, $dbname, $file_location);
 
		if($restore['error']){
			$_SESSION['error'] = $restore['message'];
		}
		else{
			$_SESSION['success'] = $restore['message'];
		}
 
	}
	else{
		$_SESSION['error'] = 'Completa las credenciales de la base de datos primero';
	}
 
	header('location:index.php');
 
?>