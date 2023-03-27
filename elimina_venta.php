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
$iden =$_GET['iden'];
$y=$_GET['y'];
$venta=$_SESSION['venta'];
if($y=="")
{
	echo "<script>if(confirm('SEURO DESEA ELIMINAR EL REGISTRO DE ESTA VENTA'))
{
	location.replace('elimina_venta.php?iden=$iden&&y=1');
}else{
	location.replace('venta.php');
}</script>";
}

if($y==1 and $iden!="")
{
	$conexion2->query("delete from venta where id='$iden' and sessiones='$venta'")or die($conexion2->error());
echo "<script>location.replace('venta.php')</script>";
}else
{
	echo "<script>alert('SE PRODUJO UN ERROR INTENTELO NUEVAMENTE')</script>";
echo "<script>location.replace('venta.php')</script>";
}

?>
</body>
</html>