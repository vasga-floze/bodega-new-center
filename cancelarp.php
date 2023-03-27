<?php
include("conexion.php");
$barra=$_SESSION['cbarra'];
if($barra=="")
{
	echo "<script>location.replace('index.php')</script>";
}else
{
	$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error);
	$n=$c->rowCount();
	if($n!=0)
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$id=$f['id_registro'];
		//$conexion2->query("delete from detalle where registro='$id'")or die($conexion2->error);
		$conexion2->query("update registro set observacion='CANCELADO SYS',activo='0',estado='2' where id_registro='$id'")or die($conexion2->error);
		$_SESSION['cbarra']="";
		echo "<script>location.replace('index.php')</script>";
	}else
	{
		$_SESSION['cbarra']="";
		echo "<script>location.replace('index.php')</script>";
	}
}

?>