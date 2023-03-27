<?php
$ide=$_GET['ide'];
$op=$_GET['op'];
include("conexion.php");

if($op==1)
{
	$conexion2->query("delete from dbo.detalle where id='$ide'")or die($conexion2->error);
echo "<script>location.replace('ingreso.php?b= &&bu &&i=2')</script>";
}else
{
	echo "<script>if(confirm('SEGURO DESEA QUITAR EL ARTICULO')){
	location.replace('quitarp.php?ide=$ide&&op=1');
}else
{
	location.replace('ingreso.php?b= &&bu ');
}</script>";
}


?>