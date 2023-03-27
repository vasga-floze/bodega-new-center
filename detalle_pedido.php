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
<body style="background-color:#CCCCC000;">
<div style="display: none;">
<?php
include("conexion.php");
?>
</div>
<div class="detalle">
	<a href="#" style="text-decoration: none; color: white; float: right; margin-right: 0.5%" onclick="cerrar()">CERRAR X</a>
	<br>
	<div class="adentro" style="margin-left: 2.5%; height: 92%;">
	<?php
	$fecha=$_GET['fechac'];
	$usuarioc=$_GET['usuarioc'];
	$c=$conexion2->query("select * from pedidos where fecha_hora_confirma='$fecha' and usuario_confirma='$usuarioc'")or die($conexion2->error());
	echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
	echo "<tr>
		<td>BODEGA</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD SOLICITADA</td>
		<td>CANTIDAD CONFIRMADA</td>
	</tr>";
	$tt=0; $te=0; 
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['articulo'];
		$cantidadt=$f['cantidad_tienda'];
		$cantidade=$f['cantidad_enviada'];
		$bodega=$f['tienda'];
		$cb=$conexion1->query("select concat(bodega,':',nombre) as bodega from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bodega=$fcb['bodega'];
		$ca=$conexion1->query("select articulo,descripcion from consny.articulo where articulo='$art'")or die($conexion1->error());
		
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$articulo=$fca['articulo'];
		$desc=$fca['descripcion'];
		echo "<tr>
		<td>$bodega</td>
		<td>$articulo</td>
		<td>$desc</td>
		<td>$cantidadt</td>
		<td>$cantidade</td>
	</tr>";
	$tt=$tt+$cantidadt;
	$te=$te+$cantidade;
	}
	echo "<tr>
		<td colspan='3'>TOTAL</td>
		<td>$tt</td>
		<td>$te</td>
	</tr>";
	?>
	</div>
	
</div>
</body>
</html>