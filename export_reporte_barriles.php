<script>
	window.close();
</script>
<?php
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
$conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
$c=$conexion2->query("select fecha from trabajo where fecha between '$desde' and '$hasta' group by fecha order by fecha")or die($conexion2->error());
	$n=$c->rowCount();

	if($n==0)
	{
		echo "<script>alert('NO SE PUEDE EXPORTAR, NO SE ENCONTRO INFORMACION INTENTALO NUEVAMENTE')</script>";
	}else
	{
		header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=reporte-barriles-pro.xls');
		$car=$conexion2->query("select articulos from trabajo where fecha between '$desde' and '$hasta' group by articulos")or die($conexion2->error());
		$numero=0;
		$text='';
		while($fcar=$car->FETCH(PDO::FETCH_ASSOC))
		{
			$text.="<td>".$fcar['articulos']."</td>";
			$fila[$numero]=$fcar['articulos'];
			$numero++;
		}
		echo "<table border='1' cellpadding='10' style='border-collapse:collapse;' width='200%'>";
		echo "<tr>
		<td>FECHA</td>
		<td colspan='$numero' style='text-align:center;'>PRODUCTOS</td>
		<td>TOTAL</td>
		</tr>";
		echo "<tr><td>--</td>$text</tr>";
		$totalf=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$fecha=$f['fecha'];
			$num=0;
			$total=0;
			echo "<tr><td>$fecha</td>";
			while($num<$numero)
			{
				$articulos=$fila[$num];
				$qar=$conexion2->query("select isnull(SUM(peso),0.00) as peso from trabajo where fecha='$fecha' and articulos='$fila[$num]'
")or die($conexion2->error());
				$fqar=$qar->FETCH(PDO::FETCH_ASSOC);
				$peso=$fqar['peso'];
				$total=$total+$peso;
				$totalf=$totalf+$total;
				$texto=$fila[$num];
				echo "<td onclick='prueba($texto)'>$peso</td>";
				$num++;



			}
			echo "<td>$total</td></tr>";
		}
		echo "<tr><td>TOTAL</td>";
		$num=0;
		$t=0;
		while($num<$numero)
		{
			$ct=$conexion2->query("select isnull(SUM(peso),0) as peso from trabajo where fecha between '$desde' and '$hasta' and articulos='$fila[$num]'
")or die($conexion2->error());
			
			$fct=$ct->FETCH(PDO::FETCH_ASSOC);
			$totales=$fct['peso'];
			echo "<td>$totales</td>";
			$t=$t+$totales;
			$num++;
		}
		echo "<td>$t</td></tr>";

		

	}
?>

<script>
	window.close();
</script>