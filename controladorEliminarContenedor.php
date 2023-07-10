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
}


?>