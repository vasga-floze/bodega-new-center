<!DOCTYPE html>
<html>
<head>
	<title></title>
	<META http-equiv=refresh content=60>
</head>
<body>
<?php
include("conexion.php");

?>
<div style="width: 130%; background-color: red; height: 110%;">
	

<?php
$fecha=date("Y-m-d");
$c=$conexion2->query("select eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,count(eximp600.consny.articulo.articulo) as cantidad,sum(registro.lbs)as peso from registro inner join eximp600.consny.articulo on eximp600.consny.articulo.articulo=pruebabd.dbo.registro.codigo where pruebabd.dbo.registro.fecha_documento='$fecha'  group by eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2 order by eximp600.consny.articulo.clasificacion_2
")or die($conexion2->error());
$n=$c->rowCount();
echo "<table border='1' style='border-collapse:collapse; width:45%; float:left; margin-left:-3%; margin-bottom:2%;'>";
	echo "<tr>
		<td colspan='5' style='text-decoration:underline; font-size:150%; color:black; text-align:center; font-family:verdana;'>
		<hr style='color:blue;'>
		<b>PRODUCCION (BODEGA) DE: $fecha</b>
		<hr style='color:blue;'>
		</td>
	</tr>";
if($n!=0)
{
	
	echo "<tr>
		<td>CLASIFICACION</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>PESO</td>
	</tr>";
	$t=0;
	$tp=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$cod=$f['articulo'];
		$cant=$f['cantidad'];
		$desc=$f['descripcion'];
		$clasi=$f['clasificacion_2'];
		$peso=$f['peso'];
		$tp=$tp+$peso;
		echo "<tr>
		<td>$clasi</td>
		<td>$cod</td>
		<td>$desc</td>
		<td>$cant</td>
		<td>$peso</td>
	</tr>";
	$t=$t +$cant;
	}
	echo "<tr>
		<td colspan='3'>TOTAL:</td>
		<td>$t</td>
		<td>$tp</td>
	</tr></table>";
}else
{
	echo "<tr>
	<td colspan='5'>NO SE HA DIGITADO PRODUCCION</td>
	</tr>";
}
$cd=$conexion2->query("select eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,eximp600.consny.bodega.bodega,eximp600.consny.bodega.nombre,count(eximp600.consny.articulo.articulo)as cantidad,sum(isnull(pruebabd.dbo.registro.lbs,0) + isnull(pruebabd.dbo.registro.peso,0)) as peso from pruebabd.dbo.registro inner join eximp600.consny.articulo on eximp600.consny.articulo.articulo=pruebabd.dbo.registro.codigo inner join eximp600.consny.bodega on pruebabd.dbo.registro.bodega=eximp600.consny.bodega.bodega where pruebabd.dbo.registro.fecha_desglose='$fecha' group by eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,eximp600.consny.bodega.bodega,eximp600.consny.bodega.nombre order by eximp600.consny.bodega.bodega,eximp600.consny.articulo.clasificacion_2
")or die($conexion2->error());
$ncd=$cd->rowCount();
echo "<table border='1' style='border-collapse:collapse; margin-left:0.5%; float:left; width:54%; margin-bottom:2%;'>";
echo "<tr>

	<td colspan='8' style='border:none; text-align:center; font-family:verdana; font-size:150%; color:black;'>
		<hr style='color:blue;'>
		<b>DESGLOSES(TIENDAS) DE: $fecha</b><br>
		<hr style='color:blue;'>
	</td>
	

	</tr>";
echo "<tr>
<td>BODEGA</td>
<td>NOMBRE</td>
<td>CLASIFICACION</td>
<td>ARTICULO</td>
<td>DESCRIPCION</td>
<td>CANTIDAD</td>
<td>TOTAL PESO</td>
<td>TOTAL DESGLOSE</td>
</tr>";
if($ncd==0)
{
	echo "<tr>
	<td>
		<td colspan='9'>
			NO SE HAN INGRESADO DESGLOSES
		</td>
	</td>

	</tr>";

}else
{
	$tp=0; $tc=0; $td=0;
	while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
	{
		$bodega=$fcd['bodega'];
		$nom=$fcd['nombre'];
		$art=$fcd['articulo'];
		$desc=$fcd['descripcion'];
		$clasi=$fcd['clasificacion_2'];
		$cant=$fcd['cantidad'];
		$peso=$fcd['peso'];
		$tc=$tc + $cant;
		$tp=$tp + $peso;

		$cd1=$conexion2->query("select sum(isnull(desglose.cantidad,0) * isnull(desglose.precio,0)) as total from desglose inner join registro on desglose.registro=registro.id_registro where registro.codigo='$art' and registro.bodega='$bodega' and fecha_desglose='$fecha'")or die($conexion2->error());
		$fcd1=$cd1->FETCH(PDO::FETCH_ASSOC);
		$total=$fcd1['total'];
		echo "<tr>
			<td>$bodega</td>
			<td>$nom</td>
			<td>$clasi</td>
			<td>$art</td>
			<td>$desc</td>
			<td>$cant</td>
			<td>$peso</td>
			<td>$total</td>
		</tr>";
		$td=$td + $total;


	}
	echo "<tr>
		<td colspan='5'>TOTAL</td>
		<td>$tc</td>
		<td>$tp</td>
		<td>$td</td>
	</tr>
	</table>";
}


?>	
</div>
</body>
</html>