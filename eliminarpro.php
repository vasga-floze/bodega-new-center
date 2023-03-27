<?php
error_reporting(0);
$id=$_GET['b'];
$ba=$_GET['id'];
$y=$_GET['y'];
include("conexion.php");

if($id=="" or $ba=="")
{
	echo "<script>alert('ERROR INTENTELO NUEVAMENTE')</script>";
	echo "<script>location.replace('buscar.php')</script>";
}else
{
	
	$c=$conexion2->query("select * from registro where id_registro='$id' and barra='$ba'")or die($conexion2->error);
	$n=$c->rowCount();
	if($n==0)
	{
	  echo "<script>alert('ERROR INTENTELO NUEVAMENTE ')</script>";
	  echo "<script>location.replace('buscar.php')</script>";
	}else
	{
		if($y==1)
		{
			//$conexion2->query("delete from detalle where registro='$id'")or die($conexion2->error);
			$ficha=date("Y-m-d H:i:s");
		$conexion2->query("update registro set activo='0',observacion='Eliminado SYS $ficha' where id_registro='$id'")or die($conexion2->error);
		echo "<script>alert('ELIMINADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('buscar.php')</script>";
		}else
		{
			
			echo "<script>if(confirm('SEGURO DESEA ELIMAR EL REGISTRO')){
		location.replace('eliminarpro.php?y=1&&b=$id&&id=$ba');	
	}else{
		location.replace('buscar.php');
	}</script>";
		}
		
	}
}
?>