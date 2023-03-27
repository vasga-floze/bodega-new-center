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
<input type="date" name="desde">
<input type="text" name="bodega">

<input type="submit" name="btn" value="GENERAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select id_registro,concat(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION) as art,(ISNULL(lbs,0)+ISNULL(peso,0)) as peso,fecha_documento,bodega,registro.activo from registro
inner join EXIMP600.consny.ARTICULO on registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO where
registro.bodega_produccion='$bodega'  and registro.fecha_documento<='$desde' and registro.codigo='FARD0-0004'

")or die($conexion2->error());

	echo "<table border='1' >";
	echo "<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$op=0;
		$idr=$f['id_registro'];
		$activo=$f['activo'];
		$fecha_documento=$f['fecha_documento'];
		$art=$f['art'];
		//traslados
		$ct=$conexion2->query("select * from traslado where registro='$idr' and fecha<='$desde' and (origen='$bodega' or destino='$bodega')
")or die($conexion2->error());
		$nct=$ct->rowCount();
		if($nct==0)
		{
			$op++;
		}else
		{
			while($fct=$ct->FETCH(PDO::FETCH_ASSOC))
			{
				$origen=$fct['origen'];
				$destino=$fct['destino'];
				if($origen==$bodega)
		{
			$op++;
		}
		if($destino==$bodega)
		{
			$op--;
		}
			}
		}

		//fin traslados

		//mesa
		$cm=$conexion2->query("select mesa.fecha,mesa.bodega,registro.activo from registro inner join detalle_mesa on registro.id_registro=
detalle_mesa.registro inner join mesa on detalle_mesa.mesa=mesa.id where registro.id_registro='$idr' and mesa.fecha<='$desde' and registro.bodega='$bodega'")or die($conexion2->error());
		$ncm=$cm->rowCount();
		if($ncm!=0)
		{
			//$op=0;
		}else
		{
			$op++;
		}
		//fin mesa

		//venta
		$cv=$conexion2->query("select * from venta where registro='$idr' and venta.fecha<='$desde' and venta.bodega_venta='$bodega'
")or die($conexion2->error());
		$ncv=$cv->rowCount();
		if($ncv==0)
		{
			$op++;
		}else
		{
			//$op
		}
		//fin venta
		$q=$conexion2->query("select lbs,peso,bodega,fecha_documento,activo from registro where  id_registro='$idr'")or die($conexion2->error());
		if($op==0)
		{
			
			$fq=$q->FETCH(PDO::FETCH_ASSOC);
			
				echo "<tr>
					<td>".$fq['lbs']."</td>
					<td>".$fq['peso']."</td>
					<td>".$fq['bodega']."</td>
					<td>".$fq['fecha_documento']."</td>
					<td>".$fq['activo']." cccv</td>
				</tr>";
			
		}

	}

}
?>
</body>
</html>