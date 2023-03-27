<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
include("conexion.php");
?>
<h3>ACTIVACION TARJETA</h3>
<form method="POST" style="border:groove; border-color: black; width: 60%; height: 50%;">
<label>
	CODIGO TARJETA:<BR>
	<input type="text" name="codigo" id="codigo" class="text">
</label><br><br>
<input type="submit" name="btn" value="ACTIVAR" class="boton3" style="float: right; margin-right: 0.5%;"><br><br>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$bodega=$_SESSION['bodega'];
	$c=$conexion2->query("select * from tarjeta where codigo='$codigo' and estado='R' and bodega='$bodega'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('ERROR DE ACTIVACION, PUEDE SER QUE NO ESTE ASIGNADO A TU BODEGA O EL $codigo NO EXISTE O YA FUE UTILIZADO')</script>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$id=$f['ID'];
		$usu=$_SESSION['usuario'];
		$paq=$_SESSION['paquete'];
		$conexion2->query("insert into transaccion_tarjeta(id_tarjeta,fecha_hor_crea,referencia,usuario,paquete) values('$id',getdate(),'ACTIVACION DE TARJETA $codigo','$usu','$paq')")or die($conexion2->error());
		$hoy=date("Y-m-d");
		$conexion2->query("update tarjeta set fecha_activacion='$hoy',estado='A' where id='$id'")or die($conexion2->error());
		echo "<script>alert('ACTIVACION REALIZADA CON EXITO')</script>";
		echo "<script>location.replace('activa_tarjeta.php')</script>";
	}
}
?>
</body>
</html>