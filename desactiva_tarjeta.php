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
<h3>DESACTIVAR TARJETA DE REGALO</h3>
<form method="POST" style="border:groove; width: 60%; height: 40%; border-color: black;">
	<label>
		CODIGO TARJETA:<br>
		<input type="text" name="codigo" id="codigo" class="text">
	</label><br><br>
	<label>
		NUMERO DE TICKET:<br>
		<input type="text" name="numero" id="numero" class="text">
	</label><br><br>
	<input type="submit" name="" class="boton3" value="DESACTIVAR" style="float: right; margin-right: 0.5%;"><br><br>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$bodega=$_SESSION['bodega'];
	$c=$conexion2->query("select * from tarjeta where codigo='$codigo' and estado ='A' and bodega in('CA25','CA28') and bodega='$bodega'
")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('ERROR: $codigo NO ESTA ACTIVO O NO ESTA EN TU BODEGA O EL CODIGO NO EXISTE')</script>";
		echo "<script>location.replace('desactiva_tarjeta.php')</script>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$usu=$_SESSION['usuario'];
		$paq=$_SESSION['paquete'];
		$id=$f['ID'];
		$conexion2->query("insert into transaccion_tarjeta(id_tarjeta,fecha_hor_crea,usuario,paquete,referencia) values('$id',getdate(),'$usu','$paq','DESACTIVACION DE TARJETA $codigo')")or die($conexion2->error());
		$hoy=date("Y-m-d");
		$conexion2->query("update tarjeta set fecha_desactivacion='$hoy',estado='D',num_ticket='$numero' where id='$id'")or die($conexion2->error());
		echo "<script>alert('DESACTIVADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('desactiva_tarjeta
		.php')</script>";
	}
}
?>
</body>
</html>