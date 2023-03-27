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
		?>
	</div>
<div class="detalle">
	<button style="float: right; margin-right: 0.5%;">CERRAR X</button>
	<div class="adentro" style="margin-top: 0.5%; margin-left: 2.5%; height: 95%; padding-left: 0.5%;">
	<form method="POST">
		<input type="text" name="b" placeholder="ARTICULO O DESCRIPCION" class="text" style="width: 20%;">
		<input type="submit" name="" value="BUSCAR" class="boton3">
		<hr>
		<?php
		if($_POST)
		{
			extract($_REQUEST);
			$be=str_replace("", "%", $b);
			$c=$conexion1->query("select * from consny.articulo where articulo='$b' or descripcion like '%$be%'")or die($conexion1->error());
		}else
		{
			$c=$conexion1->query("select * from consny.articulo")or die($conexion1->error());
		}
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO NINGUN ARTICULO : $b</h3>";
		}else
		{
			echo "<table border='1' cellpadding='10' class='tabla'>";
			echo "<tr>
					<td>ARTICULO</td>
					<td>DESCRIPCION</td>
			</tr>";

			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$art=$f['ARTICULO'];
				echo "<tr>
					<td><a href='desglose_averia.php?arti=$art'>".$f['ARTICULO']."</a></td>
					<td><a href='desglose_averia.php?arti=$art'>".$f['DESCRIPCION']."</a></td>
			</tr>";
			}
		}
		?>
	</form>	
	</div>
	
</div>
</body>
</html>