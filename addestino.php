<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function cerrar()
		{
			location.replace('traslados.php');
		}
	</script>
</head>
<body>
<div class="detalle" style="width: 100%;">
<button style="float: right; margin-right: 0.5%;" onclick="cerrar()">CERRAR X</button>
<div class="adentro" style="margin-left: 2.4%; margin-top: 0.4%; scrollbar-color:gray CadetBlue; 
	scrollbar-width:thin;">
<form method="POST">
<input type="text" name="b" placeholder="NOMBRE DE BODEGA" class="text" style="width: 30%; margin-left: 5%;">
<input type="submit" name="btn" value="BUSCAR" class="boton3">	
</form>
<?php
echo "<div style='display:none;'>";
include("conexion.php");
echo "</div>";
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion1->query("select * from consny.BODEGA where bodega='$b' or nombre like '%$b%'  and nombre not like'%(N)%' ")or die($conexion1->error);

}else
{
	$c=$conexion1->query("select * from consny.BODEGA where  nombre not like'%(N)%'")or die($conexion1->error);
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
}else
{
	echo "<table border='1' class='tabla' border='1' cellpadding='10' style='margin-left:2.5%; margin-top
	:1.5%;'>";
	echo "<tr>
		<td>BODEGA</td>
		<td>NOMBRE</td>
	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$f['BODEGA'];
		$nom=$f['NOMBRE'];
		echo "<tr>
			<td><a href='traslados.php?&&bcod=$bod&&b= ' style='text-decoration:none;'>$bod</a></td>
			<td><a href='traslados.php?&&bcod=$bod&&b= ' style='text-decoration:none;'>$nom</a></td>
		</tr>";
	}
	echo "</table>";
}
?>



</div>
	
</div>
</body>
</html>