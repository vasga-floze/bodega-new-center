<?php
require_once('TCPDF/examples/tcpdf_include.php');
require('phpqrcode/qrlib.php');
class MyTCPDF extends TCPDF {
    public function getBufferContents() {
        return $this->getBuffer();
    }
}
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdfBuffer = new MyTCPDF();
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
		$query =$dbBodega->prepare("SELECT REGISTRO.CodigoBarra, DETALLEREGISTRO.ArticuloDetalle, ex.descripcion,  DETALLEREGISTRO.Cantidad
		FROM  REGISTRO INNER JOIN
								 DETALLEREGISTRO ON REGISTRO.IdRegistro = DETALLEREGISTRO.IdRegistro
												INNER JOIN
												EXIMP600.consny.articulo ex On registro.articulo=ex.articulo
		WHERE        REGISTRO.CodigoBarra ='$codigo'");
		$query->execute();
		$data = $query->fetchAll(\PDO::FETCH_ASSOC);
		$consulta=json_encode($data);
		$arr = json_decode($consulta, true);
		$suma=0;
		foreach($data as $dataCantidad){
			$cantidad=$dataCantidad['Cantidad'];
			$suma += $cantidad; 
		}
		$col_width = 31; // Ancho de cada columna
		$row_height =48; // Altura de cada fila
		$x = 15; // Posición inicial x
		$y = 25; // Posición inicial y
		$next_y = $y; // Variable para almacenar la posición y de la siguiente celda
		$current_col = 0; // Columna actual
		$total_paginas=0;
		$cantidadPagina=0;
		$total_vinetas = 0;
		$row_height_personalizado = 28; 
		$row_width_personalizado = 28; 
		//echo($json_encode);
		foreach ($arr as $val ) {
		$cantidad=$val['Cantidad'];
		$descripcion=$val['descripcion'];
		$codigoBarra=$val['CodigoBarra'];
		$codigoArticulo=$val['ArticuloDetalle'];
		$data = range(1, $cantidad);
		$dir='temp/';
		if(!file_exists($dir))
			mkdir($dir);
		$filename = $dir.$codigoBarra.'test.png';
		$tamanio=8;
		$level='H';
		$frameSize=1;
		QRcode::png($codigoBarra,$filename,$level,$tamanio,$frameSize);
		$cantidadPaginasEsperadas=ceil($suma/30);
		foreach ($data as $key => $value) {
			$col = $current_col; // Definir la columna actual
			$row = floor($key / 7); // Calcular la fila actual
			$imagen='logo.jpeg';
		//$cantidadPagina++;
		// Calcular la posición x e y de la celda
			$cell_x = $x + ($col * $col_width);
			$cell_y = $next_y;
			$html='<style>
						.descripcion{
							margin-top: 45px;
						}
					</style>';
			$html .= '<div class="container"><br><br><br><br>';
			$html .=  '<div class="descripcion">'.$descripcion.'</div>';
			$html .= '</div>';
			$html .= '<img src="'.$filename. '" height="30" alt="Código QR">';
			$html .=  '<br>'.$codigoBarra.'';
			$html .=  '<br>'.$codigoArticulo.'<br>';
			
			$total_vinetas++;
		//$cantidadPagina=ceil($total_vinetas/30);
		// Agregar el valor a la celda
			$pdf->SetXY($cell_x, $cell_y);
		//$pdf->WriteHTMLCell($col_width, $row_height, $content, 1, 'C');
			$pdf->writeHTMLCell($row_width_personalizado,$row_height_personalizado, '', '', $html, 0, 1, 0, true, 'C', true);
		// Actualizar la posición y de la siguiente celda
			$next_y = $cell_y + $row_height;
		// Verifcar si se llegó al final de la página
			if ($next_y > $pdf->getPageHeight() - 50) {
			// Posicionar la siguiente columna a la derecha
				$current_col++;
				$next_y = $y;
			// Verificar si se llegó al final de la última columna
				if ($current_col >= 6) {
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
$col_width = 33; // Ancho de cada columna
$row_height = 52; // Altura de cada fila
$x = 7; // Posición inicial x
$y = 12; // Posición inicial y
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
	$cantidadPaginasEsperadas=ceil($suma/30);
	foreach ($data as $key => $value) {
    	$col = $current_col; // Definir la columna actual
    	$row = floor($key / 7); // Calcular la fila actual
		$imagen='logo.jpeg';
	//$cantidadPagina++;
    // Calcular la posición x e y de la celda
    	$cell_x = $x + ($col * $col_width);
    	$cell_y = $next_y;
		$html='<style>
					.descripcion{
						margin-top: 45px;
					}
				</style>';
		$html .= '<div class="container"><br><br><br><br>';
		$html .=  '<div class="descripcion">'.$descripcion.'</div>';
		$html .= '</div>';
		$html .= '<img src="'.$filename. '" height="30" alt="Código QR">';
		$html .=  '<br>'.$codigoBarra.'';
		$html .=  '<br>'.$codigoArticulo.'<br>';
		$html .= '<div class="carisma">
					<img  src="'.$imagen. '" height="50" alt="Código QR"><br>
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
    	if ($next_y > $pdf->getPageHeight() - 50) {
        // Posicionar la siguiente columna a la derecha
        	$current_col++;
        	$next_y = $y;
        // Verificar si se llegó al final de la última columna
			if ($current_col >= 6) {
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

