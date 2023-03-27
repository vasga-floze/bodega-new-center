<?php
include("conexion.php");
$id=$_GET['id'];
$art=$_GET['articulo'];
$origen=$_GET['origen'];
$destino=$_GET['destino'];
if($_SESSION['tpiezas']!='')
{
	$doc=$_SESSION['tpiezas'];
	$usu=$_SESSION['usuario'];
	$conexion2->query("delete from traslado_piezas where session='$doc' and usuario='$usu' and id='$id'")or die($conexion2->error());
	echo "<script>location.replace('traslado_piezas.php?origen=$origen&&destino=$destino&&art=$art')</script>";

}else
{
	echo "<script>alert('ERROR!!!')</script>";
	echo "<script>location.replace('traslado_piezas.php')</script>";
}
?>