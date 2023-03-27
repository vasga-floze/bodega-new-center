<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
ini_set('max_execution_time', 10000000);
date_default_timezone_set('America/El_Salvador');
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
        die("ERROR DE CONEXION CON EL SERVICIO SQL " . $e->getMessage());
    }

$bodega=base64_decode($_GET['bodega']);
$desde=$_GET['d'];
$hasta=$_GET['h'];
if($bodega=='')
	{
		$c=$conexion1->query("select CUADRO_VENTA.FECHA,CUADRO_VENTA.MONTO_USUARIO,CONCAT(consny.BODEGA.BODEGA,': ',consny.BODEGA.NOMBRE) as 
bodega,CUADRO_VENTA.CAJA from CUADRO_VENTA
inner join consny.BODEGA on consny.BODEGA.BODEGA=CUADRO_VENTA.BODEGA where CUADRO_VENTA.FECHA between 
'$desde' and'$hasta' group by CUADRO_VENTA.FECHA,CUADRO_VENTA.MONTO_USUARIO,
consny.BODEGA.BODEGA,consny.BODEGA.NOMBRE,CUADRO_VENTA.CAJA order by consny.BODEGA.NOMBRE
")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("select CUADRO_VENTA.FECHA,CUADRO_VENTA.MONTO_USUARIO,CONCAT(consny.BODEGA.BODEGA,': ',consny.BODEGA.NOMBRE) as 
bodega,CUADRO_VENTA.CAJA from CUADRO_VENTA
inner join consny.BODEGA on consny.BODEGA.BODEGA=CUADRO_VENTA.BODEGA where CUADRO_VENTA.FECHA between 
'$desde' and'$hasta' and cuadro_venta.bodega in($bodega) group by CUADRO_VENTA.FECHA,CUADRO_VENTA.MONTO_USUARIO,
consny.BODEGA.BODEGA,consny.BODEGA.NOMBRE,CUADRO_VENTA.CAJA order by consny.BODEGA.NOMBRE
")or die($conexion1->error());
	}
	
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
	}else
	{
		
		header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=reporte_gastos'.$desde.'-'.$hasta.'.xls');

		echo "<table border='1' cellpadding='10' width='99%' style='border-collapse:collapse;'>";
		echo "<tr>
			<td rowspan='2'>FECHA</td>
			<td rowspan='2'>BODEGA</td>
			<td rowspan='2'>TOTAL VENTA</td>
			<TD rowspan='2'>OTROS INGRESOS</TD>
			<td rowspan='2'>TOTAL INGRESOS</td>
			<td colspan='4' style='text-align:center;'>SALIDAS</td>
			<td rowspan='2'>TOTAL REMESADO</td>
		</tr>";
		echo "<tr>
			<td>GASTOS</td>
			<TD>BITCOIN</TD>
			<TD>VENTA CON TARJETA</TD>
			<TD>FALTANTE</TD>
			</tr>";
		$tbitcoin=0;
			$tgastos=0;
			$tarjeta=0;
			$ttotal_ingreso=0;
			$toingreso=0;
			$tmonto_usuario=0;
			$ttotal=0;
			$tfaltante=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$caja=$f['CAJA'];
			$fecha=$f['FECHA'];
			$monto_usu=$f['MONTO_USUARIO'];
			$bode=$f['bodega'];
			$tmonto_usuario=$tmonto_usuario+$monto_usu;
			//otro ingresos
			$ci=$conexion1->query("select SUM(isnull(CUADRO_VENTA_DETALLE.MONTO,0)) AS MONTO
from CUADRO_VENTA_DETALLE inner join CUADRO_VENTA on CUADRO_VENTA.ID=CUADRO_VENTA_DETALLE.CUADRO_VENTA where
CUADRO_VENTA.CAJA='$caja' and CUADRO_VENTA_DETALLE.TIPO_TRANSACCION='INGRESO' AND CUADRO_VENTA.FECHA='$fecha'")or die($conexion1->error());
			$fci=$ci->FETCH(PDO::FETCH_ASSOC);
			$otro_ingresos=$fci['MONTO'];
			if($otro_ingresos=='')
			{
				$otro_ingresos="0.00";
			}
			
			$total_ingresos=$monto_usu+$otro_ingresos;
			$ttotal_ingreso=$ttotal_ingreso+$total_ingresos;
			$toingreso=$toingreso+$otro_ingresos;
			//fin otros ingresos
			//BITCOIN
			$cb=$conexion1->query("select case(CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO)
when 'BITCOIN' then 'BITCOIN'
WHEN 'VENTA CON TARJETA' THEN 'VENTA CON TARJETA'
ELSE 'GASTOS' END AS TIPO_DOCUMENTO,SUM(MONTO) as MONTO
from CUADRO_VENTA_DETALLE INNER JOIN CUADRO_VENTA ON CUADRO_VENTA.ID=CUADRO_VENTA_DETALLE.CUADRO_VENTA
WHERE TIPO_DOCUMENTO='BITCOIN' AND CUADRO_VENTA.CAJA='$caja' AND CUADRO_VENTA.FECHA='$fecha' GROUP BY case(CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO)
when 'BITCOIN' then 'BITCOIN'
WHEN 'VENTA CON TARJETA' THEN 'VENTA CON TARJETA'
ELSE 'GASTOS' END 
")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bitcoin=$fcb['MONTO'];
			$tbitcoin=$tbitcoin+$bitcoin;

			//FIN BITCOIN

			//VENTA CON TARJETA
			$cvt=$conexion1->query("select case(CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO)
when 'BITCOIN' then 'BITCOIN'
WHEN 'VENTA CON TARJETA' THEN 'VENTA CON TARJETA'
ELSE 'GASTOS' END AS TIPO_DOCUMENTO,SUM(MONTO) as MONTO
from CUADRO_VENTA_DETALLE INNER JOIN CUADRO_VENTA ON CUADRO_VENTA.ID=CUADRO_VENTA_DETALLE.CUADRO_VENTA
WHERE TIPO_DOCUMENTO='VENTA CON TARJETA' AND CUADRO_VENTA.CAJA='$caja' AND CUADRO_VENTA.FECHA='$fecha' GROUP BY case(CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO)
when 'BITCOIN' then 'BITCOIN'
WHEN 'VENTA CON TARJETA' THEN 'VENTA CON TARJETA'
ELSE 'GASTOS' END ")or die($conexion1->error());

			$fcvt=$cvt->FETCH(PDO::FETCH_ASSOC);
			$venta_tarjeta=$fcvt['MONTO'];
			$tarjeta=$tarjeta+$venta_tarjeta;
			//FIN CON TARJETA

			//OTROS GASTOS

			$cog=$conexion1->query("select SUM(isnull(CUADRO_VENTA_DETALLE.MONTO,0)) MONTO from CUADRO_VENTA_DETALLE inner join
CUADRO_VENTA on CUADRO_VENTA.ID=CUADRO_VENTA_DETALLE.CUADRO_VENTA where TIPO_TRANSACCION=
'SALIDA' and TIPO_DOCUMENTO not in ('BITCOIN','VENTA CON TARJETA') and CONCEPTO
not like '%FALTANTE DE REMESA%' and CUADRO_VENTA.CAJA='$caja' and CUADRO_VENTA.FECHA='$fecha' ")or die($conexion1->error());
			$fcog=$cog->FETCH(PDO::FETCH_ASSOC);
			
				$otros_gastos=$fcog['MONTO'];
			//FIN OTROS GASTOS

				//FALTANTE
		$cfal=$conexion1->query("select SUM(CUADRO_VENTA_DETALLE.MONTO) monto from CUADRO_VENTA_DETALLE inner join CUADRO_VENTA on 
CUADRO_VENTA.ID=CUADRO_VENTA_DETALLE.CUADRO_VENTA where CUADRO_VENTA.CAJA='$caja' AND
CUADRO_VENTA.FECHA='$fecha' AND TIPO_TRANSACCION='SALIDA' AND TIPO_DOCUMENTO='OTROS' and concepto like '%faltante de remesa%'")or die($conexion1->error());
		$fcfal=$cfal->FETCH(PDO::FETCH_ASSOC);

		//FIN FALTANTE
		//$total=$total+$fcfal['monto'];

			$tgastos=$tgastos+$otros_gastos;
			$salidas=$bitcoin+$venta_tarjeta+$otros_gastos+$fcfal['monto'];
			$total=$total_ingresos-$salidas;
			//$ttotal=$ttotal+$total;
			$otros_gastos=$otros_gastos+$fcfal['monto'];
			
			
			if($bitcoin=='')
			{
				$bitcoin="0.00";
			}
			if($otros_gastos=='')
			{
				$otros_gastos="0.00";
			}
			if($venta_tarjeta=='')
			{
				$venta_tarjeta="0.00";
			}

			
		$otros_gastos=$otros_gastos-$fcfal['monto'];
		//$total=$total+$fcfal['monto'];
		$ttotal=$ttotal+$total;

		if($fcfal['monto']=='')
		{
			$fcfal['monto']="0";
		}
			echo "<tr>
				<td>$fecha</td>
				<td>$bode</td>
				<td>$monto_usu</td>
				<td>$otro_ingresos</td>
				<td>$total_ingresos</td>
				<td>$otros_gastos</td>
				<td>$bitcoin</td>
				<td>$venta_tarjeta</td>
				<td>".$fcfal['monto']."</td>
				<td>$total</td>
			</tr>";
			$tfaltante=$tfaltante+$fcfal['monto'];


		}
		//$ttotal=$ttotal+$tfaltante;
		//$tgastos=$tgastos-$tfaltante;
		echo "<tr>
		<td colspan='2'>TOTAL</td>
		<td>$tmonto_usuario</td>
		<td>$toingreso</td>
		<td>$ttotal_ingreso</td>
		<td>$tgastos</td>
		<td>$tbitcoin</td>
		<td>$tarjeta</td>
		<td>$tfaltante</td>
		<td>$ttotal</td>
		</tr>";


		
		

	}
?>
<script>
	window.close();
</script>
</body>
</html>