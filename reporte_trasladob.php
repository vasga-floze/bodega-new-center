<!DOCTYPE html>
<html>
<head>
	<title></title>	
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#caja").hide();
		})
	</script>
</head>
<body>
<div style="position: fixed; width: 100%; height: 120%; background-color: white;"id="caja">
	<img src="loadf.gif" style="margin-top: 15%; margin-left: 45%;" >
</div>

<?php
include("conexion.php");

?>
<form method="POST">
<select class="text" name="bodega" id="bodega" class="text" style="width: 20%;" required>
	<option value="">BODEGA</option>
	<?php
	$cb=$conexion1->query("select bodega,nombre from consny.bodega where nombre not like '%(N)%' order by nombre")or die($conexion1->error());
	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$fcb['bodega']; $nom=$fcb['nombre'];
		echo "<option value='$bod'>$nom</option>";
	}
	?>
</select>
<input type="date" name="desde" id="desde" class="text" style="width: 10%;">
<input type="date" name="hasta" id="hasta" class="text" style="width: 10%;" required>
<label><input type="checkbox" name="insumo" value="1">INCLUIR INSUMOS</label>
<select name="transaccion" id="transaccion" class="text"  style="width: 15%;">
	<option value="1">TRANSACCION</option>
	<option>ENTRADA</option>
	<option>SALIDA</option>
</select>
<select name="tipo" id="tipo" class="text" style="width: 15%;">
<option value="">TIPO TRASLADO</option>
<option>BODEGA A BODEGA</option>
<option>BODEGA A TIENDA</option>
<option>TIENDA A TIENDA</option>

</select>
<input type="submit" name="btn" value="GENERAR" class="boton3">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	error_reporting(0);
	
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
		echo "<br><a href='export_reporte_trasladob.php?d=$desde&&h=$hasta&&b=$bodega&&i=$insumo&&trans=$transaccion&&t=$tipo' target='_blank' style='margin-left:-4%;'>Exportar a Excel</a>"; 
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

}

?>
</body>
</html>