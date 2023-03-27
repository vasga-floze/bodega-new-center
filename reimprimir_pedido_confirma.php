<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
include("conexion.php");
if($_SESSION['tipo']==1)
{

}else
{
	echo "<script>alert('NO DISPONIBLE')</script>";
	echo "<script>location.replace('desglose.php')</script>";
}
?>
<form method="POST">
FECHA CONFIRMACION: <input type="date" name="fecha" required class="text" style="width: 15%;">
<input type="submit" name="btn" value="MOSTRAR" class="btnfinal" style="padding: 0.5%; background-color: white; color: black; border-color: black;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select fecha_hora_confirma,usuario_confirma,tienda,sum(cantidad_enviada) as cantidad from pedidos where convert(date,fecha_hora_confirma)='$fecha' group by fecha_hora_confirma,usuario_confirma,tienda order by tienda")or die($conexion2->error());
	echo "<table border='1' cellpadding='10' style='border-collapse: collapse; margin-top: -7%;'>";
	echo "<tr>
		<td>BODEGA</td>
		<td>FECHA CONFIRMACION</td>
		<td>USUARIO CONFIRMA</td>
		<td>CANTIDAD CONFIRMADA</td>
		<td width='5%'></td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$cantidad=$f['cantidad'];
		$usuario=$f['usuario_confirma'];
		$bodega=$f['tienda'];
		$fecha=$f['fecha_hora_confirma'];
		$fe=explode(" ", $fecha);
		$fechas=$fe[0];
		$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);

		$bod=$fcb['BODEGA'];
		$nom=$fcb['NOMBRE'];

		echo "<tr>
		<td>$bod: $nom</td>
		<td>$fechas</td>
		<td>$usuario</td>
		<td>$cantidad</td>
		<td>
		<a href='final_confirma_pedido.php?fecha=$fecha&&usuario=$usuario&&bodega=$bodega' target='_blank'>
		<img src='imprimir.png' width='50%;' height='7%' style='margin-bottom:-0.5%; margin-left:30%;'>
		</a>
		</td>
	</tr>";

	}
}
?>
</body>
</html>