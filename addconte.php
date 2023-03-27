<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function cerrar()
		{
			
			location.replace('contenedor.php');
		}
	</script>
</head>
<body>
	<div style="display: none;">
		<?php 
			include("conexion.php");
			$contenedor=$_SESSION['contenedor'];
			$fecha=$_SESSION['fecha'];
			$c=$conexion2->query("select * from registro where contenedor='$contenedor' and fecha_documento='$fecha' and tipo='C1'")or die($conexion2->error());
			$n=$c->rowCount();
			if($n==0)
			{
				echo "<script>location.replace('contenedor.php')</script>";
			}
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$bod=$f['bodega'];
		?>
	</div>
<div class="detalle">
	<button  style="float: right; margin-right: 0.5%;" onclick="cerrar()">CERRAR X</button>
	<div class="adentro" style="margin-left: 2.5%; margin-top: 0.5%;">
		<form method="POST">
		<input type="text" name="b" class="text" style="width: 30%; margin-left: 15%;" placeholder="CODIGO O NOMBRE ARTICULO">
		<input type="submit" name="btn" value="BUSCAR" class="boton3">
			
		</form><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	$b=str_replace(" ", "%", $b);
	$c=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION FROM consny.articulo inner join consny.EXISTENCIA_BODEGA ON consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO AND consny.EXISTENCIA_BODEGA.BODEGA='$bod' AND consny.ARTICULO.ACTIVO='S' AND consny.ARTICULO.CLASIFICACION_1!='DETALLE' WHERE consny.ARTICULO.ARTICULO='$b' OR consny.ARTICULO.DESCRIPCION LIKE '%$b%'
")or die($conexion1->error());
}else
{
	$c=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION FROM consny.articulo inner join consny.EXISTENCIA_BODEGA ON consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO AND consny.EXISTENCIA_BODEGA.BODEGA='$bod' AND consny.ARTICULO.ACTIVO='S' AND consny.ARTICULO.CLASIFICACION_1!='DETALLE'")or die($conexion1->error());
}

$nu=$c->rowCount();
if($nu==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10'>";
	echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$arti=$f['ARTICULO'];
		$desc=$f['DESCRIPCION'];
		echo "<tr>
				<td><a href='contenedor.php?art=$arti' style='text-decoration:none; color:black;'>$arti</a></td>
				<td><a href='contenedor.php?art=$arti' style='text-decoration:none; color:black;'>$desc</a></td>
		</tr>";
	}
}
?>

	</div>
</div>
</body>
</html>