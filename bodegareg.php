<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	$doc=$_SESSION['doc'];
	$conexion2->query("update traslado set origen='$nuevab' where id='$idt'")or die($conexion2->error);
	echo "<script>location.replace('traslados.php')</script>";
}
?>