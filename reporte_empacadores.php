<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
	<div style="background-color: #098098; width: 100%; height: 20px; position: sticky; top: -10%;">
		
	</div>
<?php
include("conexion.php");
?>
<form autocomplete="off" action="POST" style="margin-top: 1.5%; margin-left: -1%;">
	<input type="text" name="empacador" placeholder="EMPACADO POR" list="lista" class="text" style="padding: 0.5%; width: 30%;">
	<datalist id="lista" >
	<?php
	$c=$conexion1->query("select nombre from produccion_accpersonal where empaca='1' and activo='1'")or die($conexion1->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$nom=$f['nombre'];
		echo "<option>$nom</option>";
	}
	?>
	</datalist>
	<input type="date" name="desde" class="text" style="padding: 0.5%; width: 10%;">
	<input type="date" name="hasta" class="text" style="padding: 0.5%; width: 10%;">
	<input type="submit" name="btn" value="GENERAR" class="btnfinal" style="padding: 0.5%;">
</form>
</body>
</html>