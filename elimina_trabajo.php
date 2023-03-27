<?php
include("conexion.php");
$id=$_GET['id'];
if($id=='' or $_SESSION['trabajo']=='')
{
	echo "<script>location.replace('trabajos')</script>";
}else
{
	$s=$_SESSION['trabajo'];
	$c=$conexion2->query("select * from trabajo where sessiones='$s' and id='$id'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('ERROR: DESCONOCIDO')</script>";
		echo "<script>location.replace('trabajos.php')</script>";
	}
	$conexion2->query("delete from trabajo where id='$id'")or die($conexion2->error());
	echo "<script>location.replace('trabajos.php')</script>";
}
?>