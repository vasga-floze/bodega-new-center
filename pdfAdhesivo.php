<?php
require_once('TCPDF/examples/tcpdf_include.php');
require('phpqrcode/qrlib.php');
class MyTCPDF extends TCPDF {
   
}
$pdf = new TCPDF('P', 'mm', array(50.9, 70.8), true, 'UTF-8', false);

$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 6);
$image='logo.jpeg';
/* This code is preparing a SQL query to select data from multiple tables (`DETALLEREGISTRO`,
`REGISTRO`, and `EXIMP600.consny.ARTICULO`) based on a specific condition
(`REGISTRO.CodigoBarra='000'`). The selected data includes the concatenation of
`DETALLEREGISTRO.ArticuloDetalle`, `REGISTRO.CodigoBarra`, and the string `'\t1\n'` as `QR`,
`REGISTRO.CodigoBarra`, `DETALLEREGISTRO.ArticuloDetalle`, `ex.descripcion`, and
`DETALLEREGISTRO.Cantidad`. This query is used to retrieve data for generating QR codes and labels
in the PDF file. */	

if(isset($_GET["descripcion"])){
	$bandera=$_GET["descripcion"];
	if($bandera=="DESCRIPCION"){
		$codigo=$_GET["codigo"];
		$codigoArticulo=$_GET["codigoArticulo"];
		include('conexiones/conectar.php');
		$query =$dbBodega->prepare(
			"SELECT CONCAT(
				DETALLEREGISTRO.ArticuloDetalle,'\t',REGISTRO.CodigoBarra,'\t1\n') QR,
				REGISTRO.CodigoBarra, 
				DETALLEREGISTRO.ArticuloDetalle, 
				ex.descripcion,DETALLEREGISTRO.Cantidad
			   FROM DETALLEREGISTRO INNER JOIN
			REGISTRO ON DETALLEREGISTRO.IdRegistro = REGISTRO.IdRegistro
			inner join
			EXIMP600.consny.ARTICULO ex on detalleregistro.articulodetalle=ex.articulo
			where REGISTRO.CodigoBarra='".$codigo."'
			"
		);
		$query->execute();
		$data = $query->fetchAll(\PDO::FETCH_ASSOC);
		

		$consulta= json_encode($data);
		$arr = json_decode($consulta, true);
		$col_width = 48; // Ancho de cada columna
		$row_height =-5.9; // Altura de cada fila
		$x = 2; // Posición inicial x
		$y = 0; // Posición inicial y
		$next_y = $y; // Variable para almacenar la posición y de la siguiente celda
		$current_col = 0; // Columna actual
		$total_paginas=0;
		//$datos = $_SESSION['datos'];
		$suma = 0;
		foreach($data as $dataCantidad){
			$cantidad=$dataCantidad['Cantidad'];
			$suma += $cantidad; 
		}
		$cantidadPagina=0;
		$total_vinetas = 0;
		foreach ($arr as $val ) {
			$cantidad=$val['Cantidad'];
			$descripcion=$val['descripcion'];
			$codigoBarra=$val['CodigoBarra'];
			$codigoArticulo=$val['ArticuloDetalle'];
			$data = range(1, $cantidad);
			$dir='temp/';
			if(!file_exists($dir))
				mkdir($dir);
			$filename = $dir.$val['descripcion'].'test.png';
			$tamanio=8;
			$level='H';
			$frameSize=1;
			QRcode::png($val['QR'],$filename,$level,$tamanio,$frameSize);
			$cantidadPaginasEsperadas=ceil($suma/5);
			foreach ($data as $key => $value) {
				$col = $current_col; // Definir la columna actual
				$row = floor($key / 2); // Calcular la fila actual
				$imagen='logo.jpeg';
			//$cantidadPagina++;
			// Calcular la posición x e y de la celda
				$cell_x = $x + ($col * $col_width);
				$cell_y = $next_y;
				$html='<style>
							.descripcion{
								margin-top: 0px;
								font-size:8px;
							}
							.codigo{
								margin-top: 0px;
								font-size:8px;
							}
							.codigoArticulo{
								margin-top: 1px;
								font-size:8px;
							}
							.carisma{
								margin-top: 0px;
								
							}
						</style>';
				$html .= '<div class="container">';
				$html .=  '<div class="descripcion">'.$descripcion.'</div>';
				$html .= '</div>';
				$html .= '<img src="'.$filename. '" height="40" alt="Código QR">';
				$html .=  '<p class="codigo">'.$codigoBarra.'</p>';
				$html .=  '<p class="codigoArticulo">'.$codigoArticulo.'</p>';
				$html .= '<div class="carisma">
							<img  src="'.$imagen. '" height="30" width="150" alt="Código QR">
						</div>';
				$total_vinetas++;
			//$cantidadPagina=ceil($total_vinetas/30);
			// Agregar el valor a la celda
				$pdf->SetXY($cell_x, $cell_y);
			//$pdf->WriteHTMLCell($col_width, $row_height, $content, 1, 'C');
				$pdf->writeHTMLCell($col_width,$row_height, '', '', $html, 0, 0, 0, false, 'C', false);
			// Actualizar la posición y de la siguiente celda
				$next_y = $cell_y + $row_height;
			// Verifcar si se llegó al final de la página
				if ($next_y > $pdf->getPageHeight() - 300) {
				// Posicionar la siguiente columna a la derecha
					$current_col++;
					$next_y = $y;
				// Verificar si se llegó al final de la última columna
					if ($current_col >= 1) {
					//$cantidadPaginasEsperadas = ceil(($total_vinetas) / 30);
					// Verificar si se ha alcanzado la última página
						if ($total_paginas+1 != $cantidadPaginasEsperadas ) {
						// Agregar una nueva página
							$total_paginas = ceil(($total_vinetas) / 30);
							$pdf->AddPage();
							$current_col = 0;
							
						}
					}	
				}	
			}

	}
	$pdf->Output('etiquetasDetalle.pdf', 'I');
}

	

}else{
	include('conexiones/conectar.php');
session_start();
$codigoBarra=$_SESSION['cod'];
$datos = $_SESSION['datos']; 
$query =$dbBodega->prepare(
	"SELECT CONCAT(
		DETALLEREGISTRO.ArticuloDetalle,'\t',REGISTRO.CodigoBarra,'\t1\n') QR,
		REGISTRO.CodigoBarra, 
		DETALLEREGISTRO.ArticuloDetalle, 
		ex.descripcion,DETALLEREGISTRO.Cantidad
   	FROM DETALLEREGISTRO INNER JOIN
    REGISTRO ON DETALLEREGISTRO.IdRegistro = REGISTRO.IdRegistro
    inner join
    EXIMP600.consny.ARTICULO ex on detalleregistro.articulodetalle=ex.articulo
    where REGISTRO.CodigoBarra='".$codigoBarra."'
	"
);
$query->execute();
$data = $query->fetchAll(\PDO::FETCH_ASSOC);

$consulta= json_encode($data);
$arr = json_decode($consulta, true);
$col_width = 100; // Ancho de cada columna
$row_height =120; // Altura de cada fila
$x = 5; // Posición inicial x
$y = 11; // Posición inicial y
$next_y = $y; // Variable para almacenar la posición y de la siguiente celda
$current_col = 0; // Columna actual
$total_paginas=0;
$datos = $_SESSION['datos'];
$suma = 0;
foreach($datos as $dataCantidad){
	$cantidad=$dataCantidad['cantidad'];
	$suma += $cantidad; 
}
$cantidadPagina=0;
$total_vinetas = 0;
foreach ($arr as $val ) {
	$cantidad=$val['Cantidad'];
	$descripcion=$val['descripcion'];
	$codigoBarra=$val['CodigoBarra'];
	$codigoArticulo=$val['ArticuloDetalle'];
	$data = range(1, $cantidad);
	$dir='temp/';
	if(!file_exists($dir))
		mkdir($dir);
	$filename = $dir.$val['descripcion'].'test.png';
	$tamanio=8;
	$level='H';
	$frameSize=1;
	QRcode::png($val['QR'],$filename,$level,$tamanio,$frameSize);
	$cantidadPaginasEsperadas=ceil($suma/10);
	foreach ($data as $key => $value) {
    	$col = $current_col; // Definir la columna actual
    	$row = floor($key / 6); // Calcular la fila actual
		$imagen='logo.jpeg';
	//$cantidadPagina++;
    // Calcular la posición x e y de la celda
    	$cell_x = $x + ($col * $col_width);
    	$cell_y = $next_y;
		$html='<style>
					.descripcion{
						margin-top: 45px;
                        font-size:5px;
					}
                    .codigo{
						margin-top: 100px;
                        font-size:5px;
					}
                    .codigoArticulo{
						margin-top: 100px;
                        font-size:5px;
					}
                    .carisma{
						margin-top: 75px;
                        
					}
				</style>';
		$html .= '<div class="container"><br><br><br><br>';
		$html .=  '<div class="descripcion">'.$descripcion.'</div>';
		$html .= '</div>';
		$html .= '<img src="'.$filename. '" height="90" alt="Código QR">';
		$html .=  '<p class="codigo">'.$codigoBarra.'</p>';
		$html .=  '<p class="codigoArticulo">'.$codigoArticulo.'</p>';
		$html .= '<div class="carisma">
					<img  src="'.$imagen. '" height="90" alt="Código QR"><br>
				</div>';
		$total_vinetas++;
	//$cantidadPagina=ceil($total_vinetas/30);
    // Agregar el valor a la celda
    	$pdf->SetXY($cell_x, $cell_y);
    //$pdf->WriteHTMLCell($col_width, $row_height, $content, 1, 'C');
		$pdf->writeHTMLCell($col_width,$row_height, '', '', $html, 1, 1, 0, true, 'C', true);
    // Actualizar la posición y de la siguiente celda
    	$next_y = $cell_y + $row_height;
    // Verifcar si se llegó al final de la página
    	if ($next_y > $pdf->getPageHeight() - 300) {
        // Posicionar la siguiente columna a la derecha
        	$current_col++;
        	$next_y = $y;
        // Verificar si se llegó al final de la última columna
			if ($current_col >= 2) {
			//$cantidadPaginasEsperadas = ceil(($total_vinetas) / 30);
            // Verificar si se ha alcanzado la última página
            	if ($total_paginas+1 != $cantidadPaginasEsperadas ) {
                // Agregar una nueva página
                	$total_paginas = ceil(($total_vinetas) / 30);
                	$pdf->AddPage();
                	$current_col = 0;
                    
            	}
        	}	
    	}	
	}
}
$pdf->Output('etiquetasDetalle.pdf', 'I');





}


?>

