<!DOCTYPE html>
<html>
<head>
<?php
$fecha=$_GET['fecha'];
$bodega=$_GET['bodega'];
?>
	<title>DETALLE DESGLOSE <?php echo "$bodega";?></title>
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
<div style="display: none;">
<?php
include("conexion.php");
$fecha=$_GET['fecha'];
$bodega=$_GET['bodega'];
?>	
</div>
<div class="detalle" >
	<a href="#" onclick="cerrar()" style="color: white; float: right; margin-right: 0.5%;">CERRAR X</a>
<div class="adentro" style="margin-left: 2.5%; height: 93%;">
<?php
$c=$conexion2->query("select * from registro where fecha_desglose='$fecha' and bodega='$bodega'")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN DESGLOSE</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:98%; margin-left:1%;'>";
	$cd=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
	$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
	$nom=$fcd['NOMBRE'];
	echo "<tr>
		<td colspan='5'>DETALLE DESGLOSES DE $bodega: $nom FECHA: $fecha</td>
	</tr>";
	echo "<tr>
			<td>#</td>
			<td>ARTICULO</td>
			<td>DESCIPCION</td>
			<td>CODIGO BARRA</td>
			<td>TOTAL</td>
		</tr>";
		$t=0; $total=0;
		$num=1;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$idr=$f['id_registro'];
			$cod=$f['codigo'];
			$barra=$f['barra'];
			$cd=$conexion2->query("select sum(convert(decimal(10,2),precio)*cantidad) as total from desglose where registro='$idr'")or die($conexion2->error());
			$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
			$t=$fcd['total'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$arti=$fca['ARTICULO'];
			$desc=$fca['DESCRIPCION'];
			echo "<tr>
			<td>$num</td>
			<td>$arti</td>
			<td>$desc</td>
			<td>$barra</td>
			<td>$$t</td>
		</tr>";
		$total=$total+$t;
		$num++;
		}
		echo "<tr>
			<td colspan='4'>TOTAL:</td>
			<td>$$total</td>
		</tr>";
}
?>
</div>	
</div>
</body>
</html>