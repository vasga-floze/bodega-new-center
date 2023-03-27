<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	
	$_SESSION['empresa_tpieza']=$empresa;
	$_SESSION['origen_tpiezas']='';
	$_SESSION['destino_tpiezas']='';
	echo "<script>location.replace('traslado_piezas.php')</script>";

}else
{
	echo "<script>location.replace('conexiones.php')</script>";
}
?>