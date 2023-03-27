<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php

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
    $bodega=str_replace('-', "'", base64_decode($_GET['bodega']));
    

    $c=$conexion2->query("select concat(EXIMP600.consny.BODEGA.bodega,':',EXIMP600.consny.BODEGA.nombre) as bodega,registro.barra,
registro.fecha_traslado,concat(EXIMP600.consny.articulo.articulo,':',eximp600.consny.articulo.descripcion) as art,
datediff(day,registro.fecha_traslado,GETDATE()) as dias,registro.fecha_desglose,registro.activo,
(isnull(registro.lbs,0)+isnull(registro.peso,0)) as peso,registro.barra,eximp600.consny.articulo.clasificacion_2 as familia from registro inner join eximp600.consny.bodega
on EXIMP600.consny.BODEGA.BODEGA=registro.bodega inner join eximp600.consny.articulo on registro.codigo=
eximp600.consny.articulo.articulo where registro.bodega  in($bodega) and 
(registro.fecha_desglose is  null or registro.fecha_desglose='') and registro.activo is null   order by dias desc")or die($conexion2->error());

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN FARDO DISPONIBLE</h3>";
	}else
	{
		header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=tiempo_fardos_en_tienda.xls');
		
		echo "<table border='1' style='border-collapse:collapse; width:100%;'>";

		echo "<tr>
			<td>#</td>
			<td>CODIGO BARRA</td>
			<td>FECHA RECIBIDO</td>
			<td>BODEGA</td>
			<td>FAMILIA</td>
			<td>ARTICULO</td>
			<TD>PESO</TD>
			<TD>DIAS EN TIENDA</TD>
		</tr>";
		$k=1;
		$tpeso=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
			<td>$k</td>
			<td>".$f['barra']."</td>
			<td>".$f['fecha_traslado']."</td>
			<td>".$f['bodega']."</td>
			<td>".$f['familia']."</td>
			<td>".$f['art']."</td>
			<TD>".$f['peso']."</TD>
			<TD>".$f['dias']."</TD>
		</tr>";
		$tpeso=$tpeso+$f['peso'];
		$k++;
		}

		echo "<tr>
			<td colspan='6'>TOTAL</td>
			<td>$tpeso</td>
			<td></td>
		</tr>";
	}

?>
<script>
	window.close();
</script>
</body>
</html>