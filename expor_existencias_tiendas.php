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

$bodega=$_GET['bodega'];
if($bodega=='')
	{
		$c=$conexion2->query("select codigo,COUNT(codigo) as cantidad,SUM(isnull(lbs,0)+isnull(peso,0)) as peso,bodega from registro where (fecha_desglose='' or fecha_desglose is null) and activo is null and bodega not like '0%' and tipo!='C1' group by codigo,bodega")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select codigo,COUNT(codigo) as cantidad,SUM(isnull(lbs,0)+isnull(peso,0)) as peso,bodega from registro where (fecha_desglose='' or fecha_desglose is null) and activo is null and bodega='$bodega' and tipo!='C1' group by codigo,bodega")or die($conexion2->error());
	
	}

	$n=$c->rowCount();
if($n==0)
{
	echo "<script>alert('NO FUE POSIBLE EXPORTAR A EXCEL NO SE ENCONTRO NINGUN REGISTRO')</script>";
}else
{
	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=existencias_tiendas.xls');
	echo "<table border='1' style='border-collapse:collapse; width:98%;' cellpadding='7'>";
	echo "<tr>
		<td>BODEGA</td>
		<td>CLASIFICACION</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>PESO</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$cod=$f['codigo'];
		$cantidad=$f['cantidad'];
		$bodega=$f['bodega'];
		$peso=$f['peso'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$art=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];
		$clasi=$fca['CLASIFICACION_2'];
		echo "<tr>
		<td>$bodega</td>
		<td>$clasi</td>
		<td>$art</td>
		<td>$desc</td>
		<td>$cantidad</td>
		<td>$peso</td>
	</tr>";
	}
}
?>
<script>
	window.close();
</script>