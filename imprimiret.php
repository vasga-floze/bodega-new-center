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
</script>


</head>
<body>
	<?php
	include("conexion.php");
	if($_SESSION['tipo']==2)
	{
		echo "<script>alert('NO DISPONIBLE')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}else if($_SESSION['tipo']==3)
	{
		echo "<script>alert('NO DISPONIBLE')</script>";
		echo "<script>location.replace('consultar.php')</script>";
	}
	?>
	<form method="POST" name="form" id="form">
	<input type="date" name="fecha" onchange="enviar()">
		
	</form>
<?php

if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select sessiones,usuario from traslado where fecha='$fecha' and estado='1' group by sessiones,usuario order by sessiones desc")or die($conexion2->error());
	$msj=$fecha;
}else
{
	$hoy=date("Y-m-d");
	$c=$conexion2->query("select sessiones,usuario from traslado where fecha='$hoy' and estado='1' group by sessiones,usuario order by sessiones desc")or die($conexion2->error());
	$msj=$hoy;
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE ENCONTRO TRASLADO DE LA FECHA: $msj</h2>";
}else
{
	echo "<h2>MOSTRANDO TRASLADOS DE LA FECHA: $msj</h2>";
	echo "<table class='tabla' border='1' cellpadding='10'>";
	echo "<tr>
		<td>ORIGEN</td>
		<td>DESTINO</td>
		<td>FECHA TRASLADO</td>
		<td>documento_inv</td>
		<td>USUARIO</td>
		<td width='5%'>IMPRIMIR</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$sessiones=$f['sessiones'];
		$u=$f['usuario'];
		$c1=$conexion2->query("select * from traslado where sessiones='$sessiones' and usuario='$u'")or die($conexion2->error());
		$f1=$c1->FETCH(PDO::FETCH_ASSOC);
		$origen=$f1['origen'];
		$destino=$f1['destino'];
		$fecha=$f1['fecha'];
		$usuario=$f1['usuario'];
		$documento=$f1['documento_inv'];
		$co=$conexion1->query("select * FROM  consny.bodega where bodega='$origen'")or die($conexion1->error());
		$fco=$co->FETCH(PDO::FETCH_ASSOC);
		$origen="$origen: ".$fco['NOMBRE'];
		$cd=$conexion1->query("select * from consny.bodega where bodega='$destino'")or die($conexion1->error());
		$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
		$destino="$destino: ".$fcd['NOMBRE'];
		echo "<tr class='tre'>
		<td>$origen</td>
		<td>$destino</td>
		<td>$fecha</td>
		<td>$documento</td>
		<td>$usuario</td>
		<td>
		<a href='imprimir_traslado.php?doc=$sessiones&&u=$u' target='_blank' style='text-decoration:none;'>
		<img src='imprimir.png' width='40%' height='5%' style='margin-left:20%;'>
		</a>
		</td>
	</tr>";
	}
	echo "</table>";
}
?>
</body>
</html>