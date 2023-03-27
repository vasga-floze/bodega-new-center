<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function imprimir()
		{
			$("#conexion").hide();
			$("#img").hide();
			window.print();
			$("#conexion").show();
			$("#img").show();
		}
	</script>
</head>
<body>
<?php
error_reporting(0);
echo "<div id='conexion'>";
include("conexion.php");
echo "</div>";

echo "<img src='imprimir.png' width='5%' height='5%' style='float:right; cursor:pointer;' id='img' onclick='imprimir();'>";
$ventacod=$_GET['ventacod'];
$usuario=$_SESSION['usuario'];
if($_GET['usuario']!='')
{
	$usuario=$_GET['usuario'];
}
if($ventacod==''or $usuario=='')
{
	echo "<script>location.replace('ventacod.php')</script>";
	//error redirecionar
}else
{
	//impresion
	$c=$conexion2->query("select * from venta where sessiones='$ventacod' and usuario='$usuario'")or die($conexion2->error());
	$nc=$c->rowCount();
	if($nc==0)
	{
		echo "<script>location.replace('ventacod.php')</script>";
	}
	$f=$c->FETCH(PDO::FETCH_ASSOC);

	echo "<table border='1' cellpadding='5' style='border-collapse:collapse; width:100%;'>";
	echo "<tr>
	<td colspan='3'>Cliente: ".$f['cliente'] ."<br><br>
		Fecha: ".$f['fecha']."<br><br>
		Observacion: ".$f['observacion']."<br>
	</td>
	<td colspan='2' width='20%'>
	<img src='logo.png' width='50%' height='8%'>
	<br>
	".$f['documento_inv']."
	</td>
	</tr>";

	echo "<tr>
		<td>#</td>
		<td>CANTIDAD</TD>
		<td>DESCRIPCION</TD>
		<td>PRECIO</TD>
		<td>TOTAL</TD>
	</tr>";
	$c=$conexion2->query("select * from venta where sessiones='$ventacod' and usuario='$usuario' and articulo is not null")or die($conexion2->error());
	$n=1;
	$totalp=0;
	$tc=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['articulo'];
		$cantidad=$f['cantidad'];
		$precio=$f['precio'];
		$total=$precio * $cantidad;
		$totalp=$totalp +$total;
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error()); 
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);

		echo "<tr>
		<td>$n</td>
		<td>$cantidad</TD>
		<td>".$fca['DESCRIPCION']."</TD>
		<td>$precio</TD>
		<td>$$total</TD>
	</tr>";
	$tc=$tc+$cantidad;
	$n++;
	}

	echo "<tr>
		<td>TOTAL</td>
		<td>$tc</td>
		<td colspan='2'></td>
		<td>$$totalp</td>
	</tr>";
	echo "<tr>
			<td colspan='5'>Vendedor: ______________ Cliente: ______________</td>
	</tr>";
}
?>
</body>
</html>