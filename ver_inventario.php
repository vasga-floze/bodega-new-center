<?php
include("conexion.php");
$inv=$_GET['inv'];
if($inv!='')
{
	$u=$_GET['usuario'];
	$fecha=$_GET['fecha'];
	$bod=$_GET['bo'];
	echo "<a href='r_inventario.php?fecha=$fecha&&bod=$bod'>VOLVER</a>";
	$c=$conexion2->query("select * from inventario where sessiones='$inv' and registro!=0 and usuario='$u'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n!=0)
	{
		echo "<table class='tabla' border='1' cellpadding='10' style='width:100%; border-collapse:collapse;'>";
		echo "<tr>
			<td>CODIGO BARRA</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>OBSERVACION</td>
			<td>BODEGA INVENTARIO</td>
			<td>BODEGA DEL FARDO</td>
			<td>FECHA INVENTARIO</td>
			<td>DIGITADO</td>
		</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$f['registro'];
		$bodega=$f['bodega'];
		$fecha=$f['fecha'];
		$digitado=$f['digita'];
		$bodegainv=$f['bodega_actual'];
		$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion->error());
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$cod=$fcr['codigo'];
		$barra=$fcr['barra'];
		$obs=$fcr['observacion'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		if($bodega!=$bodegainv)
		{
			echo "<tr style='background-color:red; color:white;'>";
		}else
		{
			echo "<tr>";
		}
		$qv=$conexion2->query("select count(*) as total from inventario where registro='$idr' and sessiones='$inv' and usuario='$u'")or die($conexion2->error());
		$fqv=$qv->FETCH(PDO::FETCH_ASSOC);
		if($fqv['total']>1)
		{
			echo "<tr style='background-color:yellow; color:black;'>";
		}
		if($digitado=='')
		{
			$digitado='- -';
		}
		if($fecha=='')
		{
			$fecha='- -';
		}
		$art=$fca['ARTICULO'];
		$des=$fca['DESCRIPCION'];
		echo "
			<td>$barra</td>
			<td>$art</td>
			<td>$des</td>
			<td>$obs</td>
			<td>$bodega</td>
			<td>$bodegainv</td>
			<td>$fecha</td>
			<td>$digitado</td>
		</tr>";
	}

	}else
	{
		echo "<script>alert('SE PRODUJO UN ERROR INTENTELO NUEVAMENTE')</script>";

		echo "<script>location.replace('r_inventario.php?fecha=$fecha&&bod=$bod')</script>";
	}

}else
{
	echo "<script>location.replace('r_inventario.php')</script>";
}
?>