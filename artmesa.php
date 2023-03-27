<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.js"></script>
<?php
include("conexion.php");


if($_SESSION['idr']=="")
{
	echo "<script>location.replace('mesa.php')</script>";
}else
{
	$idr=$_SESSION['idr'];
		$q=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error);
		$fq=$q->FETCH(PDO::FETCH_ASSOC);
		$barra=$fq['barra'];
		$bodega=$fq['bodega'];
}
?>
</head>
<body>
<div class="detalle" style="margin-top: -5%; width: 100%;">
	<a href="mesa.php" style="float: right; margin-right: 1.2%; text-decoration: none; color: white;">
	Cerrar X</a><br>
	<div class="adentro" style="margin-left: 2.5%; margin-bottom: 1.5%; height: 93%; margin-top: -0.5%;">
		<form method="POST">
		<input type="text" name="b" class="text" placeholder="CODIGO O NOMBRE ARTICULO" style="width: 40%; margin-left: 15%;">
		<input type="submit" name="btn" value="BUSCAR" class="boton3">
		</form>
<?php
	if($_POST)
	{
		extract($_REQUEST);
		$c=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO and consny.ARTICULO.ACTIVO='S' and consny.EXISTENCIA_BODEGA.BODEGA='$bodega' where consny.ARTICULO.ARTICULO='$b' or consny.ARTICULO.DESCRIPCION like'%$b%'")or die($conexion1->error);
	}else
	{
		$c=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO and consny.ARTICULO.ACTIVO='S' and consny.EXISTENCIA_BODEGA.BODEGA='$bodega'")or die($conexion1->error);
	}
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
	}else
	{
		echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:3%; margin-top:0.5%;'>
		<tr>
			<td>CODIGO</td>
			<td>NOMBRE</td>
		</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$cod=$f['ARTICULO']; $nom=$f['DESCRIPCION']; $bod=$f['BODEGA'];
			echo "<tr>
				<td>
				<a href='mesa.php?art=$cod'  style='text-decoration:none; color:black;'>$cod</a></td>
				<td><a href='mesa.php?art=$cod'  style='text-decoration:none; color:black;'>$nom</a></td>
			</tr>";
		}
		echo "</table>";


	}
?>
	</div>
</div>
</body>
</html>