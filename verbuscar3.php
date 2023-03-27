<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<?php
		include("conexion.php");
		$codigo=$_GET['art'];
		$d=$_GET['d'];
		$h=$_GET['h'];
		$barra=$_GET['barra'];
		$clasificacion=$_GET['clasi'];
		$idr=$_GET['id'];
	?>
	<div class="detalle" style="margin-top: -5%; width: 100%; margin-left: -0.6%;">
<?php
echo "<a href='buscar3.php?codigo=$codigo&&d=$d&&h=$h&&barra=$barra&&clasi=$clasificacion'>";
?>
	<button style='margin-right: 0.5%; float: right;'>CERRAR X</button></a>
	
	<div class="adentro" style="margin-left: 2.5%; height: 90%;">
<?php
		



		if($idr=='')
		{
			echo "<script>alert('ERROR! INTENTELO NUEVAMENTE')</script>";
			echo "<script>location.replace('buscar3.php')</script>";
		}else
		{
			$k=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
			$cd=$conexion2->query("select * from detalle where registro='$idr'")or die($conexion2->error);
			$n=$cd->rowCount();
			if($n==0)
			{
				echo "<h2 style='text-align:center;'>NO SE ENCONTRO DESGLOSE DE PRODUCCION</h2>";
			}else
			{
				echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:3.5%;'>";
				echo "<tr>
					<td>ARTICULO</td>
					<td>DESCRIPCION</td>
					<td>CANTIDAD</td>
					<td>PRECIO</td>
					<td>TOTAL</td>
				</tr>";
				$t=0; $tf=0;
			while($f=$cd->FETCH(PDO::FETCH_ASSOC))
			{
				$art=$f['articulo'];
				$cant=$f['cantidad'];
				$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error());
				$fca=$ca->FETCH(PDO::FETCH_ASSOC);
				$precio=$fca['PRECIO_REGULAR'];
				$arti=$fca['ARTICULO'];
				$de=$fca['DESCRIPCION'];
				$t=$cant * $precio;
				$tf=$tf + $t;
				echo "<tr>
					<td>$arti</td>
					<td>$de</td>
					<td>$cant</td>
					<td>$precio</td>
					<td>$t</td>
				</tr>";

			}
			echo "<tr>
				<td colspan='4'>TOTAL</td>
				<td>$tf</td>
			</tr>";
			}
		}

	?>
</div>
</div>
</head>
<body>

</body>
</html>