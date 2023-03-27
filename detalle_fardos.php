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
	<a href="#" onclick="cerrar()" style="color: white; text-decoration: none; float: right; margin-right: 1%;">CERRAR X</a><br>
	<div class="adentro" style="margin-left: 2.5%; height: 92%;">
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
WHERE        (consny.DOC_POS_LINEA.BODEGA = @bodega)  AND (consny.DOCUMENTO_POS.FCH_HORA_COBRO BETWEEN DATEADD(MI, 1, @fecha) AND DATEADD(MI, 1439, 
                         @fecha)) AND (consny.DOC_POS_LINEA.TIPO = 'F') and (consny.DOC_POS_LINEA.ARTICULO not like '0%' and consny.DOC_POS_LINEA.ARTICULO not like 'BC%')
")or die($conexion1->error());

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h1>FECHA:NO SE ENCONTRO NINGUN REGISTRO O LA FECHA: $fecha NO SINCRONIZADA</h1>";
	}else
	{
		echo "<table border='1' style='border-collapse:collapse; width:98%; margin-left: 1%;' cellpadding='8'>";

		$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$nom=$fcb['NOMBRE'];
		echo "<tr>
			<td colspan='11'>DETALLE FARDOS VENDIDOS $bodega: $nom FECHA: $fecha</td>
		</tr>";
		echo "<tr>
				<td>#</td>
				<td>DOCUMENTO</td>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
				<td>TIPO</td>
				<td>PRECIO</td>
				<td>CANTIDAD</td>
				<td>DESCUENTO</td>
				<td>PORCENTAJE</td>
				<td>FECHA Y HORA COBRO</td>
				<td>TOTAL</td>
			</tr>";
			$k=0;
			$total=0;
			$tf=0;
			$tcantidad=0;
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$k++;
				$doc=$f['DOCUMENTO'];
				$art=$f['ARTICULO'];
				$desc=$f['DESCRIPCION'];
				$tipo=$f['TIPO'];
				$precio=$f['PRECIO'];
				$cantidad=$f['CANTIDAD'];
				$descuento=$f['DESCUENTO'];
				$porcentaje=$f['PORCENTAJE'];
				$fechaH=$f['FCH_HORA_COBRO'];
				$cq=$conexion1->query("declare @precio varchar(50)=$precio; declare @cantidad varchar(50)=$cantidad;declare @descuento varchar(50)=$descuento;declare @porcentaje varchar(50)=$porcentaje; select convert(decimal(10,2),@precio) as precio,convert(decimal(10,2),@cantidad) as cantidad,convert(decimal(10,2),@descuento) as descuento,convert(decimal(10,2),@porcentaje) as porcentaje")or die($conexion1->error());

				$fcq=$cq->FETCH(PDO::FETCH_ASSOC);
				$precio=$fcq['precio'];
				$cantidad=$fcq['cantidad'];
				$descuento=$fcq['descuento'];
				$porcentaje=$fcq['porcentaje'];
				$total=$precio - $descuento;
				$tf=$tf+ $total;

				$edescuento=explode(".", $descuento);
				if($edescuento[0]=='')
				{
					$descuento="0.$edescuento[1]";
				}

				$eporcentaje=explode(".", $porcentaje);
				if($eporcentaje[0]=='')
				{
					$porcentaje="0.$eporcentaje[1]";
				}
				if($tipo=='F')
				{
					$tipo='TICKET';
				}
				echo "<tr>
				<td>$k</td>
				<td>$doc</td>
				<td>$art</td>
				<td>$desc</td>
				<td>$tipo</td>
				<td>$precio</td>
				<td>$cantidad</td>
				<td>$descuento</td>
				<td>$porcentaje</td>
				<td>$fechaH</td>
				<td>$total</td>
			</tr>";
			$tcantidad=$tcantidad + $cantidad;

			}
			echo "<tr>
			<td colspan='6'>TOTAL</td>
			<td>$tcantidad</td>
			<td colspan='3'></td>
			<td>$tf</td>
			</tr>";
	}
	?>
	</div>
</div>
</body>
</html>