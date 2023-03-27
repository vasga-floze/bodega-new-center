<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST">
<input type="text" name="art" placeholder="ARTICULO" class="text" style="width: 30%;">
<input type="text" name="bodega" placeholder="BODEGA" class="text" style="width: 30%;">
<input type="submit" name="btn" value="BUSCAR" class="boton3">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($art!=''and $bodega!='')
	{
		$c=$conexion1->query("select articulo,cant_disponible,bodega from consny.existencia_bodega where articulo='$art' and bodega='$bodega'")or die($conexion1->error());
	}else if($art=='' and $bodega!='')
	{
		$c=$conexion1->query("select articulo,cant_disponible,bodega from consny.existencia_bodega where bodega='$bodega'")or die($conexion1->error());

	}else if($bodega=='' and $art!='')
	{
		$c=$conexion1->query("select articulo,cant_disponible,bodega from consny.existencia_bodega where articulo='$art'")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("select articulo,cant_disponible,bodega from consny.existencia_bodega")or die($conexion1->error());
	}

	echo "<table border='1' class='tabla'cellpadding='10'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD EXACTUS</td>
		<td>CANTIDAD SISTEMA</td>
		<td>BODEGA</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['articulo'];
		$cant_e=$f['cant_disponible'];
		$bode=$f['bodega'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$articulo=$fca['ARTICULO'];
		$descripcion=$fca['DESCRIPCION'];

		$cr=$conexion2->query("select count(codigo) as cantidad from registro where codigo='$articulo' and bodega='$bode' group by codigo")or die($conexion2->error());
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$cant_s =$fcr['cantidad'];
		echo "<tr>
		<td>$articulo</td>
		<td>$descripcion</td>
		<td>$cant_e</td>
		<td>$cant_s</td>
		<td>$bode</td>
	</tr>";

	}


}
?>


</body>
</html>