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
$c=$conexion2->query("select  eximp600.consny.articulo.articulo from registro inner join eximp600.consny.articulo on registro.codigo=eximp600.consny.articulo.articulo where registro.tipo!='C1' and eximp600.consny.articulo.articulo='P458' group by eximp600.consny.articulo.articulo")or die($conexion2->error());

echo "<table border='1' width='100%'>";
echo "<tr>
	<td>FECHA</td>
	<td>ARTICULO</td>
	<td>CONTENEDOR</td>
	<td>PRODUCCION</td>
	<td>TRABAJADO</td>
	<td>VENTA</td>
	<td>TRASLADO SALIDA</td>
	<td>TRASLADO ENTRADA</td>
	<td>ELIMINADOS</td>
	<td>FINAL</td>
</tr>";
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
 $art=$f['articulo'];	
 //echo "$art-";


$final=0;
$fechas='2021-8';

	$ca=$conexion2->query("select CONCAT(year(fecha_documento),'-',month(fecha_documento)) as fecha from registro where tipo!='C1' group by CONCAT(year(fecha_documento),'-',month(fecha_documento)) order by CONCAT(year(fecha_documento),'-',month(fecha_documento))
")or die($conexion2->error());


$k=0;
while($fca=$ca->FETCH(PDO::FETCH_ASSOC) and $k==0)
{
	$fecha=$fca['fecha'];
	$inicial=$final;
	$cp=$conexion2->query("select count(*) as produccion from registro where  codigo='$art' and bodega_produccion in('SM00') and concat(year(fecha_documento),'-',month(fecha_documento))='$fecha' and tipo='p'")or die($conexion2->error());
	$fcp=$cp->FETCH(PDO::FETCH_ASSOC);
	$produccion=$fcp['produccion'];
	//fin produccion

	$cd=$conexion2->query("select count(*) as contenedor from registro where  codigo='$art' and bodega_produccion in('SM00') and concat(year(fecha_documento),'-',month(fecha_documento))='$fecha' and tipo='CD'")or die($conexion2->error());
	$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
	$contenedor=$fcd['contenedor'];
	//fin contenedor

	$cm=$conexion2->query("select registro.codigo,COUNT(codigo) as cantidad from registro inner join detalle_mesa on registro.id_registro=detalle_mesa.registro inner join mesa on detalle_mesa.mesa=mesa.id where CONCAT(year(mesa.fecha),'-',MONTH(mesa.fecha))='$fecha' and  mesa.bodega in('SM00') and registro.codigo='$art' group by codigo")or die($conexion2->error());
	$fcm=$cm->FETCH(PDO::FETCH_ASSOC);
	$mesa=$fcm['cantidad'];
	//fin mesa

	$cv=$conexion2->query("select registro.codigo,COUNT(registro.codigo) as cantidad from venta inner join registro on venta.registro=registro.id_registro where registro.codigo='$art' and venta.bodega_venta='SM00' and concat(year(venta.fecha),'-',month(venta.fecha))='$fecha' group by registro.codigo
")or die($conexion2->error());


	$fcv=$cv->FETCH(PDO::FETCH_ASSOC);
	$venta=$fcv['cantidad'];
	//FIN VENTA

	$cts=$conexion2->query("select articulo,count(articulo) as cantidad from traslado inner join registro on registro.id_registro=traslado.registro where traslado.origen in('SM00') and concat(year(traslado.fecha),'-',month(traslado.fecha))='$fecha' and articulo='$art' group by articulo")or die($conexion2->error());
	$fcts=$cts->FETCH(PDO::FETCH_ASSOC);
	$tsalida=$fcts['cantidad'];
	//fin traslado salida

	$cte=$conexion2->query("select articulo,count(articulo) as cantidad from traslado inner join registro on registro.id_registro=traslado.registro where traslado.destino in('SM00') and concat(year(traslado.fecha),'-',month(traslado.fecha))='$fecha' and articulo='$art' group by articulo")or die($conexion2->error());
	//fin entrada tralado
if($fecha!='-')
{
	$eli=$conexion2->query("select codigo,count(codigo) as cantidad from registro where codigo='$art' and concat(year(fecha_eliminacion),'-',month(fecha_eliminacion))='$fecha' and bodega_produccion in('SM00') group by codigo")or die($conexion2->error());
	$feli=$eli->FETCH(PDO::FETCH_ASSOC);
	//revisar eliminados fallo 
	$eliminados=$feli['cantidad'];
}else
{
	$eliminados=0;
}
//fin eliminados

// lo de cancelados iria aqui $ccan=$conexion2->query("")or die($conexion2->error());
	
	$fcte=$cte->FETCH(PDO::FETCH_ASSOC);
	$tentrada=$fcte['cantidad'];
	$final_entrada=$produccion+$contenedor;
	$final_entrada=$final_entrada+$tentrada;
	$final_salida=$mesa+$venta;
	$final_salida=$final_salida+$tsalida;
	$final_entrada=$final_entrada+$inicial;
	$final_salida=$final_salida+$eliminados;
	$final=$final_entrada-$final_salida;

	if($fecha==$fechas)
	{
		echo "<tr>
	<td>$fecha $inicial</td>
	<td>$art</td>
	<td>$contenedor</td>
	<td>$produccion</td>
	<td>$mesa</td>
	<td>$venta</td>
	<td>$tsalida</td>
	<td>$tentrada</td>
	<td>$eliminados</td>
	<td>$final</td>
</tr>";
$k=1;

	}
	


}
}
?>
</body>
</html>