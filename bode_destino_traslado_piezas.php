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
$origen=$_GET['origen'];
$destinos=$_GET['destino'];

?>
<div class="detalle" style="margin-top: -4%;">
	<?php
		echo "<a href='traslado_piezas.php?origen=$origen&&destino=$destinos' style='text-decoration:none; color:white; float:right; margin-right:0.5%;'>Cerrar X</a><br>";
	?>
	<div class="adentro" style="margin-left: 2.5%; height: 92%;">
	<center>
		<form method="POST" >
		<input type="text" name="b" placeholder="BODEGA O NOMBRE" class="text" style="width: 30%;">
		<input type="submit" name="btn" value="BUSCAR" class="boton3">	
		</form>
	</center>
<?php
$empresa=$_SESSION['empresa_tpieza'];
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion1->query("select * from $empresa.bodega where (bodega='$b' or nombre like '%$b%')")or die($conexion1->error());
}else
{
	$c=$conexion1->query("select * from $empresa.bodega")or die($conexion1->error());
}
$n=$c->rowCount($c);
if($n==0)
{
	echo "<h3>NO SE ENCONTRO BODEGA DISPONIBLE</h3>";
}else
{
	echo "<br><table border='1' cellpadding='10' style='border-collapse:collapse; width:98%; margin-left:5%;'>";
	echo "<tr>
		<td>BODEGA</td>
		<td>NOMBRE</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$bodega=$f['BODEGA'];
		$nom=$f['NOMBRE'];
		echo "<tr>
		<td><a href='traslado_piezas.php?origen=$origen&&destino=$bodega' style='color:black; text-decoration:none;'>$bodega</a></td>
		<td><a href='traslado_piezas.php?origen=$origen&&destino=$bodega' style='color:black; text-decoration:none;'>$nom</a></td>
	</tr>";
	}
}
?>
	</div>
</div>
</body>
</html>