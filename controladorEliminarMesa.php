<?php
include('conexiones/conectar.php');

$codigo=$_POST["codigo"];

$response=array();
$queryEliminarMesa=$dbBodega->prepare("DELETE FROM 
                                                TRANSACCION 
                                            WHERE CodigoBarra='$codigo'
                                            AND IdTipoTransaccion=5");
if (!$queryEliminarMesa->execute()) {

    $response["message"]="No se pudo eliminar ";
    $response["success"]="2";
    echo(json_encode($response));
    return;

}

$response["message"]="Se elimino correctamente";
$response["success"]="1";

echo(json_encode($response));




?>