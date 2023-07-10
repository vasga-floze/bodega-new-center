<?php

require('fpdf/code128.php');
include('conexiones/conectar.php');
$articulo=$_GET["articulo"];
$contenedor=$_GET["contenedor"];
$fecha=$_GET["fecha"];

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
    //$this->Cell(30,10,'Title',1,0,'C');
    // Line break
    $this->Ln(5);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
$pdf=new PDF_Code128();
//$pdf->AddFont('PressStart2P','', 'PressStart2P-Regular.php');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddFont('NovaMono','','NovaMono-Regular.php');
// Instanciation of inherited class
$fechaActual = date('Y-m-d');
$query =$dbBodega->prepare("SELECT REGISTRO.CodigoBarra,
                                REGISTRO.Articulo,
                                REGISTRO.Descripcion,
                                REGISTRO.FechaCreacion,
                                REGISTRO.Libras,
                                UBICACION.Ubicacion
                            FROM REGISTRO INNER JOIN
                            UBICACION ON REGISTRO.IdUbicacion=UBICACION.IdUbicacion
                            WHERE articulo='$articulo' AND documento_inv='$contenedor' AND fechacreacion='$fecha'");
$query->execute();
$data = $query->fetchAll();
$codigoBarra='';
$contador=0;
foreach ($data as $valores){
   
    $codigoBarra = $valores["CodigoBarra"];

    //PRIMERA ETIQUETA
    $pdf->SetFont('NovaMono', '', 20);
    $pdf->Cell(60, 30, $codigoBarra, 1, 0, 'C');
    $pdf->SetFont('Times', 'B', 15);
    $pdf->Multicell(130, 7.5, "\n" . $fechaActual . "\n" . $valores['Descripcion'] . "\nUbicacion: " . $valores['Ubicacion'], 1, 'L', false);

    // Obtener la posición actual
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // Imprimir el código Code128 dentro del Multicell
    $pdf->SetXY($x, $y + 5); // Ajustar la posición vertical para imprimir el código dentro del Multicell
    $pdf->SetFont('NovaMono', '', 10);
    $pdf->Code128($x + 5, $y + 25, $codigoBarra, 45, 25);

    // Restaurar la posición después de imprimir el código
    $pdf->SetXY($x, $y ); // Ajustar la posición vertical según tus necesidades

    $pdf->SetFont('NovaMono', '', 40);
    $pdf->Cell(190, 80, $valores["Articulo"], 1, 0, 'C');
    $pdf->Ln(80);
    $pdf->SetFont('Times', 'B', 10);
    $pdf->Ln(20);




    //PRIMERA ETIQUETA
    /*$pdf->SetFont('NovaMono','',20);
    $pdf->Cell(60,30,$codigoBarra, 1,0, 'C');
    $x = $pdf->GetX();
    $pdf->SetFont('Times','B',15);
    $pdf->Multicell(130,7.5,"\n".$fechaActual."\n".$valores['Descripcion']."\nUbicacion: ".$valores['Ubicacion'], 1,0);
    $pdf->SetFont('NovaMono','',20);
    $pdf->Code128(15,60,$codigoBarra,50,25);    
    $x = $pdf->GetX();
    $pdf->Cell(60,60,'', 1,0);
    $pdf->SetFont('NovaMono','',40);
    $pdf->Cell(130,60,$valores["Articulo"], 1,0,'C');
    $pdf->Ln(60);
    $pdf->SetFont('Times','B',10);
    $pdf->Ln(40);
    $contador++;*/
}
$pdf->Output();