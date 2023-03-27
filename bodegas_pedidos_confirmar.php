<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script >
		function cerrar()
		{
			//alert("svf");
			window.history.back(-1);
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
?>
<!--<div class="detalle" style="width: 101%; margin-left: -1%;">
<a href="#" onclick="cerrar()" style="float: right; margin-right: 1%; color: white; text-decoration: none;">CERRAR X</a><br>
<div class="adentro" style="margin-left: 3%; height: 92%;"> -->
<?php
$c=$conexion2->query("select tienda,convert(date,fecha)as fecha,sum(cantidad_tienda) as cantidad,sessiones from pedidos where estado='SOLICITUD'  GROUP BY tienda,convert(date,fecha),sessiones ORDER BY tienda
")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN PEDIDO PENDIENTE DE CONFIRMAR</h3>";
}
echo "<table border='1' style='width:98%; margin-left:1%; border-collapse:collapse;' cellpadding='10'>";
echo "<tr>
<td>BODEGA</td>
<td>FECHA</td>
<td>CANTIDAD</td>
<td></td>
</tr>";
while ($f=$c->FETCH(PDO::FETCH_ASSOC))
{
$bodega=$f['tienda'];
$fecha=$f['fecha'];
$cant=$f['cantidad'];
$sessiones=$f['sessiones'];
$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion2->error());
$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
$bodega=$fcb['BODEGA'];
$nom=$fcb['NOMBRE'];
echo "<tr class='tre'>
<td>$bodega: $nom</td>
<td>$fecha</td>
<td>$cant</td>
<td width='25%'>
<a href='pedidos_confirma.php?bodega=$bodega'>
<button class='btnfinal' style='padding:1.5%; background-color:#6FBB84; color:black; margin-top:2%; border-color:black; margin-left:3.5%; cursor:pointer; margin-bottom:-3%;'>SELECIONAR</button></a>";

	echo "
	<a href='eliminar_pedido_confirma.php?session=$sessiones&&fecha=$fecha&&bodega=$bodega'>
<button style='background-color:red; color:white; border-color:black; padding:1.5%; cursor:pointer;'>ELIMINAR</button></a>";
echo "
<a href='detalle_pedido_confirma.php?session=$sessiones&&fecha=$fecha&&bodega=$bodega'>
<button style='background-color:white; color:black; border-color:black; padding:1.5%; cursor:pointer;'>DETALLE</button></a>";


ECHO "

</td>
</tr>";


}
?>
</div>
	
</div>
</body>
</html>