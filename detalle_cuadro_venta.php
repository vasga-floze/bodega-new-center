<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function cerrar()
		{
			window.close();
		}
	</script>
</head>
<body>
<div style="display: none;">
<?php
//error_reporting(0);
include("conexion.php");
?>	
</div>
<div class="detalle">
<a href="#" style="color: white;float: right; margin-right: 0.5%;" onclick="cerrar()">CERRAR X</a><br>

	<div class="adentro" style="margin-left: 2.5%; height: 93%;">
	<?php
		$fecha=$_GET['fecha'];
		$bodega=$_GET['bodega'];
		$tipo=$_GET['tipo'];
		if($tipo=='liquido')
		{


		$c=$conexion1->query("select * from cuadro_venta where fecha='$fecha' and bodega='$bodega'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
		}else
		{
			echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:98%; margin-left:1%;'>";
			
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$monto_usuario=$f['MONTO_USUARIO'];
			$id=$f['ID'];
			$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$nom=$fcb['NOMBRE'];
			echo "<tr>
				<td colspan='5'>DETALLE CUADRO VENTA: $bodega: $nom FECHA: $fecha</td>";

			echo "<tr>
				<td colspan='5'>MONTO USUARIO: $$monto_usuario</td>
			</tr>";
			echo "<tr>
				<td>TRANSACCION</td>
				<td>TIPO DOCUMENTO</td>
				<td>NUMERO DOCUMENTO</td>
				<td>CONCEPTO</td>
				<td>MONTO</td>
			</tr>";
			$total=$monto_usuario;
			$q=$conexion1->query("select * from cuadro_venta_detalle where cuadro_venta='$id'")or die($conexion1->error());
			while($fq=$q->FETCH(PDO::FETCH_ASSOC))
			{
				$transaccion=$fq['TIPO_TRANSACCION'];
				$tipo_doc=$fq['TIPO_DOCUMENTO'];
				$num_doc=$fq['NUM_DOCUMENTO'];
				$concepto=$fq['CONCEPTO'];
				$monto=$fq['MONTO'];
				if($transaccion=='SALIDA')
				{
					$total=$total-$monto;
				}else
				{
					$total=$total+$monto;
				}
				echo "<tr>
				<td>$transaccion</td>
				<td>$tipo_doc</td>
				<td>$num_doc</td>
				<td>$concepto</td>
				<td>$$monto</td>
			</tr>";
			}
			$cf=$conexion1->query("declare @total varchar(50)=$total;
					select convert(decimal(10,2),@total) as total")or die($conexion1->error());
			$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
			$total=$fcf['total'];
			echo "<tr>
					<td colspan='4'>TOTAL LIQUIDO</td>
					<td>$$total</td>
				</tr>";


		}
	}else
	{
		$c=$conexion1->query("select cuadro_venta.monto_usuario,cuadro_venta.monto_sistema,cuadro_venta_detalle.tipo_transaccion,cuadro_venta_detalle.tipo_documento,cuadro_venta_detalle.num_documento,cuadro_venta_detalle.concepto,cuadro_venta_detalle.monto from cuadro_venta inner join cuadro_venta_detalle on cuadro_venta.id=cuadro_venta_detalle.cuadro_venta where cuadro_venta.fecha='$fecha' and cuadro_venta.bodega='$bodega' and cuadro_venta_detalle.tipo_transaccion='$tipo'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO NINGUNA $tipo</h3>";
		}else
		{
			echo "<table border='1' cellpadding='8' style='border-collapse:collapse; width:98%; margin-left:2%;'>";
			$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$nom=$fcb['NOMBRE'];
			echo "<tr>
				<td colspan='5'>DETALLE CUADRO VENTA $bodega: $nom FECHA: $fecha</td>
			</tr>";

			echo "<tr>
				<td>TRANSACCION</td>
				<td>TIPO DOCUMENTO</td>
				<td>NUMERO DOCUMENTO</td>
				<td>CONCEPTO</td>
				<td>MONTO</td>
			</tr>";
			$total=0;
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$transaccion=$f['tipo_transaccion'];
				$tipo_doc=$f['tipo_documento'];
				$num_doc=$f['num_documento'];
				$concepto=$f['concepto'];
				$monto=$f['monto'];

				$total=$total + $monto;
				$qf=$conexion1->query("declare @monto varchar(50)=$monto; select convert(decimal(10,2),@monto) as monto")or die($conexion1->error());
				$fqf=$qf->FETCH(PDO::FETCH_ASSOC);
				$monto=$fqf['monto'];
				$em=explode(".", $monto);
				if($em[0]=='')
				{
					$monto="0.$em[1]";
				}
				echo "<tr>
				<td>$transaccion</td>
				<td>$tipo_doc</td>
				<td>$num_doc</td>
				<td>$concepto</td>
				<td>$$monto</td>
			</tr>";
			}
		$cf=$conexion1->query("declare @total varchar(50)=$total; select convert(decimal(10,2),@total) as total")or die($conexion1->error());
		$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
		$total=$fcf['total'];

		$et=explode(".", $total);
		if($et[0]=='')
		{
			$total="0.$et[1]";
		}
			echo "<tr>
				<td colspan='4'>TOTAL $tipo</td>
				<td>$$total</td>
			</tr>";
		}
	}
	?>
	</div>
	</div>

</body>
</html>