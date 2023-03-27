<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function imprimere()
		{
			$("#print").hide();
			$("#caja").hide();
			window.print();
			$("#print").show();
			$("#caja").show();
		}
	</script>
</head>
<body>
	<img src="imprimir.png" id='print'
	 style='width:5%; height: 5%; float: right; margin-right: 0.5%;' onclick="imprimere()">
<?php
echo "<div id='caja' style='display:none;'>";
include("conexion.php");
ECHO "</div>";

$c=$conexion2->query("declare @bod varchar(10)='CA00'
declare @art varchar(10)='FARD0-1079'


select top 2 eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,registro.barra,registro.peso from registro inner join EXIMP600.consny.articulo on registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO where registro.bodega=@bod and registro.codigo=@art and registro.activo is null and registro.tipo='CD'

")or die($conexion2->error());

while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$art=$f['articulo'];
	$desc=$f['descripcion'];
	$barra=$f['barra'];
	$peso=$f['peso'];
	$text="$art: $desc";
	$des=substr($text, 0,30);
		$des="$des($peso)";
		echo "<div class='barra' style='text-aling:center; float:none; width:98%; height:98%; border:none;'>
		<h4>$des</h4><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true' style='width:60%; height:60%;'/>	
		</div>";
		echo '<div style="page-break-after: always"></div>';

}
?>
</body>
</html>