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
$fecha=$_GET['fecha'];
$bodega=$_GET['bodega'];
?>	
</div>
<div class="detalle">
<a href="#" onclick="cerrar()" style="float: right; margin-right: 0.5%; color: white; text-decoration: none;">CERRAR X</a>
<div class="adentro" style="margin-left: 3%; height: 92%;">
<?php
$c=$conexion1->query("declare @fecha datetime='$fecha'
declare @bodega nvarchar(10)='$bodega'
SELECT        consny.DOCUMENTO_POS.DOCUMENTO, consny.DOCUMENTO_POS.TIPO, 
CASE TIPO WHEN 'F' THEN 
consny.DOCUMENTO_POS.TOTAL_PAGAR
ELSE
consny.DOCUMENTO_POS.TOTAL_PAGAR *-1 END TOTAL, 
CASE TIPO WHEN 'F' THEN 
(consny.DOCUMENTO_POS.DESCUENTO)*1.13
ELSE
((consny.DOCUMENTO_POS.DESCUENTO)*1.13*-1) END
DESCUENTO, 
consny.DOCUMENTO_POS.FCH_HORA_COBRO
FROM            consny.DOCUMENTO_POS INNER JOIN
                         consny.CAJA_POS ON consny.DOCUMENTO_POS.CAJA = consny.CAJA_POS.CAJA
WHERE        (consny.DOCUMENTO_POS.FCH_HORA_COBRO BETWEEN DATEADD(MI, 1, @fecha) AND DATEADD(MI, 1439, @fecha))
and consny.CAJA_POS.BODEGA=@bodega
ORDER BY FCH_HORA_COBRO
")or die($conexion1->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h1><center>FECHA: $fecha NO SINRONIZADA...</center></h1>";

}else
{
	$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
	$nom=$fcb['NOMBRE'];
	echo "<table border='1' style='border-collapse:collapse; width:98%; margin-left:1%;' cellpadding='8'>";
	echo "<tr>
				<td colspan='5'>DETALLE MONTO SISTEMA DE: $bodega: $nom FECHA: $fecha</td>
			</tr>";
	echo "<tr>
		<td>DOCUMENTO</td>
		<td>TIPO</td>
		<td>TOTAL</td>
		<td>DESCUENTO</td>
		<td>FECHA Y HORA COBRO</td>
	</tr>";
$t=0;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$doc=$f['DOCUMENTO'];
	$tipo=$f['TIPO'];
	$total=$f['TOTAL'];
	$descuento=$f['DESCUENTO'];
	$fechaH=$f['FCH_HORA_COBRO'];
	$qd=$conexion1->query("declare @total varchar(50)=$total; declare @descuento varchar(50)=$descuento; select convert(decimal(10,2),@total) as total,convert(decimal(10,2),@descuento) as descuento")or die($conexion1->error());
	$fqd=$qd->FETCH(PDO::FETCH_ASSOC);
	$total=$fqd['total'];
	$descuento=$fqd['descuento'];
	$ed=explode(".", $descuento);
	if($ed[0]=='')
	{
		$descuento="0.$ed[1]";
	}
	$et=explode(".", $total);
	if($et[0]=='')
	{
		$total="0.$et[1]";
	}
	if($tipo=='F')
	{
		$tipo="FACTURA";
	}
	echo "<tr>
		<td>$doc</td>
		<td>$tipo</td>
		<td>$total</td>
		<td>$descuento</td>
		<td>$fechaH</td>
	</tr>";
	$t=$t + $total;

}

$cf=$conexion1->query("declare @t varchar(50)=$t; select convert(decimal(10,2),@t) as t")or die($conexion1->error());
$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
$t=$fcf['t'];
echo "<tr>
	<td colspan='2'>TOTAL</td>
	<td>$t</td>
	<td colspan='2'></td>
</tr>";

}
?>
</div>	
</div>
</body>
</html>