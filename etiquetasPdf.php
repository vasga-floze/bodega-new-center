<?php
require_once('TCPDF/examples/tcpdf_include.php');
include('conexiones/conectar.php');
$query =$dbBodega->prepare(
    "SELECT CONCAT(DETALLEREGISTRO.ArticuloDetalle,'\t',REGISTRO.CodigoBarra,'\t1\n') QR, 
    DETALLEREGISTRO.ArticuloDetalle, 
           ex.descripcion,DETALLEREGISTRO.Cantidad
   FROM DETALLEREGISTRO INNER JOIN
    REGISTRO ON DETALLEREGISTRO.IdRegistro = REGISTRO.IdRegistro
    inner join
    EXIMP600.consny.ARTICULO ex on detalleregistro.articulodetalle=ex.articulo
    where REGISTRO.CodigoBarra='000'");

$query->execute();
$data = $query->fetchAll(\PDO::FETCH_ASSOC);


$consulta= json_encode($data);
$arr = json_decode($consulta, true);
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->addPage();
$pdf->SetAutoPageBreak(true);

$pdf->SetMargins(5,50,3,TRUE);
$last_y = $pdf->GetY();
$last_x = $pdf->GetX();
$pdf->setEqualColumns(6, 45);
$cols = 5;
$col = 0;
$count = 1;
$pdf->selectColumn($col);
foreach ($arr as $val) {
        $descripcion=$val['descripcion'];
        $cantidad=$val['Cantidad'];
        $codigoQR=$val['QR'];
    for ($i=0; $i <10 ; $i++) { 
        
      
            if ($last_y >= $pdf->getPageHeight() - 60 ) {
                $col++;
            $pdf->selectColumn($col);
                if ($col > $cols) {
                    $col = 0;
                    $pdf->selectColumn($col);
                    $pdf->AddPage();
                }
            }
            $pdf->SetFont('Times', '', 10); 
            
            $width=10;
            $height=10;
            $str =  $descripcion;
            $imagen='logo.jpeg';
            $anchoCelda=35;
            $altoCelda=56;
            
            $data = 'https://example.com';

            // Set QR code size and position
            $size = 90;

            // Set multicell height and width
            $cell_height = 10;
            $cell_width = 10;

            // Generate QR code image
            $qr_image=$pdf->write2DBarcode($codigoQR, 'QRCODE,H');
            $qr_height = $pdf->GetPageHeight($qr_image);
            $qr_width = $pdf->GetPageWidth($qr_image);
            $x_pos = ($cell_width - $qr_width) / 2;
            $y_pos = ($cell_height - $qr_height) / 2;
            // Create multicell with QR code image
            $pdf->MultiCell($cell_width, $cell_height, 'rdsfsfs', 0, 'L', false, 1, '', '', true, 0, false, true, $cell_height, 'M');

            // Get current x and y position
            $x = $pdf->GetX();
            $y = $pdf->GetY();

            $pdf->SetXY($x + $x_pos, $y + $y_pos);

            // Write QR code image to PDF document within multicell
            $pdf->Image('@'.$qr_image, 12, 30, $size, $size, '', '', '', false, 300, '', false, false, 0, false, false, false);
            
            //$pdf->MultiCell($anchoCelda,$altoCelda,1,'C',0,1,'','',true,0,false,true,40,'C');
            //list($width,$height)=getimagesize($imagen);
            //$logo="<img src=".$imagen."width=".$width."height=".$height."/>";
            //$h = $pdf->getStringHeight(45, $str);
            //$pdf->Image($imagen,$last_x,$last_y,20,30,"","","",false,300,"",false,false,0,'C',false,false,false,false);
           
            //$pdf->MultiCell($anchoCelda,$altoCelda,"\n\n\n\n\ndfdfdfdf"."\n\n\n".$pdf->Image($imagen,$pdf->getX(),$pdf->getY(),33,20)."".$pdf->write2DBarcode($codigoQR, 'QRCODE,H'), 1, 'C', 0, 1, '', '', true, 0, false, true, 60, 'C');
            //$pdf->write2DBarcode($codigoQR, 'QRCODE,H',"\n");
            
           
           //$pdf->MultiCell($anchoCelda,$altoCelda,"dfdfdf",1,'L',false,0,"","",true,0);
            //AGREGAR LA IMAGEN
            
           
            
            $count++;
            $last_y = $pdf->GetY();
           
        }
    
    # code...
}

$pdf->Output('output.pdf', 'I');