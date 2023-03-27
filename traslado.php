<!DOCTYPE html>
<html>
<head>
	<?php
		include("conexion.php");

		echo "<script>location.replace('traslados.php')</script>";
		$hoy=date("Y-m-d");
		session_start();
		$idr=$_SESSION['registroid'];
		//echo $idr;
		if($_SESSION['registroid']!="")
		{
			echo "<script>location.replace('detalle_traslado.php')</script>";
		}
	?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<form method="POST">
	CODIGO DE BARRA: <input type="text" class="text" style="width: 20%;" name="barra" required>
	<input type="submit" name="btn" value="Siguiente">
</form>
<?php
include("conexion.php");
	if($_POST)
	{
		extract($_REQUEST);
		$c=$conexion2->query("select * from dbo.registro where barra='$barra'")or die($conexion2->error);
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$_SESSION['registroid']=$f['id_registro'];
			echo "<script>location.replace('detalle_traslado.php')</script>";
		}
	}
?>
</body>
</html>