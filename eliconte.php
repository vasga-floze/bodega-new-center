<?php
error_reporting(0);
include("conexion.php");
$id=$_GET['id'];
$peso=$_GET['p'];
$fecha_hora=$_GET['fecha_hora'];
$cant=$_GET['cant'];
if($id=="")
{
	echo "<script>alert('SE PRODUJO UN ERROR!')</script>";
	echo "<script>location.replace('contenedor.php')</script>";
}else if($_GET['op']=="")
{
	echo "<script>if(confirm('SEGURO DESEA QUITAR EL REGISTRO'))
	{
		location.replace('eliconte.php?id=$id&&op=y&&p=$peso&&fecha_hora=$fecha_hora&&cant=$cant');
	}else
	{
		location.replace('contenedor.php');
	}</script>";
}

if($id!="" and $_GET['op']=="y")
{
	$fecha=$_SESSION['fecha'];
	$cont=$_SESSION['contenedor'];
	$usuario=$_SESSION['usuario'];
	//echo "<script>alert('$id - peso - $fecha_hora - $cant')</script>";
$conexion2->query("UPDATE registro set activo='0',fecha_eliminacion=getdate(),OBSERVACION='ELIMINADO SYS...',usuario_eliminacion='$usuario',estado='2' where codigo='$id' and fecha_documento='$fecha' and contenedor='$cont' and peso='$peso' and tipo='CD' and fecha_ingreso='$fecha_hora'")or die($conexion2->error());
echo "<script>location.replace('contenedor.php')</script>";
}
?>