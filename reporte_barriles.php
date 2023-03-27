<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#caja").hide();
		})
		function prueba(e)
		{
			alert('gfbt')
		}
	</script>
</head>
<body>
<div id="caja" style="width: 100%; height: 100%; position: fixed; background-color: white; opacity: 0.6">
	<center>
	<img src="loadf.gif" style="margin-top: 15%;">
</center>
	
</div>
<?php
include("conexion.php");
?>
<form method="POST">
<input type="date" name="desde" class="text" style="width: 25%;">	
<input type="date" name="hasta" class="text" style="width: 25%;">
<input type="submit" name="btn" class="boton2" value="GENERAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select fecha from trabajo where fecha between '$desde' and '$hasta' group by fecha order by fecha")or die($conexion2->error());
	$n=$c->rowCount();

	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN RESULTADO</h3>";
	}else
	{
		$car=$conexion2->query("select articulos from trabajo where fecha between '$desde' and '$hasta' group by articulos")or die($conexion2->error());
		$numero=0;
		$text='';
		while($fcar=$car->FETCH(PDO::FETCH_ASSOC))
		{
			$text.="<td>".$fcar['articulos']."</td>";
			$fila[$numero]=$fcar['articulos'];
			$numero++;
		}
		echo "<a href='export_reporte_barriles.php?desde=$desde&&hasta=$hasta' target='_blank'>Exportar Excel</a>";
		echo "<table border='1' cellpadding='10' style='border-collapse:collapse;' width='200%'>";
		echo "<tr>
		<td>FECHA</td>
		<td colspan='$numero' style='text-align:center;'>PRODUCTOS</td>
		<td>TOTAL</td>
		</tr>";
		echo "<tr><td>--</td>$text</tr>";
		$totalf=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$fecha=$f['fecha'];
			$num=0;
			$total=0;
			echo "<tr><td>$fecha ->$numero</td>";
			while($num<$numero)
			{
				$articulos=$fila[$num];
				$qar=$conexion2->query("select isnull(SUM(peso),0.00) as peso from trabajo where fecha='$fecha' and articulos='$fila[$num]'
")or die($conexion2->error());
				$fqar=$qar->FETCH(PDO::FETCH_ASSOC);
				$peso=$fqar['peso'];
				$total=$total+$peso;
				$totalf=$totalf+$total;
				$texto=$fila[$num];
				echo "<td onclick='prueba($texto)'>$peso</td>";
				$num++;



			}
			echo "<td>$total</td></tr>";
		}
		echo "<tr><td>TOTAL</td>";
		$num=0;
		$t=0;
		while($num<$numero)
		{
			$ct=$conexion2->query("select isnull(SUM(peso),0) as peso from trabajo where fecha between '$desde' and '$hasta' and articulos='$fila[$num]'
")or die($conexion2->error());
			
			$fct=$ct->FETCH(PDO::FETCH_ASSOC);
			$totales=$fct['peso'];
			echo "<td>$totales</td>";
			$t=$t+$totales;
			$num++;
		}
		echo "<td>$t</td></tr>";

		

	}
}
?>
</body>
</html>