<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
$inv=$_SESSION['inv'];
$y=$_GET['y'];
$id=$_GET['id'];
if($inv=='')
{
	echo "<script>location.replace('inventario.php')</script>";
}else if($y=='')
{
	echo "<script>if(confirm('SEGURO DESEA QUITAR EL FARDO')){
		location.replace('eli_inventario.php?y=1&&id=$id');
	}else{
		location.replace('inventario.php');
	}</script>";
	
	
	
	

}
if($id!='' and $y==1)
	{
		$conexion2->query("delete from inventario where id='$id'")or die($conexion2->error());
	echo "<script>location.replace('inventario.php')</script>";
	}else
	{
		echo "<script>location.replace('inventario.php')</script>";
	}
?>
</body>
</html>