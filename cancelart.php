<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	$doc=$_SESSION['doc'];
	$usu=$_SESSION['usuario'];
	if($doc!='')
	{
		$c=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$usu'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO')</script>";
			$_SESSION['doc']='';
				echo "<script>location.replace('traslados.php')</script>";
		}else
		{
			//echo "<script>alert('$doc')</script>";
			$conexion2->query("delete from traslado where sessiones='$doc' and usuario='$usu'")or die($conexion2->error());
			$_SESSION['doc']='';
			echo "<script>alert('TRASLADO CANCELADO CORRECTAMENTE')</script>";
			echo "<script>location.replace('traslados.php')</script>";

		}
	}else
	{
		echo "<script>location.replace('traslados.php')</script>";
	}
}else
{
	echo "<script>location.replace('traslados.php')</script>";	
}

?>