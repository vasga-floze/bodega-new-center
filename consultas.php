<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
include("conexion.php");
$dia=date("Y-m-d");
$dia=strtotime($dia);
$dia=date("w",$dia);
//echo "$dia";
$hora=date("h");
if($dia==6 or $dia==0 or $dia==6 or $_SESSION['usuario']=='staana3')
{
	
}else
{
	
		echo "<script>alert('NO DISPONIBLE')</script>";
		echo "<script>location.replace('desgloseb.php')</script>";
	
	
}

//echo "<script>location.replace('consultas.php?i=2')</script>";
?>
<h3 style="color: black; text-decoration: underline; text-align: center;">RESUMEN DE FARDOS TIENDA</h3>

<form method="POST" method="POST">
	<input type="text" name="barra" placeholder="CODIGO DE BARRA" class="text" style="width: 20%;" required>
	<input type="submit" name="btn" value="BUSCAR" class="btnfinal" style="padding: 0.5%; background-color: #2A9156;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$bodega=$_SESSION['bodega'];
	$c=$conexion2->query("select * from registro where (fecha_desglose is null or fecha_desglose='') and barra='$barra' and activo is null and bodega='$bodega'")or die();
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>CODIGO DE BARRA: $barra NO ESTA DISPONIBLE O YA FUE DESGLOSADO O NO SE A ASIGNADO A TU BODEGA $bodega</h3>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$idr=$f['id_registro'];
		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		$conexion2->query("insert into consulta(registro,bodega,usuario,paquete,fecha_hora) values('$idr','$bodega','$usuario','$paquete',getdate())")or die($conexion2->error());
		echo "<table border='1' style='border-collapse:collapse; width:100%;' cellpadding='10'>";
		echo "<tr>
			<td colspan='3'>CODIGO BARRA: ".$f['barra']."</td>
		</tr>";
		echo "<tr>
		<td>LIBRAS: ".$f['lbs']."</td>
		<td>Unidades: ".$f['und']."</td>
		<td>FECHA TRASLADO: ".$f['fecha_traslado']."</td>
		</tr>";
		echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		</tr>";
		$cd=$conexion2->query("select * from detalle where registro='$idr'")or die($conexion2->error());
		while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fcd['articulo'];
			$cant=$fcd['cantidad'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			echo "<tr>
				<td>".$fca['ARTICULO']."</td>
				<td>".$fca['DESCRIPCION']."</td>
				<td>$cant</td>
				</tr>";

		}

	}
}
?>
</body>
</html>