<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
echo "<div style='display:none;'>";
include("conexion.php");
echo "</div>";
$bodega=$_GET['bodega'];
?>
<div class="detalle" style="">
<?php
echo "<a href='pedidos_confirma.php?bodega=$bodega' style='color:white; text-decoration:none; float: right; margin-right:0.5%;'>CERRAR X</a><br>";
?>
	<div class="adentro" style="margin-left: 3%; height: 93%; ">
		

<form method="POST" style="margin-left: 3%;">
<input type="text" name="b" class="text" style="width: 30%;" placeholder="ARTICULO O DESCRIPCION">
<input type="submit" name="btn" value="BUSCAR" class=" btnfinal" style="padding: 0.5%; background-color: #529C71; color: white;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion1->query("select * from consny.articulo where articulo='$b' or descripcion like (SELECT '%'+REPLACE('$b',' ','%')+'%') and clasificacion_1!='DETALLE' and activo='S'  and descripcion not like '%(N)%' and clasificacion_2 is not null")or die($conexion1->error());
}else
{
	$c=$conexion1->query("select * from consny.articulo where activo='S' and clasificacion_1!='DETALLE' and clasificacion_2 is not null")or die($conexion1->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN ARTICULO: $b O NO ESTA ACTIVO</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width:90%; margin-left:4%; margin-top:-6%;'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>EXISTENCIA TIENDA</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['ARTICULO'];
		$desc=$f['DESCRIPCION'];
		$cr=$conexion2->query("declare @cantidad int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose='' and codigo='$art' group by codigo)
declare @cantidad1 int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose is null and codigo='$art' group by codigo)

declare @total int = (isnull(@cantidad,0) + isnull(@cantidad1,0))

select @total as total
")or die($conexion2->error());

		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$exis=$fcr['total'];
		if($exis=='')
		{
			$exis=0;
		}
		echo "<tr>
		<td><a href='pedidos_confirma.php?bodega=$bodega&&art=$art'  style='text-decoration:none;'>$art</a></td>
		<td><a href='pedidos_confirma.php?bodega=$bodega&&art=$art'  style='text-decoration:none;'>$desc</a></td>
		<td><a href='pedidos_confirma.php?bodega=$bodega&&art=$art'  style='text-decoration:none;'>$exis</a></td>
	</tr>";
	}
}
?>
</div>
</div>
</body>
</html>