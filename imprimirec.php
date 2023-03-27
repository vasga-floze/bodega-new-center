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
	echo "</div>";
	$id=$_GET['id'];
	$c=$conexion2->query("select * from registro where id_registro='$id'")or die($conexion2->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$cod=$f['codigo'];
	$barra=$f['barra'];

	$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
	$art=$fca['ARTICULO'];
	$des=$fca['DESCRIPCION'];
	$barra=$f['barra'];
	$text="$art: $des";
	$des=substr($text, 0.30); 
		echo "<div class='barra' style='text-aling:center; float:none; width:98%; height:98%; border:none;'>
		<h4>$des</h4><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true' style='width:60%; height:60%;'/>";
	?>
</div>
</body>
</html>