<meta charset="utf-8">
<?php
//echo "NO DISPONIBLE...";
$desde=$_GET['d'];
$hasta=$_GET['h'];
if($desde=='' or $hasta=='')
{
	echo "<script>alert('SELECCIONE AMBAS FECHAS')</script>";
	echo "<script>location.replace('reporte_averia.php')</script>";
}else
{
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
header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=report-averia.xls');
$c=$conexion2->query("select corelativo,tienda from averia where  tipo='D' and fecha_desglose between '$desde' and '$hasta'  group by corelativo,tienda order by tienda")or die($conexion1->error());
echo "<table border='1'>";
echo "<tr>
<td>CORELATIVO</td>
<td>FECHA DESGLOSE</td>
<td>BODEGA</td>
<td>DOCUMENTO_INV</td>
<td>CANTIDAD</td>
<td>TOTAL</td>
</tr>";
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$corelativo=$f['corelativo'];
	$q=$conexion2->query("select * from averia where corelativo='$corelativo' and tipo='D'")or die($conexion2->error());
	$cant=0;
	$total=0;
	while($fq=$q->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$fq['articulo'];
		$tienda=$fq['tienda'];
		$fecha=$fq['fecha_desglose'];
		$documento=$fq['documento_inv'];
		$cant=$cant + $fq['cantidad'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);

		$precio=$fca['PRECIO_REGULAR'];
		$t=$precio * $fq['cantidad'];
		$total=$total + $t; 
	}
	$cb=$conexion1->query("select * from consny.bodega where bodega='$tienda'")or die($conexion1->error());
	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);

	echo "<tr>
<td>$corelativo</td>
<td>$fecha</td>

<td>$tienda: ".$fcb['NOMBRE']."</td>
<td>$documento</td>
<td>$cant</td>

<td>$total</td>
</tr>";
}
}
?>