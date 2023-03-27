<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	echo "<script>alert('HECHO\\nNOTA:PARA CONTINUAR ESTE TRASLADO BUSCALO EN TRASLADOS PENDIENTES')</script>";
	$_SESSION['doc']='';
	$_SESSION['origen']='';
	echo "<script>location.replace('traslados.php')</script>";
}else
{
	//echo "<script>alert('sin post')</script>";
	echo "<script>location.replace('traslados.php')</script>";
}
?>