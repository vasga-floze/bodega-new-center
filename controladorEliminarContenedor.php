<?php
include('conexiones/conectar.php');

$articulo=$_POST["codigo"];
$fecha=$_POST["fecha"];
$documento=$_POST["contenedor"];
$response=array();
$queryEliminarTransaccion=$dbBodega->prepare("DELETE TRANSACCION 
                                            WHERE CodigoBarra IN
                                            (SELECT CodigoBarra FROM REGISTRO
                                            WHERE Articulo='$articulo' AND
                                            FechaCreacion='$fecha' AND
                                            DOCUMENTO_INV='$documento')
                                            AND Fecha='$fecha' AND
                                            NumeroDocumento='$documento'");



                    
if(!$queryEliminarTransaccion->execute()){
    $errorInfo=$queryEliminarTransaccion->errorInfo();
    $response["message"]="No se ha podido eliminar la transaccion".$errorInfo[2];
    return;
}

/**
 * *ELIMINAR LISTADO
 */
$queryEliminarListado=$dbBodega->prepare("DELETE REGISTRO
                                            WHERE Articulo='$articulo' AND
                                            FechaCreacion='$fecha' AND
                                            DOCUMENTO_INV='$documento'");
if (!$queryEliminarListado->execute()) {
    $errorInfo=$queryEliminarListado->errorInfo();
    $response["mensaje"]="No se ha podido eliminar la transaccion 2".$errorInfo[2];
    return;
}


$response["message"]="Se elimino correctamente";
$response["success"]="1";
echo(json_encode($response));


?>