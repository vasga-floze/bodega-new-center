<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function cerrar()
		{
			location.replace('nuevobarravacio.php?y=1');
		}
	</script>
</head>
<body>
	<div style="display: none;" id="conte">
	<?php
		include("conexion.php");
		echo "</div>";
		$barra=$_SESSION['barranuevo'];
		if($barra=="")
		{
			echo "<script>location.replace('nuevobarravacio.php')</script>";
		}else
		{
			$con=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());

			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$bodega=$fcon['bodega'];
		}
	?>
	

<div class="detalle">
	<button style="float: right; margin-right: 0.5%;" onclick="cerrar()">Cerrar X</button>
	<div class="adentro" style="margin-left: 2.5%; height: 93%;">
	<form method="POST">
	<input type="text" name="b" class="text" style="width: 20%; margin-left: 25%;" placeholder="CODIGO O NOMBRE ARTICULO" >
	<input type="submit" name="btn" class="boton3" value="BUSCAR" >
		
	</form><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	$b=str_replace(" ", "%", $b);
	$c=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.ARTICULO.ACTIVO,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO and consny.ARTICULO.ACTIVO='S' and consny.EXISTENCIA_BODEGA.BODEGA='$bodega' and consny.ARTICULO.CLASIFICACION_1!='detalle' WHERE consny.ARTICULO.ARTICULO='$b' OR consny.ARTICULO.DESCRIPCION LIKE '%$b%'")or die($conexion1->error());
}else
{
	$c=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.ARTICULO.ACTIVO,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO and consny.ARTICULO.ACTIVO='S' and consny.EXISTENCIA_BODEGA.BODEGA='$bodega' and consny.ARTICULO.CLASIFICACION_1!='detalle'")or die($conexion1->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRON NINGUN REGISTRO!</h3>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:3%;'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['ARTICULO'];
		$des=$f['DESCRIPCION'];
		echo "<tr>
			<td><a href='nuevobarravacio.php?y=1&&art=$art' style='text-decoration:none; color:black;'>$art</a></td>
			<td><a href='nuevobarravacio.php?y=1&&art=$art' style='text-decoration:none; color:black;'>$des</a></td>
		</tr>";
	}
	echo "</table>";
}
?>


	</div>
</div>
</body>
</html>