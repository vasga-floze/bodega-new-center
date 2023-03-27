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
	<a href="ventacod.php" style="float: right; margin-right: 0.5%; color: white; text-decoration: none;">Cerrar X</a>
	<div class="adentro" style="margin-top: 1%; margin-left: 3%;">
		<form method="POST" >
			<input type="text" name="b" placeholder="ARTICULO O DESCRIPCION" class="text" style="width: 30%;">
			<input type="submit" name="btn" value="BUSCAR" class="btnfinal" style="padding: 0.5%; background-color: #6A88CC;">
			
		</form>

		<?php
			if($_POST)
			{
				extract($_REQUEST);
				$c=$conexion1->query("select * from consny.articulo where articulo='$b' or descripcion like (SELECT '%'+REPLACE('$b',' ','%')+'%') and clasificacion_1='DETALLE'")or die($conexion1->error());

			}else 
			{
				$c=$conexion1->query("select * from consny.articulo where clasificacion_1='DETALLE'")or die($conexion1->error());

			}
			$n=$c->rowCount();
			if($n==0)
			{
				echo "<h3>NO SE ENCONTRO NINGUN ARTICULO DISPONIBLE</h3>";


			}else
			{
				echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width:100%; margin-top:-7%; '>";

				echo "<tr>
					<td>ARTICULO</td>
					<td>DESCRIPCION</td>
				</tr>";

				while($f=$c->FETCH(PDO::FETCH_ASSOC))
				{
					$art=$f['ARTICULO'];
					$desc=$f['DESCRIPCION'];
					echo "<tr>
					<td><a href='ventacod.php?art=$art' style='text-decoration:none;'>$art</a></td>
					<td><a href='ventacod.php?art=$art' style='text-decoration:none;'>$desc</a></td>
				</tr>";
				}
			}
		?>
		
	</div>
</div>


</body>
</html>