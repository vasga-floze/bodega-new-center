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
                                            ?
                                        )");
     if(!$queryLineaDoc->excute([
                                $paquete,
                                $siguienteConsecutivo,
                                $contador,
                                '~OO~',
                                $articulo,
                                $bodega,
                                'ODL',
                                'ODL',
                                'ODL',
                                $cantidad,
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


$response["articulo"]=$articulo;
$response["pesado"]=$paquete;

echo(json_encode($response));
?>