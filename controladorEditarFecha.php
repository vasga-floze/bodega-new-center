<?php
include('conexiones/conectar.php');
$fecha=$_POST["fecha"];
$fechaTemporal=$_POST["fechaHoraTemporal"];
$query =$dbBodega->prepare("UPDATE TRANSACCION SET Fecha='$fecha', fechamodificacion=GETDATE() WHERE DOCUMENTO_INV='$fechaTemporal'");
if($query->execute()){
    echo("Se edito la fecha correctamente");
}else{
    $errorInfo =$query->errorInfo();
    echo "Error al editar los datos:". $errorInfo[2];

}
?>