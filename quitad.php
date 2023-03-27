<?php
include("conexion.php");
$cod=$_GET['cod'];
$op=$_GET['id'];
if($op==1)
{
	$conexion2->query("delete from dbo.desglose where id='$cod'")or die($conexion2->error);
	echo "<script>location.replace('desglose.php?b ');</script>";
}
if($cod !="")
{
	echo "<script>if(confirm('SEGURO DESEA QUITAR EL ARTICULO DEL DESGLOSE'))
	{
		location.replace('quitad.php?cod=$cod&&id=1');
	}else
	{
		location.replace('desglose.php?b ');
	}
	</script>";
}else
{
	echo "<script>location.replace('desglose.php?b ');</script>";
}
?>