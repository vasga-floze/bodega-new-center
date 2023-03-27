
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	$(document).ready(function(){
		$("#menu").hide();
	});
</script>
<table border="1" style="float: left; width: 48%;" class="tabla">
	<tr>
		
		<td>Codigo</td>
		<td>Descripcion</td>
		
	</tr>
	
<?php
include("conexion.php");
$c=$conexion1->query("select COUNT(*) as numero from consny.ARTICULO")or die($conexion1->error);
$fi=$c->FETCH(PDO::FETCH_ASSOC);
$nn=$fi['numero'];
$d=$nn/2;
$d=ceil($d);
$n=1;
$co=$conexion1->query("select top $d * from consny.ARTICULO")or die($conexion1->error);
while($fco=$co->FETCH(PDO::FETCH_ASSOC))
{
	$cod=$fco['ARTICULO'];
	$des=$fco['DESCRIPCION'];
	echo "<tr>
			
			<td>$cod</td>
			<td>$des</td>
		</tr>";
		$n++;
}

$co1=$conexion1->query("select top $d * from consny.ARTICULO")or die($conexion1->error);
echo '<table border="1" style="float: left; width: 48%; margin-left:1%;" class="tabla">
	<tr>
		
		<td>Codigo</td>
		<td>Descripcion</td>
		
	</tr>';
while($f1=$co1->FETCH(PDO::FETCH_ASSOC))
{
	$cod1=$f1['ARTICULO'];
	$nom1=$f1['DESCRIPCION'];
  echo "<tr>
  		
  		<td>$cod1</td>
  		<td>$nom1</td>
  </tr>";
}

?>

<script type="text/javascript">
	window.print();
</script>