<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function enviar()
		{
			$("#form").submit();
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST" id="form">
FECHA DE VENTA: <input type="date" name="fecha" class="text" style="width: 20%" onchange="enviar();">
	
</form>

<?php
extract($_REQUEST);
if($_POST)
{
	//echo "<script>alert('post')</script>";
	
	$c=$conexion2->query("select sessiones,usuario from venta where fecha='$fecha' group by sessiones,usuario order by sessiones desc")or die($conexion2->error());
}else
{
	//echo "<script>alert('else')</script>";
	$fecha=date("Y-m-d");
	$c=$conexion2->query("select sessiones,usuario from venta where fecha='$fecha' group by sessiones,usuario ORDER BY sessiones desc")or die($conexion2->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUNA VENTA DE LA FECHA: $fecha</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='margin-top:0.5%; width:100%; border-collapse:collapse;'>";
	echo "<tr>
		<td>FECHA VENTA</td>
		<td>CLIENTE</td>
		<td>TIPO DE VENTA</td>
		<td>DOCUMENTO</td>
		<td>OPCION</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$sessiones=$f['sessiones'];
		$usuario=$f['usuario'];
		//echo "<script>alert('$sessiones | $usuario')</script>";
		$q=$conexion2->query("select sum(convert(int,cantidad)) as total from venta where sessiones='$sessiones' and usuario='$usuario'")or die($conexion2->error());
		$fq=$q->FETCH(PDO::FETCH_ASSOC);

		if($fq['total']=='')
		{
			$tipo='CODIGOS DE BARRA';
			$url="imprimir_venta.php?id=$sessiones";
			
		}else
		{
			$tipo='PIEZAS';
			$url='imprimir_ventacod.php?ventacod='.$sessiones.'&&usuario='.$usuario.'';
		}

		$c1=$conexion2->query("select top 1 * from venta where sessiones='$sessiones' and usuario='$usuario'")or die($conexion2->error());
		$fc1=$c1->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
		<td>".$fc1['fecha']."</td>
		<td>".$fc1['cliente']."</td>
		<td>$tipo</td>
		<td>".$fc1['documento_inv']."</td>
		<td> <a href='$url' target='_blank'>IMPRIMIR</a></td>
	</tr>";



	}
	echo "</table";
	
}
?>
</body>
</html>