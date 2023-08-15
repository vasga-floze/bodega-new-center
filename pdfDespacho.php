<?php

session_start();
include('conexiones/conectar.php');
require_once('fpdf/makefont/makefont.php');
require_once('fpdf/fpdf.php');
class PDF extends FPDF
{
// Cabecera de página


// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',12);
    // Número de página
    $this->Cell(0,-25,'Pagina'.$this->PageNo().'/{nb}',0,0,'C');
}
}
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$variable = $_GET['documento'] ;
$respuesta=$_SESSION['compania'];
$pdf->AddFont('RobotoMono-Regular','','RobotoMono-VariableFont_wght.php');
$query =$dbBodega->prepare("DECLARE @DOCUMENTO_INV NVARCHAR(25)='$variable'
SELECT MAX(CASE naturaleza WHEN 'S' THEN TRANSACCION.BODEGA END) AS ORIGEN, 
       b1.nombre AS NOMBREORIGEN, 
          MAX(CASE naturaleza WHEN 'E' THEN TRANSACCION.BODEGA END) AS DESTINO,
          b2.nombre AS NOMBREDESTINO,
       left(REGISTRO.Articulo + ' '+ REGISTRO.Descripcion,40) Descripcion, TRANSACCION.CodigoBarra, 
       TRANSACCION.Documento_Inv, TRANSACCION.Fecha
FROM TRANSACCION
INNER JOIN REGISTRO ON TRANSACCION.CodigoBarra = REGISTRO.CodigoBarra
INNER JOIN SOFTLAND.".$respuesta.".bodega b1 ON b1.bodega = 
    (SELECT MAX(BODEGA) FROM TRANSACCION WHERE naturaleza = 'S' AND TRANSACCION.Documento_Inv = @DOCUMENTO_INV)
INNER JOIN SOFTLAND.".$respuesta.".bodega b2 ON b2.bodega = 
    (SELECT MAX(BODEGA) FROM TRANSACCION WHERE naturaleza = 'E' AND TRANSACCION.Documento_Inv = @DOCUMENTO_INV)
WHERE TRANSACCION.Documento_Inv = @DOCUMENTO_INV
GROUP BY TRANSACCION.CodigoBarra, REGISTRO.Articulo, REGISTRO.Descripcion, TRANSACCION.Documento_Inv, TRANSACCION.Fecha, b1.nombre, b2.nombre
ORDER BY REGISTRO.Articulo
");
		$query->execute();
		$data = $query->fetchAll(\PDO::FETCH_ASSOC);
        
        $bodegaCodigoOrigen='';
        $bodegaNombreOrigen='';
        $bodegaNombreDestino='';
        $bodegaCodigoDestino='';
        $fecha='';
        $documento='';
        foreach ($data as $dataCantidad) {
                $bodegaNombreDestino=$dataCantidad['NOMBREDESTINO'];
                $bodegaCodigoDestino=$dataCantidad['DESTINO'];
                $bodegaCodigoOrigen=$dataCantidad['ORIGEN'];
                $bodegaNombreOrigen=$dataCantidad['NOMBREORIGEN'];
                $fecha=$dataCantidad['Fecha'];
                $documento=$dataCantidad['Documento_Inv'];
        }

$pdf->AddPage();
$pdf->Image('logo.png',15,30,30,25);
$pdf->SetFont('Arial', '', 20);
$pdf->SetY(49);
$pdf->Cell(190,7,'',0,1,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(190,7,'',0,1,'C');
$pdf->Cell(130,7,'',0,1,'L');
$pdf->SetFont('Arial','',8);

$pdf->SetY(80);
$pdf->Cell(50,-24,'Fecha:',0,1,'C');
$pdf->SetY(80);
$pdf->Cell(75,-24,$fecha,0,1,'C');
$pdf->SetY(80);
$pdf->Cell(110,-24,'Documento:',0,1,'C');
$pdf->SetY(80);
$pdf->Cell(150,-24,$documento,0,1,'C');
$pdf->SetY(85);
$pdf->Cell(112,-24,'Origen:',0,1,'C');
$pdf->SetY(85);
$pdf->Cell(155,-24,$bodegaCodigoOrigen . " " . $bodegaNombreOrigen,0,1,'C');
$pdf->SetY(91);
$pdf->Cell(110,-24,'Destino:',0,1,'C');
$pdf->SetY(91);
$pdf->Cell(166,-24,$bodegaCodigoDestino . " " . $bodegaNombreDestino,0,1,'C');
/*$pdf->Cell(27,10,'Fecha:',0,1,'C');
$pdf->Cell(69,-15,'Documento:',0,1,'R');

$pdf->Cell(70,30,'Origen: ',0,1,'R');

$pdf->Cell(70,-14,'Destino:',0,1,'R');
$pdf->Cell(112,-2,$bodegaCodigoOrigen . " " . $bodegaNombreOrigen,0,1,'R');
$pdf->SetY(80);

$pdf->Cell(112,16,$bodegaCodigoDestino . " " . $bodegaNombreDestino,0,1,'R');
$pdf->Cell(95,-34,$documento,0,1,'R');*/
//$pdf->Cell(58,34,'',0,1,'C');
//$pdf->Cell(58,-22,$fecha,0,1,'C');
$pdf->SetY(109);
$pdf->Cell(100,-24,'________________',0,1,'C');
$pdf->SetY(109);
$pdf->Cell(58,-24,'F. Despacho:',0,1,'C');
$pdf->SetY(109);
$pdf->Cell(150,-23,'F. Recibe:',0,1,'C');
$pdf->SetY(109);
$pdf->Cell(190,-23,'_________________',0,1,'C');
$pdf->SetY(109);
$pdf->Cell(240,-23,'F. Entrega:',0,1,'C');
$pdf->SetY(116);
$pdf->Cell(282,-36,'__________________',0,1,'C');
$pdf->SetY(99);

$pdf->SetLeftMargin(15);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(10,7,'',0,1,'C');
$pdf->Cell(6,6,'NO',1,0,'C');
$pdf->Cell(60,6,'ARTICULO',1,0,'C');
$pdf->Cell(20,6,'BARRA',1,0,'C');
$pdf->Cell(6,6,'NO',1,0,'C');
$pdf->Cell(60,6,'ARTICULO',1,0,'C');
$pdf->Cell(20,6,'BARRA',1,1,'C');




$query =$dbBodega->prepare("DECLARE @DOCUMENTO_INV NVARCHAR(25)='$variable'
SELECT MAX(CASE naturaleza WHEN 'S' THEN TRANSACCION.BODEGA END) AS ORIGEN, 
       b1.nombre AS NOMBREORIGEN, 
          MAX(CASE naturaleza WHEN 'E' THEN TRANSACCION.BODEGA END) AS DESTINO,
          b2.nombre AS NOMBREDESTINO,
       left(REGISTRO.Articulo + ' '+ REGISTRO.Descripcion,30) Descripcion, TRANSACCION.CodigoBarra, 
       TRANSACCION.Documento_Inv, TRANSACCION.Fecha
FROM TRANSACCION
INNER JOIN REGISTRO ON TRANSACCION.CodigoBarra = REGISTRO.CodigoBarra
INNER JOIN SOFTLAND.".$respuesta.".bodega b1 ON b1.bodega = 
    (SELECT MAX(BODEGA) FROM TRANSACCION WHERE naturaleza = 'S' AND TRANSACCION.Documento_Inv = @DOCUMENTO_INV)
INNER JOIN SOFTLAND.".$respuesta.".bodega b2 ON b2.bodega = 
    (SELECT MAX(BODEGA) FROM TRANSACCION WHERE naturaleza = 'E' AND TRANSACCION.Documento_Inv = @DOCUMENTO_INV)
WHERE TRANSACCION.Documento_Inv = @DOCUMENTO_INV
GROUP BY TRANSACCION.CodigoBarra, REGISTRO.Articulo, REGISTRO.Descripcion, TRANSACCION.Documento_Inv, TRANSACCION.Fecha, b1.nombre, b2.nombre
ORDER BY REGISTRO.Articulo
");
		$query->execute();
		$data = $query->fetchAll(\PDO::FETCH_ASSOC);
        
        $contador=1;
        foreach ($data as $i => $dataCantidad) {
            $columnIndex = $i % 2; // Índice de la columna actual (0 o 1)
            $x = $columnIndex * 101; // Posición X según la columna actual
            $y = floor($i / 2) * 5 + 112; 
            $pdf->SetXY($x, $y);
            $pdf->SetFont('RobotoMono-Regular','',8);

            $pdf->SetLeftMargin(15);

            $pdf->Cell(6,5,$contador,1,0,'C');

            $pdf->Cell(60,5,utf8_decode($dataCantidad['Descripcion']),1,0,'L');
            //$pdf->MultiCell(40, 4, $dataCantidad['Descripcion'], 1,'L','true');
            $pdf->Cell(20,5,$dataCantidad['CodigoBarra'],1,1,'C');
            $pdf->SetX($pdf->GetX() + 96); // Ajuste de posición X para la segunda fila

            $contador++;


        }
        

// If I add this cell, then i'll overlap/move to the bottom

$pdf->AddFont('RobotoMono-Regular','','RobotoMono-VariableFont_wght.php');
$pdf->AddPage();
$pdf->SetLeftMargin(8);
$pdf->Image('logo.png',15,30,30,25);
//CABECERA DEL DOCUMENTO
$pdf->SetY(40);
$pdf->SetFont('Arial','',8);
$pdf->Cell(190,7,'Documento:'.$variable,0,1,'R');
$pdf->SetLeftMargin(18);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,7,'New York Center S.A de C.V',0,1,'C');

$pdf->SetFont('Arial','B',9);
$pdf->Cell(190,7,'Venta de ropa america por mayor y al menor',0,1,'C');
$pdf->Cell(195,6,'Carretera Panamericana Salida a San Salvador Km. 135, #109, San Miguel, San Miguel
',0,1,'C');
$pdf->Cell(190,7,'Tel. 2669-5802
',0,1,'C');
$pdf->Cell(190,5,date('d/m/Y'),0,1,'C'); 
$pdf->SetFont('Arial','',9);
//$pdf->Cell(0, 10, 'Pagina ' . $pdf->PageNo(), 0, 0, 'C');
$pdf->SetY(85);
 //TERMINA LA CABECERA DEL DOCUMENTO 
 $pdf->SetLeftMargin(30);
$pdf->Cell(23,6,'ARTICULO',1,0,'C');
$pdf->Cell(90,6,'DESCRIPCION',1,0,'C');
$pdf->Cell(23,6,'CANTIDAD',1,0,'C');
$pdf->Cell(23,6,'LIBRAS',1,1,'C');
$query =$dbBodega->prepare("SELECT REGISTRO.Articulo, REGISTRO.Descripcion, COUNT(TRANSACCION.CodigoBarra)/2 AS Cantidad, SUM(REGISTRO.Libras)/2 AS Libras
FROM            TRANSACCION INNER JOIN
                         REGISTRO ON TRANSACCION.CodigoBarra = REGISTRO.CodigoBarra
WHERE       transaccion.Documento_Inv='$variable' and  (TRANSACCION.IdTipoTransaccion=7)
GROUP BY  REGISTRO.Articulo, REGISTRO.Descripcion
");
$query->execute();
$data = $query->fetchAll(\PDO::FETCH_ASSOC);
$totalCantidad=0;
$totalLibras=0;
foreach ($data as $key => $resumen) {
  
    $pdf->SetFont('RobotoMono-Regular','',8);
    $pdf->Cell(23,6,$resumen['Articulo'],1,0,'C');
    $pdf->Cell(90,6,utf8_decode($resumen['Descripcion']),1,0,'C');
    $pdf->Cell(23,6,$resumen['Cantidad'],1,0,'C');
    $pdf->Cell(23,6,$resumen['Libras'],1,1,'C');
    $totalCantidad +=$resumen['Cantidad'];
    $totalLibras +=$resumen['Libras'];
}
//$pdf->SetFont('RobotoMono-Regular', 'B', 8);
$pdf->Cell(113, 6, 'Total', 1, 0, 'R');
$pdf->Cell(23, 6, $totalCantidad, 1, 0, 'C');
$pdf->Cell(23, 6, $totalLibras, 1, 1, 'C');
$pdf->Output();
?>