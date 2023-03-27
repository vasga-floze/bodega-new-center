<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
$tipo=$_SESSION['tipo'];
$bodega=$_SESSION['bodega'];
		$tipousu=substr($bodega,0);
		if($tipousu[0]=='U')
		{
			$tipo=1;

		}else
		{
			$tipo=$_SESSION['tipo'];
		}
		if($tipo==2)
		{
			echo "<script>alert('NO TIENES PERMISOS')</script>";
			echo "<script>location.replace('desglose.php')</script>";
		}else if($tipo==3)
		{
			echo "<script>alert('NO DISPONIBLE')</script>";
			echo "<script>location.replace('consultar.php')</script>";
		}


if($_GET['d']!='')
{

	$doc=$_GET['d'];
	$consu=$conexion2->query("select * from traslado where sessiones='$doc'")or die($conexion2->error());
	$nconsu=$consu->rowCount();
	if($nconsu!=0)
	{
		if($tipousu[0]=='U')
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$usut=$f['traslado'];
		$usua=$_SESSION['usuario'];
		if($usut==$usua)
		{
			$_SESSION['doc']=$_GET['d'];
		echo "<script>location.replace('traslados.php')</script>";
		}else
		{
			echo "<script>alert('ERROR: INTENTELO NUEVAMENTE VERIFIQUE SI ESE TRASLADO LO HA INICIADO SU USUARIO')</script>";
			echo "script>location.replace('pendiente_t.php')</script>";
		}
	}

		$_SESSION['doc']=$_GET['d'];
		echo "<script>location.replace('traslados.php')</script>";
	}
	
	
}
if($tipousu[0]=='U')
{
	$usu=$_SESSION['usuario'];
	$c=$conexion2->query("select * from traslado where documento_inv='- -' and usuario='$usu' order by id desc")or die($conexion2->error());
}else
{
	$c=$conexion2->query("select * from traslado where documento_inv='- -' or documento_inv='' order by id desc")or die($conexion2->error());
}

echo "<table class='tabla' border='1' cellpadding='10'>";
echo "<tr>
	<td>ORIGEN</td>
	<td>DESTINO</td>
	<td>USUARIO</td>
	<td>FECHA Y HORA INICIO</td>
	<td>OPCION</td>
</tr>";
$session='';
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$origen=$f['origen'];
	$destino=$f['destino'];
	$usuario=$f['usuario'];
	$fechai=$f['fecha_ingreso'];
	$sessiones=$f['sessiones'];
	if($session!=$sessiones and $fechai!='')
	{
		echo "<tr>
	<td>$origen</td>
	<td>$destino</td>
	<td>$usuario</td>
	<td>$fechai</td>
	<td><a href='pendiente_t.php?d=$sessiones'><buttom>CONTINUAR</buttom></a></td>
</tr>";
$session=$sessiones;
	}
	
}
?>
</body>
</html>