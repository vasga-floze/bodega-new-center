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
<div style="display: none;">
	<?php
	include("conexion.php");
	$fecha=$_GET['fecha'];
	$bodega=$_GET['bodega'];
	?>
</div>
<div class="detalle">
	<a href="#" onclick="cerrar()" style="color: white; float: right; margin-right: 1.5%;" >CERRAR X</a><br>
	<div class="adentro" style="margin-left: 2.5%;">
	<?php
	$c=$conexion1->query("declare @fecha datetime ='$fecha'
declare @bodega nvarchar(4)='$bodega'

SELECT        consny.DOC_POS_LINEA.DOCUMENTO, consny.DOC_POS_LINEA.TIPO, consny.DOC_POS_LINEA.ARTICULO, consny.ARTICULO.DESCRIPCION, consny.DOC_POS_LINEA.CANTIDAD, 
                         ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA * 1.13, 2) AS PRECIO, ROUND(consny.DOC_POS_LINEA.DESCUENTO_LINEA * 1.13, 2) AS DESCUENTO,
                                        (ROUND(consny.DOC_POS_LINEA.DESCUENTO_LINEA * 1.13, 2)/ ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA * 1.13, 2))*100 AS PORCENTAJE, 
                                         consny.DOCUMENTO_POS.FCH_HORA_COBRO
FROM            consny.DOC_POS_LINEA INNER JOIN
                         consny.DOCUMENTO_POS ON consny.DOC_POS_LINEA.DOCUMENTO = consny.DOCUMENTO_POS.DOCUMENTO AND consny.DOC_POS_LINEA.TIPO = consny.DOCUMENTO_POS.TIPO AND 
                         consny.DOC_POS_LINEA.CAJA = consny.DOCUMENTO_POS.CAJA INNER JOIN
                         consny.ARTICULO ON consny.DOC_POS_LINEA.ARTICULO = consny.ARTICULO.ARTICULO
WHERE        (consny.DOC_POS_LINEA.BODEGA = @bodega) AND (consny.DOC_POS_LINEA.DESCUENTO_LINEA > 0) AND (consny.DOCUMENTO_POS.FCH_HORA_COBRO BETWEEN DATEADD(MI, 1, @fecha) AND DATEADD(MI, 1439, 
                         @fecha)) AND (consny.DOC_POS_LINEA.TIPO = 'F')
")or die($conexion1->error());

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h1 style='TEXT-ALIGN:CENTER;'>FECHA: $fecha NO SINCRONIZADA...</h1>";
	}else
	{
		$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$nom=$fcb['NOMBRE'];
		echo "<table border='1' style='border-collapse:collapse; width:98%; margin-left:1.5%; margin-bottom:0.5%;' cellpadding='8'>";
		echo "<tr>
			<td colspan='9'>DETALLE DESCUENTOS DE $bodega: $nom FECHA: $fecha</td>
		</tr>";
		echo "<tr>
			<td>DOCUMENTO</td>
			<td>TIPO</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
			<td>PRECIO</td>
			<td>DESCUENTO</td>
			<td>PORCENTAJE</td>
			<td>FECHA Y HORA COBRO</td>
		</tr>";
		$t=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$doc=$f['DOCUMENTO'];
			$tipo=$f['TIPO'];
			$art=$f['ARTICULO'];
			$desc=$f['DESCRIPCION'];
			$cantidad=$f['CANTIDAD'];
			$precio=$f['PRECIO'];
			$descuento=$f['DESCUENTO'];
			$porcentaje=$f['PORCENTAJE'];
			$fechaH=$f['FCH_HORA_COBRO'];
			$t=$t + $descuento;
			$cantidad=number_format($cantidad,2,'.',2);
			$precio=number_format($precio,2,'.',2);
			$descuento=number_format($descuento,2,'.',2);
			$porcentaje=number_format($porcentaje,2,'.',2);
			$qf=$conexion1->query("declare @cantidad varchar(50)=$cantidad; declare @precio varchar(50)=$precio; declare @descuento varchar(50)=$descuento; declare @porcentaje varchar(50)=$porcentaje; select convert(decimal(10,2),@cantidad) as cantidad,convert(decimal(10,2),@precio) as precio,convert(decimal(10,2),@descuento) as descuento,convert(decimal(10,2),@porcentaje) as porcentaje")or die($conexion1->error());
			$fqf=$qf->FETCH(PDO::FETCH_ASSOC);
			$cantidad=$fqf['cantidad'];
			$precio=$fqf['precio'];
			$descuento=$fqf['descuento'];
			$porcentaje=$fqf['porcentaje'];
			$ecantidad=explode(".", $cantidad);
			if($ecantidad[0]=='')
			{
				$cantidad="0.$ecantidad[1]";
			}

			$eprecio=explode(".", $precio);
			if($eprecio[0]=='')
			{
				$precio="0.$eprecio[1]";
			}

			$edescuento=explode(".", $descuento);
			if($edescuento[0]=='')
			{
				$descuento="0.$edescuento[1]";
			}
			$eporcentaje=explode(",", $porcentaje);
			if($eporcentaje[0]=='')
			{
				$porcentaje="0.$eporcentaje[1]";
			}
			echo "<tr>
			<td>$doc</td>
			<td>$tipo</td>
			<td>$art</td>
			<td>$desc</td>
			<td>$cantidad</td>
			<td>$precio</td>
			<td>$descuento</td>
			<td>$porcentaje</td>
			<td>$fechaH</td>
		</tr>";
		}
		$t=number_format($t,2,'.',2);
		echo "<tr>
			<td colspan='6'>TOTAL DESCUENTO</td>
			<td>$t</td>
			<td colspan='2'></td>
		</tr>";
	}

	?>
	</div>
</div>
</body>
</html>