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
$art=$_GET['art'];
ini_set('max_execution_time', 9000);

    try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }

    try {
        $conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("ERROR SE PERDIO CONEXION CON EL SERVIDOR: " . $e->getMessage());
    }
    session_start();

    if($bodega!='')
	{
		if($art=='')
		{
			$c=$conexion2->query("select * from registro where fecha_desglose between '$desde' and '$hasta' and bodega='$bodega' order by bodega,fecha_desglose,codigo ")or die($conexion2->error());	
		}else
		{
			$c=$conexion2->query("select * from registro where fecha_desglose between '$desde' and '$hasta' and bodega='$bodega' and codigo='$art' order by bodega,fecha_desglose,codigo")or die($conexion2->error());
		}

		
	}else
	{
		if($art=='')
		{
			$c=$conexion2->query("select * from registro where fecha_desglose between '$desde' and '$hasta' order by bodega,fecha_desglose,codigo")or die($conexion2->error());
		}else
		{
			$c=$conexion2->query("select * from registro where fecha_desglose between '$desde' and '$hasta' and codigo='$art' order by bodega,fecha_desglose,codigo")or die($conexion2->error());
		}
		
	}
	
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE PUDO EXPORTAR INTENTELO NUEVAMENTE')</script>";
		echo "<script>window.close();</script>";
	}


	if($n!=0)
	{

	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=reporte-desglose-'.$desde.'-'.$hasta.'-'.$bodega.'.xls');
	echo "<table border='1' cellpadding='10' style=' border-collapse:collapse; width:100%;'>";
	echo "<tr>
	<td>#</td>
	<td>FAMILIA</td>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	<td>CODIGO DE BARRA</td>
	<td>PESO</td>
	<td>BODEGA</td>
	<td>FECHA DESGLOSE</td>
	<td>FECHA INGRESO (TRASL)</td>";
	if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['tipo']!=2)
	{
		echo "<td>UNIDADES</td><td>TOTAL</td>";
	}
	echo"</tr>";
	}
	$t=0;
	$num=0;
	$tcantidad=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$num++;
		$art=$f['codigo'];
		$barra=$f['barra'];
		$fecha=$f['fecha_desglose'];
		$fechat=$f['fecha_traslado'];
		$bodega=$f['bodega'];
		$idr=base64_encode($f['id_registro']);
		$id=$f['id_registro'];
		if($f['tipo']=='P')
		{
			$peso=$f['lbs'];
		}else
		{
			$peso=$f['peso'];
		}
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);

		$art=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];
		$familia=$fca['CLASIFICACION_2'];
		$cb=$conexion1->query("select concat(bodega,': ',nombre) as bodega from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bodegas=$fcb['bodega'];
		echo "<tr class='tre'>
		<td>$num</td>
	<td>$familia</td>
	<td>$art</td>
	<td>$desc</td>
	<td>$barra</td>
	<td>$peso</td>
	<td>$bodegas</td>
	<td>$fecha</td>
	<td>$fechat</td>";
	if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['tipo']!=2)
	{
		$cd=$conexion2->query("select convert(decimal(10,2),sum(precio * cantidad)) as total,sum(cantidad) as cantidad from desglose where registro='$id' group by registro
")or die($conexion2->error());
		$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
		$total=$fcd['total'];
		$cantidadd=$fcd['cantidad'];
		$tcantidad=$tcantidad+$cantidadd;
		$t=$t+$total;
		echo "<td>$cantidadd</td><td>$$total</td>";
	}

	echo "</tr>";

	}

	if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALAVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['tipo']!=2 and $t>0)
	{
		echo "<tr>
					<td colspan='9'>TOTAL</td>
					<td>$tcantidad</td>
					<td>$$t</td>
			</tr>
			  </table>";
	}


?>
<script>
	window.close();
</script>
</body>
</html>
