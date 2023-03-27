<?php
if($_POST)
{
	extract($_REQUEST);
	include("conexion.php");
	$doc=$_SESSION['doc'];
	if($doc=="")
	{
		echo "<script>location.replace('traslados.php')</script>";
	}else
	{
		if($op==1)
		{
			$conexion2->query("update traslado set origen='$bodg' where sessiones='$doc' and origen='SM01'")or die($conexion2->error);
			echo "<script>location.replace('traslados.php')</script>";
		}else if($op==2)
		{
			$conexion2->query("update traslado set origen='$bodg' where sessiones='$doc'")or die($conexion2->error);
			echo "<script>location.replace('traslados.php')</script>";
		}
	}
}
?>