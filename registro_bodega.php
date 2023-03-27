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
error_reporting(0);
?>
<form method="POST">
<input type="text" name="art" placeholder="ARTICULO" class="text" style="width: 40%;">
<input type="text" name="bodega" placeholder="BODEGA" class="text" style="width: 40%;">
<input type="submit" name="" value="BUSCAR" class="boton2">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	echo "<hr>";
	if($art!='' and $bodega!='')
	{

		$c1=$conexion2->query("select codigo,count(codigo) as cantidad from registro where bodega='$bodega' and codigo='$art' and activo is null and bodega='$bodega' group by codigo ")or die($conexion2->error());
		
		$c2=$conexion1->query("select cant_disponible from consny.existencia_bodega where articulo='$art' and bodega='$bodega'")or die($conexion1->error());

	

	echo "<table border='1' class='tabla' cellpadding='10'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>BODEGA</td>
		<td>CANTIDAD SISTEMA</td>
		<td>CANTIDAD EXACTUS</td>
		<td>DIFERENCIA</td>
	</tr>";
	$f1=$c1->FETCH(PDO::FETCH_ASSOC);
	$f2=$c2->FETCH(PDO::FETCH_ASSOC);
if($f1['cantidad']>$f2['cant_disponible'])
{
	$dif=$f1['cantidad'] - $f2['cant_disponible'];
}else if($f2['cant_disponible']>$f1['cantidad'])
{
	$dif=$f2['cant_disponible'] - $f1['cantidad'];
}else
{
	$dif=0;
}


	echo "<tr>
		<td>$art</td>
		<td>$bodega</td>
		<td>".$f1['cantidad']."</td>
		<td>".$f2['cant_disponible']."</td>
		<td>$dif</td>
	</tr>";

	

	

echo "</table>";
}else if($art=='' and $bodega!='')
{
	$c=$conexion2->query("select codigo,count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and codigo is not null  group by codigo")or die($conexion2->error());
	echo "<table border='1' class='tabla' cellpadding='10'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>BODEGA</td>
		<td>CANTIDAD SISTEMA</td>
		<td>CANTIDAD EXACTUS</td>
		<td>DIFERENCIA</td>
		<td>PENDIENTES</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['codigo'];
		$cant=$f['cantidad'];
		$ce=$conexion1->query("select cant_disponible from consny.existencia_bodega where articulo='$art' and bodega='$bodega'")or die($conexion1->error());
		$fce=$ce->FETCH(PDO::FETCH_ASSOC);

		$cv=$conexion1->query("select count(*)as pendiente from consny.linea_doc_inv where articulo='$art' and bodega='$bodega'")or die($conexion1->error());
		$fcv=$cv->FETCH(PDO::FETCH_ASSOC);
		$pendiente=$fcv['pendiente'];

		$cante=$fce['cant_disponible'];
		//if($cant!=$cante)
		//{
		if($cant>$cante)
		{
			$dif=$cant-$cante;
		}else if($cante>$cant)
		{
			$dif=$cante -$cant;
		}else
		{
			$dif=0;
		}
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		//if($dif>0)
		//{
			echo "<tr>
		<td>".$fca['ARTICULO'].": ".$fca['DESCRIPCION']."</td>
		<td>$bodega</td>
		<td>$cant</td>
		<td>$cante</td>
		<td>$dif</td>
		<td>$pendiente</td>";
		//}
		
	//}
	}
} else if($art!='' and $bodega=='')
{

	$c=$conexion2->query("select codigo,count(codigo) as cantidad,bodega from registro where codigo='$art' and activo is null group by codigo,bodega")or die($conexion2->error());
	echo "entra";
	echo "<table border='1' class='tabla' cellpadding='10'>";


	echo "<tr>
		<td>ARTICULO</td>
		<td>BODEGA</td>
		<td>CANTIDAD SISTEMA</td>
		<td>CANTIDAD EXACTUS</td>
		<td>DIFERENCIA</td>
		<td>PENDIENTES</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$bodega=$f['bodega'];
		$cant=$f['cantidad'];
		$ce=$conexion1->query("select cant_disponible from consny.existencia_bodega where articulo='$art' and bodega='$bodega'")or die($conexion1->error());

		$fce=$ce->FETCH(PDO::FETCH_ASSOC);
		$canti=$fce['cant_disponible'];

		if($cant>$canti)
		{
			$dif=$cant -$canti;
		}else if($canti>$cant)
		{
			$dif=$canti - $cant;
		}else
		{
			$dif=0;
		}
		$cp=$conexion1->query("select count(*) as total from consny.linea_doc_inv where articulo='$art' and bodega='$bodega'")or die($conexion1->error);
		$fcp=$cp->FETCH(PDO::FETCH_ASSOC);
		$pendiente=$fcp['total'];
	echo "<tr>
		<td>$art</td>
		<td>$bodega</td>
		<td>$cant</td>
		<td>$canti</td>
		<td>$dif</td>
		<td>$pendiente</td>
	</tr>";

	}
	echo "</table>";
}
}
?>
</body>
</html>