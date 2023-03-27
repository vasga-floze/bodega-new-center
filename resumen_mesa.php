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
<form method="POST">
DESDE: <input type="date" name="desde" required class="text" style="width: 17%;">

HASTA: <input type="date" name="hasta" required class="text" style="width: 17%;">
<select name="clasificacion" class="text" style="width: 17%;">
	<option value="">CLASIFICACION</option>
<?php
$q=$conexion1->query("select clasificacion_2 from consny.articulo where clasificacion_2 is not null group by clasificacion_2")or die($conexion1->error());
while($fq=$q->FETCH(PDO::FETCH_ASSOC))
{
	echo "<option>".$fq['clasificacion_2']."</option>";
}
?>
</select>

<select name="bodega" class="text" style="width: 17%;" required>
	<option value="">BODEGA</option>
<?php
$qb=$conexion1->query("select bodega from consny.bodega where bodega like 'SM%' and nombre not like '%(N)%' or bodega='CA00' order by bodega asc")or die($conexion1->error());
while($fqb=$qb->FETCH(PDO::FETCH_ASSOC))
{
	echo "<option>".$fqb['bodega']."</option>";
}
?>
</select>
<input type="submit" class="boton2" name="" value="BUSCAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	/*$c=$conexion2->query("SELECT        registro.codigo, COUNT(registro.codigo) as cantidad, SUM(ISNULL(convert(int,registro.peso),0) + ISNULL(CONVERT(int,registro.lbs),0)) AS peso
FROM            detalle_mesa INNER JOIN
                         mesa ON detalle_mesa.mesa = mesa.id INNER JOIN
                         registro ON detalle_mesa.registro = registro.id_registro
                         where mesa.fecha between '$desde' and '$hasta' and registro.bodega='$bodega' and registro.activo is not null
GROUP BY registro.codigo order by registro.codigo
")or die($conexion2->error());/*/
$c=$conexion2->query("select registro.codigo,count(registro.codigo) as cantidad,sum(isnull(registro.lbs,0)+isnull(registro.peso,0)) as peso,mesa.producido from registro  inner join detalle_mesa on registro.id_registro=detalle_mesa.registro inner join mesa on detalle_mesa.mesa=mesa.id where mesa.fecha between '$desde' and '$hasta' and mesa.bodega='$bodega' group by mesa.producido,registro.codigo order by registro.codigo
")or die($conexion2->error());
	if($clasificacion=='')
	{
		$msj='TODAS';
	}else
	{
		$msj=$clasificacion;
	}
	$titulo="RESULTADO DESDE LA FECHA:$desde ; HASTA:$hasta ; CLASIFICACION: $msj ; BODEGA: $bodega";
echo "<table border='1' cellpadding='10' class='tabla'>";
echo "<tr>
<td colspan='7'>$titulo <a href='exportar_resumen_mesa.php?desde=$desde&&hasta=$hasta&&bodega=$bodega&&clasificacion=$clasificacion&&titulo=$titulo' style='float:right; margin-right:0.5%;' target='_blank'>Exportar a Excel</a></td>
</tr>";
echo "<tr>
<td>PRODUCIDO POR</td>
<td>ARTICULO</td>
<td>DESCRIPCION</td>
<td>CLASIFICACION</td>
<td>CANTIDAD</td>
<td>PESO</td>
<td>BODEGA</td>
</tr>";
$t=0;
$tp=0;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$art=$f['codigo'];
	$cant=$f['cantidad'];
	$peso=$f['peso'];
	$producidos=$f['producido'];
	$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
if($clasificacion=='' or $clasificacion==$fca['CLASIFICACION_2'])
{
	$t=$t + $cant;
	echo "<tr>
	<td>$producidos</td>
<td>".$fca['ARTICULO']."</td>
<td>".$fca['DESCRIPCION']."</td>
<td>".$fca['CLASIFICACION_2']."</td>
<td>$cant</td>
<td>$peso</peso>
<td>$bodega</td>
</tr>";
$tp=$tp+$peso;





}
}
echo "<tr>
	<td colspan='4'>TOTAL</td>
	<td>$t</td>
	<td>$tp</td>
	<td></td>
</tr>";
echo "</table>";
}//fin post
?>

</body>
</html>