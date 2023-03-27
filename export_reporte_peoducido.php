<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
<style>

	body{
		font-family: Consolas, monaco, monospace;
	}
</style>
<script>
	window.close();
</script>
</head>
<body>
<?php

$persona=$_GET['persona'];
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
?>

<?php
if($desde!='' and $hasta!='')
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

	header('Content-type:application/xls');
    
	header('Content-Disposition: attachment; filename=REPORTE-PRODUCIDO.xls');

	$c=$conexion2->query("select pruebabd.dbo.registro.producido,pruebabd.dbo.registro.fecha_documento,
EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,EXIMP600.consny.ARTICULO.CLASIFICACION_2,
SUM(pruebabd.dbo.registro.lbs) as peso,COUNT(EXIMP600.consny.ARTICULO.ARTICULO) as cantidad from 
pruebabd.dbo.registro inner join EXIMP600.consny.ARTICULO on pruebabd.dbo.registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO
where pruebabd.dbo.registro.tipo='p' and pruebabd.dbo.registro.fecha_documento between '$desde' and '$hasta' and pruebabd.dbo.registro.producido like '%$persona%'  group by 
pruebabd.dbo.registro.producido,pruebabd.dbo.registro.fecha_documento,EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,EXIMP600.consny.ARTICULO.CLASIFICACION_2 order by pruebabd.dbo.registro.producido,pruebabd.dbo.registro.fecha_documento,EXIMP600.consny.ARTICULO.CLASIFICACION_2,EXIMP600.consny.ARTICULO.ARTICULO
")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3></h3>";
	}else
	{
		
		echo "<table border='1' style='width:100%; border-collapse:collapse;'>";
		echo "<tr>
			<td>PERSONA</td>
			<td>FECHA</td>
			<td>CATEGORIA</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
			<td>PESO</td>
		</tr>";
		$tpeso=0; $tcant=0;
		while($fc=$c->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
			<td>".$fc['producido']."</td>
			<td>".$fc['fecha_documento']."</td>
			<td>".$fc['CLASIFICACION_2']."</td>
			<td>".$fc['ARTICULO']."</td>
			<td>".$fc['DESCRIPCION']."</td>
			<td>".$fc['cantidad']."</td>
			<td>".$fc['peso']."</td>
		</tr>";
		$tcant=$tcant + $fc['cantidad'];
		$tpeso=$tpeso + $fc['peso'];

		}

		echo "<tr>
			<td colspan='5'>TOTAL</td>
			<td>$tcant</td><td>$tpeso</td>
		</tr> </table>";
	}
}else
{
	echo "<script>alert('ERROR AL INTENTAR EXPORTAR LOS DATOS')</script>";
	echo "<script>window.close()</script>";

}
?>

</body>
</html>