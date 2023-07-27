<?php
include('conexiones/conectar.php');
$usuario=$_POST['usuario'];
$nombre=$_POST['nombre'];
$empaca=$_POST['empaca'];
$digita=$_POST['digita'];
$produce=$_POST['produce'];
$id=isset($_POST['id'])?$_POST['id']:'';
$activo=isset($_POST['activo'])?$_POST['activo']:'';
$editar=isset($_POST['editar'])?$_POST['editar']:'';



if(!$editar == 'EDITAR'){
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
        echo("Se registro exitosamente");

}else{
    $queryInsertarUsuario=$dbBodega->prepare("UPDATE USUARIO
                                                SET Usuario=?,
                                                    Nombre=?,
                                                    Digita=?,
                                                    Produce=?,
                                                    Empaca=?,
                                                    Activo=?
                                                WHERE IdUsuario=?


                                                
                                                
                                                ");
        if(!$queryInsertarUsuario->execute([
                                        $usuario,
                                        $nombre,
                                        $digita,
                                        $produce,
                                        $empaca,
                                        $activo,
                                        $id
                                        ])){
        $errorInfo=$queryInsertarUsuario->errorInfo();
        echo("No se pudo ejecutar".$errorInfo[2]);
        return;
        }
        echo("Editado exitosamente");
}





//$response=array();


/*$response["usuario"]=$usuario;
$response["nombre"]=$nombre;
$response["empaca"]=$empaca;
$response["digita"]=$digita;
$response["produce"]=$produce;*/










?>