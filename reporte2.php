<?php
	setlocale(LC_TIME, 'es_CL.UTF-8');
	include('inc/config.php');
	
	
		
	/*echo "fecha reporte ".$_REQUEST["fecha"]."<br>";
	echo "desde ".$_REQUEST["desde"]."<br>";
	echo "hasta ".$_REQUEST["hasta"]."<br>";*/
	
	
	
//	$fechaReporte = $_REQUEST["fechaReporte"];
	
	if($_REQUEST["fechaReporte"]){$fechaReporte = $_REQUEST["fechaReporte"];}else{$fechaReporte = date('Y-m-d');}
	
	$fecha = $fechaReporte;
	$fechaTexto = strtotime ( '0 day' , strtotime ( $fecha ) ) ;
	$fechaTexto = date ( 'd-m-Y' , $fechaTexto );
	$fechaTexto = strftime("%d de %B %Y", strtotime($fechaTexto));
	$fechaReporte = $fechaTexto;
	
		
	
	$sql = "SELECT planilla.id, planilla.correlativo, planilla.especie, planilla.serie, planilla.num_pieza, planilla.unitario, planilla.fecha_pedido, planilla.orden_compra, planilla.observacion, departamento.nombre as 'departamento', estado.nombre as 'estado', sector.nombre as 'sector' 
			FROM planilla, departamento, sector, estado
			WHERE planilla.departamento = departamento.id
			AND planilla.sector = sector.id
			AND planilla.estado = estado.id	";
			
	$AND = null;		
	if($_REQUEST["estado"]!= null){
		$AND = " AND planilla.estado = ".$_REQUEST["estado"];
	}			
	
	if($_REQUEST["sector"]!= null){
		$AND = " AND sector = ".$_REQUEST["sector"];		
	}
	
	if($_REQUEST["desde"]!= null && $_REQUEST["hasta"]!= null){
		$AND = " AND planilla.correlativo BETWEEN ".$_REQUEST["desde"]." AND ".$_REQUEST["hasta"];
	}
	
	$sql = $sql.$AND." ORDER BY planilla.correlativo ASC";
	
	//echo $sql;
	
	$stmt = cnn()->prepare($sql); $stmt->execute(); $reporte = $stmt->fetchAll();
	 	


require_once('tcpdf/tcpdf_include.php');
$pdf = new TCPDF("LANDSCAPE", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->setHeaderFont(Array('courier', '', 9));
$pdf->setFooterFont(Array('courier', '', 7));
$pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('courier', '', 7,true);
$pdf->AddPage();
	
	
	  
$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="css/sir.css" /> 
<title>Reporte Inventario</title>


<style>       
       .celda{font:verdana,serif;font-size:9px;padding: 4px;} 
       .encabezado{font:verdana,serif;font-size:9px;text-align:center;} 
       .firma{font:verdana,serif;font-size:11px;}
	   .SaltoDePagina {PAGE-BREAK-AFTER: always}
     </style>

</head>

<body>
	
  
  ';
  
  $encabezado = '<br><br><br><h3>Ilustre Municipalidad de Taltal </h3>  
  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td align="center"><H3>PLANILLA DE INVENTARIO</H3></td></tr>
</table>
<table width="100%" border="1" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td width="5%"><strong>CORR</strong></td>
    <td width="30%"><strong>ESPECIE</strong></td>    
    <td width="10%"><strong>SERIE</strong></td>    
    <td width="5%"><strong>N PIEZA</strong></td>
    <td width="5%"><strong>ESTADO</strong></td>
    <td width="10%"><strong>UNITARIO</strong></td>
    <td width="10%"><strong>F. PEDIDO</strong></td>
    <td width="5%"><strong>OC</strong></td>
    <td width="10%"><strong>SECTOR</strong></td>
    <td width="10%"><strong>OBSERVACION</strong></td>
  </tr>
  ';
  
   $verEncabezado = true;
   $contarFila = 0;
	foreach($reporte as $row) {
  			
  		if($contarFila == 6){
  			$verEncabezado=true; 
  			$html .= '</TABLE><BR>';//.$fechaReporte;
  			
  			$espacio = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	
			$html .= '<br><br><br><br>
			<table align="center" width="70%"  border="0">
				<tr>
				    <td width="30%"></td>
				    <td width="30%"><hr width="100%" align="center" /></td>
					<td>'.$espacio.'</td>
					<td width="30%"><hr width="100%" align="center" /></td>
				</tr>
				<tr>
				    <td width="30%"></td>
					<td align="center">ENCARGADO DE BODEGA</td>
					<td align="center">'.$espacio.'</td>
					<td align="center">ALCALDE</td>
				</tr>
			</table>
			<div class="SaltoDePagina"></div>
			';
  			 
  			$contarFila = 0;}
  					
  		if($verEncabezado){ $html .= $encabezado; $verEncabezado=false;}		
  			
  		
  		$html .= '<tr>
  					<td align="center">'.$row["correlativo"].'</td>
				    <td>'.$row["especie"].'</td>
				    <td align="center">'.$row["serie"].'</td>
				    <td align="center">'.$row["num_pieza"].'</td>
				    <td align="center">'.$row["estado"].'</td>
				    <td align="center">$'.number_format($row["unitario"],0,",",".").'</td>
				    <td>'.$row["fecha_pedido"].'</td>
				    <td>'.$row["orden_compra"].'</td>
				    <td align="center">'.$row["sector"].'</td>
				    <td>'.$row["observacion"].'</td></tr>';
				    
				    
				    
  		$contarFila++; 
  	}
	
	$html .= '</table><br>';//.$fechaReporte
		
	$espacio = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	
	$html .= '<br><br><br><br>
	<table align="center"  border="0">
				<tr>
					<td width="190"><hr width="190px" align="center" /></td>
					<td>'.$espacio.'</td>
					<td width="190px"><hr width="190px" align="center" /></td>
				</tr>
				<tr>
					<td align="center">ENCARGADO DE BODEGA</td>
					<td align="center">'.$espacio.'</td>
					<td align="center">ALCALDE</td>
				</tr>
			</table>';		
	$html .= '</body></html>';
	
	//$html = utf8_decode($html);
	
	//echo $html;
	
	
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();

$pdf->Output($nombreArch = "ReportInventario.pdf", 'I');

	
	
	//echo $html; 	
	/*require_once("dompdf/dompdf_config.inc.php");
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$orientation = "landscape";
	$tamano = "letter";
	$dompdf->set_paper($tamano,$orientation);
	
	$dompdf->render();
	
	$nombreArch = "Reporte_Inventario_IMT.pdf";
	$dompdf->stream($nombreArch);*/
?>