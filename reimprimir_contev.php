<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			//$("#caja").hide();
		})
		function imprimir()
		{
			$("#caja").hide();
			window.print();
			$("#caja").show();
		}
	</script>
</head>
<body>
<div id="caja">
<?php
error_reporting(0);
include("conexion.php");
?>
<img src="imprimir.png" width="5%" height="5%" style="float: right; margin-right: 0.5%; cursor: pointer;" onclick="imprimir()">
</div>
<?php

$tipo=$_GET['tipo'];
$peso=$_GET['peso'];
$art=$_GET['art'];
$fechai=$_GET['fechai'];
$contenedor=$_GET['contenedor'];
$fecha=$_GET['fecha'];
$id=base64_decode($_GET['id']);

if($tipo==1)
{
	

	$c=$conexion2->query("select * from registro where contenedor='$contenedor' and fecha_documento='$fecha' and codigo='$art' and fecha_ingreso='$fechai' and activo is null")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{

		echo "<h3>NO SE ENCONTRO REGISTRO ACTIVOS INTETELO NUEVAMENTE</h3>";
	}else
	{
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$cod=$f['codigo'];
		$barra=$f['barra'];
		$peso=$f['peso'];
		$ca=$conexion1->query("select  * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$art=$fca['ARTICULO'];
		$des=$fca['DESCRIPCION'];
		$cp=$conexion1->query("select * from consny.articulo where articulo='$cod' and DESCRIPCION like '%PACA%'")or die($conexion1->error());
		$ncp=$cp->rowCount();
		if($ncp!=0)
		{
			$text="$art: $des";
		}else
		{
			$text="$art: $des";
		}
		
	
		$des=substr($text, 0,30);
		$des="$des($peso)";
		echo "<div class='barra' style='text-aling:center; float:none; width:98%; height:98%; border:none;'>
		<h4>$des</h4><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true' style='width:60%; height:60%;'/>	
		</div>";
		echo '<div style="page-break-after: always"></div>';
		}
	}
}else if($tipo==2)
{
	$c=$conexion2->query("select * from registro where id_registro='$id' and activo is null")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO ACTIVO</H3>";
	}else
	{
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$cod=$f['codigo'];
		$barra=$f['barra'];
		$ca=$conexion1->query("select  * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$art=$fca['ARTICULO'];
		$des=$fca['DESCRIPCION'];
		$text="$art: $des";
	
		$des=substr($text, 0,30);
		echo "<div class='barra' style='text-aling:center; float:none; width:98%; height:98%; border:none;'>
		<h4>$des</h4><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true' style='width:60%; height:60%;'/>	
		</div>";
		echo '<div style="page-break-after: always"></div>';
		}
	}
}
?>
</body>
</html>