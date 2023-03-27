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
	function enviar()
	{

		$("#form").submit();
	}
	</script>
</head>
<body>
<div style="width: 110%; height: 110%; position: fixed; float: center; margin-left: -5%; margin-top:-2%; background-color: white;" id="img">
<img src="loadf.gif" style="float: center; margin-left: 40%; margin-top: 15%; width: 20%; height: 22%;">
</div>
<?php
include("conexion.php");
?>
	<h3 style="text-align: center; text-decoration: underline;">EXISTENCIAS CODIGOS DE BARRA</h3>
<form method="POST" id="form">
	BODEGA
	<select id="bodega" name="bodega" onchange="enviar()" class="text" style="width: 30%;">
		<option></option>
		<option value="">TODAS LAS BODEGAS</option>
		<?php
		$cb=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%' order by bodega")or die($conexion1->error());
		while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
		{
			$bod=$fcb['BODEGA'];
			$nom=$fcb['NOMBRE'];
			echo "<option value='$bod'>$bod: $nom</option>";
		}
		
		?>
	</select>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	//echo "<script>alert('$bodega j')</script>";
	if($bodega=='')
	{
		$c=$conexion2->query("select codigo,count(codigo) as cantidad,bodega from registro where (fecha_desglose is null or fecha_desglose='') and  bodega!='' and activo is null and tipo!='C1' and bodega not like '0%' group by codigo,bodega")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select codigo,count(codigo) as cantidad,bodega from registro where (fecha_desglose is null or fecha_desglose='') and bodega='$bodega' and activo is null and tipo!='c1' group by codigo,bodega")or die($conexion2->error());
	
	}

	$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO REGISTRO</h3>";
}else
{
	echo "<br><a href='expor_existencias_tiendas.php?bodega=$bodega' target='_blank' style='float:right; margin-right:2%;'>exportar a Excel</a>";
	echo "<table border='1' style='border-collapse:collapse; width:98%;' cellpadding='7'>";
	echo "<tr>
		<td>BODEGA</td>
		<td>CLASIFICACION</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$cod=$f['codigo'];
		$cantidad=$f['cantidad'];
		$bodega=$f['bodega'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$art=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];
		$clasi=$fca['CLASIFICACION_2'];
		echo "<tr>
		<td>$bodega</td>
		<td>$clasi</td>
		<td>$art</td>
		<td>$desc</td>
		<td>$cantidad</td>
	</tr>";
	}
}
}
?>
</body>
</html>