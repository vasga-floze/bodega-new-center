<?php
include('conexiones/conectar.php');
session_start();

$arregloData = json_decode($_POST['arregloData'], true);
$paquete=$_SESSION['PAQUETE'];
$contenedor=$_SESSION['contenedor'];
$respuesta=$_SESSION['compania'];
$usuario=$_SESSION['usuario'];
$base=$_SESSION['BASE'];
$bodega=$_SESSION['bodega'];
$fecha=$_SESSION['fecha'];

//RESPONSE
$response=array();
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
    echo(json_encode($response));
    return;
    

    # code...
}
/**
 * *DATOS ARREGLO DE LA TABLA
 */
$contador=0;
$articulo='';
$contenedor='';
 foreach ($arregloData as $value) {
    $articulo=$value["articulo"];
    $peso=$value["peso"];
    $nombre=$value["nombre"];
    $cantidad=$value["cantidad"];
    $totalPeso=$value["totalPeso"];
    
    $queryLineaDoc=$dbEximp600->prepare("INSERT INTO 
                                        ".$respuesta.".LINEA_DOC_INV
                                        (
                                            PAQUETE_INVENTARIO,
                                            DOCUMENTO_INV,
                                            LINEA_DOC_INV,
                                            AJUSTE_CONFIG,
                                            ARTICULO,
                                            BODEGA,
                                            TIPO,
                                            SUBTIPO,
                                            SUBSUBTIPO,
                                            CANTIDAD,
                                            COSTO_TOTAL_LOCAL,
                                            COSTO_TOTAL_DOLAR,
                                            PRECIO_TOTAL_LOCAL,
                                            PRECIO_TOTAL_DOLAR,
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
                                            ?
                                        )");
     if(!$queryLineaDoc->execute([
                                $paquete,
                                $siguienteConsecutivo,
                                $contador,
                                '~OO~',
                                $articulo,
                                $bodega,
                                'O',
                                'O',
                                'O',
                                $cantidad,
                                1,
                                1,
                                1,
                                1,
                                0
                                ])){
    $errorInfo=$queryLineaDoc->errorInfo();
    $response["mensaje"]="No se ha podido finalizar el ingreso".$errorInfo[2];
    echo(json_encode($response));
    return;
    
    }                                      
    $contador++;
}

/**
 * *ACTUALIZAR EL ESTADO DE LOS CODIGOS DE BARRAS CREADOS
 * 
 */




 $queryActualizarEstado=$dbBodega->prepare("UPDATE REGISTRO
                                            SET Estado=?,Activo=1 
                                            WHERE Estado='PROCESO' AND
                                             DOCUMENTO_INV=? AND
                                             FechaCreacion=? ");
if (!$queryActualizarEstado->execute(['FINALIZADO',
                                      $contenedor,
                                      $fecha])) {


    $errorInfo=$queryActualizarEstado->errorInfo();
    $response["mensaje"]="No se puede actualizar el estado".$errorInfo[2];
    return;
}


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

$queryEditarConsecutivo=$dbEximp600->prepare("UPDATE ".$respuesta.".CONSECUTIVO_CI
                                                SET SIGUIENTE_CONSEC=? 
                                            WHERE CONSECUTIVO='COMPRA'");


if (!$queryEditarConsecutivo->execute([$documentoConsecutivoING])) {
    $errorInfo=$queryEditarConsecutivo->errorInfo();
    $response["mensaje"]="No se puede editar el consecutivo".$errorInfo[2];
    return;
}



$response["documento"]=$documentoConsecutivoING;
$response["pesado"]=$paquete;
$response["contenedor"]=$contenedor;
$response["fecha"]=$fecha;

echo(json_encode($response));
?>