<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST">
<input type="text" name="cod">
<input type="text" name="bod">
<input type="submit" value="buscar">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($cod=='' and $bod=='')
	{
	

}else if($bod!='' and $cod=='')
{
	$c=$conexion1->query("select bodega,articulo,convert(int,cant_disponible) as cant_disponible from consny.existencia_bodega where bodega='$bod' and articulo in(select articulo from consny.articulo where clasificacion_1!='detalle'
) order by articulo")or die($conexion1->error());
}else if($bod=='' and $cod!='')
{
	$c=$conexion1->query("select bodega,articulo,convert(int,cant_disponible) as cant_disponible from consny.existencia_bodega where articulo='$cod'
 order by bodega")or die($conexion1->error());

}else if($bod!='' and $cod!='')
{
	$c=$conexion1->query("select bodega,articulo,convert(int,cant_disponible) as cant_disponible from consny.existencia_bodega where bodega='$bod' and articulo='$cod'")or die($conexion1->error());
}
echo "<table border='1'><tr>
<td>#</td>
<td>BODEGA</td>
	<td>
		ARTICULO
	</td>
	<td>
		DESCRIPCION
	</td>
	<td>CANTIDA BARRA</td>
	<td>CANTIDA EXACTUS</td>
</tr>";
$n=0;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$art=$f['articulo'];
	$cant1=$f['cant_disponible'];
	$bod=$f['bodega'];

	$q=$conexion2->query("select count(*) as cantidad from registro where (fecha_desglose is null or fecha_desglose='') and activo is null and bodega='$bod' and codigo='$art'
")or die($conexion2->error());

	$fq=$q->FETCH(PDO::FETCH_ASSOC);

	$cat=$fq['cantidad'];

	$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
	$desc=$fca['DESCRIPCION'];
	if($cat!=$cant1)
	{
		echo "<tr style='background-color:white;'>";
		echo "
		<td>$n</td>
		<td>$bod</td>
		<td>$art</td>
		<td>$desc</td>
		<td>$cat</td>
		<td>$cant1</td>
	</tr>";
		$n++;
	}else
	{
		//echo "<tr>";
	}
	
		
	
	
}
}
?>
</body>
</html>