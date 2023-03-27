<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="shortcut icon" href="logos.png">
</head>
<body>
<?php
include("conexion.php");
?>
<center>
<form method="POST">
<input type="text" name="nombre" class="text" style="width: 30%; margin-left: -3.5%;" placeholder="NOMBRE"><br><br>
<input type="number" name="telefono" class="text" style="width: 30%;" placeholder="TELEFONO"><br><br>
<input type="email" name="correo" class="text" style="width: 30%;" placeholder="E-MAIL"><br><br>
<input type="text" name="direccion" class="text" style="width: 30%;" placeholder="DIRECCION"><br><br>
<input type="submit" name="" value="GUARDAR" class="boton2">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$usuario=$_SESSION['usuario'];
	$paquete=$_SESSION['paquete'];
	$bodega=$_SESSION['bodega'];
	$conexion2->query("insert into agenda(nombre,telefono,correo,direccion,bodega,usuario,paquete) values('$nombre','$telefono','$correo','$direccion','$bodega','$usuario','$paquete')")or die($conexion2->error());
	echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
	echo "<script>location.replace('agenda.php')</script>";
}
?>
</center>
</body>
</html>