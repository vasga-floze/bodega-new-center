<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function imprimir()
		{
			$("#me").hide();
			$("#img").hide();
			$("#img1").hide();
			window.print();
			$("#me").show();
			$("#img").show();
			$("#img1").show();

		}
	</script>
</head>
<body>

<?php
echo '<div id="me">';
include("conexion.php");
error_reporting(0);
echo "</div>
<img src='imprimir.png' width='5%' height='5%' style='float:right; margin-right:0.5%; cursor:pointer; margin-top:-3%;' onclick='imprimir()' id='img'>";
$id=1;
if($id=="")
{
	echo "<script>alert('SE PRODUJO UN ERROR INTENTELO NUEVAMENTE')</script>";
	echo "<script>location.replace('contenedor.php')</script>";
}else
{
	

	$c=$conexion2->query("select * from registro where id_registro='2092' or id_registro='2075' or id_registro='2088' or id_registro='2090'")or die($conexion2->error);
	$n=$c->rowCount();

		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$barra=$f['barra'];
			$cod=$f['codigo'];
		$cd=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error);
		$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
		$de=$fcd['DESCRIPCION'];
		$de="$cod: $de";
		$de=substr($de, 0,30);


		echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
		if($nums==8)
		{
			echo "<br><br><br><br><br>";
			$nums=1;
		}
		$nums++;
		}
	}

?>
</body>
</html>
