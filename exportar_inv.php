<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
//include("conexion.php");
ini_set('max_execution_time', 1900);
date_default_timezone_set('America/El_Salvador');
session_start();
//include("menu.php");

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
        die("Error connecting to SQL Server: " . $e->getMessage());
    }


    $p=$_SESSION['paquete'];
    $u=$_SESSION['usuario'];
   if($_SESSION['paquete']=="" or $_SESSION['usuario']=="")
   {
    $p=$_SESSION['paquete'];
    $u=$_SESSION['usuario'];
    echo "<script>alert('INICIE SESION')</script>";
    echo "<script>location.replace('conexiones.php')</script>";
   }
       
     
 ?>
	<?php

header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=archivo.xls');


$inv=$_GET['inv'];
if($inv!='')
{
 $c=$conexion2->query("select * from inventario where sessiones='$inv' and registro!='0'")or die($conexion2->error());
 $n=$c->rowCount();
 if($n==0)
 {
 	echo "<script>alert('NO SE ENCONTRO INGRESO DE ESTE INVENTARIO')</script>";
 	echo "<script>location.replace('r_inventario.php')</script>";
 }else
 {
 	$cb=$conexion2->query("select top 1 * from inventario where sessiones='$inv'")or die($conexion2->error());
 	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
 	$bodegai=$fcb['bodega'];
 	$fecha=$fcb['fecha'];
 	$digita=$fcb['digita'];
 	echo "<table border='1'>
 	<tr>
 	<td colspan='6'>BODEGA INVENTARIO: $bodegai <BR>FECHA: $fecha <BR>DIGITADO: $digita</td>
 	</tr>
 	</table>
 	<table border='1'>
 	<tr>
 	<td>ARTICULO</td>
 	<td>DESCRIPCION</td>
    <td>OBSERVACION</td>
 	<td>BODEGA INVENTARIO</td>
 	<td>CODIGO DE BARRA</td>
 	<td>BODEGA FARDO</td>
 	</tr>";
 	while($f=$c->FETCH(PDO::FETCH_ASSOC))
 	{
 		$idr=$f['registro'];
 		$bodega=$f['bodega'];

 		$bodegainv=$f['bodega_actual'];
 		$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
 		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
 		$cod=$fcr['codigo'];
 		$barra=$fcr['barra'];
        $obs=$fcr['observacion'];
 		$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());

 		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
 		$art=$fca['ARTICULO'];
 		$des=$fca['DESCRIPCION'];
 if($bodegainv!=$bodega)
 {
 	echo "<tr style='background-color:red; color:white;'>";
 }
$u=$_SESSION['usuario'];
 $qv=$conexion2->query("select count(*) as total from inventario where registro='$idr' and sessiones='$inv' and usuario='$u'")or die($conexion2->error());
 $fqv=$qv->FETCH(PDO::FETCH_ASSOC);
 if($fqv['total']>1)
 {
    echo "<tr style='background-color:yellow; color:black;'>";
 }
 if($bodegainv==$bodega and $fqv['total']==0)
 {
    echo "<tr>";
 }

 		echo"
 
 	<td>$art</td>
 	<td>$des</td>
    <td>$obs</td>
 	<td>$bodega</td>
 	<td>$barra</td>
 	<td>$bodegainv</td>
 	</tr>";
 	}
 	echo "</table>";

 }
 echo "<script>location.replace('r_inventario.php')</script>";

}else
{
	echo "<script>location.replace('r_inventario.php')</script>";
}
?>