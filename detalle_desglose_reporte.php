<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">

	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function cerrar()
		{
			window.close();
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
$bodega=$_GET['bodega'];
$idr=base64_decode($_GET['id']);
$c=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<script>location.replace('desglose_reporte.php')</script>";
}else
{
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$cod=$f['codigo'];
	$barra=$f['barra'];
	$id=$f['id_registro'];
	$bode=$f['bodega'];
	$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
	$articulo=$fca['ARTICULO'];
	$desc=$fca['DESCRIPCION'];

}
?>
<div class="detalle" style="margin-top: -5%; width: 105%; margin-left: -0.5%;">
<?php
echo "<a href='#' onclick='cerrar()' style='text-decoration:none; color:white; float:right; margin-right:6%; margin-top:
1.2%;'>CERRAR X</a><br>";
?>
<div class="adentro" style="margin-left: 1%; height: 93%; width: 93%;">
<?php
echo "<h3 style='text-align:center;'>DESGLOSE DE $articulo: $desc (BARRA: $barra)</h3> <hr>";
$cd=$conexion2->query("select * from desglose where registro='$idr'")or die($conexion2->error());

$cb=$conexion1->query("select concat(bodega,':',nombre) as bodega from consny.bodega where bodega='$bode'")or die($conexion1->error());
$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
$bodegas=$fcb['bodega'];
$ncd=$cd->rowCount();
if($ncd==0)
{
	echo "<h3>NO SE ENCONTRARON PIEZAS</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:98%; margin-left:1%;'>";

echo "<tr>
<td>BODEGA</td>
<td>ARTICULO</td>
<td>DESCRIPCION</td>
<td>CANTIDAD</td>";
if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALAVARADO' or $_SESSION['usuario']=='staana3' OR $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['tipo']!=2)
{
	echo "<td>PRECIO</td>
		  <td>TOTAL</td>";
}
echo "</tr>";
$total=0;
$tc=0;
while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
{
$art=$fcd['articulo'];
$cant=$fcd['cantidad'];
$precio=$fcd['precio'];
$t=$cant*$precio;
$total=$total+$t;
$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$arti=$fca['ARTICULO'];
$descri=$fca['DESCRIPCION'];
echo "<tr class='tre'>
<td>$bodegas</td>
<td>$arti</td>
<td>$descri</td>
<td>$cant</td>";
$tc=$tc+$cant;


echo "";
if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALAVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['tipo']!=2)
{
	echo "<td>$precio</td>
		  <td>$$t</td>";
}
}
if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALAVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['tipo']!=2)
{
echo "<tr>
<td colspan='3'>TOTAL:</td>
<td>$tc</td>
<td></td>
<td>$$total</td>
</tr>";
}

}




?>
</div>
	
</div>
</body>
</html>