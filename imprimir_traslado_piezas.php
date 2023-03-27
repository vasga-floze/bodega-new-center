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
			window.print();
			$("#caja").show();
		}
	</script>
</head>
<body>
	<div id="caja">
	<img src="imprimir.png" width="5%" height="5%" style="float: right; margin-right: 0.5%; cursor:pointer;" onclick="imprimir()">
<?php
include("conexion.php")
?>
</div>
<?php
$doc=$_GET['doc'];
$usuario=$_GET['usu'];
$c=$conexion2->query("select * from traslado_piezas where session='$doc' and usuario='$usuario'")or die($conexion2->error());
$fila=1;
$total=0;
$num=1;
$cr=$conexion2->query("select count(*) as cantidad from traslado_piezas where session='$doc' and usuario='$usuario'")or die($conexion2->error());
$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
$paginas=$fcr['cantidad']/37;
$paginas=ceil($paginas);
$pag=1;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$art=$f['articulo'];
	$origen=$f['origen'];
	$destino=$f['destino'];
	$cantidad=$f['cantidad'];
	$documento=$f['documento'];
	$fecha=$f['fecha'];
	$empresa=$f['empresa'];
	$paquete=$f['paquete'];
	$total=$total+$cantidad;
	$co=$conexion1->query("select * from $empresa.bodega where bodega='$origen'")or die($conexion1->error());
	$fco=$co->FETCH(PDO::FETCH_ASSOC);
	$cd=$conexion1->query("select * from $empresa.bodega where bodega='$destino'")or die($conexion1->error());
	$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
	$origen="".$fco['BODEGA'].": ".$fco['NOMBRE']."";
	$destino="".$fcd['BODEGA'].": ".$fcd['NOMBRE']."";
	$ca=$conexion1->query("select * from $empresa.articulo where articulo='$art'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
	if($fila==1)
	{
		$cj=$conexion1->query("select * from conjunto where conjunto='$empresa'")or die($conexion1->error());
		$fcj=$cj->FETCH(PDO::FETCH_ASSOC);
		$titulo=$fcj['NOMBRE'];
		echo "<h1 style='margin-bottom:-0.5%; float:left'>$titulo</h1>";
		echo "<p style='float:right; margin-bottom:-0.5%;'>PAGINA: $pag - $paginas</p>";
		echo "<table border='1' style='border-collapse:collapse; width:100%; '>";
		echo "<tr>
			<td colspan='2'>ORIGEN: $origen</td>
			<td rowspan='3' style='text-align:left;' width='35%'>FECHA:$fecha<br>DOCUMENTO: $documento</td>
		</tr>";

		echo "<tr>
			<td colspan='2'>DESTINO: $destino</TD>
		</tr>";

		echo "<tr>
			<td colspan='2'>PAQUETE: $paquete</TD>
		</tr>
		<tr>
			<td colspan='3'>
		</tr>";

		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
		</tr>";
		$fila=0;

		
	}
	
	
		if($num==37)
		{
			$num=0;
			$fila=0;
			$pag++;
			echo "</table> <div style='page-break-after: always'></div>";

			echo "<h1 style='margin-bottom:-3%;'>$titulo</h1>";
		echo "<p style='float:right; margin-top:0.5%;'>PAGINA: $pag - $paginas</p>";

		echo "<table border='1' style='border-collapse:collapse; width:100%; margin-top:3%;'>";
		echo "<tr>
			<td colspan='2'>ORIGEN: $origen</td>
			<td rowspan='3' style='text-align:left;' width='35%'>FECHA:$fecha<br>DOCUMENTO: $documento</td>
		</tr>";

		echo "<tr>
			<td colspan='2'>DESTINO: $destino</TD>
		</tr>";

		echo "<tr>
			<td colspan='2'>PAQUETE: $paquete</TD>
		</tr>
		<tr>
			<td colspan='3'>
		</tr>";

		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
		</tr>";
		echo "<tr>
			<td>".$fca['ARTICULO']." $num</td>
			<td>".$fca['DESCRIPCION']."</td>
			<td>$cantidad</td>
		</tr>";
		}else
		{
			echo "<tr>
			<td>".$fca['ARTICULO']." $num</td>
			<td>".$fca['DESCRIPCION']."</td>
			<td>$cantidad</td>
		</tr>";
		}
		
		$num++;

}
echo "<tr>
	<td colspan='2'>TOTAL</td>
	<td>$total</td>
</tr></table>";

echo " <br><label style='text-decoration:overline; margin-top:5%; text-align:center;'>ENTREGA&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label> <label style='text-decoration:overline; margin-top:1%; float:right; text-alogn:center;margin-right:0.5%;'>RECIBE &nbsp; &nbsp; &nbsp; &nbsp;</label>";
?>
</body>
</html>