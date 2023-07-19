<?php
include('conexiones/conectar.php');
session_start();
$datos=isset($_POST["datos"])?$_POST["datos"]:'';
$datosDecodificados=json_decode($datos,true);
//$valor1=$datosDecodificados['Articulo'];
//echo("CONTROLADOR TRASLADO:".json_encode($datosDecodificados));
//$articulo='';
//foreach($datosDecodificados as $valores){
    //$articulo=$valores["articulo"];
//}
/*foreach($datosDecodificados as $valores){
    $articulo=$valores['articulo'];
    $bodegaDes=$valores['bodegaDestino'];
    $bodegaOr=$valores['bodegaOrigen'];
    $codigoBarra=$valores['codigoBarra'];
    $fechaOrigen=$valores['fechaOrigen'];
    $usuario=$valores['usuario'];
    $documento=$valores['documento'];
    $descripcion=$valores['descripcion'];
    $libras=$valores['libras'];
    
}*/
//$articulo = $datosDecodificados['articulo'];
//var_dump($datosDecodificados);
foreach ($datosDecodificados as $key => $value) {
    $articulo=isset($value['articulo'])?$value['articulo']:'';
    $bodegaDes=isset($value['bodegaDestino'])?$value['bodegaDestino']:'';
    $bodegaOr=isset($value['bodegaOrigen'])?$value['bodegaOrigen']:'';
    $codigoBarra=isset($value['codigoBarra'])?$value['codigoBarra']:'';
    $fechaOrigen=isset($value['fechaOrigen'])?$value['fechaOrigen']:'';
    $usuario=isset($value['usuario'])?$value['usuario']:'';
    $documento=isset($value['documento'])?$value['documento']:'';
    $descripcion=isset($value['descripcion'])?$value['descripcion']:'';
    $libras=isset($value['libras'])?$value['libras']:'';

}
//echo("Articulo ".$bodegaDes);

//$_SESSION['documentoInventario']=$documento;


//echo("Bodega origen " .$bodegaOr. "Bodega destino" .$bodegaDes. "articulo" .$articulo. "codigobarra" .$codigoBarra);
//REVISAR SI EL CODIGO DE BARRA EXISTE EN LA BODEGA DE ORIGEN

$query =$dbBodega->prepare("SELECT * FROM REGISTRO WHERE CodigoBarra='$codigoBarra' AND BodegaActual='$bodegaOr' AND Estado='FINALIZADO'");
$query->execute();
if($query->fetchColumn()==0){
    
    $response["status"]="error";
    $response["message"]="El codigo de barra no existe en la bodega de origen";
    echo(json_encode($response));
    return;
 
}

//REVISAR SI EL CODIGO DE BARRA ESTA SIENDO UTLIZADO EN OTRA TRANSACCION SIN FINZALIZAR
$query1=$dbBodega->prepare("SELECT * FROM TRANSACCION WHERE CodigoBarra='$codigoBarra' AND Estado IS NULL");
$query1->execute();
if($query1->fetchColumn()>0){
    $response["status"]="error";
    $response["message"]="El codigo de barra esta siendo utilizado en otra transaccion";
    echo(json_encode($response));
    return;
 
}


//INSERTAR PARA CADA CODIGO PISTOLEADO, LOS DOS INSERT POR CADA UNO


$query2=$dbBodega->prepare("INSERT INTO TRANSACCION (CodigoBarra, IdTipoTransaccion,Fecha,Bodega,Naturaleza,UsuarioCreacion,Documento_Inv) VALUES (?,?,?,?,?,?,?)");

if(!$query2->execute([$codigoBarra,3,$fechaOrigen,$bodegaOr,'S',$usuario,$documento])){
    echo "Error en la consulta" .$query2->errorInfo()[2];
    return;
}
$query3=$dbBodega->prepare("INSERT INTO TRANSACCION (CodigoBarra, IdTipoTransaccion,Fecha,Bodega,Naturaleza,UsuarioCreacion,Documento_Inv) VALUES (?,?,?,?,?,?,?)");

if(!$query3->execute([$codigoBarra,3,$fechaOrigen,$bodegaDes,'E',$usuario,$documento])){
    echo "Error en la consulta" .$query3->errorInfo()[2];
    return;
}
$response["message"]="success";
$response["articulo"]=$articulo;
$response["descripcion"]=$descripcion;
$response["codigoBarra"]=$codigoBarra;
$response["libras"]=$libras;


echo(json_encode($response));


?>