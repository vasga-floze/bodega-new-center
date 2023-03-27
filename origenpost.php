<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	if($op1==1)
	{
		//echo "$origen";
		$c=$conexion1->query("select * from consny.BODEGA where BODEGA='$origen' and nombre not like '%(N)%'")or die($conexion1->error());
		$n=$c->rowCOUNT();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO LA BODEGA $origen O NO ESTA DISPONIBLE')</script>";
			echo "<script>location.replace('traslados.php')</script>";
		}else
		{
			$_SESSION['origen']='';
			echo "<script>location.replace('traslados.php?ori=$origen')</script>";
		}
	}else if($op1==2)
	{
			$c=$conexion1->query("select * from consny.BODEGA where BODEGA='$origen' and nombre not like '%(N)%'")or die($conexion1->error());
		$n=$c->rowCOUNT();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO LA BODEGA $origen O NO ESTA DISPONIBLE')</script>";
			echo "<script>location.replace('traslados.php')";
		}else
		{
			$_SESSION['origen']=$origen;
			echo "<script>location.replace('traslados.php?ori=$origen&&io=1')</script>";
		}
	}

}else
{
	echo "<script>location.replace('traslados.php')</script>";
}
?>