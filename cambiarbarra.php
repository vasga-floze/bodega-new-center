<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php
	include("conexion.php");
		if($_SESSION['tipo']!=1)
		{
			echo "<script>alert('NO TIENES AUTORIZACION')</script>";
			echo "<script>location.replace('desglose.php')</script>";

		}
	?>
</head>
<body>
<form method="POST">
	<input type="text" name="buscar" required>
<input type="submit" name="btn" value="buscar">

</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select * from registro where barra='$buscar'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO <a href='nuevobarravacio.php?b=$buscar'>NUEVO</a></h3>";
	}else
	{
		echo "<table class='tabla' border='1' cellpadding='10'>";
		echo "<tr>
			<td>CODIGO BARRA</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>FECHA REGISTRO</td>
			<td>REGISTRADO POR</td>
			<td>CONTENEDOR</td>
			<td>CANTIDAD</td>
			<Td>PESO</td>
			<Td>OPCION</td>
		</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$f['codigo'];
			$barra=$f['barra'];
			$fecha_r=$f['fecha_documento'];
			$registrado=$f['usuario'];
			$cantidad=$f['cantidad'];
			$peso=$f['peso'];
			$contenedor=$f['contenedor'];
			$k=$conexion1->query("select * from consny.ARTICULO WHERE consny.ARTICULO.ARTICULO='$art'")or die($conexion1->error());
			$fk=$k->FETCH(PDO::FETCH_ASSOC);
			$de=$fk['DESCRIPCION'];

			echo "<tr>
			<td>$barra</td>
			<td>$art</td>
			<td>$de</td>
			<td>$fecha_r</td>
			<td>$registrado</td>
			<td>$contenedor</td>
			<td>$cantidad</td>
			<Td>$peso</td>
			<Td><a href='nuevobarra.php?b=$barra'>NUEVO</a></td>
		</tr>";

		}
	}
}
?>
</body>
</html>