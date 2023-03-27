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
	</script>
</head>
<body>
<div class="detalle" style="width: 150%; height: 100%; margin-left: -5%; background-color: white;">
	<img src="loadf.gif" style="margin-left: 30%; margin-top: 10%;">
</div>
<?php
include("conexion.php");


?>
<h3 style="text-decoration: underline; text-align: center;">REPORTE DE TIEMPO DE FARDOS EN BODEGAS</h3>
<?php
$c=$conexion2->query("select DATEDIFF(day,isnull(fecha_traslado,fecha_documento),getdate())as dias,isnull(fecha_traslado,
fecha_documento) as fecha,EXIMP600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,
eximp600.consny.ARTICULO.CLASIFICACION_2 as familia,
(isnull(lbs,0)+isnull(peso,0)) as peso,barra,concat(registro.bodega,': ',EXIMP600.consny.BODEGA.nombre) as bodega  from registro inner join eximp600.consny.articulo on
registro.codigo=eximp600.consny.articulo.articulo inner join eximp600.consny.bodega on registro.bodega=
eximp600.consny.bodega.bodega  where (registro.bodega like 'SM%' or registro.bodega='CA00') and registro.activo is null
order by dias desc")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<p>NO SE ENCONTRO  NINGUN FARDO DISPONIBLE</p>";
}else
{
	echo "<table border='1' style='border-collapse:collapse; width:100%;'>";
	echo "<tr>
		<td>#</td>
		<td>FAMILIA</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>BARRA</td>
		<td>BODEGA</td>
		<TD>FECHA INGRESO</TD>
		<td>DIAS EN BODEGA</td>
	</tr>";
	$num=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$num++;
		echo "<tr>
		<td>$num</td>
		<td>".$f['familia']."</td>
		<td>".$f['articulo']."</td>
		<td>".$f['descripcion']."</td>
		<td>".$f['barra']."</td>
		<td>".$f['bodega']."</td>
		<td>".$f['fecha']."</td>
		<td>".$f['dias']."</td>
	</tr>";
	}

}
?>
</body>
</html>