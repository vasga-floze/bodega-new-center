<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
		})
		function cerrar()
		{
			window.close();
		}
	</script>
</head>
<body>
<div style="display: none;">
<?php
include("conexion.php");
$fecha=$_GET['fecha'];
$bodega=$_GET['bodega'];
?>	
</div>
<div class="detalle">
	<a href="#" style="color: white; text-decoration: none; float: right;" onclick="cerrar()">CERRAR X</a>
	<br>
	<div class="adentro" style="margin-left: 2.5%; height: 92%;">
	<?php
	$c=$conexion2->query("select art_origen,art_destino,convert(decimal(10,2),precio_origen) as precio_origen,convert(decimal(10,2),precio_destino) as precio_destino,cantidad from liquidaciones where bodega='$bodega' and fecha='$fecha'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUNA LIQUIDACION</h3>";
	}else
	{
		echo "<table border='1' style='width:98%; margin-left:1%; border-collapse:collapse;' cellpadding='8'>";
		$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bodegas="".$fcb['BODEGA'].": ".$fcb['NOMBRE']."";
		echo "<tr>
			<td colspan='8'>DETALLE LIQUIDACIONES DE: $bodegas FECHA: $fecha</td>
		</tr>";

		echo "<tr style='text-align:center;'>
			<td colspan='3' >ARTICULO ORIGEN</td>
			<td colspan='3' >ARTICULO DESTINO</td>
			<td>CANTIDAD</td>
			<td>TOTAL</td>
		</tr>";
		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>PRECIO</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>PRECIO</td>
			<td></td>
			<td></td>
		</tr>";
		$to=0;
		$td=0;
		$t=0;
		$total=0;
		$totalf=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$art_o=$f['art_origen'];
			$precio_o=$f['precio_origen'];
			$art_d=$f['art_destino'];
			$precio_d=$f['precio_destino'];
			$cantidad=$f['cantidad'];
			$to=$precio_o*$cantidad;
			$td=$precio_d*$cantidad;
			$total=$to -$td;
			$totalf=$totalf+$total;
			$po=explode(".", $precio_o);
			if($po[0]=='')
			{
				$precio_o="0.$po[1]";
			}
			$pd=explode(".", $precio_d);
			if($pd[0]=='')
			{
				$precio_d="0.$pd[1]";
			}
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art_o'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$desc=$fca['DESCRIPCION'];
			$ca1=$conexion1->query("select * from consny.articulo where articulo='$art_d'")or die($conexion1->error());
			$fca1=$ca1->FETCH(PDO::FETCH_ASSOC);
			$desc1=$fca1['DESCRIPCION'];
			echo "<tr>
			<td>$art_o</td>
			<td>$desc</td>
			<td>$precio_o</td>
			<td>$art_d</td>
			<td>$desc1</td>
			<td>$precio_d</td>
			<td>$cantidad</td>
			<td>$total</td>
		</tr>";

		}
		echo "<tr>
			<td colspan='7'>TOTAL LIQUIDACION</td>
			<td>$totalf</td>
		</tr>";
	}
	?>
	</div>
</div>
</body>
</html>