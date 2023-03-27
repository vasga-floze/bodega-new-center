<?php
include("conexion.php");
$id=$_GET['id'];
$doc=$_SESSION['doc_ripio'];
$usuario=$_SESSION['usuario'];
if($id!='' and $doc!='' and $usuario!='')
{
	$conexion2->query("delete from traslado_ripio where id='$id' and session='$doc' and usuario='$usuario'")or die($conexion2->error());
}else
{
	echo "<script>alert('ERROR NO SE PUDO ELIMINAR')</script>";
}

echo "<script>location.replace('despacho_ripio.php')</script>";

?>