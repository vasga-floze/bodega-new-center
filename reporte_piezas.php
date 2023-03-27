<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
$bodega='CA02';
$desde='2021-09-27';
$hasta='2022-02-20';
$c=$conexion2->query("select EXIMP600.consny.ARTICULO.articulo,EXIMP600.consny.ARTICULO.DESCRIPCION,
EXIMP600.consny.ARTICULO.CLASIFICACION_2 as familia,
SUM(desglose.cantidad)as cantidad from desglose inner join eximp600.consny.articulo
on desglose.articulo=eximp600.consny.articulo.articulo where desglose.registro 
in(select id_registro from registro where fecha_desglose between '$desde' and 
'$hasta' and registro.bodega='$bodega')group by eximp600.consny.articulo.articulo,
eximp600.consny.articulo.articulo,EXIMP600.consny.ARTICULO.CLASIFICACION_2,
EXIMP600.consny.ARTICULO.DESCRIPCION")or die($conexion2->error());

$n=$c->rowCount();
if($n==0)
{
	echo "no se encontro nada";
}else
{
	echo "<table border='1'>";

	echo "<tr>
	<td>FAMILIA</td>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	<td>CANT. EN DESGLOSES</td>
	<td>E. POR LIQUIDACION</td>
	<td>S. POR LIQUIDACION</td>
	<td>S. AVERIAS/MERCA. NO VENDIBLE</td>
	<td>VENTA</td>
	<td>DEVOLUCION</td>
	<td>TOTAL</td>
	</tr>";
$tf=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$eliquidacion=0;
			$sliquidacion=0;
			$venta=0;
		$art=$f['articulo'];
		//liquidacion entrada
		$cle=$conexion2->query("select sum(cantidad) as cantidad from liquidaciones where bodega='$bodega' and fecha between '$desde' and '$hasta' and art_destino='$art'")or die($conexion2->error());
		$fcle=$cle->FETCH(PDO::FETCH_ASSOC);
		$eliquidacion=$fcle['cantidad'];
		//fin liquidaciones salida

		//liquidacion entrada
		$cle=$conexion2->query("select sum(cantidad) as cantidad from liquidaciones where bodega='$bodega' and fecha between '$desde' and '$hasta' and art_origen='$art'")or die($conexion2->error());
		$fcle=$cle->FETCH(PDO::FETCH_ASSOC);
		$sliquidacion=$fcle['cantidad'];
		//fin liquidaciones salida
		//averias
		$ca=$conexion2->query("select isnull(SUM(cantidad),0) as cantidad from averias where bodega='$bodega' and fecha between '$desde' and '$hasta' and articulo='$art'")or die($conexion2->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$averias=$fca['cantidad'];
		$total=$f['cantidad']+$eliquidacion-$sliquidacion-$averias;
		$tf=$tf+$total;
		//fin averias

		//VENTA
		$cv=$conexion1->query("
SELECT isnull(SUM(convert(int,CANTIDAD)),0) AS CANTIDAD FROM consny.DOC_POS_LINEA where BODEGA='$bodega' and 
ARTICULO='$art' and DOCUMENTO in(select DOCUMENTO from consny.DOCUMENTO_POS where 
CONVERT(date,FCH_HORA_COBRO) between '$desde' and '$hasta' and TIPO='F')")or die($conexion1->error());
		$fcv=$cv->FETCH(PDO::FETCH_ASSOC);
		$venta=$fcv['CANTIDAD'];
		//FIN VENTA

		//devoluciones
		$cd=$conexion1->query("
SELECT isnull(SUM(convert(int,CANTIDAD)),0) AS CANTIDAD FROM consny.DOC_POS_LINEA where BODEGA='$bodega' and 
ARTICULO='$art' and DOCUMENTO in(select DOCUMENTO from consny.DOCUMENTO_POS where 
CONVERT(date,FCH_HORA_COBRO) between '$desde' and '$hasta' and TIPO='D')")or die($conexion1->error());
		$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
		$devoluciones=$fcd['CANTIDAD'];
		$total=($f['cantidad']+$devoluciones+$eliquidacion)-$sliquidacion-$averias-$venta;
		$tf=$tf+$total;
		//FIN devoluciones
			echo "<tr>
	<td>".$f['familia']."</td>
	<td>".$f['articulo']."</td>
	<td>".$f['DESCRIPCION']."</td>
	<td>".$f['cantidad']."</td>
	<td>$eliquidacion</td>
	<td>$sliquidacion</td>
	<td>$averias</td>
	<td>$venta</td>
	<td>$devoluciones</td>
	<td>$total</td>
	</tr>";
		}
		echo "<tr><td colspan='9'>TOTAL</td><td>$tf</td>";
}
?>
</body>
</html>