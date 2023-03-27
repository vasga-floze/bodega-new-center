<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
	<?php
	include("conexion.php");
	?>
<form method="POST">
<input type="date" name="desde" class="text" style="width: 20%;">
<input type="date" name="hasta" class="text" style="width: 20%;">
<input type="text" name="bodega" class="text" style="width: 20%;">
<input type="submit" name="">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$q=$conexion2->query("select min(fecha_documento) from registro ")or die($conexion2->error());
	$fq=$q->FETCH(PDO::FETCH_ASSOC);
	$fecha_inicio=$fq['fecha_documento'];

	$c=$conexion2->query("select codigo from registro where tipo!='C1' group by codigo")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$cod=$f['codigo'];
		$c1=$conexion2->query("select id_registro,lbs,peso from registro where fecha_traslado is null and bodega='$bodega' and fecha_documento between '$fecha_inicio' and '$desde' and codigo='$cod'")or die($conexion2->error());
		$num=0;
		$total=0;
		while($f1=$c1->FETCH(PDO::FETCH_ASSOC))
		{
			//
		}

	}//fin while de articulos



}//fin post
?>
</body>
</html>