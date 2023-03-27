<?php
include("conexion.php");
$barra=$_GET['b'];
//echo "<script>alert('d')</script>";
$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<script>alert('NO SE ENCONTRO REGISTRO DEL CODIGO: $barra')</script>";
	echo "<script>location.replace('venta.php')</script>";
}else
{
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$lbs=$f['lbs'];
	$peso=$f['peso'];
	$t=$lbs + $peso;
	echo "<script>location.replace('venta.php?p=$t&&bara=$barra')</script>";
}
?>