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
<input type="date" name="fecha">
<input type="submit" name="btn" value="generar">
	
</form>
<?php
error_reporting(0);
if($_POST)
{
	extract($_REQUEST);
	$bodega='SM00';
	$c=$conexion2->query("select  id_registro,barra,codigo,(isnull(lbs,0)+isnull(registro.peso,0)) as peso,bodega,activo,fecha_documento from registro where observacion not in('CANCELADO SYS','ELIMINADO SYS...') and fecha_documento<='$fecha' and bodega_produccion='$bodega' and estado!='2' and tipo!='C1' and codigo='t034'")or die($conexion2->error());
	echo "<table border='1'>";
	echo "<tr>
	<td>BARRA</td>
	<td>codigo</td>
	<td>cantidad</td>
	<td>PESO</td>
	<td>activo</td>
	<td>fechas</td>
	<td>fecha doc</td>

	</tr>";
	$numero=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$barra=$f['barra'];
	$codigo=$f['codigo'];
	$peso=$f['peso'];
	$activo=$f['activo'];
		$numero++;
		$idr=$f['id_registro'];
		$bodega='SM00';
		$fecha_doc=$f['fecha_documento'];
		$valido=1;
		$fechas='';	
		//traslados
		$ct=$conexion2->query("select origen,destino,fecha from traslado where (origen='$bodega' or destino='$bodega') and estado='1' and fecha<='$fecha' and registro='$idr'")or die($conexion2->error());
$nct=$ct->rowCount();
//echo "$nct - $bodega - $fecha<br>";
if($nct!=0)
{

	while($fct=$ct->FETCH(PDO::FETCH_ASSOC))
	{
		$ori=$fct['origen'];
		$des=$fct['destino'];
		$f=$fct['fecha'];
			//echo "id: $idr o: $ori -> D: $des - $valido - $bodega<hr>";
		if($ori==$bodega)
		{
			$valido=0;
				//echo "id: $idr o: $ori -> D: $des - $valido- $f origen<hr>";
			
			$fechas=$fct['fecha'];
			
		}
		else if($des==$bodega)
		{
				//echo "id: $idr o: $ori -> D: $des - $valido- $f destino<hr>";
			$valido=1;
			$fechas='';
		}
		//echo "id: $idr o: $ori -> D: $des - $valido - $fecha<hr>";

		
	}
}
	

		//fin traslados
	//messa 
	$cm=$conexion2->query("select  mesa.fecha from mesa inner join detalle_mesa on mesa.id=detalle_mesa.mesa inner join registro on detalle_mesa.registro=registro.id_registro where mesa.fecha<'$fecha' and registro.id_registro='$idr' and registro.bodega='$bodega'")or die($conexion2->error());
	$ncm=$cm->rowCount();
	if($ncm!=0)
	{
		$fcm=$cm->FETCH(PDO::FETCH_ASSOC);
	$fechas=$fcm['fecha'];
	$valido=0;
	}
	
	//fin mesa

	//venta
	$cv=$conexion2->query("select venta.fecha from venta inner join registro on venta.registro=registro.id_registro where venta.fecha<'$fecha' and registro.bodega='$bodega' and registro.id_registro='$idr'")or die($conexion2->error());
	$ncv=$cv->rowCount();
	if($ncv!=0)
	{
		$fcv=$cv->FETCH(PDO::FETCH_ASSOC);
		$fechas=$fcv['fecha'];
		$valido=0;
	}
	//fin venta

	 

	echo "<tr>
	<td>$barra</td>
	<td>$codigo</td>
	<td>$valido</td>
	<td>$peso</td>
	<td>$activo</td>
	<td>$fechas</td>
	<td>$fecha_doc</td>

	</tr>";
	
}
echo "</table>";
//echo "$numero";
	
}
?>
</body>
</html>