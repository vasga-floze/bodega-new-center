<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<?php
include("conexion.php");
$bodega=$_SESSION['bodega'];
$tipo=substr($bodega, 0);
if($tipo[0]=='U' or $_SESSION['usuario']=='staana3')
{

}else
{
	echo "<script>alert('NO TIENES AUTORIZACION')</script>";
	echo "<script>location.replace('desglose.php')</script>";
}
$hoy=date("Y-m-d");
?>
<body>
	<form method="POST">
		DESDE: <input type="date" name="b" class="text" style="width: 20%;">
		HASTA: <input type="date" name="b1" class="text" style="width: 20%;">
		<input type="submit" name="" class="boton2" value="MOSTRAR">
		
	</form>
	<br>
	<?php
	if($_POST)
	{
		extract($_REQUEST);
		if($b=='' and $b1!='')
		{
			$c=$conexion2->query("select * from traslado where fecha='$b1'")or die($conexion2->error());
		}else if($b1=='' and $b!='')
		{
			$c=$conexion2->query("select * from traslado where fecha='$b'")or die($conexion2->error());
		}else
		{
			$c=$conexion2->query("select * from traslado where fecha between '$b' and '$b1'")or die($conexion2->error());
		}
		
	}else
	{
		$hoy=date("Y-m-d");
		$c=$conexion2->query("select * from traslado where fecha='$hoy'")or die($conexion2->error());
	}
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h2>NO SE ENCONTRO TRASLADO DE LA FECHA SELECCIONADA</h2>";
	}else
	{
		echo "<table class='tabla' border='1' cellpadding='10'>";
		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CODIGO DE BARRA</td>
			<td>FECHA TRASLADO</td>
			<td>ORIGEN</td>
			<td>DESTINO</td>
		</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$origen=$f['origen'];
			$destino=$f['destino'];
			$fecha=$f['fecha'];
			$idr=$f['registro'];
			$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
			$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
			$codigo=$fcr['codigo'];
			$barra=$fcr['barra'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$codigo'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$art=$fca['ARTICULO'];
			$de=$fca['DESCRIPCION'];

			$cb=$conexion1->query("select * from consny.bodega where bodega='$origen' and nombre like '%USU%' and nombre not like '%(N)%'")or die($conexion1->error());
			$ncb=$cb->rowCount();
			if($ncb!=0)
			{
				$cbo=$conexion1->query("select * from consny.bodega where bodega='$origen'")or die($conexion1->error());
				$fcbo=$cbo->FETCH(PDO::FETCH_ASSOC);
				$origen=$fcbo['NOMBRE'];
				$cbd=$conexion1->query("select * from consny.bodega where bodega='$destino'")or die($conexion1->error());
				$fcbd=$cbd->FETCH(PDO::FETCH_ASSOC);
				$destino=$fcbd['NOMBRE'];
				echo "<tr>
				<td>$art</td>
			<td>$de</td>
			<td>$barra</td>
			<td>$fecha</td>
			<td>$origen</td>
			<td>$destino</td>
				</tr>";
			}
		}
	}
	?>
</body>
</html>