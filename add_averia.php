<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	$(document).ready(function(){
		$("#b").focus();
	});
</script>
</head>
<body>
<div style="display: none;">
	<?php
	include("conexion.php");
	?>
</div>
<div class="detalle">
	<a href="averia.php">
	<button style="float: right; margin-right: 0.7%; cursor: pointer;">CERRAR X</button></a>
<div class="adentro" style="margin-left: 2.5%; margin-top: 0.5%;">
<form method="POST">
	<input type="text" name="b" id="b" placeholder="ARTICULO O DESCRIPCION" class="text" style="width: 15%; margin-left: 2%;">
	<input type="submit" name="" value="BUSCAR" class="boton3">
</form><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	$bo=str_replace(' ', '%', $b);
	$c=$conexion1->query("select * from consny.articulo where articulo='$b' or descripcion like '%$bo%'")or die($conexion1->error());
}else
{
	$c=$conexion1->query("select * from consny.articulo")or die($conexion1->error());

}
$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE ENCONTRO NINGUN ARTICULO</h2>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:2%;'>";

	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['ARTICULO'];
		echo "<tr>
		<td><a href='averia.php?art=$art' style='text-decoration:none; color:black;'>$art</a></td>
		<td><a href='averia.php?art=$art' style='text-decoration:none; color:black;'>".$f['DESCRIPCION']."</a></td>
	</tr>";
	}
}
?>
</div>
</div>
</body>
</html>