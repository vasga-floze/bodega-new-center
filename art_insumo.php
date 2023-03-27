<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<div style="display: none;">
<?php
include("conexion.php");
echo "</div>";
$digitado=$_GET['digitado'];
$fecha=$_GET['fecha'];

?>
<div class="detalle">
	<a href="insumo.php" style="float: right; margin-right: 0.5%; color: white; text-decoration: none;">CERAR X</a><br>
	<div class="adentro" style="margin-left: 3%;">
		<form method="POST">
		<input type="text" name="b" class="text" style="width: 30%; margin-left: 3%;" placeholder="ARTICULO O DESCRIPCION">
		<input type="submit" name="btn" value="BUSCAR" style="padding: 0.5%; background-color: blue; color: white; border-color: white;">
		</form>
		<hr>
		<?php
		if($_POST)
		{
			extract($_REQUEST);
			$c=$conexion1->query("select * from consny.articulo where clasificacion_1='insumo' and articulo='$b' or descripcion like '%$b%'")or die($conexion1->error());
		}else
		{
			$c=$conexion1->query("select * from consny.articulo where clasificacion_1='insumo' and descripcion not like '(n)'")or die($conexion1->error());
		}
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO NINGUN ARTICULO</h3>";
		}else
		{
			echo "<table border='1' style='width:100%; border-collapse:collapse;' cellpadding='10'>";
			echo "<tr>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
			</tr>";
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$art=$f['ARTICULO'];
				echo "<tr>
				<td><a href='insumo.php?art=$art&&digitado=$digitado&&fecha=$fecha' style='text-decoration:none;'>".$f['ARTICULO']."</td>
				<td><a href='insumo.php?art=$art&&digitado=$digitado&&fecha=$fecha' style='text-decoration:none;'>".$f['DESCRIPCION']."</td>
			</tr>";
			}
		}
		?>
	</div>
	
</div>
</body>
</html>