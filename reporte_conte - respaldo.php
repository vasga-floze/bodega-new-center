<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php
	error_reporting(0);
	include("conexion.php");
	$art=$_GET['art'];
	$c=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$art=$f['ARTICULO'];
	$de=$f['DESCRIPCION'];
	?>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<script>
		$(document).ready(function(){

		});
		function cambiar()
		{
			$("#op").val("1");
		}
		function enviar()
		{
			document.form.submit();
		}
		function enviar1()
		{
			$("#op").val("2");
		}
	</script>
</head>
<body>
<form method="POST" name="form" method="POST">
	<input type="text" name="cod" class="text" style="width: 15%;" placeholder="ARTICULO" onkeypress="cambiar()" onchange="enviar()" value='<?php echo "$art";?>'>
	<input type="text" name="nom" class="text" style="width: 40%;" placeholder="DESCRIPCION" value='<?php echo "$de";?>'>
	<input type="hidden" name="op" id="op">
	<input type="submit" name="btn" class="boton3" value="BUSCAR" onclick="enviar1()">
</form><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	

	if($op==1)
	{
		$c=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO ARTICULO')</script>";
			echo "<script>location.replace('reporte_conte.php')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$art=$f['ARTICULO'];
			echo "<script>location.replace('reporte_conte.php?art=$art')</script>";
		}
	}else if($op==2)
	{
		$cr=$conexion2->query("select * from registro where tipo!='C1' and codigo='$cod'  and bodega like 'SM%' and activo is null")or die($conexion2->error());
		

	}
}else
{
	//$cr=$conexion2->query("select * from registro where tipo='CD' ")or die($conexion2->error());
}
$nr=$cr->rowCount();
if($nr==0)
{
	echo "<h2>NO SE ENCONTRO REGISTRO</h2>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10'>";
	echo "<tr>
	<td>CODIGO BARRA</td>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	<td>PESO</td>
	<td>TRABAJADO</td>
	<td>BODEGA</td>
	<td>FECHA TRASLADO</td>
	</tr>";
	while($fcr=$cr->FETCH(PDO::FETCH_ASSOC))
	{
		$cod=$fcr['codigo'];
		$peso=$fcr['peso'] + $fcr['lbs'];
		$bodega=$fcr['bodega'];
		$fechat=$fcr['fecha_traslado'];
		$idr=$fcr['id_registro'];
		$barra=$fcr['barra'];
		$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$arti=$fca['ARTICULO'];
		$descr=$fca['DESCRIPCION'];
		$cm=$conexion2->query("
select mesa.mesa,detalle_mesa.registro from mesa inner join detalle_mesa on detalle_mesa.mesa=mesa.id where  (mesa.estado='1' or mesa.estado='T') and detalle_mesa.registro='$idr' ")or die($conexion2->error());
		$ncm=$cm->rowCount();
		if($ncm==0 )
		{
			$trabajado='NO';
		}else
		{
			$trabajado='SI';
		}
		if($fechat=='')
		{
			$fechat='- -';
		}

		if($trabajado=='NO' and $fechat='- -')
		{
			echo "<tr>
		<td>$barra</td>
	<td>$arti</td>
	<td>$descr</td>
	<td>$peso</td>
	<td>$trabajado</td>
	<td>$bodega</td>
	<td>$fechat</td>
	</tr>";
		}
		
	}
	echo "</table>";
}
?>
</body>
</html>