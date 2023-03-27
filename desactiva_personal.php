<?php
include("conexion.php");
$cod=$_GET['cod'];
if($cod!='')
{
	$conexion1->query("update produccion_accpersonal set activo='0' where codigo='$cod' ")or die($conexion1->error());
}


echo "<script>location.replace('personal_produccion.php?i=0')</script>";
?>