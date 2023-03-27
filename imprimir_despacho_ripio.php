<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function imprimir()
		{
			$("#caja").hide();
			window.print();
			$("#caja").show();
		}
	</script>
</head>
<body>
	<div id="caja">
	<img src="imprimir.png" style="float: right; margin-right: 0.5%; width: 5%; height: 5%; cursor: pointer;" onclick="imprimir()">
<?php
include("conexion.php");
echo "</div>";
$doc=$_GET['doc'];
$user=$_GET['user'];
$c1=$conexion2->query("select * from traslado_ripio where session='$doc' and usuario='$user'")or die($conexion2->error());
$fc1=$c1->FETCH(PDO::FETCH_ASSOC);

$n=$c1->rowCount();
if($n!=0)
{
	echo "<table border='1' cellpadding='10' width='98%' style='border-collapse:collapse;'>";
	echo "<tr>
		<td colspan='3'>BODEGA DESPACHO: SM00</td>
		<td rowspan='3' width='15%' style='text-align:center;'><img src='logo.png' width='70%' height='70%'></td>
	</tr>";
	echo "<tr>
		<td colspan='3'>FECHA DESPACHO: ".$fc1['fecha']."</td>
	</tr>";
	echo "<tr>
		<td colspan='3'>PAQUETE: ".$fc1['paquete']."</td>
	</tr>";
	echo "<tr>
			<td>#</td>
			<td>BARRA</td>
			<td>CLASIFICACION</td>
			<td>PESO</td>
	</tr>";
	$num=1;
	$c=$conexion2->query("select ripio.clasificacion,ripio.barra,ripio.peso from ripio inner join traslado_ripio on ripio.id=traslado_ripio.ripio where traslado_ripio.session='$doc' and traslado_ripio.usuario='$user'")or die($conexion2->error());
	$tpeso=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
			echo "<tr>
			<td>$num</td>
			<td>".$f['barra']."</td>
			<td>".$f['clasificacion']."</td>
			<td>".$f['peso']."</td>
	</tr>";
	$num++;
	$tpeso=$tpeso +$f['peso'];

	}
	echo "<tr>
			<td colspan='3'>TOTAL PESO:</td>
			<td>$tpeso</td>
	</tr></table>";
	echo "<p style='text-decoration:overline; margin-top1%;'>F. DESPACHO</p>";

}else
{
	echo "<h2>NO SE ENCONTRO NINGUN INGRESO</h2>";
}
?>

</body>
</html>