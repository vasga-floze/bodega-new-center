<?php

require('fpdf/code128.php');


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
include('conexiones/conectar.php');


$pdf=new PDF_Code128();
//$pdf->AddFont('PressStart2P','', 'PressStart2P-Regular.php');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddFont('NovaMono','','NovaMono-Regular.php');

// Instanciation of inherited class
if(isset($_GET["descripcion"])){
    $urlCodigoBarra=$_GET["codigo"];
    $fechaActual = date('Y-m-d');
    $query =$dbBodega->prepare(
        "SELECT REGISTRO.CodigoBarra, REGISTRO.Articulo, REGISTRO.Descripcion, REGISTRO.Observaciones,UBICACION.Ubicacion Ubicacion
        FROM REGISTRO INNER JOIN
            UBICACION ON REGISTRO.IdUbicacion = UBICACION.IdUbicacion
        WHERE (REGISTRO.CodigoBarra = '".$urlCodigoBarra."')");
    $query->execute();
    $data = $query->fetchAll();
    foreach ($data as $valores){
   

        //PRIMERA ETIQUETA
        $pdf->SetFont('NovaMono','',20);
        $pdf->Cell(60,30,$valores['CodigoBarra'], 1,0, 'C');
        $x = $pdf->GetX();
        $pdf->SetFont('Times','B',15);
        $pdf->Multicell(130,7.5,"\n".$fechaActual."\n".$valores['Descripcion']."\nUbicacion: ".$valores['Ubicacion'], 1,0);
        $pdf->SetFont('NovaMono','',20);
        $pdf->Code128(15,58,$valores['CodigoBarra'],50,25);
        $x = $pdf->GetX();
        $pdf->Cell(60,60,'', 1,0);
        $pdf->SetFont('NovaMono','',40);
        $pdf->Cell(130,60,$valores["Articulo"], 1,0,'C');
        $pdf->Ln(60);
        $pdf->SetFont('Times','B',10);
        $pdf->MultiCell(190, 10, $valores['Observaciones'], 1, 'C', false);
        $x = $pdf->GetX();
        $pdf->Ln(40);
    
    
          //SEGUNDA ETIQUETA
        
    
        $pdf->SetFont('NovaMono','',20);
        $pdf->Cell(60,30,$valores['CodigoBarra'], 1,0, 'C');
        $x = $pdf->GetX();
        $pdf->SetFont('Times','B',15);
        $pdf->Multicell(130,7.5,"\n".$fechaActual."\n".$valores['Descripcion']."\nUbicacion: ".$valores['Ubicacion'], 1,0);
        $pdf->Code128(15,200,$valores['CodigoBarra'],50,25);
        $x = $pdf->GetX();
        $pdf->Cell(60,60,'', 1,0);
        $pdf->SetFont('NovaMono','',40);
        $pdf->Cell(130,60,$valores["Articulo"], 1,0,'C');
        $pdf->Ln(60);
        $pdf->SetFont('Times','B',10);
        $pdf->MultiCell(190, 10, $valores['Observaciones'], 1, 'C', false);
        
        
    }
    $pdf->Output();

}else{
    session_start();


    $codigoBarra=$_SESSION['cod'];
    $fechaActual = date('Y-m-d');

$query =$dbBodega->prepare(
    "SELECT REGISTRO.CodigoBarra, REGISTRO.Articulo, REGISTRO.Descripcion, REGISTRO.Observaciones,UBICACION.Ubicacion Ubicacion
    FROM REGISTRO INNER JOIN
        UBICACION ON REGISTRO.IdUbicacion = UBICACION.IdUbicacion
    WHERE (REGISTRO.CodigoBarra = '".$codigoBarra."')");
$query->execute();
$data = $query->fetchAll();


foreach ($data as $valores){
   

    //PRIMERA ETIQUETA
    $pdf->SetFont('NovaMono','',20);
    $pdf->Cell(60,30,$valores['CodigoBarra'], 1,0, 'C');
    $x = $pdf->GetX();
    $pdf->SetFont('Times','B',15);
    $pdf->Multicell(130,7.5,"\n".$fechaActual."\n".$valores['Descripcion']."\nUbicacion: ".$valores['Ubicacion'], 1,0);
    $pdf->SetFont('NovaMono','',20);
    $pdf->Code128(15,58,$valores['CodigoBarra'],50,25);
    $x = $pdf->GetX();
    $pdf->Cell(60,60,'', 1,0);
    $pdf->SetFont('NovaMono','',40);
    $pdf->Cell(130,60,$valores["Articulo"], 1,0,'C');
    $pdf->Ln(60);
    $pdf->SetFont('Times','B',10);
    $pdf->MultiCell(190, 10, $valores['Observaciones'], 1, 'C', false);
    $x = $pdf->GetX();
    $pdf->Ln(40);


      //SEGUNDA ETIQUETA
    

    $pdf->SetFont('NovaMono','',20);
    $pdf->Cell(60,30,$valores['CodigoBarra'], 1,0, 'C');
    $x = $pdf->GetX();
    $pdf->SetFont('Times','B',15);
    $pdf->Multicell(130,7.5,"\n".$fechaActual."\n".$valores['Descripcion']."\nUbicacion: ".$valores['Ubicacion'], 1,0);
    $pdf->Code128(15,200,$valores['CodigoBarra'],50,25);
    $x = $pdf->GetX();
    $pdf->Cell(60,60,'', 1,0);
    $pdf->SetFont('NovaMono','',40);
    $pdf->Cell(130,60,$valores["Articulo"], 1,0,'C');
    $pdf->Ln(60);
    $pdf->SetFont('Times','B',10);
    $pdf->MultiCell(190, 10, $valores['Observaciones'], 1, 'C', false);
    
    
}
$pdf->Output();


}




?>