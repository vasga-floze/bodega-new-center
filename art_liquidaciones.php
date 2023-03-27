<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<div style="display: none;">
<?php
include("conexion.php");
$tipo=$_GET['tipo'];
$art=$_GET['art'];
$art1=$_GET['art1'];
$autoriza=$_GET['autoriza'];
$fecha=$_GET['fecha'];

?>
</div>
<div class="detalle">
<?php
echo "
<a href='liquidaciones.php?art1=$art1&&art=$art&&autoriza=$autoriza&&fecha=$fecha' style='float: right; margin-right: 0.5%; color: white; text-decoration: none;'>";
?>
CERAR X</a>
<div class="adentro" style="margin-left: 2.7%;">
<form method="POST">
<input type="text" name="b" class="text" style="width: 30%; margin-left: 1.5%;">
	
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion1->query("select * from consny.articulo where clasificacion_1='DETALLE' AND articulo='$b' or descripcion like (SELECT '%'+REPLACE('$b',' ','%')+'%')")or die($conexion1->error());

}else
{
	$c=$conexion1->query("select * from consny.articulo where clasificacion_1='DETALLE' and activo='S'")or die($conexion1->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN ARTICULO DISPONIBLE</h3>";
}else
{
	echo "<table border='1' style='border-collapse:collapse; width:100%;' cellpadding='10'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>

	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$articulo=$f['ARTICULO'];
		echo "";
		echo "<tr>
		<td><a href='liquidaciones.php?articulo=$articulo&&tipo=$tipo&&art=$art&&art1=$art1&&autoriza=$autoriza&&fecha=$fecha' style='text-decoration:none;'>".$f['ARTICULO']."</a></td>
		<td><a href='liquidaciones.php?articulo=$articulo&&tipo=$tipo&&art=$art&&art1=$art1&&autoriza=$autoriza&&fecha=$fecha' style='text-decoration:none;'>".$f['DESCRIPCION']."</a></td>

	</tr>";
	}
}
?>
</div>
	
</div>
</body>
</html>