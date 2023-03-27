<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

</head>
<body>
<?php
include("conexion.php");
if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' OR $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='GJURADO' or $_SESSION['usuario']=='gjurado')
{

}else
{
	echo "<script>alert('NO DISPONIBLE')</script>";
	echo "<script>location.replace('conexiones.php')</script>";
}
?>
<form method="POST">
<input type="text" name="barra" class='text' style='width:20%;' 	placeholder="CODIGO BARRA">
<input type="date" name="fecha" class='text' style='width:20%;'>
<input type="text" name="contenedor" class='text' style='width:35%;' placeholder="CONTENEDOR">

<input type="submit" name="btn" value="BUSCAR" style="background-color: #84C889; border-color: black; padding: 0.5%; cursor: pointer;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($barra=='' and $fecha!='' and $contenedor!='')
	{
		$c=$conexion2->query("select codigo,peso,fecha_ingreso,count(codigo) as cantidad from registro where fecha_documento='$fecha' and contenedor='$contenedor' and tipo='CD' and activo is null group by codigo,peso,fecha_ingreso")or die($conexion2->error());
	

	
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<H3 style='text-decoration:underline;'>NO ENCONTRO NINGUN REGISTRO DE CONTENEDOR</H3>";
	}else
	{
		echo "<table border='1' width='100%' cellpadding='10' style='border-collapse:collapse;'>";
		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD DISPONIBLE</td>
			<td width='5%;'>IMPRIMIR</td>
		</tr>";

		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$f['codigo'];
			$bodega='e';
			$peso=$f['peso'];
			$fechai=$f['fecha_ingreso'];
			$cantidad=$f['cantidad'];
			/*$id=$f=$f['id_registro'];
			$id=base64_encode($id);*/
			$ca=$conexion1->query("select articulo,descripcion from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			echo "<tr>
			<td>".$fca['articulo']."</td>
			<td>".$fca['descripcion']."</td>
			<td>$cantidad</td>
			<td>
			<a href='reimprimir_conte.php?tipo=1&&art=$art&&peso=$peso&&fechai=$fechai&&contenedor=$contenedor&&fecha=$fecha' target='_blank'>
			<img src='imprimir.png' width='30%' height='5%'></a>
			<a href='reimprimir_contev.php?tipo=1&&peso=$peso&&art=$art&&fechai=$fechai&&contenedor=$contenedor&&fecha=$fecha' target='_blank'>
			<img src='viñeta.png' width='40%' height='15%'></a>
			</td>
		</tr>";

		}
	}

	}else if($barra!='')
	{
		$c=$conexion2->query("select * from registro where barra='$barra' and tipo!='p' and activo is null")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo  "<H3 style='text-decoration:underline;'>NO ENCONTRO NINGUN REGISTRO DE CONTENEDOR CON BARRA: $barra O YA  NO ESTA ACTIVO</H3>";
		}else
		{
			echo "<table border='1' width='100%' cellpadding='10' style='border-collapse:collapse;'>";
		echo "<tr>
		<td>CODIGO BARRA</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>BODEGA ACTUAL</td>
			<td width='5%;'>IMPRIMIR</td>
		</tr>";
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$id=base64_encode($f['id_registro']);
				$art=$f['codigo'];
				$bodega=$f['bodega'];
				$barra=$f['barra'];
				$ca=$conexion1->query("select articulo,descripcion from consny.articulo where articulo='$art'")or die($conexion1->error());
				$fca=$ca->FETCH(PDO::FETCH_ASSOC);
				$articulo=$fca['articulo'];
				$desc=$fca['descripcion'];
				echo "<tr>
			<td>$barra</td>
			<td>$articulo</td>
			<td>$desc</td>
			<td><B><U>$bodega</U></B></td>
			<td>
			<a href='reimprimir_conte.php?tipo=2&&id=$id' target='_blank'>
			<img src='imprimir.png' width='30%' height='5%'></a>
			<a href='reimprimir_contev.php?tipo=2&&id=$id' target='_blank'>
			<img src='viñeta.png' width='40%' height='15%'></a>
			</td>
		</tr>";

			}
		}
		
	}
}
?>
</body>
</html>