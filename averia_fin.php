<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	$s=$_SESSION['p_averia'];
	
	$c=$conexion2->query("select * from averia where sessiones='$s' and tipo='P'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('ERROR: AL FINALIZAR ERROR: F-AVERIA-001')</script>";
		echo "<script>location.replace('averia.php')</script>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$corelativo=$f['corelativo'];
		$conexion2->query("update averia set estado='1' where corelativo='$corelativo'")or die($conexion2->error());
		echo "<script>alert('FINALIZADO CORECTAMENTE')</script>";
		$_SESSION['p_averia']='';
		echo "<script>location.replace('averia.php')</script>";
	}

}else
{
	$s=$_SESSION['p_averia'];
	echo "$s no";
	echo "<script>location.replace('averia.php?j=0909')</script>";
}
?>