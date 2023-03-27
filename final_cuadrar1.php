<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
//IMPORTANTE IMPORTANTE NOTA IMPORTANTE
//--------------------------------------------------------------------------
//------EL CORRECTO PARA QUE CAIGA EN EXACTUS ES EL ARCHIVO FINAL_CUDRAR.PHP CAMBIAR EN LA OPCION DEL FINAL DEL ARCHIVO CUQDRQR.PHP----------------------------------------------------------|
//--------------------------------------------------------------------------
include("conexion.php");
$cuadrar=$_SESSION['cuadrar'];
$usuario=$_SESSION['usuario'];
$liquido=$_SESSION['liquido'];
$conexion1->query("update cuadro_venta set 	monto_liquido='$liquido',estado='1' where usuario='$usuario' and session='$cuadrar'")or die($conexion1->error());

$_SESSION['cuadrar']='';
$_SESSION['liquido']='';
echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";
echo "<script>location.replace('cuadrar.php')</script>";
?>
</body>
</html>