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
$destino=$_GET['destino'];
$origenes=$_GET['origen'];
?>
<div class="detalle" id="divorigen" style="margin-top: -4%;  position: fixed; width: 100%;">
	<?php echo "<a href='traslado_piezas.php?destino=$destino&&origen=$origenes' id='cerrar' style='color: white; float: right; margin-right: 2%; text-decoration: none; ' onclick='cerrar()'>Cerrar X</a>";?>
	<br>
	<div class="adentro" style=" margin-left: 1%; width: 98%; height: 92%;">

	<form method="POST" style="margin-left: 5%;">
	<input type="text" name="b" placeholder="BODEGA O NOMBRE" class="text" style="width: 25%;">
	<input type="submit" name="btn" value="BUSCAR" class="boton4" style="color: black; background-color: #abaead;">
	</form>
	<br>
	<?php
	$empresa=$_SESSION['empresa_tpieza'];
	if($_POST)
	{
		extract($_REQUEST);
		
		$c=$conexion1->query("select * from $empresa.bodega where (bodega='$b' or nombre like '%$b%')")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("select * from $empresa.bodega")or die($conexion1->error());

	}
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUNA BODEGA: $b";
	}else
	{
		echo "<table border='1' style='border-collapse:collapse; width:90%;  margin-left:5%;' cellpadding='10'>";
		echo "<tr>
				<td>BODEGA</td>
				<td>NOMBRE</td>
		</tr>";

		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$ori=$f['BODEGA'];
				echo "<tr>
				<td><a href='traslado_piezas.php?origen=$ori&&destino=$destino' style='color:black; text-decoration:none;'>".$f['BODEGA']."</a></td>
				<td><a href='traslado_piezas.php?origen=$ori&&destino=$destino' style='color:black; text-decoration:none;'>".$f['NOMBRE']."</a></td>
		</tr>";
		}
		echo "<table>";
	}

	?>
	</div>
</div>
</body>
</html>