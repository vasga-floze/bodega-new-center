<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#img").show();
			$("#img").hide();
		})
	</script>
</head>
<body>
<div style="width: 110%; height: 110%; position: fixed; float: center; margin-left: -5%; margin-top:-2%; background-color: white;" id="img">
<img src="loadf.gif" style="float: center; margin-left: 40%; margin-top: 15%; width: 20%; height: 22%;">
</div>
<?php
include("conexion.php");
if($_SESSION['tipo']==1 or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['usuario']=='staana3')
{

}else
{
	echo "<script>location.replace('conexiones.php')</script>";
}
?>
<h3 style="text-align:center; text-decoration: underline;">REPORTE PRODUCCION</h3>

	<form method="POST">
	<label>DESDE: <input type="date" name="desde" class="text" style="padding: 0.5%; width: 20%;"></label>
	<label>HASTA: <input type="date" name="hasta" class="text" style="padding: 0.5%; width: 20%;"></label>
	<input type="submit" name="btn" value="GENERAR" class="btnfinal" style="padding: 0.5%; background-color: #B9D1C8; color: #000;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select codigo from registro where fecha_documento between '$desde' and '$hasta' and tipo='p' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null  group by codigo")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUNA PRODUCCION</h3>";
	}else
	{
		//fechas
		$q=$conexion2->query("declare @desde date='$desde';
declare @hasta date='$hasta';

select datediff(day,@desde,@hasta)+1 as dias")or die($conexion2->error());
		$fq=$q->FETCH(PDO::FETCH_ASSOC);
		$dias=$fq['dias'] * 2;
		//fin fechas
		echo "<a href='export_reporte_produccion.php?desde=$desde&&hasta=$hasta' target='_blank'>Exportar a Excel</a>";
		echo "<table border='1' style='border-collapse:collapse; margin-top:0%; width:110%;'>";
		echo "<tr style='text-align:center;'>
		<td rowspan='3'>ARTICULO</td>
		<td rowspan='3' width='30%;'>DESCRIPCION</td>
		<td colspan='$dias'>FECHA</td>
		<td rowspan='2' colspan='2'>TOTAL GENERAL</td>
		</tr>";

		echo "<tr  style='text-align:center;'>";
		$desde1=$desde;
		$num=0;
		while($desde1<=$hasta)
		{
			$num++;
			$fechas=$desde1;
			$qf=$conexion2->query("declare @fecha date ='$fechas';
select dateadd(day,1,@fecha) as fecha")or die($conexion2->error());
			$fqf=$qf->FETCH(PDO::FETCH_ASSOC);
			$desde1=$fqf['fecha'];
			echo "<td colspan='2'>$fechas</td>";
		}

		echo "</tr>";
		while($num>0)
		{
			echo "<td>CANTIDAD</td><td>PESO</td>";
			$num=$num-1;
		}
		echo "<td>CANTIDAD</td><td>PESO</td>";
		
		
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['codigo'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->erro());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$art=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];
		echo "<tr>
			<td>$art</td>
			<td>$desc</td>";
			$fechas2=$desde;
			$tcantidad=0;
			$tpeso=0;
		while($fechas2<=$hasta)
		{
			$fecha=$fechas2;
			$cr=$conexion2->query("select codigo,count(codigo) as cantidad,sum(convert(decimal(10,2),isnull(lbs,0))) as peso from registro where fecha_documento='$fechas2' and codigo='$art' and codigo!='' and tipo='p' and observacion!='cancelado sys' and fecha_eliminacion is null  group by codigo")or die($conexion2->error());
			$qf=$conexion2->query("declare @fecha date='$fecha'; select dateadd(day,1,@fecha) as fecha")or die($conexion2->error());
			$fqf=$qf->FETCH(PDO::FETCH_ASSOC);
			$fechas2=$fqf['fecha'];
			
			$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
			$cantidad=$fcr['cantidad'];
			$peso=$fcr['peso'];
			if($cantidad=='')
			{
				$cantidad="0";
			}
			if($peso=='')
			{
				$peso="0";
			}
			$tcantidad=$tcantidad + $cantidad;
			$tpeso=$tpeso + $peso;
			$epeso=explode(".", $peso);
			if($epeso[0]=='')
			{
				if($epeso[1]!='')
				{
					$peso="0.00";
				}else
				{
					$peso="0.$epeso[1]";
				}
			}
			echo "<td>$cantidad</td><td>$peso</td>";
		}
		echo "<td>$tcantidad</td><td>$tpeso</td></tr>";
	}

	$desde3=$desde;
	echo "<tr> <td colspan='2'>TOTAL</td>";
	$totalcantidadf=0;
	$totalpesof=0;
	while($desde3<=$hasta)
	{
		$fecha=$desde3;
		$cf=$conexion2->query("select count(*) as cantidad,isnull(sum(lbs),0) as peso from registro where fecha_documento='$desde3' and tipo='p' and observacion!='cancelado sys' and fecha_eliminacion is null")or die($conexion2->error());
		$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
		$cantidadf=$fcf['cantidad'];
		$pesof=$fcf['peso'];
		$totalcantidadf=$totalcantidadf + $cantidadf;
		$totalpesof=$totalpesof + $pesof;
		echo "<td>$cantidadf</td><td>$pesof</td>";

		$qf=$conexion2->query("declare @fecha date='$desde3'; select dateadd(day,1,@fecha) as fecha")or die($conexion2->error());
		$fqf=$qf->FETCH(PDO::FETCH_ASSOC);
		$desde3=$fqf['fecha'];

	}
	echo "
	<td>$totalcantidadf</td><td>$totalpesof</td></tr>";

	
	}
	echo "</table>";
}
?>
</body>
</html>