<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
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
$tarjeta=$_GET['t'];
$desde=$_GET['d'];
$bodega=str_replace("-", "'", $_GET['bodega']);
$hasta=$_GET['h'];
if($tarjeta==1)
	{

		$c=$conexion1->query("SELECT        CUADRO_VENTA.BODEGA, consny.BODEGA.NOMBRE, CUADRO_VENTA.FECHA, CUADRO_VENTA.ESTADO, CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO, CUADRO_VENTA_DETALLE.CONCEPTO, 
                         CUADRO_VENTA_DETALLE.MONTO
FROM            CUADRO_VENTA INNER JOIN
                         CUADRO_VENTA_DETALLE ON CUADRO_VENTA.ID = CUADRO_VENTA_DETALLE.CUADRO_VENTA INNER JOIN
                         consny.BODEGA ON CUADRO_VENTA.BODEGA = consny.BODEGA.BODEGA
WHERE     CUADRO_VENTA_DETALLE.TIPO_TRANSACCION='SALIDA' AND   CUADRO_VENTA.FECHA between '$desde' and '$hasta' and CUADRO_VENTA.BODEGA in($bodega) and CUADRO_VENTA_DETALLE.CONCEPTO not  like '%FALTANTE DE REMESA%' order by 1,3")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("SELECT        CUADRO_VENTA.BODEGA, consny.BODEGA.NOMBRE, CUADRO_VENTA.FECHA, CUADRO_VENTA.ESTADO, CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO, CUADRO_VENTA_DETALLE.CONCEPTO, 
                         CUADRO_VENTA_DETALLE.MONTO
FROM            CUADRO_VENTA INNER JOIN
                         CUADRO_VENTA_DETALLE ON CUADRO_VENTA.ID = CUADRO_VENTA_DETALLE.CUADRO_VENTA INNER JOIN
                         consny.BODEGA ON CUADRO_VENTA.BODEGA = consny.BODEGA.BODEGA
WHERE   CUADRO_VENTA_DETALLE.TIPO_TRANSACCION='SALIDA' AND     CUADRO_VENTA.FECHA between '$desde' and '$hasta' and CUADRO_VENTA.BODEGA in($bodega) and CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO!='VENTA CON TARJETA' and CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO!='BITCOIN' and CUADRO_VENTA_DETALLE.CONCEPTO not  like '%FALTANTE DE REMESA%' order by 1,3")or die($conexion1->error());
	}

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO INFORMACION CON EL FILTRO DADO')</script>";
	}else
	{
		header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=reporte-flujo-diario.xls');
		echo "<table border='1' style='border-collapse:collapse; width:98%;'>";
		echo "<tr>
		<td>BODEGA</td>
		<td>NOMBRE</td>
		<td>FECHA</td>
		<td>ESTADO</td>
		<td>TIPO DOCUMENTO</td>
		<td>CONCEPTO</td>
		<td>MONTO</td>
		</tr>";
		$total=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
				<td>".$f['BODEGA']."</td>
				<td>".$f['NOMBRE']."</td>
				<td>".$f['FECHA']."</td>
				<td>".$f['ESTADO']."</td>
				<td>".$f['TIPO_DOCUMENTO']."</td>
				<td>".$f['CONCEPTO']."</td>
				<td>".$f['MONTO']."</td>
				</tr>";
				$total=$total+$f['MONTO'];
		}

		echo "<tr>
		<td COLSPAN='6'>TOTAL</td>
		<td>$total</td>
		</tr>";
		echo "</table>";
	}
?>
<script>
	window.close();
</script>
</body>
</html>