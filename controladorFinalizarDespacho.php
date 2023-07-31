<?php
session_start();
include('conexiones/conectar.php');
$respuesta=$_SESSION['compania'];
$usuario=$_SESSION['usuario'];
$traslado='INGRESO';
$traslado2='VENTA';

$bodegaOrigenPOST=$_POST['bodegaOrigen'];
$bodegaDestinoPOST=$_POST['bodegaDestino'];
$fechaOrigen=$_POST['fechaOrigen'];
$N='N';
$N2='N';
$ajusteConfigVenta="~VV~";
$tipoVenta="C";
$subtipoVenta="D";
$subsubtipoVenta='N';

$ajusteConfigIngreso="~OO~";
$tipoIngreso="O";
$subtipoIngreso="D";
$subsubtipoIngreso='L';

$cantidad="1";
$costoTotalLocal="0";///
$costoTotalDolar="0";
$precioTotalLocal="0";
$precioTotalDolar="0";
$noteExitsFlags="0";
//echo($respuesta."".$usuario."".$traslado."".$bodegaOrigen."".$bodegaDestino."".$fechaOrigen."".$N."".$N2);
$cero=0;
$response=array();
$trasladoBodega='DESPACHO DE BODEGA '.$bodegaOrigenPOST.' A '.$bodegaDestinoPOST;


/**
 * *TRAER EL CONSECUTIVO DE VENTA
 */
$queryConsecutivo=$dbEximp600->prepare("SELECT CONSECUTIVO, SIGUIENTE_CONSEC FROM ".$respuesta.".CONSECUTIVO_CI WHERE CONSECUTIVO='VENTA'");




/**
 * *TRAER EL CONSECUTIVO DE INGRESO
 */
$queryConsecutivoIngreso=$dbEximp600->prepare("SELECT CONSECUTIVO, SIGUIENTE_CONSEC FROM ".$respuesta.".CONSECUTIVO_CI WHERE CONSECUTIVO='INGRESO'");


$queryPaquete=$dbEximp600->prepare("SELECT PAQUETE FROM dbo.USUARIOBODEGA WHERE USUARIO='$usuario'");
$queryPaquete->execute();

/**
 * *EJECUTAR EL QUERY DEL CONSECUTIVO VENTA
 */
$queryConsecutivo->execute();

/**
 * *EJECUTAR EL QUERY DEL CONSECUTIVO INGRESO
 */

 $queryConsecutivoIngreso->execute();


$dataQueryConsecutivoIngreso=$queryConsecutivoIngreso->fetchAll();
$dataQueryConsecutivo=$queryConsecutivo->fetchAll();



$dataQueryPaquete=$queryPaquete->fetchAll();
foreach ($dataQueryConsecutivo as $key) {
    $doc_consecutivo_ci=$key['CONSECUTIVO'];
    # code...
}

 /**
  * *TRAER EL CONSECUTIVO INGRESO
  */

foreach ($dataQueryConsecutivoIngreso as $key) {
    $doc_consecutivo_ci_ingreso=$key['CONSECUTIVO'];
    # code...
}

foreach ($dataQueryPaquete as $key) {
    $paqueteInventario=$key['PAQUETE'];
    # code...
}


function obtener_documento_ingreso($dataQueryConsecutivoIngreso){
    foreach($dataQueryConsecutivoIngreso as $key){
        $documento=$key['SIGUIENTE_CONSEC'];
    }

    return $documento;
}

function obtener_documento($dataQueryConsecutivo){
    foreach ($dataQueryConsecutivo as $key) {
        $documento=$key['SIGUIENTE_CONSEC'];
    }
    return $documento;
}

/**
 * *OBTENER EL SIGUIENTE DOCUMENTO INGRESO
 */
$documento_consecutivo_ingreso=obtener_documento_ingreso($dataQueryConsecutivoIngreso);
$documento_consecutivo=obtener_documento($dataQueryConsecutivo);



function obtener_consecutivo($documento_consecutivo){
    $consecutivo=preg_replace_callback('/\d+/',function($matches){
        return sprintf('%0'.strlen($matches[0]). 'd', intval($matches[0])+1);
    },$documento_consecutivo);
    return $consecutivo;
}


function obtener_consecutivo_ingreso($documento_consecutivo_ingreso){
    $consecutivo=preg_replace_callback('/\d+/',function($matches){
        return sprintf('%0'.strlen($matches[0]). 'd', intval($matches[0])+1);

    },$documento_consecutivo_ingreso);

    return $consecutivo;
}


$consecutivo_elemento=obtener_consecutivo($documento_consecutivo);
$consecutivo_elemento_ingreso=obtener_consecutivo_ingreso($documento_consecutivo_ingreso);

$_SESSION['documentoInventarioPdf']=$documento_consecutivo;



/**
 * * INSERTAR DOCUMENTO INV
 * 
 * 
 * 
 */



$query= ("INSERT INTO ".$respuesta.".DOCUMENTO_INV (
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
if(!$stmt->execute()){
    $error = $stmt->errorInfo();
    $response["message"]="Registro salio mal". $error[2];
    echo(json_encode($response));
    return;
}



/**
 * *INSERTA EL DOCUMENTO INV 2.0
 */


$query= ("INSERT INTO ".$respuesta.".DOCUMENTO_INV (
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
$stmt->bindParam(":doc_consecutivo_ci",$documento_consecutivo_ingreso);
$stmt->bindParam(":traslado",$traslado2);
$stmt->bindParam(":trasladoBodega",$trasladoBodega);
$stmt->bindParam(":fechaOrigen",$fechaOrigen);
$stmt->bindParam(":N",$N);
$stmt->bindParam(":usuario",$usuario);
$stmt->bindParam(":N2",$N2);
$stmt->bindParam(":cero",$cero);
if(!$stmt->execute()){
$error = $stmt->errorInfo();
$response["message"]="Registro salio mal". $error[2];
echo(json_encode($response));
return;
}




$contador=1;

$json = $_POST['json'];
//$datos=isset($_POST['data'])?$_POST['data']:'';
$datosDecodificados=json_decode($json,true);


/**
 * 
 * *INSERTAR EN LINEA DOCUMENTOINV
 */


foreach ($datosDecodificados as $key) {
    $bodegaDestino=isset($key["bodegaDestino"])?$key["bodegaDestino"]:$key["BODEGA DESTINO"];
    $bodegaOrigen=isset($key["bodegaOrigen"])?$key["bodegaOrigen"]:$key["BODEGA ORIGEN"];
    $codigoBarra=isset($key["codigoBarra"])?$key["codigoBarra"]:$key["CodigoBarra"];
    $descripcion=isset($key["descripcion"])?$key["descripcion"]:$key["Descripcion"];
    $articulo=isset($key["articulo"])?$key["articulo"]:$key["Articulo"];

    
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
                            $ajusteConfigVenta,
                            $articulo,
                            $bodegaOrigen,
                            $tipoVenta,
                            $subtipoVenta,
                            $subsubtipoVenta,
                            $cantidad,
                            $costoTotalLocal,
                          
                            $costoTotalDolar,
                            $precioTotalLocal,
                            $precioTotalDolar,
                            $bodegaDestino,
                            $noteExitsFlags]);
    $contador++;
}





/**
 * 
 * *INSERTAR EN LINEA DOCUMENTOINV 2.0
 */


 foreach ($datosDecodificados as $key) {
    $bodegaDestino=isset($key["bodegaDestino"])?$key["bodegaDestino"]:$key["BODEGA DESTINO"];
    $bodegaOrigen=isset($key["bodegaOrigen"])?$key["bodegaOrigen"]:$key["BODEGA ORIGEN"];
    $codigoBarra=isset($key["codigoBarra"])?$key["codigoBarra"]:$key["CodigoBarra"];
    $descripcion=isset($key["descripcion"])?$key["descripcion"]:$key["Descripcion"];
    $articulo=isset($key["articulo"])?$key["articulo"]:$key["Articulo"];

    
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
                            $documento_consecutivo_ingreso,
                            $contador,
                            $ajusteConfigIngreso,
                            $articulo,
                            $bodegaOrigen,
                            $tipoIngreso,
                            $subtipoIngreso,
                            $subsubtipoIngreso,
                            $cantidad,
                            $costoTotalLocal,
                          
                            $costoTotalDolar,
                            $precioTotalLocal,
                            $precioTotalDolar,
                            $bodegaDestino,
                            $noteExitsFlags]);
    $contador++;
}




/**
 * *ACTUALIZA EL  CONSECUTIVO_CI
 */

$queryActualizar= "UPDATE ".$respuesta.".consecutivo_ci SET SIGUIENTE_CONSEC=? WHERE consecutivo='VENTA' ";
$actualizarConsecutivo=$dbEximp600->prepare($queryActualizar);
if($actualizarConsecutivo->execute([$consecutivo_elemento])){
    $response["message"]="Registro exitoso";
}else{
    $error = $actualizarConsecutivo->errorInfo();
    $response["message"]="Registro salio mal ".$error[2];
    //echo("Registro salio mal". $error[2]);
}

/**
 * *ACTUALIZA EL  CONSECUTIVO_CI 2.0
 */

 $queryActualizar= "UPDATE ".$respuesta.".consecutivo_ci SET SIGUIENTE_CONSEC=? WHERE consecutivo='INGRESO' ";
 $actualizarConsecutivo=$dbEximp600->prepare($queryActualizar);
 if($actualizarConsecutivo->execute([$consecutivo_elemento_ingreso])){
     $response["message"]="Registro exitoso";
 }else{
     $error = $actualizarConsecutivo->errorInfo();
     $response["message"]="Registro salio mal ".$error[2];
     //echo("Registro salio mal". $error[2]);
 }
 



/**
 * *ACTUALIZA LA TABLA TRANSACCION
 */

$fechaHoraTemporal=$_POST['fechaHoraTemporal'];
$queryTransaccion= "UPDATE TRANSACCION  SET Estado='F', DOCUMENTO_INV=? WHERE IdTipoTransaccion='7' AND Estado is null AND Documento_Inv=? ";
$actualizarTransaccion=$dbBodega->prepare($queryTransaccion);
if($actualizarTransaccion->execute([$documento_consecutivo,$fechaHoraTemporal])){

    $response["message"]="Registro exitoso";
    
    //var_dump($queryTransaccion= "UPDATE TRANSACCION  SET Estado='F', DOCUMENTO_INV='$consecutivo_elemento' WHERE IdTipoTransaccion='3' AND Estado is null AND Documento_Inv='$fechaHoraTemporal' ");
}else{
    $error = $actualizarConsecutivo->errorInfo();
    $response["message"]="Registro salio mal". $error[2];
    //echo("Registro salio mal". $error[2]);
}

/**
 * *ACTUALIZA LA TABLA TRANSACCION2.0
 */

 $fechaHoraTemporal=$_POST['fechaHoraTemporal'];
 $queryTransaccion= "UPDATE TRANSACCION  SET Estado='F', DOCUMENTO_INV=? WHERE IdTipoTransaccion='8' AND Estado is null AND Documento_Inv=? ";
 $actualizarTransaccion=$dbBodega->prepare($queryTransaccion);
 if($actualizarTransaccion->execute([$documento_consecutivo,$fechaHoraTemporal])){
 
     $response["message"]="Registro exitoso";
     
     //var_dump($queryTransaccion= "UPDATE TRANSACCION  SET Estado='F', DOCUMENTO_INV='$consecutivo_elemento' WHERE IdTipoTransaccion='3' AND Estado is null AND Documento_Inv='$fechaHoraTemporal' ");
 }else{
     $error = $actualizarConsecutivo->errorInfo();
     $response["message"]="Registro salio mal". $error[2];
     //echo("Registro salio mal". $error[2]);
 }
 







/**
 * *ACTUALIZA LA TABLA REGISTRO
 */


$queryRegistro= "UPDATE REGISTRO  SET BodegaActual=? WHERE CodigoBarra IN(SELECT CodigoBarra
                FROM TRANSACCION
                where Documento_Inv=? and Naturaleza='S')
                ";
$actualizarRegistro=$dbBodega->prepare($queryRegistro);
if($actualizarRegistro->execute([$bodegaDestinoPOST,$documento_consecutivo])){

    $response["message"]="Registro exitoso";
    $response["documentoConsecutivo"]=$documento_consecutivo;
    $response["descripcion"]=$descripcion;
    $response["CodigoBarra"]=$codigoBarra;
    $response["Bodega origen"]=$bodegaOrigenPOST;
    $response["Bodega destino"]=$bodegaDestinoPOST;
    $response["articulo"]=$articulo;  
    //echo("Registro exitoso");
}else{
    $error = $actualizarRegistro->errorInfo();
    $response["message"]="Registro salio mal".$error[2];
    //echo("Registro salio mal". $error[2]);
}





/**
 * *ACTUALIZA LA TABLA REGISTRO2.0
 */


 $queryRegistro= "UPDATE REGISTRO  SET BodegaActual=? WHERE CodigoBarra IN(SELECT CodigoBarra
 FROM TRANSACCION
 where Documento_Inv=? and Naturaleza='E')
 ";
$actualizarRegistro=$dbBodega->prepare($queryRegistro);
if($actualizarRegistro->execute([$bodegaDestinoPOST,$documento_consecutivo])){

$response["message"]="Registro exitoso";
$response["documentoConsecutivo"]=$documento_consecutivo;
$response["descripcion"]=$descripcion;
$response["CodigoBarra"]=$codigoBarra;
$response["Bodega origen"]=$bodegaOrigenPOST;
$response["Bodega destino"]=$bodegaDestinoPOST;
$response["articulo"]=$articulo;  
//echo("Registro exitoso");
}else{
$error = $actualizarRegistro->errorInfo();
$response["message"]="Registro salio mal".$error[2];
//echo("Registro salio mal". $error[2]);
}



echo(json_encode($response))
?>