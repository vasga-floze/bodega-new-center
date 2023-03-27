<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<meta charset="utf-8">
	<script>
		function imprimire()
		{
			$("#caja").hide();
			window.print();
			$("#caja").show();
		}
	</script>
</head>
<body style="font-family: Consolas, monaco, monospace;">
<div id="caja">
<?php
include("conexion.php");
echo "<img src='imprimir.png' width='5%' height='5%' style='cursor:pointer; float:right;' onclick='imprimire()'>";
echo "</div>";
$barra=$_GET['b'];
//echo "<script>alert('$barra')</script>";
$c=$conexion2->query("select * from ripio where barra='$barra'")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE PUDO GENERAR LA IMPRESION CORRECTAMENTE, INTENTA REIMPRIMIR</h3>";
}else
{
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$barra=$f['barra'];
	$clasificacion=$f['clasificacion'];
	$comentario=$f['comentario'];
	echo "<div style='width:100%; text-align:center; border:double; height:100%; font-family: Consolas, monaco, monospace;'><h1>RIPIO($clasificacion)</h1><br>";
	echo "<img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true' style='width:40%; height:40%; font-family: Consolas, monaco, monospace;'/>
	<p style='text-decoration:underline; font-size: 300%;'>$comentario</p>
	</div>";

}

?>
</body>
</html>