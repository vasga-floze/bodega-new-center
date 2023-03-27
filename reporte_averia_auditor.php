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
DESDE: <input type="date" name="desde" class="text" style="width: 20%;" required>
HASTA: <input type="date" name="hasta" class="text" style="width: 20%;" required>
<input type="submit" name="" value="BUSCAR" class="boton2">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select corelativo,fecha_documento,usuario,tienda,auditor from averia where tipo='P' and articulo is null and fecha_documento between '$desde' and '$hasta'  group by corelativo,fecha_documento,usuario,tienda,auditor")or die($conexion2->error());
	echo "<table border='1' class='tabla' cellpadding='10'>";
	echo "<tr>
		<td>CORELATIVO</td>
		<td>FECHA</td>
		<td>TIENDA</td>
		<td>CANTIDAD</td>
		<td>TOTAL</td>
		<td>AUDITOR</td>
		</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$corelativo=$f['corelativo'];
		$fecha=$f['fecha_documento'];
		$tienda=$f['tienda'];
		$auditor=$f['auditor'];

		$q=$conexion2->query("select articulo,cantidad from averia where corelativo='$corelativo'")or die($conexion2->error());
		$t=0;
		$cantidad=0;
		$total=0;
		while($fq=$q->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fq['articulo'];
			$cant=$fq['cantidad'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$precio=$fca['PRECIO_REGULAR'];

			$t=$cant * $precio;
			$cantidad=$cantidad + $cant;
			$total=$total + $t;

		}
		$cb=$conexion1->query("select * from consny.bodega where bodega='$tienda'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
		<td>$corelativo</td>
		<td>$fecha</td>
		<td>".$fcb['BODEGA'].": ".$fcb['NOMBRE']."</td>
		<td>$cantidad</td>
		<td>$total</td>
		<td>$auditor</td>
		</tr>";
	}
}
?>
</body>
</html>