<!DOCTYPE html>
<html>
<head>
	
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			

		});
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
echo "</div>
<img src='imprimir.png' width='5%' height='5%' style='float:right; margin-right:0.5%; cursor:pointer; margin-top:-3%; ' onclick='imprimir()' id='img'>";
$id=$_GET['id'];
$peso=$_GET['p'];
$fecha_hora=$_GET['fecha_hora'];
$cant_get=$_GET['cant'];
if($_GET['id']=="")
{
	echo "<script>alert('SE PRODUJO UN ERROR INTENTELO NUEVAMENTE')</script>";
	echo "<script>location.replace('contenedor.php')</script>";
}else
{
	$fecha=$_SESSION['fecha'];
	$conte=$_SESSION['contenedor'];

	$c=$conexion2->query("select * from registro where codigo='$id' and peso='$peso' and fecha_documento='$fecha' and contenedor='$conte' and tipo='CD' and fecha_ingreso='$fecha_hora'")or die($conexion2->error);

	$cc=$conexion2->query("select count(*) as nu from registro where codigo='$id' and peso='$peso' and fecha_documento='$fecha' and contenedor='$conte' and tipo='CD' and fecha_ingreso='$fecha_hora' ")or die($conexion2->error);
	$fcc=$cc->FETCH(PDO::FETCH_ASSOC);
	$nu=$fcc['nu'];
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO REGISTRO INTENTELO NUEVAMENTE')</script>";
	echo "<script>location.replace('contenedor.php')</script>";
	}else
	{
		$nums=0;
echo '<a href="contenedor.php"><img src="atras.png" width="2.5%" height="2.5%" title="VOLVER" style="cursor: pointer; margin-top:-5%;" id="img1"></a><br>';
$ni=2;
$f=$c->FETCH(PDO::FETCH_ASSOC);
$art=$f['codigo'];
$cant=$f['cantidad'];
$cont=$f['contenedor'];
$peso=$f['peso'];
$barra=$f['barra'];
$ca=$conexion1->query("select ARTICULO,DESCRIPCION from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error);
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$co=$fca['ARTICULO'];
$de=$fca['DESCRIPCION'];
$text="$co: $de";
$de=substr($text, 0,30);

	echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";

	$nums=2;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{

			$art=$f['codigo'];
$cant=$f['cantidad'];
$cont=$f['contenedor'];
$peso=$f['peso'];
$barra=$f['barra'];
$ca=$conexion1->query("select ARTICULO,DESCRIPCION from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error());

			if($nums==6)
			{
			
				echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
			?>
			<br><br><br><br>
			<br><br><br><br><br><br><br><br>
			<?php
			echo '<div style="page-break-after: always"></div>';




				

				

				
				$nums=0;

			}else
			{
				echo "<div class='barra' ><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
			}
			$nums++;
		
		}
	}
}
?>
</body>
</html>
