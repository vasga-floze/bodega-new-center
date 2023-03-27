<!DOCTYPE html>
<html>
<head>
	<title></title>
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
<img src="atras.png" width="5%" height="5%" style="cursor: pointer; float:right;" onclick="cerrar()">
<?php
include("conexion.php");
$registro=$_GET['id'];
$cr=$conexion2->query("select * from registro where id_registro='$registro'")or die($conexion2->error());

$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
$barra=$fcr['barra'];
$fecha_documento=$fcr['fecha_documento'];
$lbs=$fcr['lbs'];
$und=$fcr['und'];
$fecha_traslado=$fcr['fecha_traslado'];
$bodega=$fcr['bodega'];
$empacado=$fcr['empacado'];
$producido=$fcr['producido'];
$empaque=$fcr['empaque'];
$familia=$fcr['categoria'];
$desc=$fcr['subcategoria'];
$art=$fcr['codigo'];
$bod=$fcr['bodega'];
$cb=$conexion1->query("select * from consny.bodega where bodega='$bod'")or die($conexion1->error());
$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
$bodega=$fcb['BODEGA'];
$nombre=$fcb['NOMBRE'];
$fecha_desglose=$fcr['fecha_desglose'];
//precio promedio
				$cpromedio=$conexion2->query("SELECT h.codigo, sum(h.expr1) CANT_FARDOS, sum(h.precio_total) TOTAL_PRECIO_DESGLOSE, Round(sum(h.precio_total)/sum(h.expr1),0) PRECIO_PROMEDIO from
(SELECT        E.PRECIO_TOTAL, COUNT(registro_1.codigo) AS Expr1, E.codigo
FROM            (SELECT        desglose.registro, registro.codigo, SUM(desglose.precio * desglose.cantidad) AS PRECIO_TOTAL
                          FROM            desglose INNER JOIN
                                                    registro ON desglose.registro = registro.id_registro
where registro.codigo='$art'
                          GROUP BY registro.codigo, desglose.registro) AS E INNER JOIN
                         registro AS registro_1 ON E.registro = registro_1.id_registro AND E.codigo = registro_1.codigo
GROUP BY E.PRECIO_TOTAL, E.registro, E.codigo
)as H
group by h.codigo
ORDER BY 1")or die($conexion2->error());
				$numero=$cpromedio->rowCount();
			$fcpromedio=$cpromedio->FETCH(PDO::FETCH_ASSOC);
			$precio_tienda=$fcpromedio['PRECIO_PROMEDIO'];
			//fin precio promedio

echo "<table border='1' width='100%' cellpadding='10' style='border-collapse:collapse; '>
<tr>
	<td>CODIGO DE BARRA: $barra</td>
	<td colspan='2'>BODEGA ACTUAL: $bodega: $nombre</td>
	<td colspan='2'>PRECIO PROMEDIO: $precio_tienda </td>
</tr>
<tr>
	<td>FAMILIA: $familia</td>
	<td>ARTICULO: $art</td>
	<td>DESCRIPCION: $desc</td>
	<td>PESO: $lbs</td>
	<td>UNIDADES: $und</td>
</tr>
<tr>
	<td>FECHA PRODUCCION: $fecha_documento</td>
	<td>FECHA TRASLADO: $fecha_traslado</td>
	<td>FECHA DESGLOSE: $fecha_desglose</td>
	<td>EMPACADO POR: $empacado</td>
	<td>PRODUCIDO POR: $producido</td>
</tr>
</table><hr>";
$cd=$conexion2->query("select concat(eximp600.consny.articulo.articulo,': ',eximp600.consny.articulo.descripcion) as articulo,detalle.cantidad,convert(decimal(10,2),eximp600.consny.articulo.precio_regular) as precio from
eximp600.consny.articulo inner join detalle on detalle.articulo=eximp600.consny.articulo.articulo where detalle.registro='$registro'
")or die($conexion2->error());



echo "<table border='1' cellpadding='10' style='border-collapse:collapse; float:left;' width='48%'>";
echo "<tr>
	<td colspan='4' style='text-align:center;'>DESGLOSE DE PRODUCCION(BODEGA)</td>
</tr>";
echo "<tr>
	<td>ARTICULO</td>
	<td>CANTIDAD</td>
	<td>PRECIO</td>
	<td>TOTAL</td>
</tr>";
$total_detalle=0;
$cantidad_detalle=0;
while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
{

	$t=$fcd['cantidad']* $fcd['precio'];
	$total_detalle=$total_detalle+$t;
	$cantidad_detalle=$cantidad_detalle+$fcd['cantidad'];
	echo "<tr><td>".$fcd['articulo']."</td>
	<td>".$fcd['cantidad']."</td>
	<td>".$fcd['precio']."</td>
	<td>$t</td>
</tr>";
}
echo "<tr>
		<td>TOTAL</td>
		<td>$cantidad_detalle</td>
		<td></td>
		<td>$total_detalle</td>
</tr>
</table>";

$cdes=$conexion2->query("select CONCAT(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION) as articulo,desglose.cantidad,desglose.precio,(ISNULL(desglose.cantidad,0) * ISNULL(desglose.precio,0)) as total,desglose.precio from desglose inner join EXIMP600.consny.ARTICULO on EXIMP600.consny.ARTICULO.ARTICULO=desglose.articulo where desglose.registro='$registro'")or die($conexion2->error());
echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:48%; float:left; margin-left:3%;'>";
echo "<tr>
	<td colspan='4' style='text-align:center;'>DESGLOSE EN TIENDA</td>
</tr>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>CANTIDAD</td>
		<td>PRECIO</td>
		<td>TOTAL</td>
	</tr>";
	$t=0;
	$cant=0;
while($fcdes=$cdes->FETCH(PDO::FETCH_ASSOC))
{
	echo "<tr>
	<td>".$fcdes['articulo']."</td>
	<td>".$fcdes['cantidad']."</td>
	<td>".$fcdes['precio']."</td>
	<td>".$fcdes['total']."</td>
</tr>";
$t=$t + $fcdes['total'];
$cant=$cant+$fcdes['cantidad'];

}
echo "<tr>
	<td>TOTAL</td>
	<td>$cant</td>
	<td></td>
	<td>$t</td>

</tr>";





?>
</body>
</html>