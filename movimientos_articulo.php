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
?>
<form method="POST" autocomplete="off" style="margin-bottom: -5%;">
<input type="text" name="art" placeholder="ARTICULO" class="text" style="width: 20%; " list="listas">
<datalist id="listas">
<?php
$car=$conexion1->query("select * from consny.articulo where descripcion not like '%NO USAR%' and clasificacion_1!='detalle'")or die($conexion1->error());
while($fcar=$car->FETCH(PDO::FETCH_ASSOC))
{
	$articulo=$fcar['ARTICULO'];
	$nom=$fcar['DESCRIPCION'];
	echo "<option>$articulo: $nom </option>";
}
?>
</datalist>
<input type="date" name="desde" class="text" style="width: 20%;">
<input type="date" name="hasta" class="text" style="width: 20%;">
<input type="submit" name="btn" value="BUSCAR" class="btnfinal" style="padding: 0.5%; background-color: #a6d2c7; color: #000;">
</form>

<?php
if($_POST)
{
	extract($_REQUEST);
	$e_art=explode(":",$art);
	if($e_art[0]!='')
	{
		$art=$e_art[0];
	}
	if($desde!='' and $hasta!='' and $art!='')
	{
		$c=$conexion2->query("select articulo,count(articulo) as cantidad,destino from traslado where fecha between '$desde' and '$hasta' and articulo='$art' and origen in ('SM00','CA00') group by articulo,destino")or die($conexion2->error());
	}else if($art!='' and $desde=='' and $hasta=='')
	{
		$c=$conexion2->query("select articulo,count(articulo) as cantidad,destino from traslado where articulo='$art' and origen in('SM00','CA00') group by articulo,destino")or die($conexion2->error());
	}else if($art=='' and $desde!='' and $hasta!='')
	{
		$c=$conexion2->query("select articulo,count(articulo) as cantidad,destino from traslado where fecha between '$desde' and '$hasta' and origen in('SM00','CA00') group by articulo,destino order by destino,articulo")or die($conexion2->error());
	}
	
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<hr>NO SE ENCONTRO NINGUN REGISTRO</hr>";
	}else
	{
		echo "<table border='1' style='border-collapse:collapse; width: 98%;' cellpadding='5'>";
		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
			<td>BODEGA DESTINO</td>
		</tr>";
		$total=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$f['articulo'];
			$cant=$f['cantidad'];
			$destino=$f['destino'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$desc=$fca['DESCRIPCION'];

			$cb=$conexion1->query("select * from consny.bodega where bodega='$destino'")or die($conexion1->error());

			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bode=$fcb['BODEGA'];
			$nom=$fcb['NOMBRE'];
			echo "<tr>
			<td>$art</td>
			<td>$desc</td>
			<td>$cant</td>
			<td>$bode: $nom</td>
			</tr>";
			$total=$total+$cant;
		}
		echo "<tr>
		<td colspan='2'>TOTAL</td>
		<td>$total</td>
		<td></td>
		</tr>";
	}
}
?>
</body>
</html>