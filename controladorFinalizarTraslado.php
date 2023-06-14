<?php
session_start();
include('conexiones/conectar.php');
$respuesta=$_SESSION['compania'];
$usuario=$_SESSION['usuario'];
$traslado='TRASLADO';
$bodegaOrigen=$_POST['bodegaOrigen'];
$bodegaDestino=$_POST['bodegaDestino'];
$fechaOrigen=$_POST['fechaOrigen'];
$N='N';
$N2='N';
$ajusteConfig="~TT~";
$tipo="T";
$subtipo="D";
$subsubtipo='';
$cantidad="1";
$costoTotalLocal="1";
$costoTotalDolar="0";
$precioTotalLocal="0";
$precioTotalDolar="0";
$noteExitsFlags="0";
//echo($respuesta."".$usuario."".$traslado."".$bodegaOrigen."".$bodegaDestino."".$fechaOrigen."".$N."".$N2);
$cero=0;
$response=array();
$trasladoBodega='TRASLADO DE BODEGA '.$bodegaOrigen.' A '.$bodegaDestino;

$queryConsecutivo=$dbEximp600->prepare("SELECT CONSECUTIVO, SIGUIENTE_CONSEC FROM ".$respuesta.".CONSECUTIVO_CI WHERE CONSECUTIVO='TRASLADO'");


$queryPaquete=$dbEximp600->prepare("SELECT PAQUETE FROM dbo.USUARIOBODEGA WHERE USUARIO='$usuario'");
$queryPaquete->execute();
$queryConsecutivo->execute();
$dataQueryConsecutivo=$queryConsecutivo->fetchAll();
$dataQueryPaquete=$queryPaquete->fetchAll();
foreach ($dataQueryConsecutivo as $key) {
    $doc_consecutivo_ci=$key['CONSECUTIVO'];
    # code...
}
foreach ($dataQueryPaquete as $key) {
    $paqueteInventario=$key['PAQUETE'];
    # code...
}
function obtener_documento($dataQueryConsecutivo){
    foreach ($dataQueryConsecutivo as $key) {
        $documento=$key['SIGUIENTE_CONSEC'];
        
        
        # code...
    }

    return $documento;
}

$documento_consecutivo=obtener_documento($dataQueryConsecutivo);
function obtener_consecutivo($documento_consecutivo){
    $consecutivo=preg_replace_callback('/\d+/',function($matches){
        return sprintf('%0'.strlen($matches[0]). 'd', intval($matches[0])+1);
    },$documento_consecutivo);
    return $consecutivo;
}
$consecutivo_elemento=obtener_consecutivo($documento_consecutivo);

$_SESSION['documentoInventarioPdf']=$documento_consecutivo;


$query= (
    
    "INSERT INTO 
        '$respuesta'.DOCUMENTO_INV (
            PAQUETE_INVENTARIO,
            DOCUMENTO_INV,
            CONSECUTIVO,
            REFERENCIA,
           
            FECHA_DOCUMENTO,
            SELECCIONADO,
            USUARIO,
            APROBADO,
            NoteExistsFlag
        ) VALUES (
            :paqueteInventario,
            :doc_consecutivo_ci,
            :traslado,
            :trasladoBodega,
            :fechaOrigen,
            :N,
            :usuario,
            :N2,
            :cero

        )");
$stmt=$dbEximp600->prepare($query);
$stmt->bindParam(":paqueteInventario",$paqueteInventario);
$stmt->bindParam(":doc_consecutivo_ci",$documento_consecutivo);
$stmt->bindParam(":traslado",$traslado);
$stmt->bindParam(":trasladoBodega",$trasladoBodega);
$stmt->bindParam(":fechaOrigen",$fechaOrigen);
$stmt->bindParam(":N",$N);
$stmt->bindParam(":usuario",$usuario);
$stmt->bindParam(":N2",$N2);
$stmt->bindParam(":cero",$cero);
if($stmt->execute()){

    $response["message"]="registro exitoso";

    //echo("Registro exitoso");
}else{
    $error = $stmt->errorInfo();
    $response["message"]="Registro salio mal". $error[2];
}
$contador=1;
$datos=isset($_POST['json'])?$_POST['json']:'';
$datosDecodificados=json_decode($datos,true);
foreach ($datosDecodificados as $key => $value) {
    $descripcion = isset($value['descripcion']) ? $value['descripcion'] : '';
    $codigoBarra = isset($value['CodigoBarra']) ? $value['CodigoBarra'] : '';
    $articulo = isset($value['Articulo']) ? $value['Articulo'] : '';
    $bodegaOrigen = isset($value['BODEGA ORIGEN']) ? $value['BODEGA ORIGEN'] : '';
    $bodegaDestino = isset($value['BODEGA DESTINO']) ? $value['BODEGA DESTINO'] : '';




    //$codigoBarra= isset($value)
    //echo $bodegaDestino;
    # code...

    
    $query =$dbEximp600->prepare("INSERT INTO 
                                ".$respuesta.".LINEA_DOC_INV 
                                (PAQUETE_INVENTARIO,
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
                                BODEGA_DESTINO,
                                NoteExistsFlag) 
                                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $query->execute([$paqueteInventario,
                            $documento_consecutivo,
                            $contador,
                            $ajusteConfig,
                            $articulo,
                            $bodegaOrigen,
                            $tipo,
                            $subtipo,
                            $subsubtipo,
                            $cantidad,
                            $costoTotalLocal,
                          
                            $costoTotalDolar,
                            $precioTotalLocal,
                            $precioTotalDolar,
                            $bodegaDestino,
                            $noteExitsFlags]);
    $contador++;
}

$queryActualizar= "UPDATE ".$respuesta.".consecutivo_ci SET SIGUIENTE_CONSEC=? WHERE consecutivo='TRASLADO' ";
$actualizarConsecutivo=$dbEximp600->prepare($queryActualizar);
if($actualizarConsecutivo->execute([$consecutivo_elemento])){
    $response["message"]="Registro exitoso";
}else{
    $error = $actualizarConsecutivo->errorInfo();
    $response["message"]="Registro salio mal ".$error[2];
    //echo("Registro salio mal". $error[2]);
}


$fechaHoraTemporal=$_POST['fechaHoraTemporal'];
$queryTransaccion= "UPDATE TRANSACCION  SET Estado='F', DOCUMENTO_INV=? WHERE IdTipoTransaccion='3' AND Estado is null AND Documento_Inv=? ";
$actualizarTransaccion=$dbBodega->prepare($queryTransaccion);
if($actualizarTransaccion->execute([$documento_consecutivo,$fechaHoraTemporal])){

    $response["message"]="Registro exitoso";
    
    //var_dump($queryTransaccion= "UPDATE TRANSACCION  SET Estado='F', DOCUMENTO_INV='$consecutivo_elemento' WHERE IdTipoTransaccion='3' AND Estado is null AND Documento_Inv='$fechaHoraTemporal' ");
}else{
    $error = $actualizarConsecutivo->errorInfo();
    $response["message"]="Registro salio mal". $error[2];
    //echo("Registro salio mal". $error[2]);
}


$queryRegistro= "UPDATE REGISTRO  SET BodegaActual=? WHERE CodigoBarra IN(SELECT CodigoBarra
                FROM [BODEGA].[dbo].[TRANSACCION]
                where Documento_Inv=? and Naturaleza='S')
                ";
$actualizarRegistro=$dbBodega->prepare($queryRegistro);
if($actualizarRegistro->execute([$bodegaDestino,$documento_consecutivo])){

    $response["message"]="Registro exitoso";
    $response["documentoConsecutivo"]=$documento_consecutivo;
    $response["descripcion"]=$descripcion;
    $response["CodigoBarra"]=$codigoBarra;
    $response["Bodega origen"]=$bodegaOrigen;
    $response["articulo"]=$articulo;  
    //echo("Registro exitoso");
}else{
    $error = $actualizarRegistro->errorInfo();
    $response["message"]="Registro salio mal".$error[2];
    //echo("Registro salio mal". $error[2]);
}


echo(json_encode($response))
?>