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
			//alert('dfgfd');
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
$idr=$_GET['id'];
$c=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
$f=$c->FETCH(PDO::FETCH_ASSOC);
$cod=$f['codigo'];
$barra=$f['barra'];
$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$art=$fca['ARTICULO'];
$de=$fca['DESCRIPCION'];

?>

<div class="detalle" style="margin-top: -5%;">
	<?php
	error_reporting(0);
	$barra1=$_GET['barra'];
	?>
	<button style="float: right; margin-right: 0.5%; cursor: pointer; margin-top: 0.5%;" onclick="cerrar()">CERRAR X</button>
	<div class="adentro" style="margin-top: 0%; margin-left: 2.6%; text-align: center;">
<?php
echo "<h2><u>DESGLOSE DE $art: $de, CODIGO DE BARRA: $barra</u></h2>";
$cd=$conexion2->query("select * from desglose where registro='$idr'")or die($conexion2->error());
$ncd=$cd->rowCount();
if($ncd==0)
{
	echo "<h3>NO SE ENCONTRO DESGLOSE</h3>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:2.5%;'>
	<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
	</tr>";
	$t=0;
	while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$fcd['articulo'];
		$cantidad=$fcd['cantidad'];
		$car=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fcar=$car->FETCH(PDO::FETCH_ASSOC);
		$art=$fcar['ARTICULO'];
		$de=$fcar['DESCRIPCION'];
		echo "<tr>
		<td>$art</td>
		<td>$de</td>
		<td>$cantidad</td>
	</tr>";
	$t=$t + $cantidad;

	}
	echo "<tr><td colspan='2'>TOTAL</td><td>$t</td></table>";
}
?>
	</div>
</div>
</body>
</html>