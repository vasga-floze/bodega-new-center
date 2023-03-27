<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");

//----query----
//declare @fecha date='2020-11-06'
/*select pruebabd.dbo.registro.codigo,COUNT(pruebabd.dbo.registro.codigo) AS CANTIDAD,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,sum(convert(decimal(10,2),isnull(pruebabd.dbo.registro.lbs,0))+convert(decimal(10,2),isnull(pruebabd.dbo.registro.peso,0))) as peso from pruebabd.dbo.registro inner join eximp600.consny.articulo on pruebabd.dbo.registro.codigo=eximp600.consny.articulo.articulo where pruebabd.dbo.registro.fecha_documento<=@fecha and pruebabd.dbo.registro.activo is  null and pruebabd.dbo.registro.bodega!='0000' --and bodega IN ('SM00','SM04') 
and id_registro not IN (select registro from venta where fecha<=@fecha and registro is not null)
and id_registro not in (select registro from traslado where origen='SM00' and fecha<=@fecha)
and id_registro not IN (select registro from detalle_mesa where mesa in (select id from mesa where fecha<=@fecha)) group by pruebabd.dbo.registro.codigo,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2*/

$fecha="2020-11-10";
$c=$conexion2->query("select codigo from registro where tipo!='c1' and fecha_documento<='$fecha' group by codigo")or die($conexion2->error());
echo "<table border='1'>";
echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>PESO</td>
</tr>";
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$cod=$f['codigo'];

	$cr=$conexion2->query("select count(codigo) as cantidad,sum(convert(decimal(10,2),isnull(lbs,0))+convert(decimal(10,2),isnull(peso,0))) as peso from registro where codigo='$cod' and fecha_documento<='$fecha' group by codigo")or die($conexion2->error());
	$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
	$peso=$fcr['peso'];
	$cantidad=$fcr['cantidad'];

	//traslados origen y destino
	$ct=$conexion2->query("select count(registro.codigo) as cantidad,sum(convert(decimal(10,2),isnull(registro.lbs,0))+convert(decimal(10,2),isnull(registro.peso,0))) as peso from registro inner join traslado on registro.id_registro=traslado.registro where traslado.origen='SM00' and registro.codigo='$cod' and traslado.fecha<='$fecha' group by registro.codigo")or die($conexion2->error());
	$fct=$ct->FETCH(PDO::FETCH_ASSOC);
	$cantidad=$cantidad -$fct['cantidad'];
	$peso=$peso -$fct['peso'];

	$ct1=$conexion2->query("select count(registro.codigo) as cantidad,sum(convert(decimal(10,2),isnull(registro.lbs,0))+convert(decimal(10,2),isnull(registro.peso,0))) as peso from registro inner join traslado on registro.id_Registro=traslado.registro where traslado.destino='SM00' and registro.codigo='$cod' and traslado.fecha<='$fecha' group by registro.codigo")or die($conexion2->error());

	$fct1=$ct1->FETCH(PDO::FETCH_ASSOC);
	$cantidad=$cantidad +$fct1['cantidad'];
	$peso=$peso +$fct1['peso'];
	//fin traslados

	//ventas
	$cv=$conexion2->query("select count(registro.codigo) as cantidad,sum(convert(decimal(10,2),isnull(registro.lbs,0))+convert(decimal(10,2),isnull(registro.peso,0))) as peso from registro inner join venta on registro.id_registro=venta.registro where registro.codigo='$cod' and venta.fecha<='$fecha' group by registro.codigo")or die($conexion2->error());
	$fcv=$cv->FETCH(PDO::FETCH_ASSOC);
	$cantidad=$cantidad - $fcv['cantidad'];
	$peso=$peso - $fcv['peso'];

	$cm=$conexion2->query("select count(registro.codigo) as cantidad,sum(convert(decimal(10,2),isnull(registro.lbs,0))+convert(decimal(10,2),isnull(registro.peso,0))) as peso from registro inner join detalle_mesa on registro.id_registro=detalle_mesa.registro inner join mesa on detalle_mesa.mesa=mesa.id where registro.codigo='$cod' and mesa.fecha<='$fecha' group by registro.codigo")or die($conexion2->error());
	$fcm=$cm->FETCH(PDO::FETCH_ASSOC);
	$cantidad=$cantidad- $fcm['cantidad'];
	$peso=$peso - $fcm['peso'];
	echo "<tr>
		<td>$cod</td>
		<td></td>
		<td>$cantidad</td>
		<td>$peso</td>
	</tr>";
}

?>
</body>
</html>