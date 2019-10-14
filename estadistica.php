<?php
	include('inc/config.php');
	setlocale(LC_TIME, 'es_CL.UTF-8');
	
	$sql = 'SELECT anho,mes,cod_establecimiento,establecimiento,sum(total_haberes) 
				FROM `haber_general` 
				GROUP BY anho,mes,cod_establecimiento,establecimiento ORDER BY anho,mes,cod_establecimiento';
				
	$stmt = cnn()->prepare($sql);$stmt->execute();$modificacion = $stmt->fetchAll();	 

?>