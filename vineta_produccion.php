<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function imprimir()
		{
			$("#caja").hide();
			window.print();
			$("#caja").show();
		}
	</script>
</head>
<body>
	<div id="caja">
	<img src="imprimir.png" width="5%" height="5%" style="float: right; margin-right:0.5%; cursor: pointer;" onclick="imprimir()">
<?php
include("conexion.php");
echo "</div>";
if($_GET['barra']!='')
{
	$barra=base64_decode($_GET['barra']);
	$c=$conexion2->query("select * from registro where barra='$barra' and tipo='P' and activo is null")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('ERROR: NO ENCONTRADO $barra EN PRODUCCION DISPONIBLE')</script>";
		echo "<script>window.close()</script>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$text="".$f['codigo'].":".$f['subcategoria']."";
		$des=substr($text, 0,60);
		$peso=$f['lbs'];
		$obs=$f['observacion'];
		$obs=substr($obs, 0,60);
		$des="$des<br>obs: $obs<br>Fecha: ".$f['fecha_documento']."";

		echo "<div style='text-aling:center; float:none; width:90%; border:none; height:90%;'>
		<center><p style='margin-top:-6.5%;'>$des</p><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true' style='width:115%; height:115%; margin-top:-5%;'></center>";
	}

}else
{

?>
	<script>
		alert('ERROR: SE PERDIO LA CONEXION INTENTALO  NUEVAMENTE');
		window.close();
</script>
<?php		
}

?>
</body>
</html>