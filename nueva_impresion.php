<!DOCTYPE html>
<html>
<head>
	<title></title>
<script>
	window.print();
</script>
</head>
<body>


<link rel="stylesheet" type="text/css" href="style.css">
<center>
<?php
echo "<div style='display:none;'>";
include("conexion.php");
echo "</div>";
$cod=$_GET['id'];
$peso=$_GET['p'];
$fecha_hora=$_GET['fecha_hora'];
$fecha=$_SESSION['fecha'];
$contenedor=$_SESSION['contenedor'];
$cant=$_GET['cant'];
//echo "<script>alert('$cod - $peso - $fecha - $contenedor - $fecha_hora')</script>";
if($cod!='' and $peso!='' and $fecha!='' and $contenedor!='' and $fecha_hora!='' and $cant!='')
{



$c=$conexion2->query("select * from registro where fecha_documento='$fecha' and contenedor='$contenedor' and codigo='$cod' and peso='$peso' and fecha_ingreso='$fecha_hora' ")or die($conexion2->error());	
$n=$c->rowCount();
//echo "<script>alert('$n <-')</script>";
if($n!=0)
{
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$cod=$f['codigo'];
		$barra=$f['barra'];
		$ca=$conexion1->query("select  * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);

		$cb=$conexion1->query("select  * from consny.articulo where articulo='$cod' and descripcion like '%PACA%'")or die($conexion1->error());
		$ncb=$cb->rowCount();

		$art=$fca['ARTICULO'];
		$des=$fca['DESCRIPCION'];
		$text="$art: $des";
	
		$des=substr($text, 0,25);
		if($ncb!=0)
		{
			$libra=$f['peso'];
			$des="$des($libra)";

		}
		echo "<div class='barra' style='text-aling:center; float:none; width:98%; height:98%; border:none;'>
		<h4>$des</h4><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true' style='width:60%; height:60%;'/>	
		</div>";
		echo '<div style="page-break-after: always"></div>';



	}

}else
{
	echo "<script>alert('SE PRODUJO UN ERROR DE CONEXION INTENTELO NUEVAMENTE')</script>";
  echo "<script>location.replace('contenedor.php')</script>";
}
}else
{
	echo "<script>alert('SE PRODUJO UN ERROR DE CONEXION INTENTELO NUEVAMENTE')</script>";
  echo "<script>location.replace('contenedor.php')</script>";
}



?>
</center>
</body>
</html>