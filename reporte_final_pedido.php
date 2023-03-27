<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function imprimir()
		{
			$("#caja").hide();
			$("#imprimir").hide();
			window.print();
			$("#caja").show();
			$("#imprimir").show();

		}
	</script>
</head>
<body>


<?php
error_reporting(0);
echo "<div id='caja'>";
include("conexion.php");
echo '<a href="reporte_pedidos.php">Volver</a>';
echo "</div>";

echo "<img src='imprimir.png' id='imprimir' width='4%' height='5%' onclick=imprimir() style='float:right; margin-right:0.5%; cursor:pointer;'>";
if($_POST)
{
	extract($_REQUEST);
	$t=1;
	$n=0;
	while($n<=$num)
	{
		if($t==1)
		{
			$w="where tienda='000000'";
			$t++;
		}
		
		if($bodega[$n]!='')
		{
			$w.=" or tienda='$bodega[$n]'";

		}
		$n++;
	}
	$bodegas=str_replace("where",'BODEGAS: ', $w);
	$bodegas=str_replace("or", " ", $bodegas);
	$bodegas=str_replace("tienda=", "", $bodegas);
	$bodegas=str_replace("'", "", $bodegas);
	$bodegas=str_replace("000000", '', $bodegas);
	$bodegas=str_replace(": ;",'', $bodegas);

	$c=$conexion2->query("select articulo,sum(cantidad_enviada) as cantidad from pedidos $w and estado='CONFIRMADO' AND despacho='N' group by articulo")or die($conexion2->error());

	echo "<table border='1' cellpadding='5' style='border-collapse:collapse; width:98%; font-size:14px;'>";
echo "<tr>
	<td colspan='3'>$bodegas</td>
</tr>";
	echo "<tr>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	<td>CANTIDAD</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['articulo'];
		$cantidad=$f['cantidad'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$articulo=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];
		if($cantidad!='' and $cantidad>0)
		{
				echo "<tr>
	<td>$articulo</td>
	<td>$desc</td>
	<td>$cantidad</td>
	</tr>";
		}
	

	}



	

}else
{
	echo "<script>location.replace('reporte_pedidos.php')</script>";
}
?>
</body>
</html>