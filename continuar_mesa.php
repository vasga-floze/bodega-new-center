<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="moment.min.js"></script>
	<script>
		$(document).ready(function(){

			$("#load").hide();
		})
		function seleccion(e)
		{	
			$("#ide").val(e);
			$("#form").submit();
		}
	</script>
</head>
<body>
<div style="position: fixed; background-color: white; width:100%; height: 100%;" id="load">
	<img src="loadf.gif" style="margin-left: 45%; margin-top:10%;" >
</div>

<form method="POST" id="form" style="display: none;">
<input type="hidden" name="id" id='ide'>
</form>
<?php
include("conexion.php");
$user=$_SESSION['usuario'];

$c=$conexion2->query("select * from mesa where estado is null and usuario='$user'")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUNA MESA PENDIENTE DE FINALIZAR DE: $user</h3>";
}else
{
	echo "<table border='1' cellspadding='8' style='border-collapse:collapse;'>";
	echo "<tr>
		<td>usuario</td>
		<td>FECHA Y HORA INICIO</td>
		<td>CANTIDAD FARDOS</td>
		<td>CONTINUAR</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$fecha=$f['fecha_ingreso'];
		$usuario=$f['usuario'];
		$cd=$conexion2->query("select count(*) as cantidad from detalle_mesa where mesa='$id'")or die($conexion2->error());
		$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
		$cantidad=$fcd['cantidad'];
		echo "<tr class='tre'>
		<td>$usuario</td>
		<td>$fecha</td>
		<td>$cantidad</td>
		<td onclick='seleccion($id)' style='background-color: skyblue; cursor:pointer;'>CONTINUAR</td>
		</tr>";
	}
}
if($_POST)
{
	extract($_REQUEST);
	$_SESSION['id']=$id;
	echo "<script>location.replace('mesa.php')</script>";
}
?>
</body>
</html>