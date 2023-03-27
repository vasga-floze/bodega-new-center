<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
$usuario=$_SESSION['usuario'];
$pe=$_SESSION['pedidos'];

$c=$conexion2->query("select sessiones,usuario,sum(cantidad_tienda)as cantidad,convert(date,fecha) from pedidos where usuario='$usuario' and  estado='soli...' group by sessiones,usuario,convert(date,fecha)
")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO TIENES NINGUN PEDIDO PENDIENTE DE FINALIZAR.</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='width:98%; border-collapse:collapse;'>";
	echo "<tr>
	<td>FECHA INICIO PEDIDO</td>
	<td>CANTIDAD ARTICULOS SOLICITADOS</td>
	<td>CONTINUAR</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$fecha=$f['fecha'];
		$sessiones=$f['sessiones'];
		$cantidad=$f['cantidad'];
		$fecha=explode('.', $fecha);
		$fecha=$fecha[0];
		$usuario=$f['usuario'];
		echo "<tr>
	<td>$fecha</td>
	<td>$cantidad</td>
	<td>
	<form method='POST'>
	<input type='hidden' name='sessiones' value='$sessiones'>
	<input type='submit' value='CONTINUAR' class='btnfinal' style='padding=0.1%; margin-bottom:-1%; background-color:#629B7A;'>
	</form>
	</td>
	</tr>";
	}
}
if($_POST)
{
	extract($_REQUEST);
	$_SESSION['pedidos']=$sessiones;
	echo "<script>location.replace('pedidos.php')</script>";
}
?>
</body>
</html>