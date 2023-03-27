<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php
include("conexion.php");
?>
<h2 style="text-decoration: underline; text-align: center; margin-top: -1.5%;">MESAS SIN FINALIZAR</h2>
<?php
$c=$conexion2->query("select * from mesa where estado is null and year(fecha)>2019 order by fecha desc")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUNA MESA PENDIENTE DE FINALIZAR</h3>";
}else
{
	echo "<table border='1' cellpadding='8' style='border-collapse: collapse; width:98%; margin-left:1.3%;'>";
	echo "<tr>
		<td>FECHA</td>
		<td>PRODUCIDO</td>
		<td>USUARIO</td>
		<td>CANTIDAD</td>
		<td>CONTINUAR</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$producido=$f['producido'];
		$fecha=$f['fecha'];
		$id=$f['id'];
		$usuario=$f['usuario'];

		$cdm=$conexion2->query("select count(*) as cantidad from detalle_mesa where mesa='$id' ")or die($conexion2->error());
		$fcdm=$cdm->FETCH(PDO::FETCH_ASSOC);
		$cantidad=$fcdm['cantidad'];
		if($cantidad>0)
		{
				echo "<tr>
			<td>$fecha</td>
			<td>$producido($id)</td>
			<td>$usuario</td>
			<td>$cantidad</td>
			<td>CONTINUAR</td>
		</tr>";
		}
		
	}

}

?>
</body>
</html>