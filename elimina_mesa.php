<?php
error_reporting(0);
include("conexion.php");

if($_GET['y']=='')
{
	$id=$_GET['id'];
	echo "<script>
	if(confirm('DESEA QUITAR EL FARDO'))
	{
		location.replace('elimina_mesa.php?id=$id&&y=1');
		}else
		{
			location.replace('mesa.php');
		}
	</script>";
}else
{
	if($_GET['id']=='')
	{
		echo "<script>alert('SE PRODUJO UN ERROR!')</script>";
		echo "<script>location.replace('mesa.php')</script>";
	}else
	{
		$id=$_GET['id'];
		$c=$conexion2->query("select * from detalle_mesa where id='$id'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('SE PRODUJO UN ERROR!')</script>";
		echo "<script>location.replace('mesa.php')</script>";
		}else
		{
			$conexion2->query("delete from detalle_mesa where id='$id'")or die($conexion2->error());
			echo "<script>location.replace('mesa.php')</script>";
		}
	}
}
?>