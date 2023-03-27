<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function imprimir()
		{
			$("#cone").hide();
			$("#imprimir").hide();
			window.print();
			$("#cone").show();
			$("#imprimir").show();

		}
	</script>
</head>
<body>
<?php
error_reporting(0);
echo "<div id='cone'>";
include("conexion.php");
echo "</div>";
$id=base64_decode($_GET['id']);
$tipo=$_GET['tipo'];
echo "<img src='imprimir.png' width='2.5%;' height='2.5%' style='float:right; margin-right:0.5%; cursor:pointer;' id='imprimir' onclick='imprimir()'>";
if($tipo==2)
{
	$c=$conexion2->query("select * from registro where id_registro='$id' and activo is null")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>location.replace('reimprimir_viñeta.php')</script>";
	}else
	{
		
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		
		$art=$f['codigo'];
		$barra=$f['barra'];
		$ca=$conexion1->query("select ARTICULO,DESCRIPCION from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error);
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$co=$fca['ARTICULO'];
$de=$fca['DESCRIPCION'];
$text="$co: $de";
$de=substr($text, 0,30);

	echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
	echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
	}
}else if($tipo==1)
{
	$peso=$_GET['peso'];
	$art=$_GET['art'];
	$fechai=$_GET['fechai'];
	$contenedor=$_GET['contenedor'];
	$fecha=$_GET['fecha'];
	//echo "<script>alert('$peso | $art | $fechai | $contenedor | $fecha | $tipo')</script>";
	$c=$conexion2->query("select * from registro where contenedor='$contenedor' and fecha_documento='$fecha' and codigo='$art' and fecha_ingreso='$fechai' and activo is null")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('SE DIO UN ERROR INTENTELO NUEVAMENTE')</script>";
		echo "<script>location.replace('reimprimir_viñeta.php')</script>";
	}else
	{
		$num=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$num++;
				$art=$f['codigo'];
		$barra=$f['barra'];
		$ca=$conexion1->query("select ARTICULO,DESCRIPCION from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error);
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$co=$fca['ARTICULO'];
$de=$fca['DESCRIPCION'];
$text="$co: $de";
$de=substr($text, 0,30);
if($num==3)
{
	echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
	echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
	?>
			<br><br><br><br>
			<br><br><br><br><br><br><br><br>
			<?php
			echo '<div style="page-break-after: always"></div>';
			$num=0;
}else{
	echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
	echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div> ";
}
	


		}
	}
}
?>
</body>
</html>