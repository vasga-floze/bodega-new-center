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
	if($_SESSION['tipo']==3)
	{
		$tipo=1;
	}else
	{
		$tipo=$_SESSION['tipo'];
	}
	if($tipo!=1)
	{
		echo "<script>alert('NO TIENES AUTORIZACION')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}
	?>
<form method="POST">
<input type="text" name="corelativo" class="text" style="width: 20%;" placeholder="CORELATIVO">
<input type="submit" name="btn" value="MOSTRAR" class="boton2">
</form>
<hr>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select top 1 * from averia where corelativo='$corelativo' and tipo='P'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{	
		echo "<h2>NO SE ENCONTRO REGISTRO DEL CORELATIVO: $corelativo</h2>";
	}else
	{
		echo "<table border='1' cellpadding='6' class='tabla' style='width:100%;'>";
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
			<td>CORELATIVO <br>
			".$f['corelativo']."
			</td>
			<td>UNIDADES <br>
			".$f['unidades']."
			</td>
			<td>FECHA INGRESO <br>
			".$f['fecha_documento']."</td>
		</tr>";

		echo "<tr>
			<td>DESGLOSADO POR: <br>
			".$f['desglosado_por']."
			</td>
			<td>DESGLOSE DIGITADO POR: <br>
			".$f['digita_desglose']."
			</td>
			<td>FECHA DESGLOSE <br>
			".$f['fecha_desglose']."</td>
		</tr>";

		echo "<tr>
			<td colspan='3'>AUDITOR: <br>
			".$f['auditor']."</td>
		</tr>";
		echo "<tr>
			<td colspan='3'>ENCARGADO: <br>
			".$f['encargado']."</td>
		</tr>";

		echo "<tr>
			<td colspan='3'>TIENDA: <br>
			".$f['tienda']."</td>
		</tr>";
		echo "<tr>
			<td colspan='3'>DOCUMENTO: <br>
			".$f['documento_inv']."</td>
		</tr>";
		$obsauditor=$f['observacion'];
		echo "<tr>
			<td colspan='3'>OBSERVACION AUDITOR: 
			$obsauditor</td>
		</tr>";
		$q=$conexion2->query("select observacion from averia where corelativo='$corelativo' and tipo='D' group by observacion")or die($conexion2->error());
		$fq=$q->FETCH(PDO::FETCH_ASSOC);
		$obstienda=$fq['observacion'];
		echo "<tr>
			<td colspan='3'>OBSERVACION TIENDA: 
			$obstienda</td>
		</tr>";
		echo "</table>";

		$cp=$conexion2->query("select * from averia where corelativo='$corelativo' and tipo='P' and articulo is not null order by articulo")or die($conexion2->error());

		echo "<table class='tabla' border='1' cellpadding='10' style='width:48.5%; float:left;'>";
		echo "<tr style='background-color:#27E2DC;'>
		<td colspan='3'>DESGLOSE AUDITOR</td>
		</tr>";
		echo "<tr style='background-color:#27E2DC;'>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		</tr>";
		$t=0;
		while($fcp=$cp->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fcp['articulo'];
			$cant=$fcp['cantidad'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			echo "<tr>
				<td>".$fca['ARTICULO']."</td>
				<td>".$fca['DESCRIPCION']."</td>
				<td>$cant</td>
				</tr>";
				$t=$t + $cant;
		}
		echo "<tr>
		<td colspan='2'>TOTAL</td>
		<td>$t</td>
		</tr>";


		$cd=$conexion2->query("select * from averia where corelativo='$corelativo' and tipo='D' and articulo is not null order by articulo")or die($conexion2->error());

		echo "<hr><table border='1' class='tabla' cellpadding='10' style='width:48.5%; margin-left:2.5%; float:left;'>";
		echo "<tr style='background-color:#27E2DC;'>
		<td colspan='3'>DESGLOSE TIENDA</td>
		</tr>";
		echo "<tr style='background-color:#27E2DC;'>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		</tr>";
		$t=0;
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
		$t=$t + $cant;

		}

		echo "<tr>
		<td colspan='2'>TOTAL</td>
		<td>$t</td>
		</tr>";

	}
	
}
?>

</body>
</html>