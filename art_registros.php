<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div style="display: none;">
<?php
include("conexion.php");
?>
</div>
<div class="detalle">
	<a href="registros.php" style="text-decoration: none; color: white; font-size: 105%; float: right;">Cerrar X</a>
	<div class="adentro" style="margin-left: 0.9%; width: 98%; height: 93%;">
<form method="POST">
	<input type="text" class="text" name="b" placeholder="ARTICULO O DESCIPCION" style="width: 20%; margin-left: 0.5%;">
	<input type="submit" name="btn" value="BUSCAR" class="boton2">
</form>
<br>
<?php
if($_POST)
{
	extract($_REQUEST);
	$bd=str_replace(" ", "%", $b);
	if($b=='')
	{
		$c=$conexion1->query("select * from consny.articulo")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("select * from consny.articulo where articulo='$b' or descripcion like '%$bd%'")or die($conexion1->error());
	}
	
}else
{
	$c=$conexion1->query("select * from consny.articulo")or die($conexion1->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "NO SE ENCONTRO NINGUN ARTICULO";
}else
{
	echo "<table class='tabla' cellpadding='10' border='1' style='margin-left:3%;'>
	<tr>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	</tr>";
}
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	echo "<tr>
	<td><a href='registros.php?art=".$f['ARTICULO']."' style='text-decoration:none;'>".$f['ARTICULO']."</a></td>
	<td><a href='registros.php?art=".$f['ARTICULO']."' style='text-decoration:none;'>".$f['DESCRIPCION']."</a></td>
	</tr>";
}

?>
</div>
</div>
</body>
</html>