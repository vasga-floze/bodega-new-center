<?php
include('conexiones/conectar.php');
$documentoInventario=$_POST['fechaHoraTemporal'];
//echo($documentoInventario);

//echo($documentoInventario);



$response=array();
$query =$dbBodega->prepare("DELETE TRANSACCION where IdTipoTransaccion=3 and Estado is null and Documento_Inv='$documentoInventario'");
$query->execute();
if($query){
    //var_dump($query);
    $response["status"]="success";
    $response["message"]="Cancelacion exitosa";
    echo json_encode($response);
}else{
    $errorInfo=$query->errorInfo();
    $response["status"]="error";
    $response["message"]="ha ocurrido un error";
    $response["error"]=$errorInfo[2];

    echo json_encode($response);
}



?>