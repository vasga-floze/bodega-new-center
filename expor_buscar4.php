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

    $opc=$_GET['opc'];
    $cod=$_GET['cod'];
    $clasi=$_GET['clasi'];
    $d=$_GET['d'];
    $h=$_GET['h'];

    if($opc==1)
{
	if($clasi=='' and $cod=='' and $d!='' and $h!='')
	{
		$c=$conexion2->query("select codigo,count(codigo) as cantidad,sum(lbs) as libra,sum(und) as unidad from registro where fecha_documento between '$d' and '$h' and tipo='P' group by codigo")or die($conexion2->error());
	}


	//tabla resumen

	echo "<table border='1'>";
	echo "<tr>
		<td>CLASIFICACION</td>
		<td># FARDOS</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>TOTAL</td>
		<td>UNIDADES</td>
		<td>LIBRAS</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['codigo'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$precio=$fca['PRECIO_REGULAR'];
		$t=$f['unidad'] * $precio;
		echo "<tr>
		<td>".$fca['CLASIFICACION_1']."</td>
		<td>".$f['cantidad']."</td>
		<td>".$fca['ARTICULO']."</td>
		<td>".$fca['DESCRIPCION']."</td>
		<td>$t</td>
		<td>".$f['unidad']."</td>
		<td>".$f['libra']."</td>
	</tr>";
	}
}
?>