<!DOCTYPE html>
<html>
<head>
	<title></title>
		<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#img").hide();
		})
	</script>
</head>
<body>
<div style="width: 110%; height: 110%; position: fixed; float: center; margin-left: -5%; margin-top:-2%; background-color: white;" id="img">
<img src="loadf.gif" style="float: center; margin-left: 40%; margin-top: 15%; width: 20%; height: 22%;">
</div>
<?php
include("conexion.php");
if($_SESSION['tipo']==1 or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ')
{

}else
{
	echo "<script>location.replace('conexiones.php')</script>";
}
?>
<h3 style="text-decoration: underline; text-align: center;">REPORTE TRASLADOS</h3>
<center>
<form method="POST" style="margin-bottom: -5%;">
<label>DESDE: <input type="date" name="desde" class="text" style="padding: 0.5%; width: 10%;"></label>	
<label>HASTA: <input type="date" name="hasta" class="text" style="padding: 0.5%; width: 10%;"></label>	
<select name="bodega" class="text" style="padding: 0.5%; width:15%;">
	<option value="">BODEGA DESTINO</option>
<?php
	$c=$conexion1->query("select * from consny.bodega where bodega not like 'SM%' and nombre not like '%(N)%' order by bodega")or die($conexion1->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$f['BODEGA'];
		$nom=$f['NOMBRE'];
		echo "<option value='$bod'>$bod: $nom</option>";
	}
?>
</select>
<input type="submit" name="btn" value="GENERAR" class="btnfinal" style="padding: 0.5%; background-color: #B9D1C8; color: black;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);


	if($bodega=='')
	{
		$c=$conexion2->query("select eximp600.consny.articulo.articulo,count(eximp600.consny.articulo.articulo)as cantidad,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino,sum(convert(decimal(10,2),isnull(pruebabd.dbo.registro.lbs,0)+isnull(pruebabd.dbo.registro.peso,0))) as peso 
from pruebabd.dbo.registro inner join eximp600.consny.articulo on 
pruebabd.dbo.registro.codigo=eximp600.consny.articulo.articulo inner join pruebabd.dbo.traslado on 
pruebabd.dbo.registro.id_registro=pruebabd.dbo.traslado.registro where (pruebabd.dbo.traslado.origen like 'SM%' or traslado.origen='CA00')
and pruebabd.dbo.traslado.destino not like 'SM%' and pruebabd.dbo.traslado.fecha between '$desde' and '$hasta' 

group by eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino,eximp600.consny.articulo.clasificacion_2 order by traslado.destino,eximp600.consny.articulo.clasificacion_2")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select eximp600.consny.articulo.articulo,count(eximp600.consny.articulo.articulo)as cantidad,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino,sum(convert(decimal(10,2),isnull(pruebabd.dbo.registro.lbs,0)+isnull(pruebabd.dbo.registro.peso,0))) as peso 
from pruebabd.dbo.registro inner join eximp600.consny.articulo on 
pruebabd.dbo.registro.codigo=eximp600.consny.articulo.articulo inner join pruebabd.dbo.traslado on 
pruebabd.dbo.registro.id_registro=pruebabd.dbo.traslado.registro where (pruebabd.dbo.traslado.origen like 'SM%' or traslado.origen='CA00') 
and pruebabd.dbo.traslado.destino='$bodega' and pruebabd.dbo.traslado.fecha between '$desde' and '$hasta' 

group by eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino,eximp600.consny.articulo.clasificacion_2 order by traslado.destino,eximp600.consny.articulo.clasificacion_2")or die($conexion2->error());
	}

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE OBTUVO NINGUN RESULTADO</h3>";
	}else
	{
		echo "<a href='export_reporte_traslado.php?desde=$desde&&hasta=$hasta&&bodega=$bodega' target='_blank' style='float:left;'>Exportar a Excel</a><br>";
		echo "<table border='1' style='border-collapse:collapse; width:130%;'>";
		echo "<tr style='text-align:center;'>
			<td colspan='8'>RESULTADO DE LA BUSQUEDA DE $desde - $hasta $bodega</td>
		</tr>";
		echo "<tr>
			<td>EMPRESA</td>
			<td width='20%'>BODEGA ORIGEN</td>
			<td>BODEGA DESTINO</td>
			<td>ARTICULO</td>
			<td width='25%'>DESCRIPCION</td>
			<td>CATEGORIA</td>
			<td>CANTIDAD</td>
			<td>TOTAL PESO</td>
		</tr>";
		$tc=0; $tp=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$origen=$f['origen'];
			$destino=$f['destino'];
			$art=$f['articulo'];
			$cantidad=$f['cantidad'];
			$peso=$f['peso'];
			$art=$f['articulo'];
			$desc=$f['descripcion'];
			$clasi=$f['clasificacion_2'];
			$destino1=$destino;
			$cbd=$conexion1->query("select concat(consny.bodega.bodega,': ',consny.bodega.nombre) as bodega,usuariobodega.esquema from consny.bodega inner join usuariobodega on usuariobodega.bodega=consny.bodega.bodega where consny.bodega.bodega='$destino'")or die($conexion1->error());
			$fcbd=$cbd->FETCH(PDO::FETCH_ASSOC);
			$destino=$fcbd['bodega'];
			$empresa=$fcbd['esquema'];

			$cbo=$conexion1->query("select concat(bodega,': ',nombre) as bodega from consny.bodega where bodega='$origen'")or die($conexion1->error());
			$fcbo=$cbo->FETCH(PDO::FETCH_ASSOC);
			$origen=$fcbo['bodega'];
			if($destino1=='US01')
			{
				$empresa='USULUTAN';
			}
			if($bodega=='CA00')
			{
				$qd=$conexion1->query("select concat(bodega,': ',nombre) as bodega from consny.bodega where bodega='$bodega'")or die($conexion1->error());
				$fqd=$qd->FETCH(PDO::FETCH_ASSOC);
				$destino=$fqd['bodega'];
			}
			echo "<tr>
			<td>$empresa</td>
			<td>$origen</td>
			<td>$destino</td>
			<td>$art</td>
			<td>$desc</td>
			<td>$clasi</td>
			<td>$cantidad</td>
			<td>$peso</td>
		</tr>";
		$tc=$tc+$cantidad;
		$tp=$tp+$peso;
		}
		echo "<tr>
			<td colspan='6'>TOTAL</td>
			<td>$tc</td>
			<td>$tp</td>
		</tr>";
	}

}
//query dos bd para traslado
//select eximp600.consny.articulo.articulo,count(eximp600.consny.articulo.articulo)as cantidad,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino,sum(convert(decimal(10,2),isnull(pruebabd.dbo.registro.lbs,0)+isnull(pruebabd.dbo.registro.peso,0))) as peso from pruebabd.dbo.registro inner join eximp600.consny.articulo on pruebabd.dbo.registro.codigo=eximp600.consny.articulo.articulo inner join pruebabd.dbo.traslado on pruebabd.dbo.registro.id_registro=pruebabd.dbo.traslado.registro where pruebabd.dbo.traslado.origen like 'SM%' and pruebabd.dbo.traslado.destino not like 'SM%' and pruebabd.dbo.traslado.fecha between '2019-11-11' and '2020-11-11' group by eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino order by traslado.destino,eximp600.consny.articulo.clasificacion_2

?>
</body>
</html>