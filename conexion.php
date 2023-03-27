
<meta charset="utf-8">
<?php

ini_set('max_execution_time', 10000000);
date_default_timezone_set('America/El_Salvador');
session_start();
/*
TIPO=1 SON LOS DE BODEGA
TIPO=2 SON LAS TIENDAS 
TIPO= 3 SON LOS AUDITORES
TIPO=4 LAS DE CONTABILIDAD
TIPO=5 EL LIC

*/
if($_SESSION['tipo']==1)
{
    include("menus.php");
    //include("menu.php");
}else if($_SESSION['tipo']==2)
{
    include("menu_tienda.php");
}
if($_SESSION['tipo']==4)
{
    
    include("menus1.php");
}

if($_SESSION['tipo']==5)
{
    include("menus1.php");
    
}if($_SESSION['tipo']==3)
{
    include("menu.php");
    
}



    try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("!!ERROR!! SE PERDIO CONEXION CON EL SERVIDOR: ". $e->getMessage() );
    }

    try {
        $conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("ERROR DE CONEXION CON EL SERVICIO SQL " . $e->getMessage());
    }


    $p=$_SESSION['paquete'];
    $u=$_SESSION['usuario'];
   if($_SESSION['usuario']=="")
   {
    $p=$_SESSION['paquete'];
    $u=$_SESSION['usuario'];
    echo "<script>alert('INICIE SESION')</script>";
    echo "<script>location.replace('conexiones.php')</script>";
   }


   
function validacion_disponible($ba)
{
    try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("!!ERROR!! SE PERDIO CONEXION CON EL SERVIDOR: " );
    }

    try {
        $conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("ERROR DE CONEXION CON EL SERVICIO SQL " . $e->getMessage());
    }
    $error="FARDO NO SE PUEDE USAR POR:";

    $activo=$conexion2->query("select * from registro where barra='$ba' and activo='0'")or die($conexion2->error());
    $nactivo=$activo->rowCount();
    if($nactivo!=0)
    {
        $error.="\\n- NO ESTA DISPONIBLE";
    }
    $eliminacion=$conexion2->query("select * from registro where barra='$ba' and fecha_eliminacion is not  null")or die($conexion2->error());

    $neliminacion=$eliminacion->rowCount();
    //echo "<script>alert('$neliminacion -> $ba')</script>";
    if($neliminacion!=0)
    {
    $error="\\n- ELIMINADO";
    }

    $mesa=$conexion2->query("select * from detalle_mesa inner join registro on registro.id_registro=detalle_mesa.registro where registro.barra='$ba'")or die($conexion2->error());
    $nmesa=$mesa->rowCount();

    if($nmesa!=0)
    {
    $error.="\\n- BULTOS TRABAJADOS";
    }

    $venta=$conexion2->query("select * from venta inner join registro on venta.registro=registro.id_registro where registro.barra='$ba'")or die($conexion2->error());
    $nventa=$venta->rowCount();
    if($nventa!=0)
    {
    $error.="\\n- FARDOS VENDIDO";
    }

    $desglose=$conexion2->query("select * from desglose inner join registro on desglose.registro=registro.id_registro where registro.barra='$ba'")or die($conexion2->error());
    $ndesglose=$desglose->rowCount();
    if($ndesglose!=0)
    {
    $error.="\\n- DESGLOSE";

    }

    $venta_tienda=$conexion2->query("select * from registro where  barra='$ba' and fecha_hora_venta is not null")or die($conexion2->error());
    $nventa_tienda=$venta_tienda->rowCount();
    if($nventa_tienda!=0)
    {
        $error.="\\n- VENTA EN TIENDA";
    }

    $traslados=$conexion2->query("select traslado.origen,traslado.destino,traslado.usuario,traslado.fecha_ingreso from registro inner join traslado on traslado.registro=registro.id_registro where registro.barra='$ba' and traslado.estado='0'
")or die($conexion2->error());
    $ntraslados=$traslados->rowCount();
    if($ntraslados!=0)
    {
        $ftraslados=$traslados->FETCH(PDO::FETCH_ASSOC);
        $origenfuncion=$ftraslados['origen'];
        $destinofuncion=$ftraslados['destino'];
        $fechaingresofuncion=$ftraslados['fecha_ingreso'];
        $usufuncion=$ftraslados['usuario'];
        $error.="\\n- TRASLADOS SIN FINALIZAR DE LA $origenfuncion A LA $destinofuncion USUARIO: $usufuncion FECHA Y HORA $fechaingresofuncion";
    }

    return $error;
}

     
 ?>