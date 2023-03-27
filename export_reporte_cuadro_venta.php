<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>
<?php
$bodega=$_GET['bodega'];
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
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
session_start();
if($bodega=='' and $desde=='' and $hasta=='')
{
	echo "<script>alert('IMPOSIBLE EXPORTAR A EXCEL')</script>";
}else
{
	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=reporte_cuadro_venta_detalle.xls');
	
}
if($bodega=='')
	{
		$c=$conexion1->query("select usuariobodega.bodega from usuariobodega  inner join consny.bodega on usuariobodega.bodega=consny.bodega.bodega where usuariobodega.bodega like 'C%' or usuariobodega.bodega like 'E%' or usuariobodega.bodega like 'N%' and consny.bodega.nombre not like '%(N)%' group by usuariobodega.bodega order by usuariobodega.bodega")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("select bodega from usuariobodega where bodega='$bodega' group by bodega order by bodega")or die($conexion1->error());
	}
	
echo "<table border='1' style='border-collapse:collapse; width:110%;'>";
echo "<tr>
	<td width='8%'>FECHA</td>
	<td width='20%'>BODEGA</td>
	<td>MONTO TIENDA</td>
	<td>MONTO SISTEMA</td>
	<td>TOTAL INGRESOS</td>
	<td>TOTAL SALIDAS</td>
	<td>TOTAL DESCUENTOS</td>
	<td>MONTO LIQUIDO</td>
	<td>FARDOS VENDIDOS</td>
	<td>MONTO FARDOS</td>
	<td>TOTAL LIQUIDACIONES</td>
	<td>FARDOS ABIERTOS</td>
	<td>TOTAL DESGLOSE</td>
	<td>TOTAL AVERIAS</td>
	<td>TOTAL MERCA. NO VENDIBLE</td>
</tr>";
$fecha1=$desde;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		
		$bodega=$f['bodega'];
		$desde=$fecha1;
		//echo "<script>alert('$bodega')</script>";
		while($desde<=$hasta)
		{
			$cantidad_desglose=0;
			$total_desglose=0;
			$liquidacion=0;
			$averias=0;
			$merca=0;
			$monto_fardo=0;
			$monto_liquido=0;
			$monto_usuario=0;
			$monto_sistema=0;
			$monto_fardo=0;
			$fardos_vendidos=0;
			$descuentos=0;
			$fecha=$desde;
			$q=$conexion1->query("declare @fecha date='$fecha'

select dateadd(day,1,@fecha) as fecha
")or die($conexion1->error());
			$fq=$q->FETCH(PDO::FETCH_ASSOC);
			$desde=$fq['fecha'];
	//inicio cuadro venta/detalle
		$cc=$conexion1->query("select * from cuadro_venta where bodega='$bodega' and fecha='$fecha'")or die($conexion1->error());
		$nc=$cc->rowCount();
		$fc=$cc->FETCH(PDO::FETCH_ASSOC);
		$id=$fc['ID'];
		$monto_usuario=$fc['MONTO_USUARIO'];
		$monto_sistema=$fc['MONTO_SISTEMA'];
		$monto_liquido=$fc['MONTO_LIQUIDO'];
		$estado=$fc['ESTADO'];
		$monto_fardo=$fc['TOTAL_FARDO'];
		$fardos_vendidos=$fc['FARDOS_VENDIDOS'];
		$descuentos=$fc['DESCUENTO'];
		$cd=$conexion1->query("select sum(convert(decimal(10,2),monto))as monto,tipo_transaccion from cuadro_venta_detalle where cuadro_venta='$id' group by tipo_transaccion")or die($conexion1->error());
		$ingreso=0; $salida=0;
	while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
	{
		$tipo=$fcd['tipo_transaccion'];
		$monto=$fcd['monto'];
		if($tipo=='SALIDA')
		{
			$salida=$salida + $monto;
		}else
		{
			$ingreso=$ingreso + $monto;
		}
	}
	$monto_liquido=$monto_usuario+$ingreso;
	$monto_liquido=$monto_liquido -$salida;
	//FIN cuadro venta/detalle

	//INICIO LIQUIDACIONES
	$cl=$conexion2->query("select isnull(convert(decimal(10,2),sum((precio_origen * cantidad)-(precio_destino * cantidad))),0.00) as liquidacion from liquidaciones where fecha='$fecha' and bodega='$bodega'")or die($conexion2->error());
	$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
	$liquidacion=$fcl['liquidacion'];
	//FIN LIQUIDACIONES

	//inicio desgloses
	$cd=$conexion2->query("select(select count(*) from registro where fecha_desglose='$fecha' and bodega='$bodega')as cantidad, (select sum(precio*cantidad)as total from desglose where registro in(select id_registro from registro where fecha_desglose='$fecha' and bodega='$bodega')) as total
")or die($conexion2->error());

	$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
	$cantidad_desglose=$fcd['cantidad'];
	$total_desglose=$fcd['total'];


	//fin desglose

	//inicio averias
	$ca=$conexion2->query("select sum(convert(decimal(10,2),(precio * cantidad))) as total,tipo from averias where fecha='$fecha' and bodega='$bodega' group by tipo
")or die($conexion2->error());
while($fca=$ca->FETCH(PDO::FETCH_ASSOC))
{
	$tipo=$fca['tipo'];
	if($tipo=='AVERIA')
	{
		$averias=$averias + $fca['total'];
	}else
	{
		$merca=$merca + $fca['total'];
	}
}

	//fin averias
	
		if($monto_usuario=='')
		{
			$monto_usuario="0.00";
		}
		if($monto_sistema=='')
		{
			$monto_sistema="0.00";
		}
		if($descuentos=='')
		{
			$descuentos="0.00";
		}
		if($fardos_vendidos=='')
		{
			$fardos_vendidos="0.00";
		}
		if($monto_fardo=='')
		{
			$monto_fardo="0.00";
		}
		if($total_desglose=='')
		{
			$total_desglose="0.00";
		}
		if($ingreso==0)
		{
			$ingreso="0.00";
		}
		if($salida==0)
		{
			$salida="0.00";
		}
		if($monto_liquido==0)
		{
			$monto_liquido="0.00";
		}

		$eliquidacion=explode(".", $liquidacion);
		if($eliquidacion[0]=='')
		{
			if($eliquidacion[1]=='')
			{
				$liquidacion="0.00";
			}else
			{
				$liquidacion="0.$eliquidacion[1]";
			}
		}

		$emonto_fardo=explode(".", $monto_fardo);
		if($emonto_fardo[0]=='')
		{
			if($emonto_fardo[1]=='')
			{
				$monto_fardo="0.00";
			}else
			{
				$monto_fardo="0.$emonto_fardo[1]";
			}
		}
		$efardos_vendidos=explode(".", $fardos_vendidos);
		if($efardos_vendidos[0]=='')
		{
			if($efardos_vendidos[1]=='')
			{
				$fardos_vendidos="0.00";
			}else
			{
				$fardos_vendidos="0.$efardos_vendidos[1]";
			}
		}

		$edescuento=explode(".", $descuentos);
		if($edescuento[0]=='')
		{
			if($edescuento[1]=='')
			{
				$descuentos="0.00";
			}else
			{
				$descuentos="0.$edescuento[1]";
			}
		}

		$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bod="".$fcb['BODEGA'].": ".$fcb['NOMBRE']."";
		
	if($bodega!='CA16')
	{
		if((strtoupper($_SESSION['usuario'])=='OCAMPOS' or $_SESSION['tipo']==5) and $nc==0  )
		{
			echo "<tr style='background-color:#EAFD07;'>";

		}else
		{
			if(  (strtoupper($_SESSION['usuario'])=='OCAMPOS' or $_SESSION['tipo']==5) and $estado==0)
			{
				echo "<tr style='background-color:red;'>";
			}else
			{
				echo "<tr>";
			}
		}


	echo "
	<td>$fecha</td>
	<td>$bod</td>
	<td>$monto_usuario</td>
	<td>$monto_sistema</td>
	<td>$ingreso</td>
	<td>$salida</td>
	<td>$descuentos</td>
	<td>$monto_liquido</td>
	<td>$fardos_vendidos</td>
	<td>$monto_fardo</td>
	<td>$liquidacion</td>
	<td>$cantidad_desglose</td>
	<td>$total_desglose</td>
	<td>$averias</td>
	<td>$merca</td>
</tr>";
}


		}
	}
?>
<script>
	window.close();
</script>
</body>
</html>