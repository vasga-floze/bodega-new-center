<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<meta charset="utf-8">
<?php
 try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("!!ERROR!! SE PERDIO CONEXION CON EL SERVIDOR: " );
    }

    try {
        $conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }
ini_set('max_execution_time', 9000);
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
$familia=$_GET['familia'];
$bo=$_GET['bo'];
$art=$_GET['art'];
 header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=valor_fardo.xls');
	if($familia!='' and $bo!='' and $art=='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and categoria='$familia' and bodega in($bo) order by codigo,fecha
")or die($conexion2->error());
	}else if($familia!='' and $bo=='' and $art=='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and categoria='$familia' order by codigo,fecha
")or die($conexion2->error());
	}else if($familia=='' and $bo=='' and $art=='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null order by codigo,fecha
")or die($conexion2->error());
	}else if($familia=='' and $bo!='' and $art=='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and bodega in($bo) order by codigo,fecha
")or die($conexion2->error());
	}else if($familia!='' and $bo!='' and $art!='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and bodega in($bo) and subcategoria like  (SELECT '%'+REPLACE('$art',' ','%')+'%') and categoria='$familia' order by codigo,fecha
")or die($conexion2->error());
	}else if($familia=='' and $bo!='' and $art!='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and bodega in($bo) and subcategoria like  (SELECT '%'+REPLACE('$art',' ','%')+'%') order by codigo,fecha
")or die($conexion2->error());
	}else if($familia!='' and $bo=='' and $art!='')
	{
		//echo "<script>alert('bghh')</script>";
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null  and subcategoria like  (SELECT '%'+REPLACE('$art',' ','%')+'%') and categoria='$familia' order by codigo,fecha
")or die($conexion2->error());
	}else if($familia=='' and $bo=='' and $art!='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null  and subcategoria like  (SELECT '%'+REPLACE('$art',' ','%')+'%') order by codigo,fecha
")or die($conexion2->error());

	}
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h2>NO ENCONTRO PRODUCCION EN LA FECHAS INGRESADAS</h2>";
	}else
	{
		
		echo "<table border='1' style='border-collapse:collapse;' cellspadding='10' width='100%'>";
		echo "<tr>
		<td>#</td>
		<td>FECHA</td>
			<td>FAMILIA</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>PESO</td>
			<td>BODEGA PRODUCCION</td>
			<td>UNIDADES BODEGA</td>
			<td>UNIDADES TIENDA</td>
			<td>PRECIO BODEGA</td>
			<td>PRECIO TIENDA</td>

		</tr>";
		$numeros=1;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$registro=$f['id_registro'];
			$art=$f['ARTICULO'];
			$desc=$f['DESCRIPCION'];
			$peso=$f['LIBRAS'];
			$familia=$f['FAMILIA'];
			$mes=$f['FECHA'];
			//bodega producccion
			$cbp=$conexion2->query("select * from registro where id_registro='$registro'")or die($conexion2->error());
			$fcbp=$cbp->FETCH(PDO::FETCH_ASSOC);
			$bodega=$fcbp['bodega_produccion'];
			//fin bodega produccion
			$cb=$conexion1->query("select concat(bodega,': ',nombre) as bodegas from consny.bodega where bodega='$bodega'")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bode=$fcb['bodegas'];

			//precio bodega
			$cd=$conexion2->query("select sum(isnull(detalle.cantidad,0)*(isnull(eximp600.consny.articulo.precio_regular,0))) as precio from detalle inner join EXIMP600.consny.articulo on detalle.articulo=EXIMP600.consny.ARTICULO.ARTICULO where detalle.registro='$registro'
")or die($conexion2->error());
			$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
			$precio_bodega=$fcd['precio'];
			//fin precio bodega

			//unidades de produccion y tienda
			$cup=$conexion2->query("select sum(cantidad) as cantidad from detalle where registro='$registro'")or die($conexion2->error());
			$fcup=$cup->FETCH(PDO::FETCH_ASSOC);
			$unidades_bodega=$fcup['cantidad'];
			$cut=$conexion2->query("select sum(cantidad) as cantidad from desglose where registro='$registro'")or die($conexion2->error());
			$fcut=$cut->FETCH(PDO::FETCH_ASSOC);
			$unidades_tienda=$fcut['cantidad'];
			if($unidades_bodega=='')
			{
				$qup=$conexion2->query("select * from registro where id_registro='$registro'")or die($conexion2->error());
				$fqup=$qup->FETCH(PDO::FETCH_ASSOC);
				$unidades_bodega=$fqup['und'];
			}
			if($unidades_tienda=='')
			{
				$unidades_tienda="0.00";
			}

			//fin unidades bodega y tienda

			//precio desglose

			$cdes=$conexion2->query("select sum(isnull(desglose.cantidad,0)*isnull(desglose.precio,0)) as precio from desglose  where registro='$registro'")or die($conexion2->error());
			$fcdes=$cdes->FETCH(PDO::FETCH_ASSOC);
			$precio_tienda=$fcdes['precio'];
			//fin precio desglose
			if($precio_tienda=='' or $precio_tienda==0)
			{
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

			$fcpromedio=$cpromedio->FETCH(PDO::FETCH_ASSOC);
			$precio_tienda=$fcpromedio['PRECIO_PROMEDIO'];
				//fin precio promedio
			}
			if($precio_bodega=='')
			{
				$precio_bodega='0.00';
			}
			echo "<tr>
			<td>$numeros</td>
			<td>$mes</td>
			<td>$familia</td>
			<td>$art</td>
			<td>$desc</td>
			<td>$peso</td>
			<td>$bode</td>
			<td>$unidades_bodega</td>
			<td>$unidades_tienda</td>
			<td>$precio_bodega</td>
			<td>$precio_tienda</td>
		</tr>";
		$numeros++;

		}
	}
?>
</body>
</html>