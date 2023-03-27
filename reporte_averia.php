<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST">
<input type="date" name="desde" class="text" style="width: 20%;">
<input type="date" name="hasta" class="text" style="width: 20%;">
<input type="submit" name="" value="BUSCAR" class="boton2">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select corelativo,tienda from averia where  tipo='D' and fecha_desglose between '$desde' and '$hasta'  group by corelativo,tienda order by tienda")or die($conexion2->error());
	$n=$c->rowcount();
	echo "<table border='1' class='tabla' cellpadding='10'>";
	echo "<tr>
	<td colspan='6'><a href='export_averia.php?d=$desde&&h=$hasta'>EXPORTAR A EXCEL</a></td>
	</tr>";
	echo "<tr>
		<td>CORELATIVO</td>
		<td>FECHA DESGLOSE</td>
		<td>BODEGA</td>
		<td>DOCUMENTO_INV</td>
		<td>CANTIDAD</td>
		<td>TOTAL</td>

	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$corelativo=$f['corelativo'];
		$q=$conexion2->query("select * from averia where corelativo='$corelativo' and tipo='D'  and articulo is not null")or die($conexion2->error());
		$t=0;
		$total=0;
		$cantidad=0;
		while($fq=$q->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fq['articulo'];
			$cant=$fq['cantidad'];
			$documento=$fq['documento_inv'];
			$tienda=$fq['tienda'];
			$fecha=$fq['fecha_desglose'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);

			$precio=$fca['PRECIO_REGULAR'];
			$t=$precio * $cant;
			$total=$total + $t;
			$cantidad=$cantidad + $cant;
		}
		$cb=$conexion1->query("select * from consny.bodega where bodega='$tienda'")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);

		echo "<tr>
		<td>$corelativo</td>
		<td>$fecha</td>
		<td>$tienda:".$fcb['NOMBRE']."</td>
		<td>$documento</td>
		<td>$cantidad</td>
		<td>$total</td>
		</tr>";

	}
	echo "</table>";
}
?>
</body>
</html>