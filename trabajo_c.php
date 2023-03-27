<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	if($_SESSION['usuario']!=$usuarios)
	{
		echo "<script>alert('ERROR: SOLO PUEDE CONTINUAR EL USUARIO $usuarios')</script>";
		echo "<script>location.replace('trabajos_pendiente.php')</script>";
	}else
	{
		$c=$conexion2->query("select * from trabajo where sessiones='$sessiones' and usuario='$usuarios'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('ERROR: INTENTELO NUEVAMENTE')</script>";
		echo "<script>location.replace('trabajos_pendiente.php')</script>";
		}else
		{
			$_SESSION['trabajo']=$sessiones;
			echo "<script>location.replace('trabajos.php')</script>";
		}
	}
	
}
?>