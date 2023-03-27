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
<h3 style="text-align: center; text-decoration: underline;">REPORTE DE CUADRO VENTA</h3>
<form method="POST" id="form">
	FECHA: <input type="date" name="fecha" onchange="enviar()" class="text" style="width: 20%;">
	
</form>

<?php
extract($_REQUEST);
$user=$_SESSION['usuario'];
if($_POST)
{
	$c=$conexion1->query("select cuadro_venta.usuario,cuadro_venta.caja,cuadro_venta.monto_usuario,cuadro_venta.fecha,cuadro_venta_detalle.tipo_transaccion,sum(cuadro_venta_detalle.monto) as total,cuadro_venta.id from cuadro_venta inner join cuadro_venta_detalle on cuadro_venta.id=cuadro_venta_detalle.cuadro_venta where cuadro_venta.usuario='$user' and cuadro_venta.fecha='$fecha' group by cuadro_venta.usuario,cuadro_venta.caja,cuadro_venta.monto_usuario,cuadro_venta.fecha,cuadro_venta_detalle.tipo_transaccion,cuadro_venta.id
")or die($conexion1->error());
}else
{
	$fecha=date('Y-m-d');
	$c=$conexion1->query("select cuadro_venta.usuario,cuadro_venta.caja,cuadro_venta.monto_usuario,cuadro_venta.fecha,cuadro_venta_detalle.tipo_transaccion,sum(cuadro_venta_detalle.monto) as total,cuadro_venta.id from cuadro_venta inner join cuadro_venta_detalle on cuadro_venta.id=cuadro_venta_detalle.cuadro_venta where cuadro_venta.usuario='$user' and cuadro_venta.fecha='$fecha' group by cuadro_venta.usuario,cuadro_venta.caja,cuadro_venta.monto_usuario,cuadro_venta.fecha,cuadro_venta_detalle.tipo_transaccion,cuadro_venta.id
")or die($conexion1->error());
}
$n=$c->rowCount();

if($n==0)
{
	$cu=$conexion1->query("select * from cuadro_venta where fecha='$fecha' and usuario='$user'")or die($conexion1->error());
	$ncu=$cu->rowCount();
	if($ncu==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
	}else
	{
		$fcu=$cu->FETCH(PDO::FETCH_ASSOC);
		echo "<table border='1' width='100%' cellpadding='10' style='border-collapse:collapse;'>";
	echo "<tr>
		<td colspan='5'>REPORTE DEL CUADRO VENTA DE LA FECHA: $fecha <a href='#' style='float:right; margin-right:0.5%; color:black;'>MONTO: $".$fcu['MONTO_USUARIO']."</a></td>
	</tr>";
	}
	
}else
{
	$v=1;
	
$total=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		if($v==1)
		{
			echo "<table border='1' width='100%' cellpadding='10' style='border-collapse:collapse;'>";
	echo "<tr>
		<td colspan='5'>REPORTE DEL CUADRO VENTA DE LA FECHA: $fecha <a href='#' style='float:right; margin-right:0.5%; color:black;'>MONTO: $".$f['monto_usuario']."</a></td>
	</tr>";
	echo "<tr>
		<td>CAJA</td>
		<td>MONTO</td>
		<td>FECHA</td>
		<td>TIPO TRANSACCION</td>
		<td>TOTAL</td>
	</tr>";
	$v=0;

		}
		if($f['tipo_transaccion']=='SALIDA')
		{
			$tipo='SALIDA';
			$total=$f['total'];
		}else
		{
			$tipo='INGRESOS';
			$total=$f['total'];
		}
		echo "<tr>
		<td>".$f['caja']."</td>
		<td>".$f['monto_usuario']."</td>
		<td>".$f['fecha']."</td>
		<td>$tipo</td>
		<td>$total</td>
	</tr>";
	}
}
?>
</body>
</html>