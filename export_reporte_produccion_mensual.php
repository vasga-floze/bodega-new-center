<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>
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
$c=$conexion2->query("select * from registro where fecha_documento between '$desde' and '$hasta' and tipo='P' and registro.estado!=2")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUNA PRODUCCION DEL RANGO DE FECHA DESDE: $desde HASTA: $hasta</h3>";
	}else
	{
		ini_set('max_execution_time', 9000);
header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=REPORTE-PRODUCCION-MENSUALDE:'.$desde.'-HASTA:'.$hasta.'.xls');
		echo "<table border='1' cellpadding='5' style='border-collapse:collapse; width:auto;'>";
		/*$consu=$conexion2->query("select month(fecha_documento) as mes,year(fecha_documento) as anio from registro where fecha_documento between '$desde' and '$hasta' and tipo='P' group by month(fecha_documento),year(fecha_documento) order by anio,mes")or die($conexion2->error());
		$consu1=$conexion2->query("select month(fecha_documento) as mes,year(fecha_documento) as anio from registro where fecha_documento between '$desde' and '$hasta' and tipo='P' group by month(fecha_documento),year(fecha_documento) order by anio,mes")or die($conexion2->error());

		
		$nconsu=count($consu1->fetchAll());*/
		$nconsu=0;
		$desde1=$desde;
		$fechas[0]='';
		$n=0;
		$nconsu=0;
		$text="<tr style='text-align:center;'>";
	while($desde1<=$hasta)
	{

		
		$query=$conexion2->query("select  
(select concat(month('$desde1'),'-',year('$desde1')))as mes,
(select dateadd(day,1,convert(date,'$desde1')))as fecha")or die($conexion2->error());

		$fquery=$query->FETCH(PDO::FETCH_ASSOC);
		$meses_a=$fquery['mes'];
		$desde1=$fquery['fecha'];

		if(in_array($meses_a, $fechas))
		{

		}else
		{
			
			$text.="<td colspan='3'>$meses_a</td>";
			$fechas[$n]=$meses_a;
			$n++;
			$nconsu++;
		}



	}

	$text.='</tr>';

		$cols=$nconsu*3;




		echo "<tr style='text-align:center;'>
			<td rowspan='3'>CATEGORIA</td>
			<td rowspan='3'>ARTICULO</td>
			<td rowspan='3' width='40%' style='text-align:center;'>DESCRIPCION</td>
			<td colspan='$cols'>FECHA</td>
			<td rowspan='2' colspan='3'>TOTAL GENERAL</td>
		</tr>";
		echo "$text";
		echo "<tr>";
		$k=0;
	//$nconsu=$nconsu/2;
	while($k<=$nconsu)
	{
		echo "
			<td>CANTIDAD</td>
			<td>PESO</td>
			<td>UNIDAD</td>
		";
		$k++;
	}
	echo "

	</tr>";//query que lleva el orden
	$consu2=$conexion2->query("select month(fecha_documento) as mes,year(fecha_documento) as anio from registro where fecha_documento between '$desde' and '$hasta' and tipo='p' and pruebabd.dbo.registro.estado!=2 group by month(fecha_documento),year(fecha_documento) order by anio,mes")or die($conexion2->error());

	$qc=$conexion2->query("select pruebabd.dbo.registro.codigo,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2 from pruebabd.dbo.registro inner join eximp600.consny.articulo on pruebabd.dbo.registro.codigo=eximp600.consny.articulo.articulo where pruebabd.dbo.registro.fecha_documento between '$desde' and '$hasta' and pruebabd.dbo.registro.tipo='P' and pruebabd.dbo.registro.estado!=2 group by pruebabd.dbo.registro.codigo,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2 order by eximp600.consny.articulo.clasificacion_2")or die($conexion2->error());

	while($fqc=$qc->FETCH(PDO::FETCH_ASSOC))
		{
			$cod=$fqc['codigo'];
			$desc=$fqc['descripcion'];
			$clasi=$fqc['clasificacion_2'];
			$num=0;
			$tcant=0;$tpeso=0;$tunidad=0;
		while($num<$n)
		{
			$item=explode("-", $fechas[$num]);
			$meses=$item[0];
			$anios=$item[1];
			$con=$conexion2->query("select eximp600.consny.articulo.clasificacion_2,eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,count(eximp600.consny.articulo.articulo) as cantidad,sum(pruebabd.dbo.registro.lbs) as peso,SUM(ISNULL(und,0)) as unidades from pruebabd.dbo.registro inner join eximp600.consny.articulo on eximp600.consny.articulo.articulo=pruebabd.dbo.registro.codigo where pruebabd.dbo.registro.tipo='P' and month(pruebabd.dbo.registro.fecha_documento)='$meses' and year(pruebabd.dbo.registro.fecha_documento)='$anios' and pruebabd.dbo.registro.codigo='$cod' and pruebabd.dbo.registro.tipo='P' and pruebabd.dbo.registro.estado!=2 group by eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2 order by eximp600.consny.articulo.clasificacion_2")or die($conexion2->error());
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$cant=$fcon['cantidad'];
			$peso=$fcon['peso'];
			$unidades=$fcon['unidades'];
			$tcant=$tcant+ $cant;
			$tpeso=$tpeso+$peso;
			$tunidad=$tunidad+$unidades;
			if($cant=='')
			{
				$cant="0";
			}
			if($peso=='')
			{
				$peso="0";
			}


			if($num==0)
			{
				echo "<tr>
					<td>$clasi</td>
					<td>$cod</td>
					<td>$desc</td>
					<td>$cant</td>
					<td>$peso</td>
					<td>$unidades</td>";
			}else
			{
				echo "<td>$cant</td><td>$peso</td><td>$unidades</td>";
			}

			$num++;

		}
		echo "<td>$tcant</td><td>$tpeso</td><td>$tunidad</td></tr>";
		}

		echo "<tr>
		<td colspan='3'>TOTAL $n</td>";
		$i=0;
		while($i<$n)
		{

			$me=explode("-", $fechas[$i]);
			$mes=$me[0];
			$anio=$me[1];
			//echo "<script>alert('$mes $anio')</script>";
			$cf=$conexion2->query("select count(*) as cantidad,sum(lbs) as peso,
			sum(isnull(und,0)) as und from registro where tipo='p' and month(fecha_documento)='$mes' and year(fecha_documento)='$anio' and registro.estado!=2")or die($conexion2->error());
			$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
			$cantidad=$fcf['cantidad'];
			$peso=$fcf['peso'];
			$und=$fcf['und'];
			echo "<td>$cantidad</td><td>$peso</td><td>$und</td>";
			$i++;
		}

		$cfinal=$conexion2->query("select count(*) as cantidad,sum(lbs)as peso, sum(isnull(und,0)) as tund from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and registro.estado!=2")or die($conexion2->error());
		$fcfinal=$cfinal->FETCH(PDO::FETCH_ASSOC);
		$cant=$fcfinal['cantidad'];
		$peso=$fcfinal['peso'];
		$ttund=$fcfinal['tund'];
		echo "<td>$cant</td><td>$peso</td><td>$ttund</td>";
		echo "</tr></table>";
	}
?>
<script>
	window.close();
</script>
</body>
</html>