<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
echo "<div style='display:none; margin-bottom:-20%;'>";
include("conexion.php");
echo "</div>";
$id=$_GET['id'];
$c=$conexion2->query("select * from registro where id_registro='$id'")or die($conexion2->error());

$f=$c->FETCH(PDO::FETCH_ASSOC);
$barra=$f['barra'];
$cod=$f['codigo'];
$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$art=$fca['ARTICULO'];
$des=$fca['DESCRIPCION'];
$fecha=$f['fecha_documento'];
$obs=$f['observacion'];
$text="$art: $des";
$art=substr($text, 0,35);
echo "<center><div style='width:100%; height:100%;'>";
echo "$art<br>";
echo "$fecha<img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true' style='width:80%; height:80%;'/>";
echo "<h5 style='text-aling:left; margin-top:-6%; text-decoration: underline; margin-bottom:-1%; '>$obs</h5></center>";
echo "</div>";

?>
</body>
</html>