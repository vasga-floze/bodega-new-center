<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function imprimire()
		{
			$("#caja").hide();
			window.print();
			$("#caja").show();
		}
	</script>
</head>
<body style="font-family: Consolas, monaco, monospace;">
<div id="caja">
<img src="imprimir.png" width="5%" height="5%;" onclick="imprimire()" style="float: right; margin-right: 0.5%; cursor: pointer;">
	<?php
include("conexion.php");
echo "</div>";
$doc=$_GET['doc'];
$usuario=$_GET['u'];
$c=$conexion2->query("select concat(traslado.articulo,': ',EXIMP600.consny.articulo.
descripcion) articulo,registro.barra,traslado.documento_inv,traslado.origen,
traslado.destino,traslado.fecha,traslado.paquete from traslado inner join registro
on traslado.registro=registro.id_registro inner join eximp600.consny.
articulo on traslado.articulo=eximp600.consny.articulo.articulo where 
traslado.sessiones='$doc' and traslado.usuario='$usuario' order by traslado.articulo")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<script>alert('NO FUE POSIBLE GENERAR REPORTE INTENTALO NUEVAMENTE EN REIMPRIMIR TRASLADO EN UN MOMENTO')</script>";
	echo "<script>window.close()</script>";
}else
{
	$q=$conexion2->query("select count(*) cantidad from traslado where sessiones='$doc' and usuario='$usuario'")or die($conexion2->error());
	$fq=$q->FETCH(PDO::FETCH_ASSOC);
	$cantidad=$fq['cantidad'];

	
	$linea=1;
	$num=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$num++;
		if($num==1)
		{
			echo "<img src='logo.png' width='15%' height='15%' style='float:right; margin-right:0.5%;'>";
			echo "<table border='0' style='border-collapse:collapse; width:100%; margin-bottom:1%;'>";
		$ori=$f['origen'];
		$dest=$f['destino'];

		$cbo=$conexion1->query("select concat(bodega,': ',nombre) origen from consny.bodega where bodega='$ori'")or die($conexion1->error());
		$fcbo=$cbo->FETCH(PDO::FETCH_ASSOC);
		$ori=$fcbo['origen'];

		$cbd=$conexion1->query("select concat(bodega,': ',nombre) destino from consny.bodega where bodega='$dest'")or die($conexion1->error());
		$fcbd=$cbd->FETCH(PDO::FETCH_ASSOC);
		$dest=$fcbd['destino'];
		$documento_inv=$f['documento_inv'];
		echo "<tr>
		<td>

		PAQUETE: ".$f['paquete']."<br>
		FECHA: ".$f['fecha']."</td>
		<td>
		DOCUMENTO: ".$f['documento_inv']."<br>
		ORIGEN: $ori<br>
		DESTINO: $dest
		<tr><td colspan='2'><br>F. DESPACHO:______________  F. RECIBE:______________  F. ENTREGA:______________</td>
		</tr></table>";

		if($cantidad<=25)
	{
		echo "<table border='1' style='border-collapse:collapse; width:100%;'>";
		echo "<tr>
		<td colspan='2'>#</td>
		<td colspan='2'>ARTICULO</td>
		<td colspan='2'>BARRA</td>
		</tr>";
	}else
	{
		echo "<table border='1' style='border-collapse:collapse; width:100%;'>";
		echo "<tr>
		<td>#</td>
		<td>ARTICULO</td>
		<td>BARRA</td>
		<td>#</td>
		<td>ARTICULO</td>
		<td>BARRA</td>
		</tr>";
	}


		}
		if($linea==1 and $cantidad>=25)
		{
			echo "<tr>
			<td>$num</td>
			<td>".$f['articulo']."</td>
			<td>".$f['barra']."</td>";
			$linea=0;

		}else if($linea==0 and $cantidad>=25)
		{
			echo "<td>$num</td>
			<td>".$f['articulo']."</td>
			<td>".$f['barra']."</td></tr>";
			$linea=1;
		}else
		{
			echo "<tr>
			<td colspan='2'>$num</td>
			<td colspan='2'>".$f['articulo']."</td>
			<td colspan='2'>".$f['barra']."</td>
			</tr>";
		}
	}
	echo "</table>";

$documento_inv=explode("TRA-", $documento_inv);
$documento_inv=$documento_inv[1];
	echo '<div style="page-break-after: always"></div>';
	//echo "scdecedcec";


$qf=$conexion2->query("select count(traslado.articulo) cantidad,concat(EXIMP600.consny.
articulo.articulo,': ',eximp600.consny.articulo.descripcion) 
descripcion, sum(isnull(registro.lbs,0)+isnull(registro.peso,0)) PESO, 
CASE when traslado.destino like 'CA%' then 'Inversiones Carisma'
when traslado.destino like 'N%' then 'NERY CELA FLORES'
when traslado.destino like 'E%' then 'EVER ALVIR MALTEZ' else 'N/A'
end NOMBRE, concat(day(traslado.fecha),'/',month(traslado.fecha),'/',year(traslado.fecha)) fechas
from traslado inner join registro on traslado.registro=registro.
id_registro inner join eximp600.consny.articulo on traslado.articulo=
eximp600.consny.articulo.articulo  where traslado.sessiones='$doc' 
and traslado.usuario='$usuario' GROUP BY traslado.articulo,
eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,
traslado.destino,traslado.fecha



")or die($conexion2->error());

echo "<table border='1' style='border-collapse:collapse; width:100%;'>";

echo "<tr height='auto'><td width='10%;'>
<img src='logo.png' width='100%' height='70px' style='float:left; margin-right:0.5%;'></td><td>
<p style='float:left; margin-right: 0.5%;'><samp style='font-family:georgia; font-size: 150%;'>NEW YORK CENTER</samp> <br>
	<samp style='font-family:georgia; font-size: 110%;'>S.A. de C.V.</samp><br>
<samp style='font-family: calibri font-style: italic;'>VENTA DE ROPA AMERICANA POR MAYOR Y MENOR<br>
Carr. Panamericana Salida A San Salvador<br>
	Km. 135, #109, San Miguel, San Miguel<br>
	Tel. 2669-5802</samp></p>
</td>
";

$encabezado=1;
while($fqf=$qf->FETCH(PDO::FETCH_ASSOC))
{
	//echo "<script>alert('$encabezado')</script>";
	if($encabezado==1)
	{
		$encabezado++;
		echo "<td style='text-align:CENTER;'>DOCUMENTO:<BR><u><b>$documento_inv</b></u><br><br>".$fqf['fechas']."

</td>
</tr>";
		echo "<tr>
		<td colspan='3'>CLIENTE: ".$fqf['NOMBRE']."</td>
		</tr>";
		echo "<tr>
				<td>CANTIDAD</td>
				<td>DESCRIPCION</td>
				<td>PESO</td>
				</tr>";
				echo "<tr>
		<td>".$fqf['cantidad']."</td>
		<td>".$fqf['descripcion']."</td>
		<td>".$fqf['PESO']."</td>
		</tr>";
	}else
	{
		echo "<tr>
		<td>".$fqf['cantidad']."</td>
		<td>".$fqf['descripcion']."</td>
		<td>".$fqf['PESO']."</td>
		</tr>";
	}
}





}

?>
</body>
</html>