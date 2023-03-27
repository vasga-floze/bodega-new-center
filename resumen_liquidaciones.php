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
error_reporting(0);
include("conexion.php");
$usuario=$_SESSION['usuario'];
$cb=$conexion1->query("select * from usuariobodega where usuario='$usuario'")or die($conexion1->error());
$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
$bodega=$fcb['BODEGA'];
$bodega=substr($bodega, 0);
if($bodega[0]=='S' or $usuario=='staana3' or $_SESSION['tipo']==3 or $_SESSION['tipo']==4)
{
	//echo "<script>alert('$bodega[0] $usuario')</script>";
	$cb=$conexion1->query("select * from consny.bodega where bodega not like 'SM%' and nombre not like '%(N)%' order by bodega")or die($conexion1->error());
}else
{

	$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
}
?>
<h3 style="text-align: center; text-decoration: underline;">REPORTE LIQUIDACIONES</h3>
<form method="POST" style="margin-left: 5%;">
<select name="bodega" class="text" style="width: 20%;">
	<option value="">BODEGA</option>
	<?php
		while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
		{
			$bod=$fcb['BODEGA'];
			$nom=$fcb['NOMBRE'];
			echo "<option value='$bod'>$bod: $nom</option>";
		}
	?>
</select>
<input type="date" name="desde" required class="text" style="width: 20%;">
<input type="date" name="hasta" required class="text" style="width: 20%;">

<input type="submit" name="btn" value="GENERAR" class="btnfinal" style="padding: 0.5%; background-color: #699C7A;">
</form>

<?php
if($_POST)
{
	extract($_REQUEST);
if($bodega!='')
{
	$c=$conexion2->query("select * from liquidaciones where bodega='$bodega' and fecha between'$desde' and '$hasta'")or die($conexion2->error());
}else
{
	if($_SESSION['tipo']==2)
	{
		$bodega=$_SESSION['bodega'];
		$c=$conexion2->query("select * from liquidaciones where bodega='$bodega' and fecha between'$desde' and '$hasta' order by bodega")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select * from liquidaciones where fecha between'$desde' and '$hasta' order by bodega")or die($conexion2->error());
	}
	
}

$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN RESULTADO</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='border-collapse:collapse; margin-left:0%; width:125%; margin-top:-6%;'>";
	echo "<tr>
	<td colspan='18'><a href='expor_liquidaciones.php?bodega=$bodega&&desde=$desde&&hasta=$hasta' target='_blakn'>Exportar a Excel</a></td>
	</tr>";
	echo "<tr style='text-align:center;'>
		<td rowspan='2'>BODEGA</td>
		<td rowspan='2'>AUTORIZADA POR</td>
		<td colspan='3'>ARTICULO ORIGEN</td>
		<td colspan='3'>ARTICULO DESTINO</td>
		<td rowspan='2'>CANTIDAD</td>
		<td rowspan='2'>DIGITADA POR</td>
		<td rowspan='2'>FECHA</td>
		<td rowspan='2'>TOTAL ORIGEN</td>
		<td rowspan='2'>TOTAL DESTINO</td>
		<td rowspan='2'>TOTAL LIQUIDACION</td>
		<td colspan='2'>DOCUMENTOS</td>
		<td rowspan='2'>OBSERVACION</td>
		<td rowspan='2'>CONSECUTIVO</td>
		<td rowspan='2'>PAQUETE</td>
	</tr>";

	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>PRECIO</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>PRECIO</td>
		<td>LIQ-AJN</td>
		<td>LIQ-AJP</td>
	</tr>";
	$total_origen=0;
	$total_destino=0;
	$total_liquidacion=0;
	$totalf=0;
	$tcantidad=0;
	$tforigen=0;
	$tfdestino=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art_o=$f['art_origen'];
		$art_d=$f['art_destino'];
		$fecha=$f['fecha'];
		$digitado=$f['digitado'];
		$cantidad=$f['cantidad'];
		$autoriza=$f['autoriza'];
		$precio_o=$f['precio_origen'];
		$precio_d=$f['precio_destino'];
		$ajn=$f['documento_inv_consumo'];
		$ajp=$f['documento_inv_ing'];
		$obs=$f['observacion'];
		$bo=$f['bodega'];
		$idl=$f['id_liquidacion'];
		$pac=$f['paquete'];
		$cb=$conexion1->query("select concat(bodega,':',nombre)as bodega from consny.bodega where bodega='$bo'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bo=$fcb['bodega'];
		$ca1=$conexion1->query("select * from consny.articulo where articulo='$art_o'")or die($conexion1->error());
		$fca1=$ca1->FETCH(PDO::FETCH_ASSOC);
		$desc_o=$fca1['DESCRIPCION'];
		$ca2=$conexion1->query("select * from consny.articulo where articulo='$art_d'")or die($conexion1->error());
		$fca2=$ca2->FETCH(PDO::FETCH_ASSOC);
		$desc_d=$fca2['DESCRIPCION'];
		$precio_o=explode(".", $precio_o);
		$e=substr($precio_o[1], 0);
		if($precio_o[0]=='')
		{
			$precio_o="0";
		}
			$precio_o="$precio_o[0].$e[0]$e[1]";
		
		
		$precio_d=explode(".", $precio_d);
		$d=substr($precio_d[1], 0);
		if($precio_d[0]=='')
		{
			$precio_d[0]='0';
		}
		$precio_d="$precio_d[0].$d[0]$d[1]";

		$total_origen=$precio_o * $cantidad;
		$total_destino=$precio_d * $cantidad;
		$total_liquidacion=$total_origen - $total_destino;
		$totalf=$totalf+$total_liquidacion;
		$tcantidad=$tcantidad + $cantidad;
		$tforigen=$tforigen+$total_origen;
		$tfdestino=$tfdestino+$total_destino;
		echo "<tr>
		<td>$bo</td>
		<td>$autoriza</td>
		<td>$art_o</td>
		<td>$desc_o</td>
		<td>$precio_o</td>
		<td>$art_d</td>
		<td>$desc_d</td>
		<td>$precio_d</td>
		<td>$cantidad</td>
		<td>$digitado</td>
		<td>$fecha</td>
		<td>$total_origen</td>
		<td>$total_destino</td>
		<td>$total_liquidacion</td>
		<td>$ajn</td>
		<td>$ajp</td>
		<td>$obs</td>
		<td>$idl</td>
		<td>$pac</td>
	</tr>";
	}

	echo "<tr>
	<td colspan='8'>TOTAL</td>
	<td>$tcantidad</td>
	<td colspan='2'></td>
	<td>$tforigen</td>
	<td>$tfdestino</td>
	<td>$totalf</td>
	</tr>";
}
}
?>
</body>
</html>