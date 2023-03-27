<!DOCTYPE html>
<html>
<head>
	<?php
		include("conexion.php");
	?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<form method="POST">
	CODIGO BARRA: <input type="text" name="barra" class="text" style="width: 40%;">
	<input type="submit" name="" value="SIGUIENTE" class="boton3">
</form><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	$t=0;
	$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO REGISTRO</h3>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$idr=$f['id_registro'];
		$fecha_d=$f['fecha_desglose'];
		$digita=$f['digita_desglose'];
		$codi=$f['codigo'];
		$sub=$f['subcategoria'];
		$desglosado=$f['desglosado_por'];
		$k=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$codi'")or die($conexion1->error());
		$fk=$k->FETCH(PDO::FETCH_ASSOC);
		$sub=$fk['DESCRIPCION'];
		echo "<p>$codi: $sub</p>";
		echo "<p>DESGLOSE DIGITADO POR: $digita</p>";

		echo "<p>DESGLOSADO POR: $desglosado</p>";

		echo "<p>FECHA DESGLOSE: $fecha_d</p>";

		echo "<table class='tabla' border='1' cellpadding='10'>";
		echo "<tr>
			<td colspan='3'>ARTICULOS AGREGADOS</td>

		</tr>";
		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>

		</tr>";
		$q=$conexion2->query("select * from desglose where registro='$idr'")or die($conexion2->error());
		while($fq=$q->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fq['articulo'];
			$cantidad=$fq['cantidad'];
			$t=$t + $cantidad;
			$ca=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION from consny.ARTICULO where consny.ARTICULO.ARTICULO='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$arti=$fca['ARTICULO'];
			$de=$fca['DESCRIPCION'];
			echo "<tr>
			<td>$arti</td>
			<td>$de</td>
			<td>$cantidad</td>

		</tr>";
		}
		echo "<tr>
		<td colspan='2'>TOTAL: </td>
		<td>$t</td>
		</tr>";
	}
}
?>
</body>
</html>