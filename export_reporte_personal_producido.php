<style>

	body{
		font-family: Consolas, monaco, monospace;
	}
</style>
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
$c=$conexion2->query("select producido from registro where (concat(month(fecha_documento),'-',year(fecha_documento))='$desde' or concat(month(fecha_documento),'-',year(fecha_documento))='$hasta') and tipo='p' group by producido order by producido
")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('IMPOIBLE EXPORTAR INTENTELO NUEVAMENTE')</script>";
			echo "<script>window.close()</script>";
		}else
		{
			header('Content-type:application/xls');
			header('Content-Disposition: attachment; filename=Reporte_personal_producido.xls');
			echo "<table border='1' style='border-collapse:collapse; width:98%; margin-left:1.2%;'>";
			echo "<tr style='text-align:center;'>
				<td rowspan='3'>PRODUCIDO POR</td>
				<td colspan='4'>MES</td>
				<td rowspan='2' colspan='2'>TOTAL</td>
			</tr>";
			echo "<tr>
				<td colspan='2'>$desde</td>
				<td colspan='2'>$hasta</td>
			</tr>";
			echo "<tr>
				<td>CANTIDAD</td>
				<td>PESO</td>
				<td>CANTIDAD</td>
				<td>PESO</td>
				<td>CANTIDAD</td>
				<td>PESO</td>
			</tr>";
			$tf_cant=0;
			$tf_peso=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$producido=$f['producido'];
			$q=$conexion2->query("select count(producido) as cantidad,sum(lbs) as peso from registro where concat(month(fecha_documento),'-',year(fecha_documento))='$desde' and producido='$producido' and tipo='p' group by producido
")or die($conexion2->error());
			$fq=$q->FETCH(PDO::FETCH_ASSOC);
			$cant=$fq['cantidad'];
			$peso=$fq['peso'];
			if($cant=='')
			{
				$cant=0;
			}
			if($peso=='')
			{
				$peso=0;
			}

			//--
			$q1=$conexion2->query("select count(producido) as cantidad,sum(lbs) as peso from registro where concat(month(fecha_documento),'-',year(fecha_documento))='$hasta' and producido='$producido' and tipo='p' group by producido
")or die($conexion2->error());
			$fq1=$q1->FETCH(PDO::FETCH_ASSOC);
			$cant1=$fq1['cantidad'];
			$peso1=$fq1['peso'];
			if($cant1=='')
			{
				$cant1=0;
			}
			if($peso1=='')
			{
				$peso1=0;
			}
			$total_cantidad=$cant+$cant1;
			$total_peso=$peso1+$peso;
			//--
			echo "<tr>
			<td>$producido</td>
			<td>$cant</td>
			<td>$peso</td>
			<td>$cant1</td>
			<td>$peso1</td>
			<td>$total_cantidad</td>
			<td>$total_peso</td>";
			$tf_cant=$tf_cant+$total_cantidad;
			$tf_peso=$tf_peso+$total_peso;
			///no cuadra<------------------
			$qf=$conexion2->query("select count(*) as cantidad,sum(lbs) as peso from registro where concat(month(fecha_documento),'-',year(fecha_documento))='$desde' and tipo='p'")or die($conexion2->error());

			$qf1=$conexion2->query("select count(*) as cantidad,sum(lbs) as peso from registro where concat(month(fecha_documento),'-',year(fecha_documento))='$hasta' and tipo='p'")or die($conexion2->error());
			$fqf=$qf->FETCH(PDO::FETCH_ASSOC);
			$fqf1=$qf1->FETCH(PDO::FETCH_ASSOC);


		}
		echo "<tr>
		<td>TOTAL</td>
		<td>".$fqf['cantidad']."</td>
		<td>".$fqf['peso']."</td>
		<td>".$fqf1['cantidad']."</td>
		<td>".$fqf1['peso']."</td>
		<td>$tf_cant</td><td>$tf_peso</td>
		</tr>";



		}
?>
<script>
	window.close();
</script>