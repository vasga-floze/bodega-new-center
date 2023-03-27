<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#img").hide();
		})
	</script>
<img src="load.gif" width="110%" id="img" height="110%" style="margin-top: -1%; position: fixed; margin-top: -5%;">
<div style="display: none;">
	<?php
	include("conexion.php");
	?>
</div>
<?php
	error_reporting(0);
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
	echo "<a href='expor_existencias_tiendas.php' target='_blank'>exportar excel $bodega</a>";
	echo "<table border='1' style='border-collapse:collapse; width:98%;' cellpadding='7'>";
	echo "<tr>
		<td>BODEGA</td>
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
		echo "<tr>
		<td>$bodega</td>
		<td>$art</td>
		<td>$desc</td>
		<td>$cantidad</td>
	</tr>";
	}
}

?>