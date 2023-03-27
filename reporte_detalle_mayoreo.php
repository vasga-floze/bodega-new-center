<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	$(document).ready(function(){
		$("#img").show();
		$("#img").hide();
	})
</script>
</head>
<body>
<div style="width: 110%; height: 110%; position: fixed; float: center; margin-left: -5%; margin-top:-2%; background-color: white;" id="img">
<img src="loadf.gif" style="float: center; margin-left: 40%; margin-top: 15%; width: 20%; height: 22%;">
</div>
<?php
error_reporting(0);
include("conexion.php");
?>
<h3 style="text-decoration: underline; text-align: center;">REPORTE DETALLE/ MAYOREO</h3>
<form method="POST" style="text-align: center;">
<select name="bod" class="text" style="padding:0.5%; width:15%;">
	<option value="">BODEGA</option>
	<?php
	$cb=$conexion1->query("select * from consny.bodega where bodega not like 'SM%' and nombre not like '%(N)%' order by bodega")or die($conexion1->error());
	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$fcb['BODEGA'];
		$nom=$fcb['NOMBRE'];
		echo "<option value='$bod'>$bod: $nom</option>";
	}
	?>
</select>
<label>DESDE: <input type="date" name="desde" class="text" style="padding:0.5%; width:10%;"></label>
<label>HASTA: <input type="date" name="hasta" class="text" style="padding:0.5%; width:10%;"></label>
<input type="submit" name="btn" value="GENERAR" class="btnfinal" style="background-color: #D7E9DC; padding: 0.5%; color: black;">

</form>
<?php

	extract($_REQUEST);

if($_POST)
{
	extract($_REQUEST);
	if($bod=='')
	{
	$c=$conexion1->query("declare @fechai  datetime ='$desde'

declare @fechaf datetime ='$hasta'
SELECT E.EMPRESA, E.BODEGA, E.NOMBRE,  E.TIPO_ARTICULO,
SUM(E.CANTIDAD) CANTIDAD, SUM(E.PRECIO_VENTA) PRECIO_VENTA,
MES, AÑO
from 
(

SELECT     consny.documento_pos.fch_hora_cobro,  CONSNY.DOC_pos_linea.documento,CONSNY.DOC_pos_linea.linea, consny.DOC_POS_LINEA.ARTICULO, CASE consny.doc_pos_linea.tipo WHEN 'F' THEN consny.DOC_POS_LINEA.CANTIDAD ELSE consny.DOC_POS_LINEA.CANTIDAD * - 1 END AS CANTIDAD, 
                         CASE consny.doc_pos_linea.tipo WHEN 'F' THEN ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA - consny.DOC_POS_LINEA.DESCUENTO_LINEA + consny.DOC_POS_LINEA.TOTAL_IMPUESTO1, 2) 
                         ELSE ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA - consny.DOC_POS_LINEA.DESCUENTO_LINEA + consny.DOC_POS_LINEA.TOTAL_IMPUESTO1, 2) * - 1 END AS PRECIO_VENTA, 
                         MONTH(consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS MES, YEAR(consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS AÑO, consny.ARTICULO.DESCRIPCION AS NOMBRE_ARTICULO, 
                         CASE consny.ARTICULO.CLASIFICACION_1 WHEN 'DETALLE' THEN 'DETALLE' ELSE 'MAYOREO' END AS TIPO_ARTICULO, consny.DOC_POS_LINEA.BODEGA, consny.BODEGA.NOMBRE, 
                         CASE LEFT(consny.DOC_POS_LINEA.BODEGA, 1) WHEN 'C' THEN 'CARISMA' WHEN 'E' THEN 'EVER' WHEN 'N' THEN 'NERY' ELSE 'BODEGA' END AS EMPRESA
FROM            consny.DOC_POS_LINEA INNER JOIN
                         consny.DOCUMENTO_POS ON consny.DOC_POS_LINEA.DOCUMENTO = consny.DOCUMENTO_POS.DOCUMENTO AND consny.DOC_POS_LINEA.TIPO = consny.DOCUMENTO_POS.TIPO AND 
                         consny.DOC_POS_LINEA.CAJA = consny.DOCUMENTO_POS.CAJA INNER JOIN
                         consny.ARTICULO ON consny.DOC_POS_LINEA.ARTICULO = consny.ARTICULO.ARTICULO INNER JOIN
                         consny.BODEGA ON consny.DOC_POS_LINEA.BODEGA = consny.BODEGA.BODEGA
WHERE        (consny.DOCUMENTO_POS.FCH_HORA_COBRO BETWEEN DATEADD(MI, 1, @fechai) AND DATEADD(MI, 1439, @fechaf)) 

) AS E

GROUP BY 
E.EMPRESA, E.BODEGA, E.NOMBRE,  E.TIPO_ARTICULO,
MES, AÑO   
ORDER BY bodega,año,mes")or die($conexion1->error());
	$c1=$c;
}else
{
	$c=$conexion1->query("declare @fechai  datetime ='$desde'

declare @fechaf datetime ='$hasta'
SELECT E.EMPRESA, E.BODEGA, E.NOMBRE,  E.TIPO_ARTICULO,
SUM(E.CANTIDAD) CANTIDAD, SUM(E.PRECIO_VENTA) PRECIO_VENTA,
MES, AÑO
from 
(

SELECT     consny.documento_pos.fch_hora_cobro,  CONSNY.DOC_pos_linea.documento,CONSNY.DOC_pos_linea.linea, consny.DOC_POS_LINEA.ARTICULO, CASE consny.doc_pos_linea.tipo WHEN 'F' THEN consny.DOC_POS_LINEA.CANTIDAD ELSE consny.DOC_POS_LINEA.CANTIDAD * - 1 END AS CANTIDAD, 
                         CASE consny.doc_pos_linea.tipo WHEN 'F' THEN ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA - consny.DOC_POS_LINEA.DESCUENTO_LINEA + consny.DOC_POS_LINEA.TOTAL_IMPUESTO1, 2) 
                         ELSE ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA - consny.DOC_POS_LINEA.DESCUENTO_LINEA + consny.DOC_POS_LINEA.TOTAL_IMPUESTO1, 2) * - 1 END AS PRECIO_VENTA, 
                         MONTH(consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS MES, YEAR(consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS AÑO, consny.ARTICULO.DESCRIPCION AS NOMBRE_ARTICULO, 
                         CASE consny.ARTICULO.CLASIFICACION_1 WHEN 'DETALLE' THEN 'DETALLE' ELSE 'MAYOREO' END AS TIPO_ARTICULO, consny.DOC_POS_LINEA.BODEGA, consny.BODEGA.NOMBRE, 
                         CASE LEFT(consny.DOC_POS_LINEA.BODEGA, 1) WHEN 'C' THEN 'CARISMA' WHEN 'E' THEN 'EVER' WHEN 'N' THEN 'NERY' ELSE 'BODEGA' END AS EMPRESA
FROM            consny.DOC_POS_LINEA INNER JOIN
                         consny.DOCUMENTO_POS ON consny.DOC_POS_LINEA.DOCUMENTO = consny.DOCUMENTO_POS.DOCUMENTO AND consny.DOC_POS_LINEA.TIPO = consny.DOCUMENTO_POS.TIPO AND 
                         consny.DOC_POS_LINEA.CAJA = consny.DOCUMENTO_POS.CAJA INNER JOIN
                         consny.ARTICULO ON consny.DOC_POS_LINEA.ARTICULO = consny.ARTICULO.ARTICULO INNER JOIN
                         consny.BODEGA ON consny.DOC_POS_LINEA.BODEGA = consny.BODEGA.BODEGA
WHERE        (consny.DOCUMENTO_POS.FCH_HORA_COBRO BETWEEN DATEADD(MI, 1, @fechai) AND DATEADD(MI, 1439, @fechaf)) and consny.doc_pos_linea.bodega='$bod' 

) AS E

GROUP BY 
E.EMPRESA, E.BODEGA, E.NOMBRE,  E.TIPO_ARTICULO,
MES, AÑO   
ORDER BY bodega,año,mes")or die($conexion1->error());
	$c1=$c;
}
$nmes=0;
$nbodega=0;

while($f1=$c1->FETCH(PDO::FETCH_ASSOC))
{
	$bodega=$f1['BODEGA'];
	
	if(in_array($bodega, $fila_bodegas))
	{

	}else
	{
		$fila_bodegas[$nbodega]=$bodega;
		$nbodega++;
	}
	$nbodega++;
}
$num=1;
$nmes=0;
$fecha=$desde;
while($fecha<=$hasta)
{
	$query=$conexion2->query("declare @fecha date='$fecha'

select dateadd(day,1,@fecha) as fecha,month(@fecha) as mes,year(@fecha) as año,concat(month(@fecha),'/',year(@fecha)) as text
")or die($conexion2->error());
	$fquery=$query->FETCH(PDO::FETCH_ASSOC);
	$mes=$fquery['mes'];
	$año=$fquery['año'];
	$text=$fquery['text'];
	$fecha=$fquery['fecha'];
	if(in_array($text, $fila_fecha))
	{

	}else
	{
		$fila_fecha[$nmes]=$text;
		$fila_fecha1[$nmes]=$text;
		$nmes++;
	}
}



$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN RESULTADO</h3>";
}else
{
	$nmes1=$nmes*2;
	echo "<a href='export_reporte_detalle_mayoreo.php?desde=$desde&&hasta=$hasta&&bodega=$bod' target='_blank'>Exportar a Excel</a>";
	echo "<table border='1' cellpadding='5' style='border-collapse:collapse; width:110%;'>";
echo "<tr>
<td rowspan='3' style='text-align:center;'>EMPRESA</td>
<td rowspan='3' style='text-align:center;'>BODEGA</td>
<td rowspan='3' width='20%' style='text-align:center;'>NOMBRE</td>
<td colspan='$nmes1' style='text-align:center;'>MES</td>
<td rowspan='3' style='text-align:center;'>TOTAL GENERAL</td></tr>";
echo "<tr>";
foreach ($fila_fecha as $key => $valor) 
{
	echo "<td colspan='2' style='text-align:center;'>$valor</td>";
	
}
echo "</tr>";
$k=0;
echo "<tr>";
while($k<$nmes)

{
	echo "<td>DETALLE</td><td>FARDOS</td>";
	$k++;

}
echo "</tr>";

foreach ($fila_bodegas as $key => $bodegas) 
{
	$bodega=$bodegas;

	$numero=0;
	$interacion=0;
	$total=0;
	$t=1;
	
	while ($numero<$nmes) 
	{
		$efe=explode("/", $fila_fecha[$numero]);
		$mes=$efe[0];
		$anio=$efe[1];
		
		$con=$conexion1->query("declare @fechai  datetime ='$desde'

declare @fechaf datetime ='$hasta'
SELECT E.EMPRESA, E.BODEGA, E.NOMBRE,  E.TIPO_ARTICULO,
SUM(E.CANTIDAD) CANTIDAD, SUM(E.PRECIO_VENTA) PRECIO_VENTA,
MES, AÑO
from 
(

SELECT     consny.documento_pos.fch_hora_cobro,  CONSNY.DOC_pos_linea.documento,CONSNY.DOC_pos_linea.linea, consny.DOC_POS_LINEA.ARTICULO, CASE consny.doc_pos_linea.tipo WHEN 'F' THEN consny.DOC_POS_LINEA.CANTIDAD ELSE consny.DOC_POS_LINEA.CANTIDAD * - 1 END AS CANTIDAD, 
                         CASE consny.doc_pos_linea.tipo WHEN 'F' THEN ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA - consny.DOC_POS_LINEA.DESCUENTO_LINEA + consny.DOC_POS_LINEA.TOTAL_IMPUESTO1, 2) 
                         ELSE ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA - consny.DOC_POS_LINEA.DESCUENTO_LINEA + consny.DOC_POS_LINEA.TOTAL_IMPUESTO1, 2) * - 1 END AS PRECIO_VENTA, 
                         MONTH(consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS MES, YEAR(consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS AÑO, consny.ARTICULO.DESCRIPCION AS NOMBRE_ARTICULO, 
                         CASE consny.ARTICULO.CLASIFICACION_1 WHEN 'DETALLE' THEN 'DETALLE' ELSE 'MAYOREO' END AS TIPO_ARTICULO, consny.DOC_POS_LINEA.BODEGA, consny.BODEGA.NOMBRE, 
                         CASE LEFT(consny.DOC_POS_LINEA.BODEGA, 1) WHEN 'C' THEN 'CARISMA' WHEN 'E' THEN 'EVER' WHEN 'N' THEN 'NERY' ELSE 'BODEGA' END AS EMPRESA
FROM            consny.DOC_POS_LINEA INNER JOIN
                         consny.DOCUMENTO_POS ON consny.DOC_POS_LINEA.DOCUMENTO = consny.DOCUMENTO_POS.DOCUMENTO AND consny.DOC_POS_LINEA.TIPO = consny.DOCUMENTO_POS.TIPO AND 
                         consny.DOC_POS_LINEA.CAJA = consny.DOCUMENTO_POS.CAJA INNER JOIN
                         consny.ARTICULO ON consny.DOC_POS_LINEA.ARTICULO = consny.ARTICULO.ARTICULO INNER JOIN
                         consny.BODEGA ON consny.DOC_POS_LINEA.BODEGA = consny.BODEGA.BODEGA
WHERE        (consny.DOCUMENTO_POS.FCH_HORA_COBRO BETWEEN DATEADD(MI, 1, @fechai) AND DATEADD(MI, 1439, @fechaf)) and CONSNY.ARTICULO.CLASIFICACION_1='DETALLE' and month(consny.DOCUMENTO_POS.FCH_HORA_COBRO)='$mes' and year(consny.DOCUMENTO_POS.FCH_HORA_COBRO)='$anio' and consny.DOC_POS_LINEA.BODEGA='$bodegas'

) AS E

GROUP BY 
E.EMPRESA, E.BODEGA, E.NOMBRE,  E.TIPO_ARTICULO,
MES, AÑO   
ORDER BY bodega,año,mes")or die($conexion1->error());

$fcon=$con->FETCH(PDO::FETCH_ASSOC);

$con1=$conexion1->query("declare @fechai  datetime ='$desde'

declare @fechaf datetime ='$hasta'
SELECT E.EMPRESA, E.BODEGA, E.NOMBRE,  E.TIPO_ARTICULO,
SUM(E.CANTIDAD) CANTIDAD, SUM(E.PRECIO_VENTA) PRECIO_VENTA,
MES, AÑO
from 
(

SELECT     consny.documento_pos.fch_hora_cobro,  CONSNY.DOC_pos_linea.documento,CONSNY.DOC_pos_linea.linea, consny.DOC_POS_LINEA.ARTICULO, CASE consny.doc_pos_linea.tipo WHEN 'F' THEN consny.DOC_POS_LINEA.CANTIDAD ELSE consny.DOC_POS_LINEA.CANTIDAD * - 1 END AS CANTIDAD, 
                         CASE consny.doc_pos_linea.tipo WHEN 'F' THEN ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA - consny.DOC_POS_LINEA.DESCUENTO_LINEA + consny.DOC_POS_LINEA.TOTAL_IMPUESTO1, 2) 
                         ELSE ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA - consny.DOC_POS_LINEA.DESCUENTO_LINEA + consny.DOC_POS_LINEA.TOTAL_IMPUESTO1, 2) * - 1 END AS PRECIO_VENTA, 
                         MONTH(consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS MES, YEAR(consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS AÑO, consny.ARTICULO.DESCRIPCION AS NOMBRE_ARTICULO, 
                         CASE consny.ARTICULO.CLASIFICACION_1 WHEN 'DETALLE' THEN 'DETALLE' ELSE 'MAYOREO' END AS TIPO_ARTICULO, consny.DOC_POS_LINEA.BODEGA, consny.BODEGA.NOMBRE, 
                         CASE LEFT(consny.DOC_POS_LINEA.BODEGA, 1) WHEN 'C' THEN 'CARISMA' WHEN 'E' THEN 'EVER' WHEN 'N' THEN 'NERY' ELSE 'BODEGA' END AS EMPRESA
FROM            consny.DOC_POS_LINEA INNER JOIN
                         consny.DOCUMENTO_POS ON consny.DOC_POS_LINEA.DOCUMENTO = consny.DOCUMENTO_POS.DOCUMENTO AND consny.DOC_POS_LINEA.TIPO = consny.DOCUMENTO_POS.TIPO AND 
                         consny.DOC_POS_LINEA.CAJA = consny.DOCUMENTO_POS.CAJA INNER JOIN
                         consny.ARTICULO ON consny.DOC_POS_LINEA.ARTICULO = consny.ARTICULO.ARTICULO INNER JOIN
                         consny.BODEGA ON consny.DOC_POS_LINEA.BODEGA = consny.BODEGA.BODEGA
WHERE        (consny.DOCUMENTO_POS.FCH_HORA_COBRO BETWEEN DATEADD(MI, 1, @fechai) AND DATEADD(MI, 1439, @fechaf)) and CONSNY.ARTICULO.CLASIFICACION_1!='DETALLE' and month(consny.DOCUMENTO_POS.FCH_HORA_COBRO)='$mes' and year(consny.DOCUMENTO_POS.FCH_HORA_COBRO)='$anio' and consny.DOC_POS_LINEA.BODEGA='$bodegas'

) AS E

GROUP BY 
E.EMPRESA, E.BODEGA, E.NOMBRE,  E.TIPO_ARTICULO,
MES, AÑO   
ORDER BY bodega,año,mes")or die($conexion1->error());
$fcon1=$con1->FETCH(PDO::FETCH_ASSOC);

	$empresa=$fcon['EMPRESA'];
	$bodega=$fcon['BODEGA'];
	$nombre=$fcon['NOMBRE'];
	$detalle=$fcon['PRECIO_VENTA'];
	$mayoreo=$fcon1['PRECIO_VENTA'];
	$ncon=$con->rowCount();
	$ncon1=$con1->rowCount();
	//echo "<script>alert('$bodegas | $mes | $anio |$ncon |$ncon1 | $desde | $hasta')</script>";
	if($detalle=='')
	{
		$detalle=0;
	}
	if($mayoreo=='')
	{
		$mayoreo=0;
	}
	$convert=$conexion1->query("declare @detalle varchar(50)='$detalle'; declare @mayoreo varchar(50)='$mayoreo'; select convert(decimal(10,2),@detalle) as detalle,convert(decimal(10,2),@mayoreo) as mayoreo")or die($conexion1->error());
	$fconvert=$convert->FETCH(PDO::FETCH_ASSOC);
	$detalle=$fconvert['detalle'];
	$mayoreo=$fconvert['mayoreo'];

	$edetalle=explode(".", $detalle);
	if($edetalle[0]=='')
	{
		if($edetalle[1]=='')
		{
			$detalle="0.00";
		}else
		{
			$detalle="0.$edetalle[1]";
		}
	}
		$emayoreo=explode(".", $mayoreo);
		if($emayoreo[0]=='')
		{
			if($emayoreo[1]=='')
			{
				$mayoreo="0.00";
			}else
			{
				$mayoreo="0.$emayoreo[1]";
			}
		}
	
	if($ncon==0 and $ncon1==0)
	{
		//echo "<script>alert('$bodegas $mes $anio')</script>";
	

	}
	
if($interacion==0)
{
	$ce=$conexion1->query("select consny.bodega.bodega,consny.bodega.nombre,usuariobodega.esquema from consny.bodega inner join usuariobodega on consny.bodega.bodega=usuariobodega.bodega where consny.bodega.bodega='$bodegas'")or die($conexion1->error());
		$fce=$ce->FETCH(PDO::FETCH_ASSOC);
		$bodega=$fce['bodega'];
		$nombre=$fce['nombre'];
		$empresa=$fce['esquema'];


	ECHO "<TR class='tre'>
	<td>$empresa</td>
	<td>$bodega</td>
	<td>$nombre</td>
	<td>$detalle</td>
	<td>$mayoreo</td>";
	$interacion=1;

}else
{
	echo "  <td>$detalle</td>
			<td>$mayoreo</td>";

}


		
	

		
	
	$numero++;
	$total=$total+$detalle+$mayoreo;
}
echo "<td>$total</td></tr>";


	

	
		
	
//fin fechas
//echo "<td>$total</td></tr>";
}//fin bodegas



}//fin else de n


}//fin post
?>
</body>
</html>