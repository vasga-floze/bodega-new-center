<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="moment.min.js"></script>
	<script>
		$(document).ready(function(){
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
?>
<h3 style="text-align: center; text-decoration: underline;">REPORTE AVERIAS/MERCADERIA NO VENDIBLE</h3>
<form method="POST" style="text-align: center; margin-bottom: -4%;">
<select class="text" name="bodegas" style="width: 14%;">
	<option value="">BODEGA</option>
<?php
if($_SESSION['tipo']==1 or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='harias' or$_SESSION['usuario']=='HARIAS' or $_SESSION['tipo']==3 or $_SESSION['tipo']==4)
{
	$cb=$conexion1->query("select * from consny.bodega where nombre not like 'SM%' and nombre not like '%(N)%' order by bodega")or die($conexion1->error());
	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$fcb['BODEGA'];
		$nom=$fcb['NOMBRE'];

		echo "<option value='$bod'>$bod: $nom</option>";
	}
}else
	{
		$bodegausu=$_SESSION['bodega'];

		$cb=$conexion1->query("select * from consny.bodega where bodega='$bodegausu'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);

		$bod=$fcb['BODEGA'];
		$nom=$fcb['NOMBRE'];
		echo "<option value='$bod'>$bod: $nom</option>";
		

	}
?>

</select>
<label>DESDE: <input type="date" name="desde" class="text" style="width: 10%;" required></label>
<label>HASTA: <input type="date" name="hasta" class="text" style="width: 10%;" required></label>

<select class="text" name="transaccion" style="width: 10%;">
	<option value="">TRANSACCION</option>
	<option>AVERIA</option>
	<option>MERCADERIA NO VENDIBLE</option>
</select>
<input type="submit" name="btn" value="GENERAR" class="btnfinal" style="padding: 0.5%; ; background-color: #D7E9DC; color: black; border-color: black;">
</form>

<?php
if($_POST)
{
	extract($_REQUEST);
	//echo "<script>alert('$bodegas | $desde | $hasta | $transaccion')</script>";
	if($transaccion=='')
	{
		if($bodegas!='')
		{
			$c=$conexion2->query("select * from averias where bodega='$bodegas' and fecha between '$desde' and '$hasta' order by bodega,fecha,tipo")or die($conexion2->error());
		}else
		{
			$c=$conexion2->query("select * from averias where fecha between '$desde' and '$hasta' order by bodega,fecha,tipo")or die($conexion2->error());
		}
		
	}else
	{
		if($bodegas!='')
		{
			$c=$conexion2->query("select * from averias where bodega='$bodegas' and fecha between '$desde' and '$hasta' and  tipo='$transaccion' order by bodega,fecha,tipo")or die($conexion2->error());
		}else
		{
			$c=$conexion2->query("select * from averias where fecha between '$desde' and '$hasta' and  tipo='$transaccion' order by bodega,fecha,tipo")or die($conexion2->error());
		}
		
	}
	

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUNA $transaccion</h3>";

	}else
	{
			echo "<a href='export_reporte_averias.php?desde=$desde&&hasta=$hasta&&tipo=$transaccion&&bodega=$bodegas' target='_blank'>Exportar a Excel</a><br>";
		

		echo "<table border='1' width='98%' cellpadding='10' style='border-collapse:collapse;'>";
		echo "<tr>
			<td>FECHA</td>
			<td>BODEGA</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
			<td>PRECIO</td>
			<td>TOTAL</td>
			<td>TRANSACCION</td>
			<td>MARCHAMO</td>
			<td>DOCUMENTO</td>
			<td>OBSERVACION</td>
			<td>CONSECUTIVO</td>

		</tr>";
		$t=0; $total=0; $tcant=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$f['articulo'];
			$precio=$f['precio'];
			$cant=$f['cantidad'];
			$fecha=$f['fecha'];
			$bodega=$f['bodega'];
			$tipo=$f['tipo'];
			$marchamo=$f['marchamo'];
			$doc=$f['documento_inv'];
			$obs=$f['observacion'];
			$t=$cant*$precio;
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$desc=$fca['DESCRIPCION'];
			$e=explode(".", $precio);
			if($e[0]=='')
			{
				$precio="0.$e[1]";
			}
			$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bode=$fcb['BODEGA'];
			$nomb=$fcb['NOMBRE'];
				echo "<tr>
			
			<td>$fecha</td>
			<td>$bode: $nomb</td>
			<td>$art</td>
			<td>$desc</td>
			<td>$cant</td>
			<td>$precio</td>
			<td>$t</td>
			<td>$tipo</td>
			<td>$marchamo</td>
			<td>$doc</td>
			<td>$obs</td>
			<td>".$f['id']."</td>

		</tr>";
		$total=$total+$t;
		$tcant=$tcant + $cant;
		}

		echo "<tr>
			<td colspan='4'>TOTAL</td>
			<td>$tcant</td>
			<td></td>
			<td>$total</td>
			<td colspan='3'></td>
		</tr>";
	}
}
?>
</body>
</html>