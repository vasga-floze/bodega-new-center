<?php
error_reporting(0);
include("conexion.php");
$id=$_GET['id'];
$r=$_GET['r'];
if($r=='')
{
	echo "<script>
	if(confirm('SEGURO DESEA QUITAR LA LINEA'))
	{
		location.replace('quitar_cuadro.php?id=$id&&r=1')

		}else
		{
			location.replace('cuadrar.php');
		}
	</script>";
}
if($id!='' and $r==1)
{
	extract($_REQUEST);
	$conexion1->query("delete from cuadro_venta_detalle where id='$id'")or die($conexion1->error());
	echo "<script>alert('ELIMINADO CORECTAMENTE')</script>";
	echo "<script>location.replace('cuadrar.php')</script>";
}else
{
	echo "<script>location.replace('cuadrar.php')</script>";

}

?>