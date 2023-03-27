<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".detalle").hide();
		})
		function validar()
		{
			$("#hasta").attr('min',$("#desde").val());
		}
		
	</script>
</head>
<body>
<?php
include("conexion.php");
?>
<div class="detalle" style="background-color: white; margin-top: -5%; width: 105%; height: 105%; margin-left: -1%;">
	<img src="loadf.gif" style="margin-left: 40%; margin-top: 12%;">
</div>
<h3 style="text-align: center; margin-top: -5%;">REPORTE PERSONAL PRODUCCION(DIARIO)</h3>
<form method="POST" autocomplete="off">
<input type="text" name="empleado" placeholder="NOMBRE EMPLEADO" class="text" list="empleados" style="width: 35%;">
<datalist id="empleados">
<?php
$c=$conexion1->query("select * from produccion_accpersonal where activo='1' and produce='1' order by nombre")or die($conexion1->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$nom=$f['NOMBRE'];
	echo "<option>$nom</option>";
}
echo "<option>BODEGA SAN MIGUEL</option>";
?>	
</datalist>
<input type="date" name="desde" id="desde" class="text" style="width: 15%;" required onchange="validar()">
<input type="date" name="hasta" id="hasta" class="text" style="width: 15%;" required>
<input type="submit" name="btn" class="btnfinal" style="padding: 0.5%; background-color: #D7E9DC; color: black;" value="GENERAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($empleado!='')
	{
		$c=$conexion2->query("select producido from registro where producido like '%$empleado%' and fecha_documento between '$desde' and '$hasta' and tipo='p' group by producido")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select producido from registro where  fecha_documento between '$desde' and '$hasta' and tipo='p' group by producido")or die($conexion2->error());
	}

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE OBTUVO NINGUN RESULTADO DE LA BUSQUEDA</h3>";
	}else
	{
		$cdias=$conexion1->query("select datediff(day,'$desde','$hasta')+1 as dias
")or die($conexion1->error());
		$fcdias=$cdias->FETCH(PDO::FETCH_ASSOC);
		$ndias=$fcdias['dias'];
		$ndias=$ndias*2;
		echo "<a href='export_reporte_personal_producido_diario.php?desde=$desde&&hasta=$hasta&&empleado=$empleado' target='_blank'>Exportar a Excel</a>";
		echo "<table border='1' style='border-collapse:collapse; width:210%;'>";
		echo "<tr style='text-align:center;'>
			<td rowspan='3' width='30%'>PRODUCIDO POR</td>
			<td  colspan='$ndias'>FECHA</td>
			<td rowspan='2'colspan='2' width='10%'>TOTAL</td>
		</tr>";

		$desde1=$desde;
		echo "<tr>";
		$nu=1;
		while($desde1<=$hasta)
		{
			echo "<td colspan='2' width='10%'>$desde1</td>";
			$query=$conexion1->query("declare @fecha date='$desde1';select dateadd(day,1,@fecha) as fecha")or die($conexion1->error());
			$fquery=$query->FETCH(PDO::FETCH_ASSOC);
			$desde1=$fquery['fecha'];
			$nu++;

			
		}
		echo "</tr>";
		$k=1;
		echo "<tr>";
		while($k<=$nu)
		{
			echo "<td width='5%'>CANTIDAD</td><td width='5%'>PESO</td>";
			$k++;
		}
		echo "</tr>";
		
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
		$pro=$f['producido'];
		echo "<tr><td>$pro</td>";
		$tpeso=0; $tcant=0;
		$desde2=$desde;
		while($desde2<=$hasta)
		{
			$con=$conexion2->query("select producido,count(producido) as cantidad,sum(lbs) as peso from registro where producido='$pro' and tipo='p' and fecha_documento='$desde2' group by producido")or die($conexion2->error());
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$cant=$fcon['cantidad'];
			$peso=$fcon['peso'];
			$tpeso=$tpeso + $peso;
			$tcant=$tcant + $cant;
			if($cant=='')
			{
				$cant=0;
			}
			if($peso=='')
			{
				$peso=0;
			}
			$cf=$conexion1->query("declare @fechas date='$desde2'; select dateadd(day,1,@fechas) as desde")or die($conexion1->error());
			$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
			$desde2=$fcf['desde'];
			echo "<td>$cant</td><td>$peso</td>";
		}
		if($tcant=='')
		{
			$tcant=0;
		}
		if($tpeso=='')
		{
			$tpeso=0;
		}
		echo "<td>$tcant</td><td>$tpeso</td></tr>";
	}

	echo "<tr><td>TOTAL</td>";
	$desde3=$desde;
	$cantf=0;
	$pesof=0;
	while($desde3<=$hasta)
	{
		$cfinal=$conexion2->query("select count(*) as cantidad,sum(lbs) as peso from registro where fecha_documento='$desde3' and producido like '%$empleado%' and tipo='p'")or die($conexion2->error());
		$fcfinal=$cfinal->FETCH(PDO::FETCH_ASSOC);
		$cantidad=$fcfinal['cantidad'];
		$peso=$fcfinal['peso'];
		$cantf=$cantf + $cantidad;
		$pesof=$pesof + $peso;
		if($cantidad=='')
		{
			$cantidad=0;
		}
		if($peso=='')
		{
			$peso=0;
		}
		echo "<td>$cantidad</td><td>$peso</td>";

		$conf=$conexion2->query("declare @fech date='$desde3'; select dateadd(day,1,@fech) as dia")or die($conexion2->error());
		$fconf=$conf->FETCH(PDO::FETCH_ASSOC);
		$desde3=$fconf['dia'];
	}
	echo "<td>$cantf</td><td>$pesof</td></tr>";


}
}
?>
</body>
</html>