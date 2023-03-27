<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
		})
	</script>
</head>
<body style="background-color: #CCC;">
<?php
echo "<div style='display:none;'>";
include("conexion.php");
echo "</div>";
$clasificacion=$_GET['clasificacion'];

?>
<div class="detalle">
	<a href='<?php echo "pedidos.php?clasificacion=$clasificacion";?>' style="float: right; text-decoration: none; color: white; font-size: 20px;">Cerrar X</a><br>
<div class="adentro" style="width: 98%; height: 98%; margin-left: 1%;">
	

<form method="POST" style="margin-left: 0.5%; margin-bottom: -5.5%;">
<input type="text" name="b" placeholder="ARTICULO O DESCRIPCION" class="text" style="width: 30%;">
<input type="submit" name="" value="BUSCAR" class="btnfinal" style="padding: 0.5%; background-color: #80D1CB;">
	
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion1->query("select * from consny.articulo where articulo='$b' or descripcion like (SELECT '%'+REPLACE('$b',' ','%')+'%') and (clasificacion_1!='DETALLE' or clasificacion_1='insumo') and clasificacion_2='$clasificacion' and activo='S' order by articulo")or die($conexion1->error());

}else
{
	$c=$conexion1->query("select * from consny.articulo where clasificacion_2='$clasificacion' and clasificacion_1!='DETALLE' and activo='S' order by articulo")or die($conexion1->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO ARTICULO: $b</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='width:98%; margin-left:1.3%; border-collapse:collapse;'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>EXISTENCIA</td>

	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['ARTICULO'];
		$desc=$f['DESCRIPCION'];
		$bodega=$_SESSION['bodega'];
		$ca=$conexion2->query("declare @cantidad int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose='' and codigo='$art' group by codigo)
declare @cantidad1 int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose is null and codigo='$art' group by codigo)

declare @total int = (isnull(@cantidad,0) + isnull(@cantidad1,0))

select @total as total
")or die($conexion2->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$cant=$fca['total'];
		IF($cant=='')
		{
			$cant=0;
		}
		echo "<tr>
		<td><a href='pedidos.php?clasificacion=$clasificacion&&art=$art'><label>$art</a></td>
		<td><a href='pedidos.php?clasificacion=$clasificacion&&art=$art'>$desc</a></td>
		<td><a href='pedidos.php?clasificacion=$clasificacion&&art=$art'>$cant</a></td>

	</tr>";
	}
}
?>
</div>
</div>
	

</body>
</html>