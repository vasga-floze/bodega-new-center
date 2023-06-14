<?php
include('conexiones/conectar.php');
$select=$_POST["dato"];
$fechaTemporal=$_POST["fechaHoraTemporal"];
$query =$dbBodega->prepare("UPDATE TRANSACCION SET BODEGA='$select', fechamodificacion=GETDATE() WHERE DOCUMENTO_INV='$fechaTemporal' AND Naturaleza='E'");
if($query->execute()){
    echo("Se elimino correctamente");
}else{
    $errorInfo =$query->errorInfo();
    echo "Error al eliminar los datos:". $errorInfo[2];

}
?>