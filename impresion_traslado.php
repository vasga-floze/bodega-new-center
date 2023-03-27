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
	$q=$conexion2->query("select count(*)/2 cantidad from traslado where sessiones='$doc' and usuario='$usuario'")or die($conexion2->error());
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
		echo "<tr>
		<td>

		PAQUETE: ".$f['paquete']."<br>
		FECHA: ".$f['fecha']."</td>
		<td>
		DOCUMENTO: ".$f['documento_inv']."<br>
		ORIGEN: $ori<br>
		DESTINO: $dest
		<tr><td colspan='2'><br><br>F. DESPACHO:______________  F. RECIBE:______________  F. ENTREGA:______________</td></td>
		</tr></table>";

		if($cantidad<=30)
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
		if($linea==1 and $cantidad>=30)
		{
			echo "<tr>
			<td>$num</td>
			<td>".$f['articulo']."</td>
			<td>".$f['barra']."</td>";
			$linea=0;

		}else if($linea==0 and $cantidad>=30)
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

}
?>
</body>
</html>