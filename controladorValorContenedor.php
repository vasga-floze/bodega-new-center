<?php

session_start();
include('conexiones/conectar.php');
$response=array();
$paquete=$_SESSION['PAQUETE'];
//$contenedor=$_SESSION['contenedor'];
$respuesta=$_SESSION['compania'];
$usuario=$_SESSION['usuario'];


if (!isset($_POST['dataArray'])) {
    $response["status"]="1";
    $response["message"]="No se ha registrado ningun calculo";
    return;
    
}

$dataArray=$_POST['dataArray'];

foreach ($dataArray as $key ) {
    $articulo=$key["articulo"];
    $subtotalFila=$key["subtotalFila"];
    $porcentaje=$key["porcentaje"];
    $totalArticulo=$key["totalArticulo"];
    $precioUnitario=$key["precioUnitario"];
    $fecha=$key["fecha"];
    $contenedor=$key["contenedor"];
    $response["articulo"]=$articulo;

    $queryActualizar=$dbBodega->prepare("DECLARE @COSTO decimal(18,6)='$precioUnitario'
            DECLARE @CONTENEDOR nvarchar(50)='$contenedor'
            DECLARE @FECHA date='$fecha'
            DECLARE @ARTICULO nvarchar(25) ='$articulo'
    
         UPDATE REGISTRO set Costo=@COSTO*Libras where CodigoBarra IN
        (SELECT CodigoBarra FROM TRANSACCION WHERE NumeroDocumento= @CONTENEDOR AND Fecha=@FECHA )
        AND Articulo=@ARTICULO
    ");

    if(!$queryActualizar->execute()){
        $errorInfo->$queryActualizar->errorInfo();
        $response["success"]="2";
        $response["message"]="No se ha podido actualizar".$errorInfo[2];
        echo(json_encode($response));
        return;
    }    
}



$querySeleccionarConsecutivo=$dbEximp600->prepare("SELECT SIGUIENTE_CONSEC
                                                 
                                                 FROM ".$respuesta."
                                                 .CONSECUTIVO_CI WHERE
                                                 CONSECUTIVO='COMPRA'");
$querySeleccionarConsecutivo->execute();
$datos=$querySeleccionarConsecutivo->fetchAll();


foreach ($datos as $key) {
    $siguienteConsecutivo=$key['SIGUIENTE_CONSEC'];
}
function obtener_consecutivoIng($siguienteConsecutivo){
    $consecutivo=preg_replace_callback('/\d+/',function($matches){
    return sprintf('%0'.strlen($matches[0]). 'd', intval($matches[0])+1);
    },$siguienteConsecutivo);
    return $consecutivo;

}
$documentoConsecutivoING=obtener_consecutivoIng($siguienteConsecutivo);


/**
 * * FINALIZAR INGRESO DOCUMENTO_INV
 * * 
 */

$referencia="COMPRA DEL CONTENEDOR " .$contenedor;

$queryFinalizarIngreso=$dbEximp600->prepare("INSERT INTO " .$respuesta.".
                                            
                                            DOCUMENTO_INV
                                            (
                                                PAQUETE_INVENTARIO,
                                                DOCUMENTO_INV,
                                                CONSECUTIVO,
                                                REFERENCIA,
                                                FECHA_DOCUMENTO,
                                                SELECCIONADO,
                                                USUARIO,
                                                MENSAJE_SISTEMA,
                                                APROBADO,
                                                NoteExistsFlag

                                            )
                                            VALUES(
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
if (!$queryFinalizarIngreso->execute([
                                    $paquete,
                                    $siguienteConsecutivo,
                                    'COMPRA',
                                    $referencia,
                                    $fecha,
                                    'N',
                                    $usuario,
                                    '',
                                    'N',
                                     0
                                    ])) {
    $errorInfo=$queryFinalizarIngreso->errorInfo();
    $response["mensaje"]="No se ha podido finalizar el ingreso".$errorInfo[2];
    $response["success"]="2";
    echo(json_encode($response));
    return;
    

    
}

//$queryExtraccionDatos=$dbBodega->prepare("SELECT * FROM REGISTRO ")
$contador=1;
foreach ($dataArray as $key) {
    $articulo=$key["articulo"];
    $subtotalFila=$key["subtotalFila"];
    $porcentaje=$key["porcentaje"];
    $totalArticulo=$key["totalArticulo"];
    $precioUnitario=$key["precioUnitario"];
    $fecha=$key["fecha"];
    $contenedor=$key["contenedor"];
    $cantidadFila=$key["cantidadFila"];
    $bodega=$key["bodega"];
    //$response["articulo"]=$articulo;

    $queryLineaDoc=$dbEximp600->prepare("INSERT INTO    
                                        ".$respuesta.".LINEA_DOC_INV
                                        (
                                            PAQUETE_INVENTARIO,
                                            DOCUMENTO_INV,
                                            LINEA_DOC_INV,
                                            AJUSTE_CONFIG,
                                            ARTICULO,
                                            BODEGA,
                                            LOCALIZACION,
                                            TIPO,
                                            SUBTIPO,
                                            SUBSUBTIPO,
                                            CANTIDAD,
                                            COSTO_TOTAL_LOCAL,
                                            COSTO_TOTAL_DOLAR,
                                            PRECIO_TOTAL_LOCAL,
                                            PRECIO_TOTAL_DOLAR,
                                            COSTO_TOTAL_DOLAR_COMP,
                                            NoteExistsFlag
                                        )VALUES(
                                            ?,
                                            ?,
                                            ?,
                                            ?,
                                            ?,
                           
                                            ?,
                                            ?,
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
    if(!$queryLineaDoc->execute([
                                 $paquete,
                                 $siguienteConsecutivo,
                                 $contador,
                                 '~OO~',
                                 $articulo,
                                 $bodega,
                                'ND',
                                'O',
                                'D',
                                'L',
                                $cantidadFila,
                                $precioUnitario,
                                0,
                                0,
                                0,
                                $precioUnitario,
                                0
                            ])){
    $errorInfo=$queryLineaDoc->errorInfo();
    $response["success"]="2";
    $response["mensaje"]="No se ha podido finalizar el ingreso".$errorInfo[2];
    echo(json_encode($response));   
    return;

    } 
    $contador++;
}


/**
 * *EDITAR TRANSACCION
 */
$queryEditarTransaccion=$dbBodega->prepare("UPDATE TRANSACCION SET Estado='F',
                                            Documento_Inv=? WHERE Estado='P'
                                            AND NumeroDocumento=?");
if (!$queryEditarTransaccion->execute([$documentoConsecutivoING,
                                       $contenedor])) {

    $errorInfo=$queryActualizarEstado->errorInfo();
    $response["mensaje"]="No se puede actualizar el estado
                            transaccion".$errorInfo[2];
    return;
}


/**
 * *EDITAR CONSECUTIVO_CI
 */
$queryEditarConsecutivo=$dbEximp600->prepare("UPDATE ".$respuesta.".CONSECUTIVO_CI
                                                SET SIGUIENTE_CONSEC=? 
                                            WHERE CONSECUTIVO='COMPRA'");


if (!$queryEditarConsecutivo->execute([$documentoConsecutivoING])) {
    $errorInfo=$queryEditarConsecutivo->errorInfo();
    $response["mensaje"]="No se puede editar el consecutivo".$errorInfo[2];
    return;
}



$response["success"]="1";
$response["message"]="Se actualizaron los costos";
$response["documentoActualizado"]=$documentoConsecutivoING;
echo(json_encode($response));







?>