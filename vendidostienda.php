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
<h3 style="text-align: center; text-decoration: underline;">INGRESO DE FARDOS VENDIDOS EN TIENDA</h3>
<form method="POST">

<input type="text" name="barra" class="text" style="width: 30%;" placeholder="CODIGO DE BARRA VENDIDO" required>
<input type="submit" name="" class="btnfinal" style="background-color: #8794B2; padding: 0.5%; border-color: black;" value="CONTINUAR" required>
</form>
<?php
	if($_POST)
	{
		extract($_REQUEST);
		$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		$cb=$conexion1->query("select * from usuariobodega where usuario='$usuario' and paquete='$paquete'")or die($conexion1->error());

		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);

		if($f['activo']=='0')
		{
			echo "<script>alert('CODIGO DE BARRA YA NO SE ENCUENTRA DISPONIBLE')</script>";
			echo "<script>location.replace('vendidostienda.php')</script>";
		}else if($f['fecha_desglose']!='')
		{
			$fd=$f['fecha_desglose'];
			echo "<script>alert('CODIGO DE BARRA FUE DESGLOSADO LA FECHA DE: $fd')</script>";
			echo "<script>location.replace('vendidostienda.php')</script>";
		}else if($f['bodega'] ==$fcb['BODEGA'])
		{
			echo "<script>location.replace('vendidostienda_desactiva.php?barra=$barra')</script>";
		}else
		{
				echo "<script>alert('CODIGO DE BARRA NO ESTA ASIGNADO A TU BODEGA')</script>";
			echo "<script>location.replace('vendidostienda.php')</script>";
		}

	}
?>


</body>
</html>