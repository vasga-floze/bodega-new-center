<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function enviar()
		{
			$("#form").submit();
		}
		function pendiente()
		{
			location.replace('trabajos_pendiente.php');
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST" name="form" id="form">
<input type="date" name="fecha" onchange="enviar()" class="text" style="width: 20%;">
</form>


<button class="btnfinal" style="margin-bottom: 0.3%; padding: 0.5%; float: right; background-color: green; margin-right: 0.5%;" onclick="pendiente()">PENDIENTES</button>
<?php
if($_POST)
{
	echo "<hr>";
	extract($_REQUEST);
	$c=$conexion2->query("select * from trabajo where fecha='$fecha' and articulos is not null")or die($conexion2->error());
}else
{
	$fecha=date("Y-m-d");
	$c=$conexion2->query("select * from trabajo where fecha='$fecha' and articulos is not null")or die($conexion2->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE ENCONTRO INGRESO EN LA FECHA: $fecha</h2>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10'>";
	echo "<tr>
	<td colspan='9'><a href='expor_trabajo.php?f=$fecha'>EXPORTAR A EXCEL</a></td>
	</tr>";
	echo "<tr>
		<td>FECHA</td>
		<td>PRODUCIDO</td>
		<td>MESA</td>
		<td>DEPOSITO</td>
		<td>ARTICULO</td>
		<td>PESO</td>
		<td>CANTIDAD</td>
		<td>TOTAL</td>
		<td>OBSERVACION</td>
	</tr>";
	$tp=0;
	$tc=0;
	$t=0;
	$m=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$tp=$tp + $f['peso'];
		$m = $f['peso'] * $f['cantidad'];
		$t=$t + $m;
		$tc=$tc + $f['cantidad'];
		echo "<tr>
		<td>".$f['fecha']."</td>
		<td>".$f['producido']."</td>
		<td>".$f['mesa']."</td>
		<td>".$f['deposito']."</td>
		<td>".$f['articulos']."</td>
		<td>".$f['peso']."</td>
		<td>".$f['cantidad']."</td>
		<td>$m</td>
		<td>".$f['observacion']."</td>
	</tr>";
	}
	echo "<tr>
	<td colspan='5'>TOTAL</td>
	<td>$tp</td>
	<td>$tc</td>
	<td>$t</td>
	<td> </td>

	</tr></table>";
}

?>

</body>
</html>