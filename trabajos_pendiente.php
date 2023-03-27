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
$usu=$_SESSION['usuario'];
$c=$conexion2->query("select * from trabajo where estado='2' and usuario='$usu' and articulos is null")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUNA MESA PENDIENTE</h3>";
}else
{
	echo "<table border='1' cellpadding='10' class='tabla'>";
	echo "<tr>
		<td>FECHA</td>
		<td>PRODUCIDO</td>
		<td>MESA</td>
		<td>USUARIO</td>
		<td>CONTINUAR</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$sesiones=$f['sessiones'];
		$usuarios=$f['usuario'];
		echo "<tr>
		<td>".$f['fecha']."</td>
		<td>".$f['producido']."</td>
		<td>".$f['mesa']."</td>
		<td>".$f['usuario']."</td>
		<td>
		<form method='POST' action='trabajo_c.php'>
		<input type='hidden' name='sessiones' value='$sesiones'>
		<input type='hidden' name='usuarios' value='$usuarios'>
		<input type='submit' value='CONTINUAR' class='boton3' style='padding:3.5%;'>
		</td>
	</tr>";
	}
}
?>
</body>
</html>