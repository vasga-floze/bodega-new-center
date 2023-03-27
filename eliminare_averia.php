<?php
include("conexion.php");
$id=$_GET['id'];
if($id!='')
{
	$conexion2->query("delete from averia where id='$id'")or die($conexion2->error());
	echo "<script>location.replace('averia.php')</script>";
}else
{
	echo "<script>location.replace('averia.php')</script>";
}
?>