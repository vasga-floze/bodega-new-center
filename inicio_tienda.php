<!DOCTYPE html>
<html>
<head>
	<title></title>
   <link rel="stylesheet" href="cssmenu/styles.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script>
   	$(document).ready(function(){
   	$(".detalle").hide(300);
   })
   </script>

</head>
<body>
<div class="detalle" style="width: 150%; margin-left: -5%; background-color: white;">
	<img src="loadf.gif" style="margin-left: 30%; margin-top: 5%;">
</div>

<?php
include("conexion.php");
$mes=date('m');
$anio=date('Y');
$fecha="$anio-$mes-01";
$bodega=$_SESSION['bodega'];
$hasta=date("Y-m-d");

$desde=$fecha;
echo "<table border='1' style='border-collapse:collapse; width:100%;'>";

echo "<tr>
	<td rowspan='3'>FECHA</td>
	<td colspan='14' style='text-align:center;'>TRANSACCION</td>
</tr>";

echo "<tr>
<td colspan='2' style='text-align:center;'>TRASLADOS</td>
<td colspan='2'>DESGLOSE</td>
<td>AVERIAS</td>
<td>MERCA. NO VENDIBLE</td>
<td colspan='2'>LIQUIDACIONES</td>
<td colspan='2' style='text-align:center;'>DESACTIVA GANCHOS/INSUMOS</td>
<td rowspan='2' style='text-align:center;'>DESACTIVADO POR VENTA</td>
<td rowspan='2' style='text-align:center;'>CUADRO DE VENTA</td>

</tr>";

echo "<tr>
	<td>INGRESOS</td>
	<td>SALIDAS</td>
	<td>FINALIZADOS</td>
	<td>PENDIENTES</td>
	<td>FINALIZADOS</td>
	<td>FINALIZADOS</td>
	<td>FINALIZADOS</td>
	<td>PENDIENTES</td>
	<td>GANCHOS</td>
	<td>INSUMOS</td>
</tr>";
while($desde<=$hasta)
{
	//traslado entrasda
	$cte=$conexion2->query("select COUNT(*) as cantidad from traslado where destino='$bodega' and fecha='$desde'")or die($conexion2->error());
	$fcte=$cte->FETCH(PDO::FETCH_ASSOC);
	$traslado_entrada=$fcte['cantidad'];
	if($traslado_entrada=='')
	{
		$traslado_entrada="0";
	}
	//fin traslado entrada

	//traslado salida
	$cts=$conexion2->query("select COUNT(*) as cantidad from traslado where origen='$bodega' and fecha='$desde'")or die($conexion2->error());
	$fcts=$cts->FETCH(PDO::FETCH_ASSOC);
	$traslado_salida=$fcts['cantidad'];
	//fin traslado salida

	//desglosado
	$cd=$conexion2->query("select count(*) as cantidad from registro where fecha_desglose='$desde' and bodega='$bodega'")or die($conexion2->error());
	$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
	$desglose=$fcd['cantidad'];
	//fin desglosado

	//liquidaciones
	$cl=$conexion2->query("select sum(cantidad) as cantidad from liquidaciones where fecha= '$desde' and bodega='$bodega' and estado=1
")or  die($conexion2->error());
	$ncl=$cl->rowCount();
	$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
	$liquidacion=$fcl['cantidad'];
	

	$cls=$conexion2->query("select sum(cantidad) as cantidad from liquidaciones where fecha= '$desde' and bodega='$bodega' and estado=0
")or  die($conexion2->error());
	$ncls=$cls->rowCount();
	$fcls=$cls->FETCH(PDO::FETCH_ASSOC);
	$liquidacionesf=$fcls['cantidad'];
	//fin liquidaciones

	//averias/merca
	$ca=$conexion2->query("select count(*) as cantidad from averias where estado=1 and tipo='averia' and bodega='$bodega' and fecha='$desde'")or die($conexion2->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
	$averias=$fca['cantidad'];

	$cm=$conexion2->query("select count(*) as cantidad from averias where estado=1 and tipo!='averia' and bodega='$bodega' and fecha='$desde'")or die($conexion2->error());
	$fcm=$cm->FETCH(PDO::FETCH_ASSOC);
	$merca=$fcm['cantidad'];
	//fin averias/merca
	//ganchos 
	$cgan=$conexion2->query("select registro.barra,EXIMP600.consny.ARTICULO.CLASIFICACION_1,EXIMP600.consny.ARTICULO.CLASIFICACION_2  from registro inner join EXIMP600.consny.articulo on registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO
where CONVERT(date,registro.fecha_eliminacion)='$desde' and registro.bodega='$bodega' and EXIMP600.consny.ARTICULO.CLASIFICACION_2='GANCHOS' ")or die($conexion2->error());
	$ganchos=$cgan->fetchAll();
$ganchos=count($ganchos);

	//fin ganchos
	//insumo
	$cin=$conexion2->query("select registro.barra,EXIMP600.consny.ARTICULO.CLASIFICACION_1,EXIMP600.consny.ARTICULO.CLASIFICACION_2  from registro inner join EXIMP600.consny.articulo on registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO
where CONVERT(date,registro.fecha_eliminacion)='$desde' and registro.bodega='$bodega' and EXIMP600.consny.ARTICULO.CLASIFICACION_1='INSUMO' ")or die($conexion2->error());
	$insumo=$cin->fetchAll();
$insumo=count($insumo);
	//fin insumo

//desgloses pendientes

	$cdp=$conexion2->query("select registro.barra,registro.bodega,convert(date,desglose.fecha) fecha from registro inner join desglose on registro.id_registro=desglose.registro where 
(registro.fecha_desglose is null or registro.fecha_desglose='') and registro.bodega='$bodega' and CONVERT(date,desglose.fecha)='$desde' group by registro.barra,registro.bodega,desglose.fecha")or die($conexion2->error());
	$desglose_pendiente=$cdp->fetchAll();
	$desglose_pendiente=count($desglose_pendiente);
//fin desgloses pendientes

//venta
$cv=$conexion2->query("select * from registro where fecha_hora_venta is not null and bodega='$bodega' and CONVERT(date,fecha_hora_venta)='$desde'")or die($conexion2->error());

$venta=$cv->fetchAll();
$venta=count($venta);
//fin venta

//cuadro venta
$ccv=$conexion1->query("select * from CUADRO_VENTA where FECHA='$desde' and BODEGA='$bodega'")or die($conexion1->error());
$nccv=$ccv->rowCount();
if($nccv==0)
{
	$cuadro="NO";
}else
{
	$cuadro="SI";
}
$fccv=$ccv->FETCH(PDO::FETCH_ASSOC);
if($fccv['ESTADO']==0)
{
	"NO";
}else
{
	"SI";
}


//fin cuadro venta

	echo "<tr>
	<td>$desde</td>
	<td>$traslado_entrada</td>
	<td>$traslado_salida</td>
	<td>$desglose</td>
	<td>$desglose_pendiente</td>
	<td>$averias</td>
	<td>$merca</td>
	<td>$liquidacion</td>
	<td>$liquidacionesf</td>
	<td>$ganchos</td>
	<td>$insumo</td>
	<td>$venta</td>
	<td>$cuadro</td>
	</tr>";

	//incremente desde
	$ci=$conexion2->query("select DATEADD(day,1,convert(date,'$desde')) as fecha")or die($conexion2->error());

	
	$fci=$ci->FETCH(PDO::FETCH_ASSOC);

	$desde=$fci['fecha'];
	//fin incremento desde
}
?>
</body>
</html>