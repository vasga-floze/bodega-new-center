<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
$usuario=$_SESSION['usuario'];
$bodega=$_SESSION['bodega'];
$c=$conexion2->query("select sessiones,autoriza,fecha,count(*) as cantidad from liquidaciones where usuario='$usuario' and estado='0' and documento_inv_consumo is null and documento_inv_ing is null group by sessiones,fecha,autoriza")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo ":) NO TIENES LIQUIDACIONES PENDIENTES DE FINALIZAR";
}else
{
	echo "<table border='1' style='border-collapse:collapse;' cellpadding='10' width='100%'>";
	echo "<tr>
		<td>AUTORIZADA POR</td>
		<td>FECHA</td>
		<td># LIQUIDACIONES</td>
		<td>OPCION</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$autoriza=$f['autoriza'];
		$fecha=$f['fecha'];
		$session=$f['sessiones'];
		$cantidad=$f['cantidad'];
		echo "<tr>
		<td>$autoriza</td>
		<td>$fecha</td>
		<td>$cantidad</td>
		<td>
			<form method='POST'>
			<input type='hidden' name='session' value='$session'>
			<input type='submit' value='CONTINUAR' class='btnfinal' style='background-color:#84C889; padding:2.5%; margin-bottom:-0.5%; color:black; border-color:black;'>

		</td>
	</tr>";

	}
}

if($_POST)
{
	extract($_REQUEST);
	$_SESSION['liquidacion']=$session;
	echo "<script>location.replace('liquidaciones.php')</script>";

}
?>
</body>
</html>