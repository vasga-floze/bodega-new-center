<?php
include('conexiones/conectar.php');
session_start();
$fecha=$_POST["fecha"];
$bodega=$_POST["bodega"];
$codigo=$_POST["codigo"]; 
$usuario=$_SESSION['usuario'];
$response=array();
$queryVerificaExista=$dbBodega->prepare("SELECT count(*) AS contador FROM registro
                                         WHERE CodigoBarra='$codigo' AND
                                         BodegaActual='$bodega' AND Activo=1");
$queryVerificaExista->execute();
$datos=$queryVerificaExista->fetchAll();

foreach ($datos as $key) {

    $contador=$key["contador"];
}

/**
 * *VERIFICA QUE EL CODIGO DE BARRA EXISTA EN LA BODEGA
 */

if ($contador == "0") {

    $response["message"]="No existe este articulo en la bodega";
    $response["success"]="2";
    echo(json_encode($response));
    return;
    
}

/**
 * *VERIFICA QUE EL CODIGO NO EXISTA EN OTRA TRANSACCION
 */
$queryVerificaTansaccion=$dbBodega->prepare(
        "SELECT 
        count(R.CodigoBarra) AS estado
        
    FROM REGISTRO AS R INNER JOIN TRANSACCION AS T
        ON R.CodigoBarra=T.CodigoBarra
    WHERE R.CodigoBarra='$codigo' AND T.IdTipoTransaccion=5 AND T.Estado='P'"
                                            );

$queryVerificaTansaccion->execute();
$datosVerificar=$queryVerificaTansaccion->fetchAll();

foreach ($datosVerificar as $key) {
    $estado=$key["estado"];
}

if (!$estado==0) {
    $response["message"]="El codigo de barra esta siendo usado en otra transaccion".$estado;
    $response["success"]="2";
    echo(json_encode($response));
    return;
}

$querySeleccionarRegistro=$dbBodega->prepare("SELECT * FROM REGISTRO
                                              WHERE CodigoBarra='$codigo'");

$querySeleccionarRegistro->execute();
$datos=$querySeleccionarRegistro->fetchAll();

foreach ($datos as $key) {
    $articulo=$key["Articulo"];
    $descripcion=$key["Descripcion"];
    $libras=$key["Libras"];
    $costo=$key["Costo"];
}

$queryInsertTransaccion=$dbBodega->prepare("INSERT INTO TRANSACCION
                                                            (
                                                            CodigoBarra,
                                                            IdTipoTransaccion,
                                                            Fecha,
                                                            Bodega,
                                                            Naturaleza,
                                                            Estado,
                                                            UsuarioCreacion,
                                                            UsuarioModificacion,
                                                            Documento_Inv,
                                                            NumeroDocumento)
                                                            
                                                            VALUES
                                                            (
                                                                
                                                                ?,
                                                                ?,
                                                                ?,
                                                                ?,
                                                                ?,
                                                                ?,
                                                                ?,
                                                                ?,
                                                                ?,
                                                                ?
                                                            )");
if(!$queryInsertTransaccion->execute([
                                       $codigo,
                                       5,
                                       $fecha,
                                       $bodega,
                                       'S',
                                       'P',
                                       $usuario,
                                       'NULL',
                                       'NULL',
                                       'NULL'

                                    ])){
    $errorInfo=$queryInsertTransaccion->errorInfo();
    $response["message"]="No se ha podido insertar la transaccion".$errorInfo[2];
    $response["success"]="2";
    echo(json_encode($response));
    return;

};



$response["message"]="Se pudo dar paso";
$response["success"]="1";
$response["descripcion"]=$descripcion;
$response["articulo"]=$articulo;
$response["libras"]=$libras;
$response["Costo"]=$costo;
$response["codigoBarra"]=$codigo;
$response["costo"]=$costo;
echo(json_encode($response));






?>