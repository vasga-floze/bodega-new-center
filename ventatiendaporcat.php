<!DOCTYPE html>
<html>
<head>
	<title></title>
<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
	$(document).ready(function(){
		
		$("#formu1").hide();
		$(".detalle").hide();
		if($("#i").val()==1)
		{
			$("#formu1").show();
		}
		
	});
</script>
</head>
<body>
<div class="detalle" style="width: 110%; margin-left: -2%; background-color: white; opacity: 0.3;">
<img src="load1.gif" width="25%" height="25%;" style="position: sticky; top:0; margin-left: 33%; margin-top: 13%;">
</div>

<?php

error_reporting(0);
include("conexion.php");

$v=$_GET['v'];
$s=$_GET['s'];
if($v==1)
{
	$usuario=$_SESSION['usuario'];
	$bod=$_SESSION['bodega'];

}

$usuario=$_SESSION['usuario'];
$bodega=$_SESSION['bodega'];

$c=$conexion1->query("select * from dbo.usuariobodega where usuario='$usuario'")or die($conexion1->error());
$usere=substr($usuario, 0);
echo "<input type='hidden' name='user' id='user' value='$usere[0]'>";
$f=$c->FETCH(PDO::FETCH_ASSOC);
$bd=$f['BASE'];
$hamachi=$f['HAMACHI'];
ini_set('max_execution_time', 10000000);
date_default_timezone_set('America/El_Salvador');
echo "<h3>REPORTE DE VENTA POR CATEGORIA</h3>" ;


?>

<form method="POST" onautocomplete="off()">
DESDE: <input type="date" name="desde" class="text" style="width: 15%; padding: 0.5%;" required>
HASTA: <input type="date" name="hasta" class="text" style="width: 15%; padding: 0.5%;" required>

<select name="bodega" class="text" style="width: 20%;">
	<option value="">BODEGA</option>


<?php
	$cb=$conexion1->query("SELECT consny.BODEGA.BODEGA, consny.BODEGA.NOMBRE, USUARIOBODEGA.HAMACHI
FROM consny.BODEGA INNER JOIN
 USUARIOBODEGA ON consny.BODEGA.BODEGA = USUARIOBODEGA.BODEGA
WHERE (consny.BODEGA.NOMBRE NOT LIKE '%(N)%') AND (consny.BODEGA.NOMBRE LIKE 'TIENDA%')
AND HAMACHI IS NOT NULL ORDER BY consny.BODEGA.NOMBRE")or die($conexion1->error());

	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$fcb['BODEGA'];
		$nom=$fcb['NOMBRE'];
		$ham=$fcb['HAMACHI'];

		echo "<option value='$bod'>$bod: $nom $ham</option>";
	}
	$usuario=$_SESSION['usuario'];
			//intentar conexión con la tienda 
			try 
			{
				$conexion_tienda=new PDO("sqlsrv:Server=$ham;Database=$bd", "sa", "$0ftland");
			}
			catch(PDOException $e) 
			{
        die("<h1>!!ERROR!! NO SE LOGRO CONECTAR CON LA BASE DE DATOS VERIFICA SI HAMACHI SE ENCUENTRA ENCENDIDO Y ACTUALIZA LA PAGINA</h1>");
    	}
?>
</select>
<input type="submit" name="btn" value="CONSULTAR" class="boton2">
</form>
<div id="formu1">
<hr style="background-color: skyblue; height:1px;">
<br>
<hr style="background-color: skyblue; height:1px;">
</div>

<?php
	if($_POST)
	{
			echo $bd;
			$venta=$conexion_tienda->query("DECLARE @FI date='$FI'
DECLARE @FF date='$FF'
SELECT        FECHA,  CASE WHEN CLASIFICACION_2 IN ('VARIOS', 'OTROS', 'GANCHOS', 'JUGUETES', 'JUGUETE') 
                         THEN 'OTROS' WHEN CLASIFICACION_2 IN ('CARTERAS', 'CARTERA', 'CINCHOS', 'GORRAS', 'ZAPATOS') THEN 'ACCESORIOS' ELSE CLASIFICACION_2 END AS CLASIFICACION,
						 ROUND(SUM(PRECIO_VENTA - DESCUENTO_LINEA + TOTAL_IMPUESTO1) / 1.13, 2) AS VENTA
                         
FROM            (SELECT        CONSNY.DOCUMENTO_POS.DOCUMENTO, CONSNY.DOCUMENTO_POS.TIPO, CONSNY.DOCUMENTO_POS.CAJA, CONSNY.DOCUMENTO_POS.CORRELATIVO, RIGHT(CONSNY.DOCUMENTO_POS.DOCUMENTO, 5) 
                                                    AS NUMERO, CASE CONSNY.DOCUMENTO_POS.TIPO WHEN 'D' THEN TOTAL_PAGAR * - 1 ELSE TOTAL_PAGAR END AS TOTAL_PAGAR, 
                                                    CASE WHEN CONSNY.DOCUMENTO_POS.DOCUMENTO LIKE '%R1%' THEN 'R1' WHEN CONSNY.DOCUMENTO_POS.DOCUMENTO LIKE '%R2%' THEN 'R2' WHEN CONSNY.DOCUMENTO_POS.DOCUMENTO LIKE '%R3%'
                                                     THEN 'R3' WHEN CONSNY.DOCUMENTO_POS.DOCUMENTO LIKE '%R4%' THEN 'R4' ELSE 'FAC' END AS RESOLUCION, CONVERT(date, CONSNY.DOCUMENTO_POS.FCH_HORA_COBRO) AS FECHA, 
                                                    CONSNY.DOCUMENTO_POS.CAJERO AS TIENDA, CONSNY.CAJA_POS.BODEGA, CONSNY.DOC_POS_LINEA.ARTICULO, 
                                                    CASE CONSNY.DOC_POS_LINEA.TIPO WHEN 'F' THEN CONSNY.DOC_POS_LINEA.CANTIDAD ELSE CONSNY.DOC_POS_LINEA.CANTIDAD * - 1 END AS CANTIDAD, 
                                                    CASE CONSNY.DOC_POS_LINEA.TIPO WHEN 'F' THEN CONSNY.DOC_POS_LINEA.PRECIO_VENTA ELSE CONSNY.DOC_POS_LINEA.PRECIO_VENTA * - 1 END AS PRECIO_VENTA, 
                                                    CASE CONSNY.DOC_POS_LINEA.TIPO WHEN 'F' THEN CONSNY.DOC_POS_LINEA.DESCUENTO_LINEA ELSE CONSNY.DOC_POS_LINEA.DESCUENTO_LINEA * - 1 END AS DESCUENTO_LINEA, 
                                                    CASE CONSNY.DOC_POS_LINEA.TIPO WHEN 'F' THEN CONSNY.DOC_POS_LINEA.TOTAL_IMPUESTO1 ELSE CONSNY.DOC_POS_LINEA.TOTAL_IMPUESTO1 * - 1 END AS TOTAL_IMPUESTO1, 
                                                    CONSNY.ARTICULO.DESCRIPCION, CONSNY.ARTICULO.CLASIFICACION_2
                          FROM            CONSNY.DOCUMENTO_POS INNER JOIN
                                                    CONSNY.CAJA_POS ON CONSNY.DOCUMENTO_POS.CAJA = CONSNY.CAJA_POS.CAJA AND CONSNY.DOCUMENTO_POS.CAJA_COBRO = CONSNY.CAJA_POS.CAJA INNER JOIN
                                                    CONSNY.DOC_POS_LINEA ON CONSNY.DOCUMENTO_POS.DOCUMENTO = CONSNY.DOC_POS_LINEA.DOCUMENTO AND CONSNY.DOCUMENTO_POS.TIPO = CONSNY.DOC_POS_LINEA.TIPO AND 
                                                    CONSNY.DOCUMENTO_POS.CAJA = CONSNY.DOC_POS_LINEA.CAJA INNER JOIN
                                                    CONSNY.ARTICULO ON CONSNY.DOC_POS_LINEA.ARTICULO = CONSNY.ARTICULO.ARTICULO
                          WHERE        (CONSNY.DOCUMENTO_POS.FCH_HORA_COBRO between dateadd(second,1,convert(datetime,'$FI')) and dateadd(second,86399,convert(datetime,'$FF'))) AND (CONSNY.DOCUMENTO_POS.ESTADO_COBRO = 'C')) AS derivedtbl_1
GROUP BY FECHA, CASE WHEN CLASIFICACION_2 IN ('VARIOS', 'OTROS', 'GANCHOS', 'JUGUETES', 'JUGUETE') THEN 'OTROS' WHEN CLASIFICACION_2 IN ('CARTERAS', 'CARTERA', 'CINCHOS', 'GORRAS', 'ZAPATOS') 
                         THEN 'ACCESORIOS' ELSE CLASIFICACION_2 END, CAJA
ORDER BY FECHA,2") or die($conexion2->error());
echo"<table border='1' cellpadding='10' style=' border-collapse:collapse; width:100%;'>";
	echo "<tr>
	<td>FECHA</td>
	<td>CLASIFICACION</td>
	<td>VENTA</td>";

	}
?>
</body>
</html>