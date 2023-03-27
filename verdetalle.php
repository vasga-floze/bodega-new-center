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
	}
</script>
</head>
<body>


<?php
include("conexion.php");
?>
<img src="atras.png" width="2.5%" height="2.5%" style="cursor: pointer;" onclick="cerrar()">
<?php
$idr=$_GET['idr'];
if($idr=="")
{
	echo "<script>alert('INTENTELO NUEVAMENTE')</script>";
	echo "<script>window.close();</script>";
}else
{
	$c=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO DETALLE DEL CONTENEDOR')</script>";
		echo "<script>window.close();</script>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$conte=$f['contenedor'];
		$fecha=$f['fecha_documento'];
		echo "<table class='tabla' border='1' cellpadding='10'>";
		echo "<tr style='background-color:#F0F8FF; color:black;'>
			<td colspan='2'>CONTENEDOR: $conte</td>
			<td colspan='2'>FECHA INGRESO: $fecha</td>
		</tr>";
		echo "<tr style='background-color:#000; color:white;'>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
			<td>PESO</td>
		</tr>";
		
		$con=$conexion2->query("select SUM(cantidad) as cantidad,peso,codigo from registro where tipo='cd' and contenedor='$conte' and fecha_documento='$fecha' and estado<>'2' group by cantidad,peso,codigo
")or die($conexion2->error());
		$t=0;
		$tp=0;
		while($fcon=$con->FETCH(PDO::FETCH_ASSOC))
		{
			$cantidad=$fcon['cantidad'];
			$peso=$fcon['peso'];
			$codigo=$fcon['codigo'];
			$k=$conexion1->query("select ARTICULO,DESCRIPCION from consny.ARTICULO where ARTICULO='$codigo'")or die($conexion1->error());
			$fk=$k->FETCH(PDO::FETCH_ASSOC);
			$art=$fk['ARTICULO'];
			$de=$fk['DESCRIPCION'];
			echo "<tr>
			<td>$art</td>
			<td>$de</td>
			<td>$cantidad</td>
			<td>$peso</td>
		</tr>";
		$t=$t + $cantidad;
		$tp=$tp + $peso;
		}
		echo "<tr style='background-color:#000; color:white;'>
			<td colspan='2'>TOTAL: </td>
			<td>$t</td>
			<td>$tp</td>

		</tr>";
	}
	

}
?>
</body>
</html>