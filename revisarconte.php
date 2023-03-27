<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<?php
	include("conexion.php");
	?>
</head>
<body>
<form method="POST">
	FECHA <input type="date" name="fecha" class="text" style="width: 20%;">
	 <input type="text" name="conte" class="text" style="width: 38%;" placeholder="CONTENEDOR">
	<input type="submit" class="boton2" value="SIGUIENTE">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select * from registro where fecha_documento='$fecha' and contenedor='$conte' and tipo='CD'")or die($conexion2->error);
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h2>NO SE ENCONTRO NINGUN REGISTRO</h2>";
	}else
	{
		echo "<table class='tabla' border='1' cellpadding='10'>
		<tr>
			<td>CODIGO</td>
			<td>NOMBRE</td>
			<td>CANTIDAD</td>
			<td>PESO</td>
		</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$cod=$f['codigo'];
			$cant=$f['cantidad'];
			$peso=$f['peso'];
			$con=$conexion1->query("select ARTICULO,DESCRIPCION from consny.ARTICULO WHERE ARTICULO='$cod'")or die($conexion1->error);
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$art=$fcon['ARTICULO'];
			$nom=$fcon['DESCRIPCION'];
			echo "<tr>
				<td>$art</td>
				<td>$nom</td>
				<td>$cant</td>
				<td>$peso</td>
			</tr>";

		}
	}
}
?>
</body>
</html>