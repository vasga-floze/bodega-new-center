<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function cancelar()
		{
			location.replace("traslados.php");
		}
	</script>
</head>
<body>
<div class="detalle" style="padding-top: 10%; padding-left: 25%; margin-top: -7%; margin-left: -15%; background-color: rgb(133,133,137,0.9);">
	<div class="adentro" style="width: 80%; height: 80%; border-radius: 10px; -webkit-box-shadow: 3px 10px 45px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 10px 3px 45px 0px rgba(0,0,0,0.75); overflow:auto; animation:disc;
">
<h6S>¿SEGURO DESEA CAMBIAR LA BODEGA DE ORIGEN PREDETERMINADA?</h6><br><br>
		<?php
		include("conexion.php");
		if($_POST)
		{
			extract($_REQUEST);
			{
				$c=$conexion1->query("select * from consny.BODEGA where BODEGA='$bodegas'")or die($conexion1->error);
				$f=$c->FETCH(PDO::FETCH_ASSOC);
				$b=$f['BODEGA'];
				$n=$f['NOMBRE'];
				$bod="$b: $n";
				echo "BEDEGA SELECCIONADA: <b><u>$bod</b></u><br><br>";

				echo "<form method='POST' action='cambiarpre.php'>
				<label>
				<input type='radio' name='op' value='1' checked>
				APLICAR A LOS REGISTROS QUE TENGAN COMO BODEGA DE ORIGEN <b><u>SM01</b></u></label><bR><br>
				<input type='hidden' name='bodg' value='$b'>
				<label>
				<input type='radio' name='op' value='2'>
				APLICAR A TODOS LOS REGISTROS</label><br><br><br><br>
				<input type='hidden' name='actualb' value='$actual'>";
				echo "<input type='submit' value='GUARDAR CAMBIO' class='btng'></form>";
				echo "
				<button onclick='cancelar()' class='btnc'>CANCELAR</button>";
				
			}
		}
		?>
	</div>
	
</div>
</body>
</html>