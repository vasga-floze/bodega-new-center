<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
<?php
include("conexion.php");
$tipo=$_SESSION['tipo'];
if($tipo==3)
{
	$tipo=1;
}
if($tipo!=1)
{
	echo "<script>location.replace('desglose.php')</script>";
}
?>
<br>
<?php
$c=$conexion2->query("select sessiones,bodega,usuario from  inventario where estado='0' group by sessiones,bodega,usuario
")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE ENCONTRO NINGUN INVENTARIO SIN FINALIZAR :-)</h2>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10'>";
	echo "<tr>
		<td>BODEGA INVENTARIO</td>
		<td>FECHA Y HORA</td>
		<td>USUARIO</td>
		<td>TOTAL FARDOS</td>
		<td>CONTINUAR</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$sessiones=$f['sessiones'];
		$bodega=$f['bodega'];
		$usu=$f['usuario'];
		$cs=$conexion2->query("select top 1 * from inventario where sessiones='$sessiones' and usuario='$usu'")or die($conexion2->error());
		$fcs=$cs->FETCH(PDO::FETCH_ASSOC);
		$csv=$conexion2->query("select count(*) as total from inventario where sessiones='$sessiones' and usuario='$usu' and registro !='0'")or die($conexion2->error());
		$fcsv=$csv->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
		<td>".$fcs['bodega']."</td>
		<td>".$fcs['fecha_ingreso']."</td>
		<td>".$fcs['usuario']."</td>
		<td>".$fcsv['total']."</td>
		<td>
		<form method='POST'>
		<input type='hidden' name='sessiones' value='$sessiones'>
		<input type='hidden' name='bodega' value='$bodega'>
		<input type='hidden' name='usu' value='$usu'>
		<button style='background-color:#076410; color:white; padding-top:2%; padding-bottom: 2%;' class='boton4'>CONTINUAR</button>
		</form>
		</td>
	</tr>";

	}
}
if($_POST)
{
	extract($_REQUEST);
	if(ctype_upper( $_SESSION['usuario'])==ctype_upper($usu))
	{
		$_SESSION['inv']=$sessiones;
		echo "<script>location.replace('inventario.php')</script>";
	}else
	{
		echo "<script>alert('INVENTARIO SOLO LO PUEDE CONTINUAR CON EL USUARIO: $usu')</script>";
	}
}
?>
</body>
</html>