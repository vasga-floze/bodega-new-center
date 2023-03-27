<?php
include("conexion.php");
if($_POST)
{
	if($_SESSION['inv']=='')
	{
		echo "<script>location.replace('inventario.php')</script>";
	}else
	{
		extract($_REQUEST);
		$inv=$_SESSION['inv'];
		$u=$_SESSION['usuario'];
		$conexion2->query("update inventario set digita='$digita',estado='1',fecha='$fecha' where sessiones='$inv' and usuario='$u'")or die($conexion2->error());
		echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
		$_SESSION['inv']='';
		echo "<a href='exportar_inv.php?inv=$inv&&usuario=$u'>EXPORTAR INVENTARIO A EXCEL</a>";
	}
}else
{
	echo "<script>location.replace('inventario.php')</script>";
}
?>