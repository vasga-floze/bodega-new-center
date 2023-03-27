<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
include("conexion.php");
?>
<h3 style="text-align: center; text-decoration: underline;">REPORTE DE VENTAS</h3>
<form method="POST">
	<input type="date" name="desde" class="text" style="width: 15%; padding: 0.3%;">
	<input type="date" name="hasta" class="text" style="width: 15%; padding: 0.3%;">
	<input type="submit" name="btn" class="btn" style="background-color: #699C7A; color: white; padding: 0.5%; border-collapse: collapse; cursor: pointer;" value="BUSCAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select documento_inv,cliente,fecha,isnull(articulo,registro.codigo)as articulo,sum(convert(decimal(10,2),isnull(venta.precio,0)) * isnull(venta.cantidad,1)) as total from venta left join registro on venta.registro=registro.id_registro where fecha between'$desde' and '$hasta' group by isnull(articulo,registro.codigo),documento_inv,cliente,fecha order by documento_inv
")or die($conexion2->error());

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<H3>NO SE ENCONTRO NINGUNA VENTA FINALIZADA</H3>";
	}else
	{
		echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:100%; margin-top:1.5%;'>";
		echo "<tr>
			<td colspan='6'><a href='expor_venta3.php?desde=$desde&&hasta=$hasta'>Exportar a Excel</a></td>
		</tr>";
		echo "<tr>
		<td>FECHA</td>
		<td>DOCUMENTO</td>
		<td>CLIENTE</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>TOTAL</td>
		</tr>";
		$tf=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$doc=$f['documento_inv'];
			$cliente=$f['cliente'];
			$total=$f['total'];
			$art=$f['articulo'];
			$fecha=$f['fecha'];
			$total=$f['total'];
			$ca=$conexion1->query("select articulo,descripcion from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		if($total>0 and $art!='')
		{

			echo "<tr>
		
		<td>$fecha</td>
		<td>$doc</td>
		<td>$cliente</td>
		<td>".$fca['articulo']."</td>
		<td>".$fca['descripcion']."</td>
		<td>$total</td>
		</tr>";
		}
		$tf=$tf+$total;
		}
		echo "<tr>
		<td colspan='5'>TOTAL</td>
		<td>$tf</td>
		</tr>";
	}
}
?>
</body>
</html>