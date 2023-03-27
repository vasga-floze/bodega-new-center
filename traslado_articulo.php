<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<style>
		.preloader {
  width: 70px;
  height: 70px;
  border: 10px solid #eee;
  border-top: 10px solid skyblue;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 3s;
  animation-iteration-count: infinite;

}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
	</style>
</head>

<body>

	<?php include("conexion.php");
	if($_SESSION['tipo']==3)
	{
		echo "<script>location.replace('consultar.php')</script>";
	}
	?>
<form method="POST">
ORIGEN: <input type="text" name="origen">
DESTINO: <input type="text" name="destino">
ARTICULO: <input type="text" name="articulo">
DESDE: <input type="date" name="desde">
HASTA: <input type="date" name="hasta">

<input type="submit" name="" value="BUSCAR" class="boton4">
	
</form>
<?php
if($_POST)
{
	echo "<hr><br>";
	extract($_REQUEST);
	if($origen!='' and $destino!='' and $articulo!='' and $desde!='' and $hasta!='')
	{
	$c=$conexion2->query("select * from traslado where articulo='$articulo' and origen='$origen' and destino='$destino' and fecha between '$desde' and '$hasta'")or die($conexion2->error());
	}else if($origen!='' and $destino!='' and $articulo=='' and $desde!='' and $hasta!='')
	{
	$c=$conexion2->query("select * from traslado where origen='$origen' and destino='$destino' and fecha between '$desde' and '$hasta'")or die($conexion2->error());
	}else if($origen=='' and $destino=='' and $desde!='' and $hasta !='' and $articulo!='')
	{
		$c=$conexion2->query("select * from traslado where articulo='$articulo' and fecha between '$desde' and '$hasta'")or die($conexion2->error());
	}else if($origen=='' and $destino=='' and $desde=='' and $hasta=='' and $articulo!='')
	{
		$c=$conexion2->query("select * from traslado where articulo='$articulo' ")or die($conexion2->error());
	}else if($origen!='' and $destino!='' and $articulo=='' and $desde=='' and $hasta=='')
	{
		$c=$conexion2->query("select * from traslado where origen='$origen' and destino='$destino' ")or die($conexion2->error());

	}else if($origen!='' and $destino!='' and $articulo!='' and $desde=='' and $hasta=='')
	{
		$c=$conexion2->query("select * from traslado where origen='$origen' and destino='$destino' and articulo='$articulo' ")or die($conexion2->error());
	}else if($origen!='' and $destino=='' and $articulo!='' and $desde=='' and $hasta=='')
	{
		$c=$conexion2->query("select * from traslado where origen='$origen' and articulo='$articulo' ")or die($conexion2->error());
	}else if($origen=='' and $destino!='' and $articulo!='' and $desde=='' and $hasta=='')
	{
		$c=$conexion2->query("select * from traslado where destino='$destino' and articulo='$articulo' ")or die($conexion2->error());

	}else if($origen==''and $destino!='' and $articulo=='' and $desde=='' and $hasta=='')
	{
		$c=$conexion2->query("select * from traslado where destino='$destino'")or die($conexion2->error());
	}else if($destino=='' and $origen!='' and $desde=='' and $hasta=='')
	{
		$c=$conexion2->query("select * from traslado where origen='$origen'")or die($conexion2->error());
	}else if($origen!='' and $desde!='' and $hasta!='' and $articulo=='' and $destino=='')
	{
		$c=$conexion2->query("select * from traslado where origen='$origen' and fecha between '$desde' and '$hasta'")or die($conexion2->error());
	}else if($destino!='' and $desde!='' and $hasta!='' and $origen=='' and $articulo=='')
	{
		$c=$conexion2->query("select * from traslado where destino='$destino' and fecha between '$desde' and '$hasta'")or die($conexion2->error());
	}else if($origen!='' and $articulo!='' and $desde!='' and $hasta!='')
	{
		$c=$conexion2->query("select * from traslado where origen='$origen' and articulo='$articulo' and fecha between '$desde' and '$hasta'")or die($conexion2->error());
	}else if($destino!='' and $articulo!='' and $desde!='' and $hasta!='')
	{
		$c=$conexion2->query("select * from traslado where destino='$destino' and articulo='$articulo' and fecha between '$desde' and '$hasta'")or die($conexion2->error());
	}

$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE ENCONTRO NINGUN REGISTRO";
}else
{
	echo "<table class='tabla' border='1' style='width:95%;' cellpadding='10'>";
	echo "<tr>
		<td>ORIGEN</td>
		<td>DESTINO</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CODIGO BARRA</td>
		<td>FECHA TRASLADO</td>
		<td>DOCUMENTO</td>
		<td>USUARIO</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$f['registro'];
		$origen=$f['origen'];
		$destino=$f['destino'];
		$fecha=$f['fecha'];
		$docu=$f['documento_inv'];
		$usua=$f['usuario'];
		$con=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
		$fcon=$con->FETCH(PDO::FETCH_ASSOC);
		$barra=$fcon['barra'];
		$art=$fcon['codigo'];

		$ca=$conexion1->query("select * from consny.ARTICULO where articulo='$art'")or die($conexion1->error());

		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$art=$fca['ARTICULO'];
		$des=$fca['DESCRIPCION'];
		echo "<tr>
		<td>$origen</td>
		<td>$destino</td>
		<td>$art</td>
		<td>$des</td>
		<td>$barra</td>
		<td>$fecha</td>
		<td>$docu</td>
		<td>$usua</td>
	</tr>";

	}

}
}
?>

</body>
</html>