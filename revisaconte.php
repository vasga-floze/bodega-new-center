<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<?php
include("conexion.php");
error_reporting(0);
if($_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='OCAMPOS' or $_SESSION['usuario']=='gjurado' or $_SESSION['usuario']=='GJURADO' or $_SESSION['usuario']=='salvarado')
		{
			
		
?>
</head>
<body>
<form method="POST">
FECHA INGRESO CONTENEDOR: <input type="date" name="b" class="text" style="width: 20%;">
<input type="submit" name='btn' value="BUSCAR" class="boton3">
</form>
<br><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=="BUSCAR")
	{
	  $c=$conexion2->query("select * from registro where tipo='c1' and fecha_documento='$b'")or die($conexion2->error());
	  $n=$c->rowCount();
	  if($n==0)
	  {
	  	echo "<h3>NO SE ENCONTRO CONTENEDOR CON FECHA DE: $b</h3>";
	  }else
	  {
	  	echo "<table class='tabla' border='1' cellpadding='10'>";
	  	echo "<tr>
	  		<td>CONTENEDOR</td>
	  		<td>TOTAL ARTICULOS</td>
	  		<td>TOTAL PESO ARTICULOS</td>
	  		<td>DETALLE</td>
	  	</tr>";
	  	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	  	{
	  		$idr=$f['id_registro'];
	  		$conte=$f['contenedor'];
	  		$cta=$conexion2->query("select cantidad,peso from registro where tipo='cd' and contenedor='$conte' and fecha_documento='$b'")or die($conexion2->error());
	  		$tc=0; $tp=0;
	  		while($fca=$cta->FETCH(PDO::FETCH_ASSOC))
	  		{
	  			$cant=$fca['cantidad'];
	  			$peso=$fca['peso'];
	  			$tc=$tc +$cant;
	  			$tp=$tp + $peso;
	  		}
	  		echo "<tr>
	  		<td>$conte</td>
	  		<td>$tc</td>
	  		<td>$tp</td>
	  		<td><a target='_blank' href='verdetalle.php?idr=$idr'>VER </a></td>
	  	</tr>";
	  	}
	  }
	}
	
}


}else
		{
			echo "<script>alert('NO TIENES AUTORIZACION')</script>";
			echo "<script>location.replace('index.php')</script>";
		}
?>
</body>
</html>