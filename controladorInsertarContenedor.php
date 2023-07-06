<?php
include('conexiones/conectar.php');
session_start();
$respuesta=$_SESSION['compania'];
$usuario=$_SESSION['usuario'];
$bodega=$_SESSION['bodega'];
//$fecha=$_SESSION['fecha'];
$fechaActual = date('Y-m-d');
//$paquete=$_SESSION['PAQUETE'];
$contenedor='';
if(isset($_POST["numeroDocumento"])){
    $contenedor=$_POST["numeroDocumento"];
}else{
    $contenedor=$_SESSION['contenedor'];
}

$descripcion=$_POST['descripcion'];
$clasificacion=$_POST['clasificacion'];
$articulo=$_POST['articulo'];
$libras=$_POST['libra'];
$fecha=$_POST['fecha'];
$cantidad=$_POST['cantidad'];
$totalCantidad=$cantidad*$libras;


$response=array();

$response['bodega']=$bodega;
$response['fecha']=$fecha;
$response['contenedor']=$contenedor;
$response['descripcion']=$descripcion;
$response['clasificacion']=$clasificacion;
$response['articulo']=$articulo;
$response['libras']=$libras;
$response['fecha']=$fecha;
$response['usuario']=$usuario;


//$contador=10;
for ($i=1; $i <=$cantidad; $i++) { 
    $fechaActual= date('m.d.y');
    $fechaComoEntero=strtotime($fechaActual);
    $anio=date("y", $fechaComoEntero);
    $mes=date("m", $fechaComoEntero);
    $dia=date("d", $fechaComoEntero);
    $DesdeLetra = "a";
    $HastaLetra = "z";
    $IdTipoEmpaque=6;
    $IdTipoRegistro=1;
    $letraAleatoria = chr(rand(ord($DesdeLetra), ord($HastaLetra)));
    $letraMayuscula = strtoupper($letraAleatoria);
    
    $query =$dbBodega->query("SELECT 
                                isnull (max(Sesion), 0) AS maximo 
                                FROM dbo.REGISTRO 
                                WHERE FechaCreacion=
                                '".$fecha."'AND IdTipoRegistro=2
                                and DOCUMENTO_INV='".$contenedor."'"
                            );

    $sesion=$query->fetch(PDO::FETCH_ASSOC);

    $devuelve=$sesion['maximo'];
    $devuelve=$devuelve+1;
    if($devuelve>=0 && $devuelve<10){
        $numero="00$devuelve";
    }else if($devuelve>9 && $devuelve<100){
        $numero="0$devuelve";
    }else{
        $numero=$devuelve;
    }
    $codigoBarra =(
            "".$letraMayuscula.""."P".$dia."".$mes."".$numero."".$anio
                );
    $queryGenerarYguardarCodigo=$dbBodega->prepare(
                                        "INSERT INTO REGISTRO
                                                (
                                                    CodigoBarra,
                                                    Articulo,
                                                    Descripcion,
                                                    Clasificacion,
                                                    Libras,
                                                    IdTipoEmpaque,
                                                    IdUbicacion,
                                                    BodegaCreacion,
                                                    BodegaActual,
                                                    UsuarioCreacion,
                                                    Estado,
                                                    Activo,
                                                    FechaCreacion,
                                                    Sesion,
                                                    IdTipoRegistro,
                                                    DOCUMENTO_INV
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
                                            ?,
                                            ?,
                                            ?,
                                            ?,
                                            ?,
                                            ?,
                                            ?
                                        ) 

                                    ");
    if (!$queryGenerarYguardarCodigo->execute([
                                        $codigoBarra,
                                        $articulo,
                                        $descripcion,
                                        $clasificacion,
                                        $libras,
                                        6,
                                        1,
                                        $bodega,
                                        $bodega,
                                        $usuario,
                                        'PROCESO',
                                        0,
                                        $fecha,
                                        $numero,
                                        2,
                                        $contenedor

                                        ])) {
        $errorInfo=$queryGenerarYguardarCodigo->errorInfo();
        $response["mensaje"]="No se pudo registrar " .$errorInfo[2];
        echo(json_encode($response));
        return;
        
    }

    $queryInsertarTransaccion=$dbBodega->prepare("INSERT INTO TRANSACCION
                                                    (
                                                        CodigoBarra,
                                                        IdTipoTransaccion,
                                                        Fecha,
                                                        Bodega,
                                                        Naturaleza,
                                                        Estado,
                                                        UsuarioCreacion,
                                                        
                                                        NumeroDocumento
                                                    )
                                                    VALUES(
                                                        
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?
                                                    )");
    if (!$queryInsertarTransaccion->execute([
                                            $codigoBarra,
                                            1,
                                            $fecha,
                                            $bodega,
                                            'E',
                                            'P',
                                            $usuario,
                                            $contenedor
                                        ])) {
        $errorInfo=$queryInsertarTransaccion->errorInfo();
        $response["message"]="No se podido ejecutar la transaccion".$errorInfo[2];
        echo(json_encode($response));
        return;
    }



    
}
//$response["siguiente"]=$siguienteConsecutivo;
$response["codigo"]=$articulo;
$response["nombre"]=$descripcion;
$response["cantidad"]=$cantidad;
$response["peso"]=$libras;
$response["totalPeso"]=$totalCantidad;
$response["mensaje"]="Se pudo ejecutar";

echo(json_encode($response));




?>