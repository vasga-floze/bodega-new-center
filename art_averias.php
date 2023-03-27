<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function cerrar()
		{
			location.replace('averias.php?num='+$("#num").val());
		}
	</script>
</head>
<body>
<?php
echo "<div style='display:none;'>";
include("conexion.php");
echo "</div>";
$num=$_GET['num'];
echo "<input type='hidden' name='num' id='num' value='$num'>";
?>
<div class="detalle">
<a href="#" style="text-decoration: none; color: white; float: right; margin-right: 0.5%;" onclick="cerrar()">CERRAR X</a><br>
<div class="adentro" style="margin-left: 2.5%; height: 92%;">
<form method="POST">
<input type="text" name="b" class="text" style="width: 20%; margin-left: 0.5%;" placeholder="ARTICULO O DESCIPCION">
<input type="submit" name="btn" value="buscar" class="btnfinal" style="padding: 0.5%; background-color: #72AB8A;">
</form>	
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion1->query("select * from consny.articulo where articulo='$b' or descripcion like (SELECT '%'+REPLACE('$b',' ','%')+'%') and clasificacion_1='DETALLE' and activo='S'")or die($conexion1->error());
}else
{
	$c=$conexion1->query("select * from consny.articulo where clasificacion_1='DETALLE' and activo='S'")or die($conexion1->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN ARTICULO</h3>";
}else
{
	echo "<table border='1' style='border-collapse:collapse; margin-top:-5.5%; width:98%;' cellpadding='10'>";
	echo "<tr>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['ARTICULO'];
		$desc=$f['DESCRIPCION'];

		echo "<tr>
	<td><a href='averias.php?art=$art' style='text-decoration:none; color:black;'>$art</a></td>
	<td><a href='averias.php?art=$art' style='text-decoration:none; color:black;'>$desc</a></td>
	</tr>";

	}
}
?>
</div>
	
</div>
</body>
</html>