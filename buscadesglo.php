<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion1->query("select * from consny.articulo where articulo='$codi'")or die($conexion1->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO ARTICULO ')</script>";
		echo "<script>location.replace('resumen3.php?b= ')</script>";
	}else
	{
		echo "<script>location.replace('resumen3.php?art=$codi&&b= ')</script>";
	}
}
?>