<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
<?php
	include("conexion.php");
	if($_SESSION['tipo']==2)
	{
		echo "<script>alert('NO TIENES AUTORIZACION')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}
	$hoy=date("Y-m-d");
?>
</head>
<body>
<form method="POST">
CONTENEDOR: <input type="text" name="contenedor" class="text" style="width: 23%;">
<input type="date" name="fecha" class="text" style="width: 17%;" value='<?php echo "$hoy";?>'>

<input type="submit" name="" value="CONTINUAR" class="boton2">

</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select * from registro where fecha_documento='$fecha' and contenedor='$contenedor' and estado='0'")or die($conexion2->error);
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO SIN FINALIZAR</h3>";
	}else
	{
		$_SESSION['contenedor']=$contenedor;
		$_SESSION['fecha']=$fecha;
		echo "<script>location.replace('contenedor.php')</script>";
	}
}
?>
</body>
</html>