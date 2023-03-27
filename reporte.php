<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<meta charset="utf-8">
<?php


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


$c=$conexion2->query("select * from registro where id_registro='11649'")or die($conexion2->error());
echo "<table border='1'>";
echo "<tr>
    <td>ARTICULO</td>
    <td>DESCRIPCION</td>
    <td>CANTIDAD</td>
    <td>PESO</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>";
$f=$c->FETCH(PDO::FETCH_ASSOC);
$conte=$f['contenedor'];
$fecha=$f['fecha_documento'];
$k=$conexion2->query("select SUM(cantidad) as cantidad,peso,codigo from registro where tipo='cd' and contenedor='$conte' and fecha_documento='$fecha' group by cantidad,peso,codigo
")or die($conexion2->error());
$tc=0;
$tp=0;
while($fk=$k->FETCH(PDO::FETCH_ASSOC))
{
    $cod=$fk['codigo'];
    $peso=$fk['peso'];
    $cantidad=$fk['cantidad'];
    $tc=$tc+$cantidad;
    $tp=$tp + $peso;
    $ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
    $fca=$ca->FETCH(PDO::FETCH_ASSOC);
    $art=$fca['ARTICULO'];
    $des=$fca['DESCRIPCION'];
    echo "<tr>
    <td>$art</td>
    <td>$des</td>
    <td>$cantidad</td>
    <td>$peso</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>";
}
echo "<tr>
<td colspan='2'>TOTAL</td>
<td>$tc</td>
<td>$tp</td>
</tr>";

?>



</body>
</html>