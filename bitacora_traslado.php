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
<input type="text" name="art">
<input type="submit" name="">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select documento_inv,origen,destino,count(documento_inv) as cantidad from traslado where articulo='$art' and origen='SM00' group by documento_inv,origen,destino")or die($conexion2->error());
	echo "<table border='1'>";
	echo "<tr>
		<td>DOCUMENTO</td>
		<td>ORIGEN</td>
		<td>DESTINO</td>
		<td>CANTIDAD</td>
		<td>ORIGEN EXACTUS</td>
		<td>DESTINO EXACTUS</td>
		<td>CANTIDAD EXACTUS</td>
		<td>TABLA EXACTUS</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$doc=$f['documento_inv'];
		$origen=$f['origen'];
		$destino=$f['destino'];
		$cantidad=$f['cantidad'];
		$cantidad_exactus=0;
		$cl=$conexion1->query("select articulo,count(articulo) as cantidad,bodega,bodega_destino from consny.linea_doc_inv where documento_inv='$doc' and articulo='$art' group by articulo,bodega,bodega_destino")or die($conexion1->error());
		$ncl=$cl->rowCount();
		if($ncl==0)
		{
			//echo "$doc->$art<br>";
			$ct=$conexion1->query("declare @audi int=(select top 1 audit_trans_inv from consny.audit_trans_inv where aplicacion='$doc');
				select articulo,count(articulo) as cantidad,bodega from consny.transaccion_inv where audit_trans_inv=@audi and articulo='$art' group by articulo,bodega order by bodega")or die($conexion1->error());
			$nct=$ct->rowCount();
			if($nct==0)
			{
				$tabla='N/A';
			}else
			{
				$numero=1;
				while($fe=$ct->FETCH(PDO::FETCH_ASSOC))
				{
					if($numero==1)
					{
						$bodega_origen_exactus=$fe['bodega'];
						$numero++;
					}else
					{
						$bodega_destino_exactus=$fe['bodega'];
						$cantidad_exactus=$fe['cantidad'];
					}
				}
				$tabla='trans_inv';
			}
		}else
		{
			$fe=$cl->FETCH(PDO::FETCH_ASSOC);
			$tabla='Linea_doc';
			$bodega_origen_exactus=$fe['bodega'];
			$bodega_origen_exactus=$fe['bodega_destino'];


		}
		echo "<tr>
		<td>$doc</td>
		<td>$origen</td>
		<td>$destino</td>
		<td>$cantidad</td>
		<td>$bodega_origen_exactus</td>
		<td>$bodega_destino_exactus</td>
		<td>$cantidad_exactus</td>
		<td>$tabla</td>
	</tr>";

	}
}
?>
</body>
</html>