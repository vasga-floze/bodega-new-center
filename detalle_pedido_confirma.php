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
$session=$_GET['session'];
$fecha=$_GET['fecha'];
$bodega=$_GET['bodega'];
if($session=='' or $fecha=='' or $bodega=='')
{
	?>

	<script>window.close();</script>

	<?php
}else
{
$c=$conexion2->query("select * from pedidos where tienda='$bodega' and sessiones='$session' and convert(date,fecha)='$fecha' order by articulo")or die($conexion2->error());
$n=$c->rowCount();
echo "<a href='bodegas_pedidos_confirmar.php' style='border-color: white; padding: 0.5%; background-color: black; text-decoration: none; color: white;'>ATRAS</a>";
if($n==0)
{
	echo "<h3>NO SE ENCONTRO DETALLE</h3>";
}else
{
	echo "<table border='1' style='border-collapse:collapse; margin-left:20%;' cellpadding='10'>";
	echo "<tr>
		<td>BODEGA</td>
		<td>FECHA PEDIDO</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
	</tr>";
	$t=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$bodega=$f['tienda'];
		$art=$f['articulo'];
		$cant=$f['cantidad_tienda'];
		$fecha=explode(' ', $f['fecha']);
		$cb=$conexion1->query("select concat(bodega,' ',nombre) as bodega from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bodegas=$fcb['bodega'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$articulo=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];
		echo "<tr>
		<td>$bodegas</td>
		<td>$fecha[0]</td>
		<td>$articulo</td>
		<td>$desc</td>
		<td>$cant</td>
	</tr>";	
	$t=$t + $cant;
	}

	echo"<tr>
		<td colspan='4'>TOTAL</td>
		<td>$t</td>
	</tr>";
}


}
?>
</body>
</html>