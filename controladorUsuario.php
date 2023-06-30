<?php
include('conexiones/conectar.php');
$usuario=$_POST['usuario'];
$nombre=$_POST['nombre'];
$empaca=$_POST['empaca'];
$digita=$_POST['digita'];
$produce=$_POST['produce'];



$queryInsertarUsuario=$dbBodega->prepare("INSERT INTO USUARIO
                                            (
                                               Usuario,
                                               Nombre,
                                               Digita,
                                               Produce,
                                               Empaca 
                                            )VALUES(
                                                ?,
                                                ?,
                                                ?,
                                                ?,
                                                ?

                                            )");
if(!$queryInsertarUsuario->execute([
                                    $usuario,
                                    $nombre,
                                    $digita,
                                    $produce,
                                    $empaca

                                    ])){
    $errorInfo=$queryInsertarUsuario->errorInfo();
    echo("No se pudo ejecutar".$errorInfo[2]);
    return;
}




//$response=array();


/*$response["usuario"]=$usuario;
$response["nombre"]=$nombre;
$response["empaca"]=$empaca;
$response["digita"]=$digita;
$response["produce"]=$produce;*/


echo("Se ejecuto exitosamente");







?>