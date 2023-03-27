<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function cerrar()
		{
			location.replace('buscar1.php');
		}
	</script>
</head>
<body>
	<div style="display: none;">
		<?php
		include("conexion.php");
		$id=$_GET['id'];
		$c=$conexion2->query("select * from registro where id_registro='$id'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>location.replace('buscar1.php')</script>";
		}
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$cod=$f['codigo'];
		?>
	</div>
<div class="detalle">
	<button style="float: right; margin-right: 0.5%;" onclick="cerrar()">CERRAR X</button>
	<div class="adentro" style="margin-top: 0.5%; margin-left: 2.4%; height: 93%;">
	<?php
		$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$de=$fca['DESCRIPCION'];

		echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:2.5%;'>";
		echo "<tr>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
				<td>PRECIO</td>
				<td>CANTIDAD</td>
				<td>TOTAL</td>
		</tr>";
		$con=$conexion2->query("select * from detalle where registro='$id'")or die($conexion2->error());
		$t=0;
		$tp=0;
		while($fcon=$con->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fcon['articulo'];
			$cant=$fcon['cantidad'];
			$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$de=$fca['DESCRIPCION'];
			$precio=$fca['PRECIO_REGULAR'];
			$t=$cant * $precio;
			$tp=$tp + $t;
			echo "<tr>
				<td>$art</td>
				<td>$de</td>
				<td>$precio</td>
				<td>$cant</td>
				<td>$t</td>
		</tr>";
		}
		echo "<tr>
			<td colspan='4'>TOTAL</td>
			<td>$tp</td>
		</tr>";
	?>

		
	</div>
</div>
</body>
</html>