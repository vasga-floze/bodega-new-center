<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
$fecha=date("Y-m-d");
//echo $fecha;
$c=$conexion2->query("select * from ripio where convert(date,fecha_hora_creacion)='$fecha'")or die($conexion2->error());

$n=$c->rowCount();
if($n==0)
{
	echo "<H3>NO SE HA INGRESADO NINGUN RIPIO EL DIA DE HOY</H3>";
}else
{
	echo "<table border='1' style='border-collapse:collapse; width:80%;'>";
	echo "<tr>
		<td>USUARIO</td>
		<td>CLASIFICACION</td>
		<td>PESO</td>
		<td>CODIGO BARRA</td>
		<td>IMPRIMIR</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$barra=$f['barra'];
		echo "<tr>
		<td>".$f['usuario_creacion']."</td>
		<td>".$f['clasificacion']."</td>
		<td>".$f['peso']."</td>
		<td>".$f['barra']."</td>
		<td><a href='imprimir_ripio.php?b=$barra'><img src='imprimir.png' width='5%' height='5%' style='cursor:pointer;'></a></td>
	</tr>";
	}
}


?>
</body>
</html>