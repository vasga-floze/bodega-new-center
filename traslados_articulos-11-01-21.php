<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".detalle").hide();
		});
	</script>
		<style>
		.preloader {
  width: 70px;
  height: 70px;
  border: 10px solid #eee;
  border-top: 10px solid skyblue;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 3s;
  animation-iteration-count: infinite;

}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
	</style>
</head>
<body>
	<center>
	<div class="detalle" style="background-color: rgb(211,211,211,0.5); width: 110%; height: 110%; margin-left: -0.5%;">
	

<div class="preloader" style="margin-top: 15%;">
</div>
</div>
</center>
<?php

include("conexion.php");

?>
<form method="POST">
DESDE: <input type="date" name="desde" class="text" style="width: 12%;" required>
HASTA: <input type="date" name="hasta" class="text" style="width: 12%;" required>
<select class="text" style="width: 12%;" name="clasificacion">
	<option value="">CLASIFICACION</option>
<?php
$q=$conexion1->query("select clasificacion_2 from consny.articulo where clasificacion_2 is not null group by clasificacion_2")or die($conexion1->error());
$nq=$q->rowCount();
while($fq=$q->FETCH(PDO::FETCH_ASSOC))
{
	echo "<option>".$fq['clasificacion_2']."</option>";
}
?>
</select>
<select class="text" style="width: 12%;" name="bodega" required>
	<option value="">BODEGA ORIGEN</option>
<?php
$qe=$conexion1->query("select bodega from consny.bodega where bodega like 'SM%' and nombre not like '%(N)%'")or die($conexion1->error());
while($fqe=$qe->FETCH(PDO::FETCH_ASSOC))
{
	echo "<option>".$fqe['bodega']."</option>";
}
?>
	
</select>
<input type="submit" name="btn" value="BUSCAR" class="boton2">


</form>
<?php
if($_POST)
{
	echo "<hr>";
	extract($_REQUEST);
	$c=$conexion2->query("select articulo,count(articulo) as cantidad,fecha from traslado where fecha between '$desde' and '$hasta' and origen='$bodega' group by articulo,fecha ")or die($conexion2->error());

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h2>NO SE ENCONTRO NINGUN RESULTADO</h2>";
	}else
	{
		echo "<table border='1' class='tabla' cellpadding='10' >";
		echo "
		<tr>
		<td colspan='5'><a href='expor_tras_art.php?d=$desde&&h=$hasta&&clasi=$clasificacion&&bodega=$bodega'>EXPORTAR A EXCEL</a>
		<a href='imprimir_tras_art.php?d=$desde&&h=$hasta&&clasi=$clasificacion&&bodega=$bodega' style='float:right;' target='_blank'>IMPRIMIR</a>
		</td>
		</tr>
		<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CLASIFICACION</td>
		<td>CANTIDAD</td>
		<td>FECHA</td>
		
		</tr>";
		$t=0;
		$n=1;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$f['articulo'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		if($clasificacion=='')
		{
			echo "<tr>
		
		<td>".$fca['ARTICULO']."</td>
		<td>".$fca['DESCRIPCION']."</td>
		<td>".$fca['CLASIFICACION_2']."</td>
		<td>".$f['cantidad']."</td>
		<td>".$f['fecha']."</td>
		
		</tr>";
		$n++;
		$t=$t + $f['cantidad'];
		}else if($clasificacion ==$fca['CLASIFICACION_2'])
		{
			echo "<tr>
		
		<td>".$fca['ARTICULO']."</td>
		<td>".$fca['DESCRIPCION']."</td>
		<td>".$fca['CLASIFICACION_2']."</td>
		<td>".$f['cantidad']."</td>
		
		</tr>";
		$n++;
		$t=$t + $f['cantidad'];
			
		}else
		{

		}

		
		}
		echo "<tr>
		<td colspan='3'>TOTAL</td>
		<td>$t</td>
		<td></td>
		</tr></table>";
	}
}
?>
</body>
</html>