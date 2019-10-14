<?php 
	if(!$_SESSION['cuenta']){
		echo("<script>var r = alert('Sesión Caducada');	if (!r) {window.location = 'index.php';} </script>");
	}
	
	 ?>
