<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#loader").hide();
		});
	</script>
</head>
<body>
<div style="background-color:white; width: 105%; height:105%; margin-top: -3%; margin-left: -3%; position: fixed;" id="loader">
	<center>
	<img src="loadf.gif" style="margin-top: 15%;"></center>
</div>
<?php
include("conexion.php");
?>
<form method="POST" autocomplete="off" style="margin-bottom: -5%;">
 <input type="text" name="desde" placeholder="FECHA INICIAL (mes-año)" list="meses" class="text" style="width: 25%;">
 <input type="text" name="hasta" placeholder="FECHA FINAL (mes-año)" list="meses" class="text" style="width: 25%;">
 <input type="submit" name="btn" class="btnfinal" style="padding: 0.5%; background-color: #D7E9DC; color: black;" value="GENERAR">
 <datalist id="meses" class="text">
 <?php
 	$c=$conexion2->query("select  convert(nvarchar(2),MONTH(fecha_documento))+'-'+convert(nvarchar(4), YEAR(FECHA_DOCUMENTO)) as op,
MONTH(fecha_documento), year(fecha_documento)
from registro
where fecha_documento>'2019-09-30'
group by MONTH(fecha_documento), YEAR(FECHA_DOCUMENTO)
order by 3,2
")or die($conexion2->error());
 	while($f=$c->FETCH(PDO::FETCH_ASSOC))
 	{
 		$op=$f['op'];
 		echo "<option>$op</option>";
 	}
 ?>
 </datalist>
</form>
<?php
if($_POST)
{
	
		extract($_REQUEST);
		//echo "<script>alert('$desde - $hasta')</script>";
		$c=$conexion2->query("select producido from registro where (concat(month(fecha_documento),'-',year(fecha_documento))='$desde' or concat(month(fecha_documento),'-',year(fecha_documento))='$hasta') and tipo='p' group by producido order by producido
")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<H3>NO SE ENCONTRO INFORMACION DE LOS MESES SELECCIONADOS</H3>";
		}else
		{
			echo "<a href='export_reporte_personal_producido.php?desde=$desde&&hasta=$hasta' style='margin-left:1.5%;' target='_blank'>Exportar a Excel</a>";
			echo "<table border='1' style='border-collapse:collapse; width:98%; margin-left:1.2%;'>";
			echo "<tr style='text-align:center;'>
				<td rowspan='3'>PRODUCIDO POR</td>
				<td colspan='4'>MES</td>
				<td rowspan='2' colspan='2'>TOTAL</td>
			</tr>";
			echo "<tr>
				<td colspan='2'>$desde</td>
				<td colspan='2'>$hasta</td>
			</tr>";
			echo "<tr>
				<td>CANTIDAD</td>
				<td>PESO</td>
				<td>CANTIDAD</td>
				<td>PESO</td>
				<td>CANTIDAD</td>
				<td>PESO</td>
			</tr>";
			$tf_cant=0;
			$tf_peso=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$producido=$f['producido'];
			$q=$conexion2->query("select count(producido) as cantidad,sum(lbs) as peso from registro where concat(month(fecha_documento),'-',year(fecha_documento))='$desde' and producido='$producido' and tipo='p' group by producido
")or die($conexion2->error());
			$fq=$q->FETCH(PDO::FETCH_ASSOC);
			$cant=$fq['cantidad'];
			$peso=$fq['peso'];
			if($cant=='')
			{
				$cant=0;
			}
			if($peso=='')
			{
				$peso=0;
			}

			//--
			$q1=$conexion2->query("select count(producido) as cantidad,sum(lbs) as peso from registro where concat(month(fecha_documento),'-',year(fecha_documento))='$hasta' and producido='$producido' and tipo='p' group by producido
")or die($conexion2->error());
			$fq1=$q1->FETCH(PDO::FETCH_ASSOC);
			$cant1=$fq1['cantidad'];
			$peso1=$fq1['peso'];
			if($cant1=='')
			{
				$cant1=0;
			}
			if($peso1=='')
			{
				$peso1=0;
			}
			$total_cantidad=$cant+$cant1;
			$total_peso=$peso1+$peso;
			//--
			echo "<tr>
			<td>$producido</td>
			<td>$cant</td>
			<td>$peso</td>
			<td>$cant1</td>
			<td>$peso1</td>
			<td>$total_cantidad</td>
			<td>$total_peso</td>";
			$tf_cant=$tf_cant+$total_cantidad;
			$tf_peso=$tf_peso+$total_peso;
			///no cuadra<------------------
			$qf=$conexion2->query("select count(*) as cantidad,sum(lbs) as peso from registro where concat(month(fecha_documento),'-',year(fecha_documento))='$desde' and tipo='p'")or die($conexion2->error());

			$qf1=$conexion2->query("select count(*) as cantidad,sum(lbs) as peso from registro where concat(month(fecha_documento),'-',year(fecha_documento))='$hasta' and tipo='p'")or die($conexion2->error());
			$fqf=$qf->FETCH(PDO::FETCH_ASSOC);
			$fqf1=$qf1->FETCH(PDO::FETCH_ASSOC);


		}
		echo "<tr>
		<td>TOTAL</td>
		<td>".$fqf['cantidad']."</td>
		<td>".$fqf['peso']."</td>
		<td>".$fqf1['cantidad']."</td>
		<td>".$fqf1['peso']."</td>
		<td>$tf_cant</td><td>$tf_peso</td>
		</tr>";



		}
	
}
?>
</body>
</html>