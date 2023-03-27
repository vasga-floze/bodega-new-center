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
if( $desde=='' or $hasta=='')
{
	echo "<script>alert('NO FUE POSIBLE EXPORTAR A EXCEL INTENTELO NUEVAMENTE')</script>";
	echo "<script>window.close();</script>";
}else
{

	ini_set('max_execution_time', 9000);
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
   
    
    header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=reporteliquidaciones.xls');
if($bodega!='')
{
	$c=$conexion2->query("select * from liquidaciones where bodega='$bodega' and fecha between'$desde' and '$hasta' order by bodega")or die($conexion2->error());
}else
{

	$c=$conexion2->query("select * from liquidaciones where fecha between'$desde' and '$hasta' order by bodega")or die($conexion2->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<script>alert('NO FUE POSIBLE EXPORTAR A EXCEL INTENTELO NUEVAMENTE')</script>";
	echo "<script>window.close();</script>";
}else
{

	echo "<table border='1' cellpadding='10' style='border-collapse:collapse; margin-left:0%; width:120%; margin-top:-6%;'>";
	echo "<tr style='text-align:center;'>
		<td rowspan='2'>BODEGA</td>
		<td rowspan='2'>AUTORIZADA POR</td>
		<td colspan='3'>ARTICULO ORIGEN</td>
		<td colspan='3'>ARTICULO DESTINO</td>
		<td rowspan='2'>CANTIDAD</td>
		<td rowspan='2'>DIGITADA POR</td>
		<td rowspan='2'>FECHA</td>
		<td rowspan='2'>TOTAL ORIGEN</td>
		<td rowspan='2'>TOTAL DESTINO</td>
		<td rowspan='2'>TOTAL LIQUIDACION</td>
		<td colspan='2'>DOCUMENTOS</td>
		<td rowspan='2'>OBSERVACION</td>
	</tr>";

	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>PRECIO</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>PRECIO</td>
		<td>LIQ-AJN</td>
		<td>LIQ-AJP</td>
	</tr>";
	$total_origen=0;
	$total_destino=0;
	$total_liquidacion=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art_o=$f['art_origen'];
		$art_d=$f['art_destino'];
		$fecha=$f['fecha'];
		$digitado=$f['digitado'];
		$cantidad=$f['cantidad'];
		$autoriza=$f['autoriza'];
		$precio_o=$f['precio_origen'];
		$precio_d=$f['precio_destino'];
		$ajn=$f['documento_inv_consumo'];
		$ajp=$f['documento_inv_ing'];
		$bo=$f['bodega'];
		$obs=$f['observacion'];
		$cb=$conexion1->query("select concat(bodega,':',nombre)as bodega from consny.bodega where bodega='$bo'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bo=$fcb['bodega'];
		$ca1=$conexion1->query("select * from consny.articulo where articulo='$art_o'")or die($conexion1->error());
		$fca1=$ca1->FETCH(PDO::FETCH_ASSOC);
		$desc_o=$fca1['DESCRIPCION'];
		$ca2=$conexion1->query("select * from consny.articulo where articulo='$art_d'")or die($conexion1->error());
		$fca2=$ca2->FETCH(PDO::FETCH_ASSOC);
		$desc_d=$fca2['DESCRIPCION'];
		$precio_o=explode(".", $precio_o);
		$e=substr($precio_o[1], 0);
		if($precio_o[0]=='')
		{
			$precio_o="0";
		}
			$precio_o="$precio_o[0].$e[0]$e[1]";
		
		
		$precio_d=explode(".", $precio_d);
		$d=substr($precio_d[1], 0);
		if($precio_d[0]=='')
		{
			$precio_d[0]='0';
		}
		$precio_d="$precio_d[0].$d[0]$d[1]";
		$total_origen=$precio_o * $cantidad;
		$total_destino=$precio_d * $cantidad;
		$total_liquidacion=$total_origen - $total_destino;
		echo "<tr>
		<td>$bo</td>
		<td>$autoriza</td>
	";
	?>
	<td style="mso-number-format:'@';">
	<?php echo "
		$art_o</td>
		<td>$desc_o</td>
		<td>$precio_o</td>";
		?>
	<td style="mso-number-format:'@';">
	<?php echo "
		$art_d</td>
		<td>$desc_d</td>
		<td>$precio_d</td>
		<td>$cantidad</td>
		<td>$digitado</td>
		<td>$fecha</td>
		<td>$total_origen</td>
		<td>$total_destino</td>
		<td>$total_liquidacion</td>
		<td>$ajn</td>
		<td>$ajp</td>
		<td>$obs</td>
	</tr>";
	}
}

}//fin else principal
?>
<script>
	window.close();
</script>
</body>
</html>