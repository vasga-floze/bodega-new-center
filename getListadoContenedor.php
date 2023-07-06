<?php
include('conexiones/conectar.php');


$numero=isset($_POST["numero"])?$_POST["numero"]:'';
$fecha=isset($_POST["fecha"])?$_POST["fecha"]:'';

//$numero=$_POST["numeroDocumento"];
//$fecha=$_POST["fechaDocumento"];
$querySelectContenedores=$dbBodega->prepare("SELECT Articulo,Descripcion,
                                                SUM(libras) libras,
                                                count(CodigoBarra) cantidad,
                                                FechaCreacion,
                                                DOCUMENTO_INV,BodegaActual
                                            FROM REGISTRO 
                                                WHERE IdTipoRegistro=2 AND
                                            DOCUMENTO_INV='".$numero."' AND
                                            FechaCreacion='".$fecha."'
                                            GROUP BY Articulo, Descripcion,
                                                     Descripcion, FechaCreacion,
                                                     DOCUMENTO_INV,BodegaActual");
$response=array();
if (!$querySelectContenedores->execute()) {

    $errorInfo=$querySelectContenedores->errorInfo();
    $response["response"]=$errorInfo[2];
    echo(json_encode($response));
    return;
}

$datos=$querySelectContenedores->fetchAll();
foreach ($datos as $key) {
    $response[]=$key;
    # code...
}


/*foreach ($datosContenedores as $key) {
    $response[]=$key;
}*/

echo(json_encode($response));


?>