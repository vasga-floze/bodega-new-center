<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
	<div style="display: none;">
<?php
	include("conexion.php");
?>
</div>
<div class="detalle">
	<a href="inventario.php">
	<button style="float: right; margin-right: 1%; cursor: pointer;">CERRAR X</button>
</a>
	<div class="adentro" style="margin-left: 1.4%; padding-left: 1.8%;">
<form method="POST">
	<input type="text" name="b" placeholder="NOMBRE BODEGA" class="text" style="width: 30%;">
	<input type="submit" name="btn" value="BUSCAR" class="boton3">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion1->query("select * from consny.bodega where bodega='$b' or nombre like '%$b%' and nombre not like '%(N)%'")or die($conexion1->error());
}else
{
	$c=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%'")or die($conexion1->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE ENCONTRO NINGUNA BODEGA</h2>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10'>";
	echo "<tr>
		<td>BODEGA</td>
		<td>NOMBRE</td>

	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$f['BODEGA'];
		$nom=$f['NOMBRE'];
	echo "<tr>
		<td><a href='inventario.php?bod=$bod' style='text-decoration:none;'>$bod</a></td>
		<td><a href='inventario.php?bod=$bod' style='text-decoration:none;'>$nom</a></td>

	</tr>";
	}
}
?>
</div></div>
</body>
</html>