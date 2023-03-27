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
	<form method="POST">
		<input type="text" name="nommbre" id="nommbre" class="text" style="width: 35%;" placeholder="NOMMBRE ARTICULO">
		<input type="submit" name="btn" value="GUARDAR" class="boton2">
	</form>
	<?php
	if($_POST)
	{
		extract($_REQUEST);
		$c=$conexion2->query("select * from articulo_facturas where descripcion='$nommbre'")or die($conexion2->error());

		$n=$c->roWCOunt();
		if($n!=0)
		{
			echo "<script>alert('AL PARECER YA SE ENCUENTRA UN ARTICULO GUARDADO CON EL NOMBRE: $nommbre, VERIFICA LA INFORMACION')</script>";
		}else
		{
			//$conexion2->query("")or die($conexion2->error());
			//ingreso articulo


		}
	}
	?>
</body>
</html>