<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST" autocomplete="off">
	<input type="text" name="art" list="articulos">
	<datalist id="articulos">
	<?php
		$car=$conexion1->query("select * from consny.articulo")or die($conexion1->error());
		while($fcar=$car->FETCH(PDO::FETCH_ASSOC))
		{
			$arti=$fcar['ARTICULO'];
			$descri=$fcar['DESCRIPCION'];
			echo "<option>$arti</option>";
		}
	?>
	</datalist>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select documento_producion from registro where")or die($conexion2->error());
}
?>
</body>
</html>