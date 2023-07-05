<?php
session_start();
include('conexiones/conectar.php');
$usuario=$_SESSION['usuario'];
//DATOS SIGUIENTE
$fecha=$_POST["fecha"];
$contenedor=$_POST["contenedor"];
$bodega=$_POST["bodega"];
$response=array();
$queryContenedor=$dbBodega->prepare("SELECT COUNT(*) AS Contador FROM
                                        TRANSACCION WHERE IdTipoTransaccion=1
                                        AND Fecha='$fecha'
                                        AND NumeroDocumento='$contenedor'
                                        
                                    ");
$queryContenedor->execute();
$datos=$queryContenedor->fetchAll();
foreach ($datos as $key) {
    $contador=$key["Contador"];  
}
if($contador==1){

    $response["message"]="No se puede trabajar el contenedor";
    echo(json_encode($response));
    return;

}
$_SESSION['bodega']=$bodega;
$_SESSION['fecha']=$fecha;
$_SESSION['contenedor']=$contenedor;
$_SESSION['fecha']=$fecha;
$response["success"]="1";
echo(json_encode($response));


?>