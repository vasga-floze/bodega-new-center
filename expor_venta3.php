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
if($desde!='' and $hasta!='')
{
	$c=$conexion2->query("select documento_inv,cliente,fecha,isnull(articulo,registro.codigo)as articulo,sum(convert(decimal(10,2),isnull(venta.precio,0)) * isnull(venta.cantidad,1)) as total from venta left join registro on venta.registro=registro.id_registro where fecha between'$desde' and '$hasta' group by isnull(articulo,registro.codigo),documento_inv,cliente,fecha order by documento_inv
")or die($conexion2->error());

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE PUDO EXPORTAR INTENTELO NUEVAMENTE')</script>";
		echo "<script>location.replace('reporte_venta3.php')</script>";
	}else
	{
		header('Content-type:application/xls');
		header('Content-Disposition: attachment; filename=reportes-ventas.xls');
		echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:100%; margin-top:1.5%;'>";
		echo "<tr>
			<td colspan='6'><a href='expor_venta3.php?desde=$desde&&hasta=$hasta'>Exportar a Excel</a></td>
		</tr>";
		echo "<tr>
		<td>FECHA</td>
		<td>DOCUMENTO</td>
		<td>CLIENTE</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>TOTAL</td>
		</tr>";
		$tf=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$doc=$f['documento_inv'];
			$cliente=$f['cliente'];
			$total=$f['total'];
			$art=$f['articulo'];
			$fecha=$f['fecha'];
			$total=$f['total'];
			$ca=$conexion1->query("select articulo,descripcion from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		if($total>0 and $art!='')
		{

			echo "<tr>
		
		<td>$fecha</td>
		<td>$doc</td>
		<td>$cliente</td>";
		?>
		<td style="mso-number-format:'@';">
			<?php
			echo "
		".$fca['articulo']."</td>
		<td>".$fca['descripcion']."</td>
		<td>$total</td>
		</tr>";
		}
		$tf=$tf+$total;
		}
		echo "<tr>
		<td colspan='5'>TOTAL</td>
		<td>$tf</td>
		</tr>";
	}

}else
{
	echo "<script>alert('NO FUE POSIBLE EXPORTAR INTENTELO NUEVAMENTE')";
	echo "<script>location.replace('reporte_venta3.php')</script>";
}
?>
<script>
	window.close();
</script>