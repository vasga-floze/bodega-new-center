<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
?>

<form method="POST">
<input type="text" name="art" value="articulo">
<input type="submit" name="btn" value="GENERAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	echo "<table border='1' style='border-collapse:collapse;' cellspadding='7'>";
	echo "<tr>
		<td>DOCUMENTO</td>
		<td>MODULO</td>
		<td>CANTIDAD SYS</td>
		<td> CANTIDAD EXACTUS</td>
		<td>TABLA EXACTUS</td>
	</tr>";
	$qc=$conexion2->query("select top 10 documento_inv_contenedor,count(documento_inv_contenedor) as cantidad from registro where tipo='CD' and codigo='$art' and estado='1' group by documento_inv_contenedor")or die($conexion2->error());
	while($fqc=$qc->FETCH(PDO::FETCH_ASSOC))
	{
	$doc=$fqc['documento_inv_contenedor'];

	$qce=$conexion1->query("select sum(cantidad) as cantidad,articulo,bodega from consny.LINEA_DOC_INV
where DOCUMENTO_INV='$doc' and articulo='$art' group by articulo,BODEGA
")or die($conexion1->error());
	$nqce=$qce->rowCount();
	if($nqce==0)
	{
		//$qce1=$conexion1->query("")or die($conexion1->error());//audit_trans
	}

	echo "<tr><td>$doc</td>
		<td>CONTENEDOR</td>
		<td>".$fqc['cantidad']."</td>
		<td> CANTIDAD EXACTUS</td>
		<td>TABLA EXACTUS</td>
	</tr>";
}

	}
?>
</body>
</html>