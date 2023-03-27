<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>

<?php
error_reporting(0);
include("conexion.php");
?>
<h3 style="text-align: center; text-decoration: underline;">REPORTE DE PEDIDOS DE ARTICULOS</h3>
<form method="POST">
DESDE: <input type="date" name="desde" class="text" style="width: 20%; padding: 0.5%;" required>
HASTA: <input type="date" name="hasta" class="text" style="width: 20%; padding: 0.5%;" required>
<input type="submit" name="" value="GENERAR" class="btnfinal" style="background-color: white; color: black; padding: 0.5%; border-color: black; border-radius: none;">	

</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$bodega=$_SESSION['bodega'];
$c=$conexion2->query("select sum(cantidad_tienda) as cantidad,convert(date,fecha)as fecha,tienda,estado from pedidos where tienda='$bodega' and convert(date,fecha) between '$desde' and '$hasta' and estado!='SOLI...' group by tienda,convert(date,fecha),tienda,estado

")or die($conexion2->error());
}else
{
	$desde=$_GET['desde'];
	$hasta=$_GET['hasta'];
	$bodega=$_GET['bodega'];
	if($desde!='' and $hasta!='' and $bodega!='')
	$c=$conexion2->query("select sum(cantidad_tienda) as cantidad,convert(date,fecha)as fecha,tienda,estado from pedidos where tienda='$bodega' and convert(date,fecha) between '$desde' and '$hasta' and estado!='SOLI...' group by tienda,convert(date,fecha),tienda,estado

")or die($conexion2->error());
}




$n=$c->rowCount();

if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN PEDIDO</h3>";
}else
{
	echo "<center><table border='1' style='border-collapse:collapse;' cellpadding='10'>";

	echo "<tr>
	<td>FECHA</td>
	<td>BODEGA</td>
	<td>CANTIDAD SOLICITADA</td>
	<td>ESTADO</td>
	<td>DETALLE</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$fecha=$f['fecha'];
		$bodega=$f['tienda'];
		$cantidad=$f['cantidad'];
		$estado=$f['estado'];

		$cb=$conexion1->query("select concat(bodega,':',nombre) as bodega from consny.bodega where bodega='$bodega'")or die($conexion1->error());

		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$tienda=$fcb['bodega'];
		if($estado=='SOLI...')
		{
			$estado='PENDIENTE';
		}
		echo "<tr>
	<td>$fecha</td>
	<td>$tienda</td>
	<td>$cantidad</td>
	<td>$estado</td>
	<td>
	<a href='detalle_reporte_pedido_tienda.php?fecha=$fecha&&bodega=$bodega&&desde=$desde&&hasta=$hasta'>
	<button style='padding:3%; background-color:skyblue; color: black; border-color:black; cursor:pointer;'>DETALLE</button></a>
	</td>
	</tr>";
	}
}
?>
</body>
</html>