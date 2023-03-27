<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
include("conexion.php");
if($_SESSION['tipo']==2)
{
	echo "<script>location.replace('desglose.php')</script>";
}
?>
<form method="POST">
<input type="text" name="barra" placeholder="CODIGO DE BARRA" class="text"style='width: 30%;' required>
<input type="submit" class="boton3" name="" value="BUSCAR">
</form><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h2>NO SE ENCONTRO REGISTRO DE <u>$barra</u></h2>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$peso=$f['lbs']+$f['peso'];
		$und=$f['und'];
		echo "<table border='1' class='tabla' cellpadding='10'style='margin-left:0%; width:100%;'>";
		$cod=$f['codigo'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$art=$fca['ARTICULO'];
		$des=$fca['DESCRIPCION'];

		echo "<tr>
		<td >NOMBRE FARDO:<br>
			$art: $des  (BARRA: $barra)
		</td>
		<td>LBS: <br>$peso</td>
		<td>UNID: <br>$und</td>
		</tr>
		";
		

		echo "<tr>
		<td>FECHA PRODUCCION: ".$f['fecha_documento']."</td>
		<td>FECHA TRASLADO: ".$f['fecha_traslado']."</td>
		<td>FECHA DESGLOSE: ".$f['fecha_desglose']."</td>
		</tr>";

		echo "<tr>
		<td colspan='3'>EMPACADO POR: ".$f['empacado']."</td>
		</tr>";
		echo "<tr>
		<td colspan='3'>PRODUCIDO POR: ".$f['producido']."</td>
		</tr>";
		echo "<tr>
		<td colspan='3'>DIGITADO POR: ".$f['digitado']."</td>
		</tr>";
		echo "<tr>
		<td colspan='3'>PRODUCIDO POR: ".$f['producido']."</td>
		</tr>";
		echo "<tr>
		<td colspan='3'>DESGLOSE DIGITADO POR: ".$f['digita_desglose']."</td>
		</tr>";
		echo "<tr>
		<td colspan='3'>DESGLOSADO POR: ".$f['desglosado_por']."</td>
		</tr>";
		echo "<tr>
		<td colspan='3'>OBSERVACION: ".$f['observacion']."</td>
		</tr>";
		echo "<tr>
		<td colspan='3'>BODEGA ACTUAL: ".$f['bodega']."</td>
		</tr>
		<tr>
		<td colspan='3'>CONSUMO: ".$f['documento_inv_consumo']." | INGRESO: ".$f['documento_inv_ing']."</td>
		</tr>
		</table>";
		$idr=$f['id_registro'];

		$cde=$conexion2->query("select * from detalle where registro='$idr'")or die($conexion2->error());
	echo "<table border='1' class='tabla' cellpadding='10' style='float:left; width:48%;'>";
	echo "<tr>
	<td colspan='3' style='background-color:green; color:white; text-align:center;'>DETALLE PRODUCCION</td>
	</tr>";
	echo "<tr>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	<td>CANTIDAD</td>
	</tr>";
	$tdet=0;
	while($fcde=$cde->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$fcde['articulo'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);

		echo "<tr>
			<td>".$fca['ARTICULO']."</td>
			<td>".$fca['DESCRIPCION']."</td>
			<td>".$fcde['cantidad']."</td>
	</tr>";
	$tdet=$tdet+$fcde['cantidad'];
	}
	echo "<tr>
		<td colspan='2'>TOTAL</td>
		<td>$tdet</td>
	</tr>";


	$cd=$conexion2->query("select * from desglose where registro='$idr'")or die($conexion2->error());
	$ncd=$cd->rowCount();
	
		//echo "<h2><u>FARDO SIN DESGLOSE FINALIZADO</u></h2>";
	
		echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:0.5%; width:48%; float:right;'>";
		echo "<tr>
		<td colspan='3' style='text-align:center; background-color:green; color:white;'>
		DETALLE DESGLOSE: 
		</td>
		</tr>";

		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
		</tr>";
		$tde=0;
		while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
		{
			$artd=$fcd['articulo'];
			$cant=$fcd['cantidad'];
			$tde=$tde+$cant;
			$ca=$conexion1->query("select * from consny.articulo where articulo='$artd'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$arti=$fca['ARTICULO'];
			$desc=$fca['DESCRIPCION'];
			echo "<tr>
			<td>$arti</td>
			<td>$desc</td>
			<td>$cant</td>
		</tr>";
		}
		echo "<tr>
			<td colspan='2'>TOTAL</td>
			<td>$tde</td>
		</tr>";
		

	}
}
?>

</body>
</html>