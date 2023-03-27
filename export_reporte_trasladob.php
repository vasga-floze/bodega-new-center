<style>

	body{
		font-family: Consolas, monaco, monospace;
	}
</style>
<meta charset="utf-8">
<?php
error_reporting(0);
$tipo=$_GET['t']; $desde=$_GET['d']; $hasta=$_GET['h'];$bodega=$_GET['b'];
$transaccion=$_GET['trans'];
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
	if($tipo!='')
	{
		//echo "--->$tipo";
		if($transaccion==1)
		{
			$c=$conexion2->query("select case when eximp600.consny.articulo.descripcion like '%BISUTE%' THEN 'BISUTERIA/MISCELANEA'
when eximp600.consny.articulo.descripcion like '%MISCELANEA%' THEN 'BISUTERIA/MICELANIA'
when eximp600.consny.articulo.clasificacion_1='INSUMO' then 'INSUMO'
ELSE EXIMP600.consny.articulo.clasificacion_2 end as familia,
traslado.origen,traslado.destino,traslado.articulo,eximp600.consny.articulo.descripcion,
sum(isnull(registro.lbs,0)+isnull(registro.peso,0)) as peso,
count(traslado.articulo) as cantidad, case  when origen like 'SM%' and destino='CA00' then 'BODEGA A BODEGA' 
WHEN ORIGEN='CA00' AND DESTINO LIKE 'SM%' THEN 'BODEGA A BODEGA'
WHEN (ORIGEN='CA00' OR ORIGEN   LIKE 'SM%') THEN 'BODEGA A TIENDA'
WHEN (DESTINO='CA00' OR DESTINO LIKE 'SM%') AND ORIGEN!='CA00' AND 
ORIGEN NOT LIKE 'SM%'THEN 'TIENDA A BODEGA'
 else 'TIENDA' end as tipo, traslado.fecha,
case when origen='$bodega' then 'SALIDA' when destino='$bodega' then 'ENTRADA' end as transaccion
from traslado inner join registro on registro.id_registro=traslado.registro 
inner join eximp600.consny.articulo on traslado.articulo =eximp600.consny.articulo.articulo
where (traslado.origen='$bodega' or traslado.destino='$bodega') and fecha BETWEEN '$desde' and '$hasta'
and

 case  when origen like 'SM%' and destino='CA00' then 'BODEGA A BODEGA' 
WHEN ORIGEN='CA00' AND DESTINO LIKE 'SM%' THEN 'BODEGA A BODEGA'
WHEN (ORIGEN='CA00' OR ORIGEN   LIKE 'SM%') THEN 'BODEGA A TIENDA'
WHEN (DESTINO='CA00' OR DESTINO LIKE 'SM%') AND ORIGEN!='CA00' AND 
ORIGEN NOT LIKE 'SM%'THEN 'TIENDA A BODEGA'
 else 'TIENDA' end='$tipo'
group by traslado.origen,traslado.destino,traslado.articulo,
EXIMP600.consny.ARTICULO.descripcion,eximp600.consny.articulo.clasificacion_2,
eximp600.consny.articulo.articulo,traslado.fecha,
eximp600.consny.articulo.clasificacion_1")or die($conexion2->error());
		}else
		{
			$c=$conexion2->query("select case when eximp600.consny.articulo.descripcion like '%BISUTE%' THEN 'BISUTERIA/MISCELANEA'
when eximp600.consny.articulo.descripcion like '%MISCELANEA%' THEN 'BISUTERIA/MICELANIA'
when eximp600.consny.articulo.clasificacion_1='INSUMO' then 'INSUMO'
ELSE EXIMP600.consny.articulo.clasificacion_2 end as familia,
traslado.origen,traslado.destino,traslado.articulo,eximp600.consny.articulo.descripcion,
sum(isnull(registro.lbs,0)+isnull(registro.peso,0)) as peso,
count(traslado.articulo) as cantidad, case  when origen like 'SM%' and destino='CA00' then 'BODEGA A BODEGA' 
WHEN ORIGEN='CA00' AND DESTINO LIKE 'SM%' THEN 'BODEGA A BODEGA'
WHEN (ORIGEN='CA00' OR ORIGEN   LIKE 'SM%') THEN 'BODEGA A TIENDA'
WHEN (DESTINO='CA00' OR DESTINO LIKE 'SM%') AND ORIGEN!='CA00' AND 
ORIGEN NOT LIKE 'SM%'THEN 'TIENDA A BODEGA'
 else 'TIENDA' end as tipo, traslado.fecha,
case when origen='$bodega' then 'SALIDA' when destino='$bodega' then 'ENTRADA' end as transaccion
from traslado inner join registro on registro.id_registro=traslado.registro 
inner join eximp600.consny.articulo on traslado.articulo =eximp600.consny.articulo.articulo
where (traslado.origen='$bodega' or traslado.destino='$bodega') and fecha BETWEEN '$desde' and '$hasta'
and

 case  when origen like 'SM%' and destino='CA00' then 'BODEGA A BODEGA' 
WHEN ORIGEN='CA00' AND DESTINO LIKE 'SM%' THEN 'BODEGA A BODEGA'
WHEN (ORIGEN='CA00' OR ORIGEN   LIKE 'SM%') THEN 'BODEGA A TIENDA'
WHEN (DESTINO='CA00' OR DESTINO LIKE 'SM%') AND ORIGEN!='CA00' AND 
ORIGEN NOT LIKE 'SM%'THEN 'TIENDA A BODEGA'
 else 'TIENDA' end='$tipo'
 and 
 case when origen='$bodega' then 'SALIDA' when destino='$bodega' then 'ENTRADA' end='$transaccion'
group by traslado.origen,traslado.destino,traslado.articulo,
EXIMP600.consny.ARTICULO.descripcion,eximp600.consny.articulo.clasificacion_2,
eximp600.consny.articulo.articulo,traslado.fecha,
eximp600.consny.articulo.clasificacion_1")or die($conexion2->error());
		}
		
	}else
	{
		if($transaccion==1)
		{
			$c=$conexion2->query("select case when eximp600.consny.articulo.descripcion like '%BISUTE%' THEN 'BISUTERIA/MICELANIA'
when eximp600.consny.articulo.descripcion like '%MISCELANEA%' THEN 'BISUTERIA/MICELANIA'
when eximp600.consny.articulo.CLASIFICACION_1='INSUMO' THEN 'INSUMO'
ELSE EXIMP600.consny.articulo.clasificacion_2 end as familia,
traslado.origen,traslado.destino,traslado.articulo,eximp600.consny.articulo.descripcion,
sum(isnull(registro.lbs,0)+isnull(registro.peso,0)) as peso,
count(traslado.articulo) as cantidad, case  when origen like 'SM%' and destino='CA00' then 'BODEGA A BODEGA' 
WHEN ORIGEN='CA00' AND DESTINO LIKE 'SM%' THEN 'BODEGA A BODEGA'
WHEN (ORIGEN='CA00' OR ORIGEN   LIKE 'SM%') THEN 'BODEGA A TIENDA'
WHEN (DESTINO='CA00' OR DESTINO LIKE 'SM%') AND ORIGEN!='CA00' AND 
ORIGEN NOT LIKE 'SM%'THEN 'TIENDA A BODEGA'
 else 'TIENDA' end as tipo, traslado.fecha,
case when origen='$bodega' then 'SALIDA' when destino='$bodega' then 'ENTRADA' end as transaccion
from traslado inner join registro on registro.id_registro=traslado.registro 
inner join eximp600.consny.articulo on traslado.articulo =eximp600.consny.articulo.articulo
where (traslado.origen='$bodega' or traslado.destino='$bodega') and fecha BETWEEN '$desde' and '$hasta'
group by traslado.origen,traslado.destino,traslado.articulo,
EXIMP600.consny.ARTICULO.descripcion,eximp600.consny.articulo.clasificacion_2,
eximp600.consny.articulo.articulo,traslado.fecha,eximp600.consny.articulo.clasificacion_1
")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select case when eximp600.consny.articulo.descripcion like '%BISUTE%' THEN 'BISUTERIA/MICELANIA'
when eximp600.consny.articulo.descripcion like '%MISCELANEA%' THEN 'BISUTERIA/MICELANIA'
when eximp600.consny.articulo.CLASIFICACION_1='INSUMO' THEN 'INSUMO'
ELSE EXIMP600.consny.articulo.clasificacion_2 end as familia,
traslado.origen,traslado.destino,traslado.articulo,eximp600.consny.articulo.descripcion,
sum(isnull(registro.lbs,0)+isnull(registro.peso,0)) as peso,
count(traslado.articulo) as cantidad, case  when origen like 'SM%' and destino='CA00' then 'BODEGA A BODEGA' 
WHEN ORIGEN='CA00' AND DESTINO LIKE 'SM%' THEN 'BODEGA A BODEGA'
WHEN (ORIGEN='CA00' OR ORIGEN   LIKE 'SM%') THEN 'BODEGA A TIENDA'
WHEN (DESTINO='CA00' OR DESTINO LIKE 'SM%') AND ORIGEN!='CA00' AND 
ORIGEN NOT LIKE 'SM%'THEN 'TIENDA A BODEGA'
 else 'TIENDA' end as tipo, traslado.fecha,
case when origen='$bodega' then 'SALIDA' when destino='$bodega' then 'ENTRADA' end as transaccion
from traslado inner join registro on registro.id_registro=traslado.registro 
inner join eximp600.consny.articulo on traslado.articulo =eximp600.consny.articulo.articulo
where (traslado.origen='$bodega' or traslado.destino='$bodega') and fecha BETWEEN '$desde' and '$hasta' and
case when origen='$bodega' then 'SALIDA' when destino='$bodega' then 'ENTRADA' end='$transaccion'
group by traslado.origen,traslado.destino,traslado.articulo,
EXIMP600.consny.ARTICULO.descripcion,eximp600.consny.articulo.clasificacion_2,
eximp600.consny.articulo.articulo,traslado.fecha,eximp600.consny.articulo.clasificacion_1
")or die($conexion2->error());
	}
	


		}
		
	

$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO INFORMACION DISPONIBLE</h3>";
	}else
	{
		header('Content-type:application/xls');
    
		header('Content-Disposition: attachment; filename=REPORTE-TRASLADOS-'.$desde.'-'.$hasta.' BODEGA: '.$bodega.'.xls');
		 
		echo "<table border='1' cellpadding='8' style='border-collapse:collapse; width:140%;'>";
		echo "<tr>
		<td>FECHA</td>
		<td>TRANSACCION</td>
		<td>TIPO TRASLADDO</td>
		<td>ORIGEN</td>
		<td>DESTINO</td>
		<td>FAMILIA</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>TOTAL PESO</td>
		</tr>";
		$tc=0; $tp=0;

		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$origen=$f['origen'];
			$destino=$f['destino'];
			$cbo=$conexion1->query("select concat(nombre,'(',bodega,')') as bodega from consny.bodega where bodega='$origen'")or die($conexion1->error());
			$fcbo=$cbo->FETCH(PDO::FETCH_ASSOC);

			$cbd=$conexion1->query("select concat(nombre,'(',bodega,')') as bodega from consny.bodega where bodega='$destino'")or die($conexion1->error());
			$fcbd=$cbd->FETCH(PDO::FETCH_ASSOC);
			if($f['transaccion']=='SALIDA')
			{
				$f['cantidad']=$f['cantidad']*-1;
				$f['peso']=$f['peso']*-1;
			}
			
				if($insumo==1)
				{
					echo "<tr>
					<td>".$f['fecha']."</td>
					<td>".$f['transaccion']."</td>
					<td>".$f['tipo']."</td>
					<td>".$fcbo['bodega']."</td>
					<td>".$fcbd['bodega']."</td>
					<td>".$f['familia']."</td>
					<td>".$f['articulo']."</td>
					<td>".$f['descripcion']."</td>
					<td>".$f['cantidad']."</td>
					<td>".$f['peso']."</td>
					</tr>";
					$tc=$tc+$f['cantidad'];
					$tp=$tp+$f['peso'];
				}else if($insumo!=1 and $f['familia']!='INSUMO')
				{
					echo "<tr>
					<td>".$f['fecha']."</td>
					<td>".$f['transaccion']."</td>
					<td>".$f['tipo']."</td>
					<td>".$fcbo['bodega']."</td>
					<td>".$fcbd['bodega']."</td>
					<td>".$f['familia']."</td>
					<td>".$f['articulo']."</td>
					<td>".$f['descripcion']."</td>
					<td>".$f['cantidad']."</td>
					<td>".$f['peso']."</td>
					</tr>";
					$tc=$tc+$f['cantidad'];
					$tp=$tp+$f['peso'];
				}
				
			}
		
		
		
		echo "<tr>
		<td colspan='8'>TOTAL</td>
		<td>$tc</td>
		<td>$tp</td>
		</tr>
		</table>";
	}

?>
<script>
	window.close();
</script>