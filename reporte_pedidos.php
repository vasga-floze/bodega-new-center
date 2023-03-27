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
?>
<center>
<form method="POST" action="reporte_final_pedido.php" style="text-align: center;">

<?php
$c=$conexion2->query("select tienda from pedidos where estado='CONFIRMADO' and despacho ='N' group by tienda")or die($conexion2->error());
$n=$c->rowCount();

if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN PEDIDO CONFIRMADO SIN DESPACHO</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='text-align:left; float:center; margin-left:35%;'>";
	echo "<tr>
		<td>SELECCIONA LAS BODEGA DEL REPORTE</td>
	</tr>";
	$num=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$bodega=$f['tienda'];
		$cb=$conexion1->query("select concat(bodega,':',nombre) as nombres,bodega from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$nom=$fcb['nombres'];
		$bod=$fcb['bodega'];
		echo "<tr><td><label><input type='checkbox' name='bodega[$num]' value='$bod'>$nom</label></td></tr>";
		$num++;
	}

	echo "<input type='hidden' name='num' value='$num'>";

	echo "<br>";

}
?>
<tr><td><input type='submit' name='btn' value='GENERAR REPORTE' class="btnfinal" style="padding: 2%; float: right; margin-right: 0.5%; background-color: #8FBAA9; color: black; margin-bottom: -0.5%;"></td></tr>
</table>
</form>

</body>
</html>