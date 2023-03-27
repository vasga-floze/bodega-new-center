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
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
$bodegas=$_GET['bodega'];
$transaccion=$_GET['tipo'];
if($transaccion=='')
	{
		if($bodegas!='')
		{
			$c=$conexion2->query("select * from averias where bodega='$bodegas' and fecha between '$desde' and '$hasta' order by bodega,fecha,tipo")or die($conexion2->error());
		}else
		{
			$c=$conexion2->query("select * from averias where fecha between '$desde' and '$hasta' order by bodega,fecha,tipo")or die($conexion2->error());
		}
		
	}else
	{
		if($bodegas!='')
		{
			$c=$conexion2->query("select * from averias where bodega='$bodegas' and fecha between '$desde' and '$hasta' and  tipo='$transaccion' order by bodega,fecha,tipo")or die($conexion2->error());
		}else
		{
			$c=$conexion2->query("select * from averias where fecha between '$desde' and '$hasta' and  tipo='$transaccion' order by bodega,fecha,tipo")or die($conexion2->error());
		}
		
	}
	$n=$c->rowCount();
	if($n==0)
	{

	}else
	{
		header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=reporteaverias.xls');
		echo "<table border='1'>";
		echo "<tr>
				<td>FECHA</td>
				<td>BODEGA</td>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
				<td>CANTIDAD</td>
				<td>PRECIO</td>
				<td>TOTAL</td>
				<td>TRANSACCION</td>
				<td>MARCHAMO</td>
				<td>DOCUMENTO</td>
				<td>OBSERVACION</td>
		</tr>";
		$tc=0; $tp=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$fecha=$f['fecha'];
			$bodega=$f['bodega'];
			$art=$f['articulo'];
			$cant=$f['cantidad'];
			$precio=$f['precio'];
			$marchamo=$f['marchamo'];
			$documento=$f['documento_inv'];
			$transaccion=$f['tipo'];
			$obs=$f['observacion'];
			$t=$cant * $precio;
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$art=$fca['ARTICULO'];
			$desc=$fca['DESCRIPCION'];
			$e=explode('.', $precio);
			if($e[0]=='')
			{
				$precio="0.$e[1]";
			}
			$tc=$tc + $cant;
			$tp=$tp=$tp+$t;
			$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bode=$fcb['BODEGA'];
			$nomb=$fcb['NOMBRE'];
			echo "<tr>
				<td>$fecha</td>
				<td>$bode: $nomb</td>";?>

				<td style="mso-number-format:'@';"><?php echo "$art</td>
				<td>$desc</td>
				<td>$cant</td>
				<td style="."mso-number-format:'@';".">$precio</td>
				<td>$t</td>
				<td>$transaccion</td>
				<td style="."mso-number-format:'@';".">$marchamo</td>
				<td>$documento</td>
				<td>$obs</td>
		</tr>";
		}
		echo "<tr>
		<td colspan='4'>TOTAL</td>
		<td>$tc</td>
		<td></td>
		<td>$tp</td>
		<td></td>
		<td></td>
		<td></td>

	</tr>";

	}
?>

<script>
	window.close();
</script>