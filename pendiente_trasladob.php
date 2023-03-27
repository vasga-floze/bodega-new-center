<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			//alert();
		})
		function enviar(e)
		{
		$("#session").val(e);
		$("#form").submit();

		}
	</script>
</head>
<body>
<?php
include("conexion.php");
$usuario=strtoupper($_SESSION['usuario']);
$c=$conexion2->query("select sessiones,count(sessiones) as cantidad,usuario,origen,destino from traslado where estado=0 and usuario='$usuario' group by  sessiones,usuario,origen,destino")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO HAY TRASLADOS PENDIENTES DEL USUARIO: $usuario</h2>";
}else
{
	echo "<h2>TRASLADOS PENDIENTES DEL USUARIO: $usuario</h2>";
	echo "<table border='1' style='border-collapse:collapse; width:98%;' cellpadding='10'>";
	echo "<tr>
	<td>FECHA HORA INICIO</td>
	<td>ORIGEN</td>
	<td>DESTINO</td>
	<td>CANTIDAD</td>
	<td>CONTINUAR</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$sessiones=$f['sessiones'];
		$cc=$conexion2->query("select count(*) as cantidad,min(fecha_ingreso) as fecha_ingreso from traslado where sessiones='$sessiones' and usuario='$usuario'")or die($conexion2->error());
		$fcc=$cc->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
	<td>".$fcc['fecha_ingreso']."</td>
	<td>".$f['origen']."</td>
	<td>".$f['destino']."</td>
	<td>".$f['cantidad']."</td>
	<td><button onclick='enviar($sessiones)' class='boton4' style='padding:2.5%; background-color: blue; color: white; border-color:white; cursor:pointer;'>CONTINUAR</button></td>
	</tr>";
	}



}

if($_POST)
{
	extract($_REQUEST);
	$_SESSION['traslado']=$session;
	echo "<script>location.replace('trasladob.php')</script>";
}



?>
<form method="POST" id="form">
	<input type="hidden" name="session" id="session" readonly>
	
</form>
</body>
</html>