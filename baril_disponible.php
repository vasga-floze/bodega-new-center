<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
?>
<div id="disponibles">
	<h3>MERCADERIA EN PROCESO SIN TRABAJAR EN MESA</h3>
<?php
$c=$conexion2->query("select * from trabajo where estado='0'")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN BARIL DISPONIBLE</h3>";
}else
{
	echo "<table border='1' style='border-collapse:collapse;' width='90%'>";
	echo "<tr>
	<td>ID BARIL</td>
	<td>PRODUCTO</td>
	<td>SELECCIONADO POR</td>
	<td>FECHA SELECCION</td>
	<td>PESO</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		echo "<tr>
	<td>".$f['sessiones']."</td>
	<td>".$f['articulos']."</td>
	<td>".$f['producido']."</td>
	<td>".$f['fecha']."</td>
	<td>".$f['peso']."</td>
	</tr>";
	}

}
?>	
</div>
<div id="reporte">
	
</div>
</body>
</html>