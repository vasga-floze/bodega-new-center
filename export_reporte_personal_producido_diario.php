<style>

	body{
		font-family: Consolas, monaco, monospace;
	}
</style>
<meta charset="utf-8">
<?php
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
$empleado=$_GET['empleado'];
if($desde=='' or $hasta=='')
{
	echo "<script>alert('ERROR AL EXPORTAR, INTENTELO DE NUEVO')</script>";
	echo "<script>window.close()</script>";
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
    if($empleado!='')
	{
		$c=$conexion2->query("select producido from registro where producido like '%$empleado%' and fecha_documento between '$desde' and '$hasta' and tipo='p' group by producido")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select producido from registro where  fecha_documento between '$desde' and '$hasta' and tipo='p' group by producido")or die($conexion2->error());
	}

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO PARA EXPORTAR')</script>";
		echo "<script>window.close()</script>";
	}else
	{
		header('Content-type:application/xls');
    
		header('Content-Disposition: attachment; filename=REPORTE-PRODUCIDO.xls');
		$cdias=$conexion1->query("select datediff(day,'$desde','$hasta')+1 as dias
")or die($conexion1->error());
		$fcdias=$cdias->FETCH(PDO::FETCH_ASSOC);
		$ndias=$fcdias['dias'];
		$ndias=$ndias*2;
		echo "<table border='1' style='border-collapse:collapse; width:210%;'>";
		echo "<tr style='text-align:center;'>
			<td rowspan='3' width='30%'>PRODUCIDO POR</td>
			<td  colspan='$ndias'>FECHA</td>
			<td rowspan='2'colspan='2' width='10%'>TOTAL</td>
		</tr>";

		$desde1=$desde;
		echo "<tr>";
		$nu=1;
		while($desde1<=$hasta)
		{
			echo "<td colspan='2' width='10%'>$desde1</td>";
			$query=$conexion1->query("declare @fecha date='$desde1';select dateadd(day,1,@fecha) as fecha")or die($conexion1->error());
			$fquery=$query->FETCH(PDO::FETCH_ASSOC);
			$desde1=$fquery['fecha'];
			$nu++;

			
		}
		echo "</tr>";
		$k=1;
		echo "<tr>";
		while($k<=$nu)
		{
			echo "<td width='5%'>CANTIDAD</td><td width='5%'>PESO</td>";
			$k++;
		}
		echo "</tr>";
		
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
		$pro=$f['producido'];
		echo "<tr><td>$pro</td>";
		$tpeso=0; $tcant=0;
		$desde2=$desde;
		while($desde2<=$hasta)
		{
			$con=$conexion2->query("select producido,count(producido) as cantidad,sum(lbs) as peso from registro where producido='$pro' and tipo='p' and fecha_documento='$desde2' group by producido")or die($conexion2->error());
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$cant=$fcon['cantidad'];
			$peso=$fcon['peso'];
			$tpeso=$tpeso + $peso;
			$tcant=$tcant + $cant;
			if($cant=='')
			{
				$cant=0;
			}
			if($peso=='')
			{
				$peso=0;
			}
			$cf=$conexion1->query("declare @fechas date='$desde2'; select dateadd(day,1,@fechas) as desde")or die($conexion1->error());
			$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
			$desde2=$fcf['desde'];
			echo "<td>$cant</td><td>$peso</td>";
		}
		if($tcant=='')
		{
			$tcant=0;
		}
		if($tpeso=='')
		{
			$tpeso=0;
		}
		echo "<td>$tcant</td><td>$tpeso</td></tr>";
	}

	echo "<tr><td>TOTAL</td>";
	$desde3=$desde;
	$cantf=0;
	$pesof=0;
	while($desde3<=$hasta)
	{
		$cfinal=$conexion2->query("select count(*) as cantidad,sum(lbs) as peso from registro where fecha_documento='$desde3' and producido like '%$empleado%' and tipo='p'")or die($conexion2->error());
		$fcfinal=$cfinal->FETCH(PDO::FETCH_ASSOC);
		$cantidad=$fcfinal['cantidad'];
		$peso=$fcfinal['peso'];
		$cantf=$cantf + $cantidad;
		$pesof=$pesof + $peso;
		if($cantidad=='')
		{
			$cantidad=0;
		}
		if($peso=='')
		{
			$peso=0;
		}
		echo "<td>$cantidad</td><td>$peso</td>";

		$conf=$conexion2->query("declare @fech date='$desde3'; select dateadd(day,1,@fech) as dia")or die($conexion2->error());
		$fconf=$conf->FETCH(PDO::FETCH_ASSOC);
		$desde3=$fconf['dia'];
	}
	echo "<td>$cantf</td><td>$pesof</td></tr>";


}




}//fin else validacion
?>