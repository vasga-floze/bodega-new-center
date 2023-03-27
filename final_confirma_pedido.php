<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	$(document).ready(function(){

	})
	function imprimir()
	{
		$("#imprimir").hide();
		$("#caja").hide();
		window.print();
		$("#imprimir").show();
		$("#caja").show();

	}
</script>
<?php
echo "<div id='caja'>";
include("conexion.php");
echo "</div>";
$bodega=$_GET['bodega'];
$usuario=$_GET['usuario'];
$fecha=$_GET['fecha'];
//echo "<script>alert('$bodega $usuario $fecha')</script>";
echo "<img src='imprimir.png' width='4%' height='6%' style='float:right; margin-right:0.5%; cursor:pointer;' title='IMPRIMIR' onclick='imprimir()' id='imprimir'>";
if($bodega=='' or $usuario=='' or $fecha=='')
{
	echo "<script>alert('SE DIO UN ERROR DE IMPRESION')</script>";
	echo "<script>location.replace('pedidos_confirma.php')</script>";
}else
{
	$c=$conexion2->query("select * from pedidos where fecha_hora_confirma='$fecha' and usuario_confirma='$usuario' and tienda='$bodega' and estado='CONFIRMADO' and despacho='N' order by articulo")or die($conexion2->error());
	$c1=$conexion2->query("select convert(date,fecha) as fecha from pedidos where fecha_hora_confirma='$fecha' and usuario_confirma='$usuario' and tienda='$bodega' and estado='CONFIRMADO' and despacho='N' order by articulo")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO EL PEDIDO CONFIRMADO')</script>";
	echo "<script>location.replace('pedidos_confirma.php')</script>";
	}else
	{
		$fc1=$c1->FETCH(PDO::FETCH_ASSOC);
		$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$nomb="".$fcb['BODEGA'].":".$fcb['NOMBRE']."";
		$fechap=$fc1['fecha'];
		echo "<table border='1' style='border-collapse: collapse; width:98%; font-size:14px;'>";
		echo "<tr>
		<td colspan='6'>PEDIDO DE: $nomb</td>
		</tr>";

		echo "<tr>
		<td>CLASIFICACION</td>
		<td width='15%'>ARTICULO</td>
		<td width='50%'>DESCRIPCION</td>
		<td>EXISTENCIA</td>
		<td>CANT. SOLICITADA</td>
		<td>CANT. CONFIRMADA</td>
		</tr>";
		$tt=0;
		$te=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$clasi=$f['clasificacion'];
			$cantidadt=$f['cantidad_tienda'];
			$cantidade=$f['cantidad_enviada'];
			$exi=$f['existencia_tienda'];
			$art=$f['articulo'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$arti=$fca['ARTICULO'];
			$desc=$fca['DESCRIPCION'];
			if($cantidade>0)
			{
				echo "<tr>
		<td>$clasi</td>
		<td>$arti</td>
		<td>$desc</td>
		<td style='text-align:right;'>$exi</td>
		<td style='text-align:right;'>$cantidadt</td>
		<td style='text-align:right;'>$cantidade</td>
		</tr>";
		$tt=$tt+$cantidadt;
		$te=$te +$cantidade;
			}
			
		
		}
		echo "<tr>
		<td colspan='4'>TOTAL</td>
		<td style='text-align:right;'>$tt</td>
		<td style='text-align:right;'>$te</td>

		</tr>";
	}
}
?>