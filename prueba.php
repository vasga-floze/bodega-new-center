<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
<?php
include("conexion.php");
$c=$conexion2->query("select concat(codigo,': ',subcategoria) articulo,barra from registro where id_registro=245641
")or die($conexion2->error());
$f=$c->FETCH(PDO::FETCH_ASSOC);
$des=$f['articulo'];
$barra=$f['barra'];
$cd=$conexion2->query("select * from detalle where registro=245641")or die($conexion2->error());
echo "<div class='barra' style='text-aling:center; float:none; width:98%; height:98%; border:none;'>
		<h4>$des</h4><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true' style='width:40%; height:40%;'/><br>";
		$n=0;
while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
{
	$n++;
	if($n==8)
	{
		echo "<br>";
		$n=0;
	}



		$id=$fcd['id'];
		echo "<img src='barcode/barcode.php?text=$id\n&size=80&codetype=Code39&print=false' style='width:10%; height:10%; float:left; margin-left:0.5%;'/>";

}
		echo "</div>";
?>
</body>
</html>
