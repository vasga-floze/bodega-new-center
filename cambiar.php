<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	$(document).ready(function()
		{
			$(".menu").hide();
			$("#m").hide();
		});
</script>
<?php
error_reporting(0);
include("conexion.php");
$i=$_GET['i'];
$b=$_GET['b'];
$con=$conexion1->query("select * from consny.BODEGA where BODEGA='$b'")or die($conexion1->error);
$fcon=$con->FETCH(PDO::FETCH_ASSOC);
$bbo=$fcon['BODEGA'];
$nbo=$fcon['NOMBRE'];
$sbo="$bbo $nbo";

if($i==2)
{

	echo "<script>
	if(confirm('SEGURO DESEA ESTABLECER LA BODEGA: $sbo COMO DESTINO'))
	{
		location.replace('cambiar.php?i=3&&b=$b');

		}else { location.replace('traslados.php'); } </script>"; }else if($i==3) {
$ncon=$con->rowCount(); if($ncon==0) { echo "<script>alert('SE PRODUJO UN
ERROR. SELECIONE NUEVAMENTE LA BODEGA')</script>"; echo
"<script>location.replace('cambiar.php')</script>"; }else {
$doc=$_SESSION['doc']; $conexion2->query("update traslado set destino='$bbo'
where sessiones='$doc'")or die($conexion2->error); echo
"<script>location.replace('traslados.php')</script>"; } } ?> </head> <center>
<body> <div class="detalle" style="margin-top: -5%;"> <a
href="traslados.php" style="float: right; margin-right: 0.5%; text-decoration:
none; color: white;">Cerrar X</a> <div class="adentro" style="margin-top:
0.5%; height: 93%; margin-left: 0.5%; width: 95%;"> <form method="POST">
<input type="text" name="buscar" placeholder="CODIGO O NOMBRE BODEGA"
class="text" style="width: 30%;"> <input type="submit" name=""
value="BUSCAR">
	
</form>
	
	<?php
	extract($_REQUEST);
	
	$doc=$_SESSION['doc'];
	if($_POST)
	{
		$c=$conexion1->query("select * from consny.BODEGA where BODEGA='$buscar' or nombre like'%$buscar%' and nombre not like '%(N)%'")or die($conexion1->error);
	}else
	{
		$c=$conexion1->query("select * from consny.BODEGA where nombre not like '%(N)%'")or die($conexion1->error);
	}
	if($doc=="")
	{
		echo "<script>location.replace('traslados.php')</script>";
	}
	

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h2>NO SE ENCONTRO NINGUN REGISTRO</h2>";
	}else
	{
		echo "<h3>SELECIONA LA NUEVA BODEGA DE DESTINO</h3>";
echo"<table class='tabla' border='1' cellpadding='10'>";
echo "<tr>
<td>BEDEGAS DISPONIBLES</td>
</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$b=$f['BODEGA'];
		$n=$f['NOMBRE'];
		$bodega="$b: $n";

		echo "<tr>
		<td><label><a href='cambiar.php?i=2&&b=$b' style='text-decoration:none; color:black;'>$bodega</a></label></td>
			
		</tr>";
	}
	echo "</table>";

	}
	
	?>
		
	</form>
		
	</div>
	
</div>
</body>
</html>