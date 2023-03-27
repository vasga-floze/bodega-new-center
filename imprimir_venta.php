<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="moment.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			//$("#me").hide();
			//$("#img").hide();
			//window.print();
			$("#me").show();
			$("#img").show();
		});
		function imprimir()
		{
			$("#me").hide();
			$("#img").hide();
			window.print();
			$("#me").show();
			$("#img").show();
		}
	</script>
<?php
echo "<div id='me'>";
include("conexion.php");
echo "</div>";
echo "<br>";
echo "<img src='imprimir.png' width='5%' height='5%' style='float:right; cursor:pointer;' id='img' onclick='imprimir()'>";

$id=$_GET['id'];
$c=$conexion2->query("select * from venta where sessiones='$id'")or die($conexion2->error());
$f=$c->FETCH(PDO::FETCH_ASSOC);
$docu=$f['documento_inv'];
$fecha=$f['fecha'];
$cliente=$f['cliente'];
$c=$conexion2->query("select * from venta where sessiones='$id' and precio!='' and registro !=''")or die($conexion2->error());
$qn=$conexion2->query("select count(*) as total from venta where sessiones='$id' and registro!='' and precio!=''")or die($conexion2->error);
	$fqn=$qn->FETCH(PDO::FETCH_ASSOC);
	$totalf=$fqn['total'];
?>
</head>
<body>
	<div>
<table class="tabla" style="border-color: black;margin-top: 0.5%; float: left;  width: 100%;" border="1"  cellpadding="5">

<tr border='0'>
	<td width="70%" colspan="4">
		<P>CLIENTE: <?php echo "$cliente";?></P>

		<P>FECHA: <?php echo "$fecha";?></P>

		<P>observacion: <?php echo "".$f['observacion']."";?></P>

	</td>


	<td width="30%" style="text-align: center;" colspan="2">
		<img src="logo.png" width="50%" height="8%">
		<br><?php echo "$docu";?>
	</td>
</tr>
<tr>
	<td colspan="7">TOTAL FARDO: <?php echo "$totalf";?></td>
</tr>
<tr>
	<td>#</td>
	<td>CANTIDAD</td>
	<td>DESCRIPCION</td>
	<td>LIBRAS</td>
	<td>PRECIO</td>
	<td>TOTAL</td>
</tr>
<?php
$t=0;
$total=0;
$k=1;
$c=$conexion2->query("select * from venta where sessiones='$id' and precio!='' and registro !=''")or die($conexion2->error());
$tcant=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$cantidad=1;
		$idr=$f['registro'];
		$precio=$f['precio'];
		$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$cod=$fcr['codigo'];
		$libras=$fcr['lbs'] + $fcr['peso'];
		$ca=$conexion1->query("select DESCRIPCION FROM consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$de=$fca['DESCRIPCION'];
		$t=$precio * $cantidad;
		$total=$total + $t;
		echo "<tr>
		<td>$k</td>
	<td>$cantidad</td>
	<td>$de</td>
	<td>$libras</td>
	<td>$$precio</td>
	<td>$$t</td>
</tr>";
$tcant=$tcant + $cantidad;
$k++;
	}
	echo "<tr>
		<td>TOTAL</td>
		<td>$tcant</td>
		<td colspan='3'></td>
		<td>$$total</td>
	</tr>
	<tr>
	<td colspan='6'>Vendedor: ______________ Cliente: ______________</td>
	</tr>
	</table>";

?>

</div>




</body>
</html>
