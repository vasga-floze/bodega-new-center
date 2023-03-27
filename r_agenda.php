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
$usuario=$_SESSION['usuario'];
$c=$conexion2->query("select * from agenda where usuario='$usuario'")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN CONTACTO</h3>";
}else
{
	echo "<table border='1' class='tabla' cellpadding='10'>";
	echo "<tr>
		<td>NOMBRE</td>
		<td>NUMERO</td>
		<td>TELEFONO</td>
		<td>E-MAIL</td>
		<td>DIRECCION</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		echo "<tr>
		<td>".$f['nombre']."</td>
		<td>".$f['telefono']."</td>
		<td>".$f['telefono']."</td>
		<td>".$f['correo']."</td>
		<td>".$f['direccion']."</td>
	</tr>";
	}
}
?>
</body>
</html>