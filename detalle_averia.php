<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function cerrar()
		{
			window.close();
		}
	</script>
</head>
<body> 
<div style="display: none;">

		
	
<?php 
include("conexion.php");
echo "</div>";
?>
<div class="detalle">
<a href="href='#'" onclick="cerrar()" style="color: white; text-decoration: none; float: right;">CERRAR X</a>
<div class="adentro" style="margin-left: 2%;">
<?php
$fecha=$_GET['fecha'];
$bodega=$_GET['bodega'];
$tipo=$_GET['tipo'];
if($tipo=='AVERIA')
{
	$c=$conexion2->query("select * from averias where tipo='AVERIA' and fecha='$fecha' and bodega='$bodega'")or die($conexion2->error());
	$m="AVERIA";

}else
{
	$c=$conexion2->query("select * from averias where tipo='MERCADERIA NO VENDIBLE' and fecha='$fecha' and bodega='$bodega'")or die($conexion2->error());
	$m="MERCADERIA NO VENDIBLE";
}
$n=$c->rowcount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUNA $m</h3>";
}else
{
	$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
	$nom=$fcb['NOMBRE'];
	echo "<table border='1' cellpadding='10' style='border-collapse:collapse; margin-left:0.8%; width:98%;'>";
	echo "<tr>
		<td colspan='8'>DETALLE $m DE: $bodega: $nom FECHA: $fecha</td>
	</tr>";
	echo "<tr>
			<td>FECHA</td>
			<td>BODEGA</td>
			<td>TIPO</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>PRECIO</td>
			<td>CANTIDAD</td>
			<td>TOTAL</td>
			
		  </tr>";
		  $t=0;
		  $total=0;
		  while($f=$c->FETCH(PDO::FETCH_ASSOC))
		  {
		  	$art=$f['articulo'];
		  	$precio=$f['precio'];
		  	$cant=$f['cantidad'];
		  	$fecha=$f['fecha'];
		  	$bodega=$f['bodega'];
		  	$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		  	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		  	$arti=$fca['ARTICULO'];
		  	$desc=$fca['DESCRIPCION'];

		  	$t=$precio*$cant;
		  	$e=explode(".", $precio);
		  	if($e[0]=='')
		  	{
		  		$precio="0.$e[1]";
		  	}
		  	$cb=$conexion1->query("select concat(bodega,': ',nombre) as nom from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		  	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		  	$nombode=$fcb['nom'];
		  	 echo "<tr>
		  	 <td>$fecha</td>
			 <td>$nombode</td>
		  	 <td>$m</td>
			<td>$arti</td>
			<td>$desc</td>
			<td>$precio</td>
			<td>$cant</td>
			<td>$t</td>

		  </tr>";
		  $total=$total +$t;
		  }
		  echo "<tr>
		  	<td colspan='7'>TOTAL</td>
		  	<td>$total</td>
		  </tr></table>";
}
?>	
</div>
</div>
</body>
</html>