<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<div style="display: none;">
<?php
error_reporting(0);
include("conexion.php");
?>	
</div>

<div class="detalle">
<?php
	$fecha=$_GET['fecha'];
	$bodega=$_GET['bodega'];
	$desde=$_GET['desde'];
	$hasta=$_GET['hasta'];

	echo "<a href='reporte_pedido_tienda.php?desde=$desde&&hasta=$hasta&&bodega=$bodega' style='float: right; margin-right: 0.5%; color: white; text-decoration: none;'>CERRAR X</a><br>";

	?>
	<div class="adentro" style="margin-left: 2.5%;">
	<?php


	$c=$conexion2->query("select * from pedidos where tienda='$bodega' and convert(date,fecha)='$fecha' and estado!='SOLI...'")or die($conexion2->error());

	echo "<table border='1' style='border-collapse:collapse; width:98%; margin-left:1%;' cellpadding='10'>
		<tr>
			<td>FECHA</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD SOLICITADA</td>
			<td>ESTADO</td>
		</tr>";
		$t=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$f['articulo'];
			$cant=$f['cantidad_tienda'];
			$estado=$f['estado'];
			$t=$t+$cant;
			if($estado=='SOLI...')
			{
				$estado='PENDIENTE';
			}

			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$arti=$fca['ARTICULO'];
			$desc=$fca['DESCRIPCION'];


			echo "<tr>
			<TD>$fecha</TD>
			<td>$arti</td>
			<td>$desc</td>
			<td>$cant</td>
			<td>$estado</td>
		</tr>";
		}
		echo "<tr>
			<td colspan='3'>TOTAL</td>
			<td>$t</td>
			<TD></TD>
		</tr>";
	echo "</table>";
	?>
	</div>
</div>
</body>
</html>