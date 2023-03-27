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
//query pablo cambio a reporte_veta3 02-09-2020



?>
<form method="POST">
Desde: <input type="date" name="desde" class="text" style="width: 30%;" required>

Hasta: <input type="date" name="hasta" class="text" style="width: 30%;" required>

<input type="submit" name="btn" value="GENERAR" class="btnfinal" style="padding: 0.5%; background-color:skyblue; color: black; border-color: black;">
	
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select sessiones,usuario from venta where fecha between '$desde' and '$hasta'")or die($conexion2->error());

	
			$q=$conexion2->query("select e.fecha, e.cliente, e.documento_inv,e.Articulo, e.TOTAL_VENTA, e.CANTIDAD,e.TOTAL_VENTA* e.CANTIDAD as totalv  from 
(SELECT        venta.fecha, venta.cliente, venta.documento_inv, CASE WHEN registro IS NULL THEN articulo ELSE registro.codigo END AS Articulo,
SUM(cast(venta.precio as decimal(8,4))) TOTAL_VENTA,
case when registro IS NULL then SUM(cast(venta.cantidad as int)) else COUNT(registro) end AS CANTIDAD
FROM            venta LEFT OUTER JOIN
                         registro ON venta.registro = registro.id_registro
WHERE        venta.fecha between '$desde' and '$hasta'
group by venta.registro, venta.fecha, venta.cliente, venta.documento_inv,CASE WHEN registro IS NULL THEN articulo ELSE registro.codigo END) as E
where e.TOTAL_VENTA is not null
")or die($conexion2->error());
			$n=$q->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUNA VENTA</h3>";
	}else
	{
		echo "<table border='1' style='border-collapse:collapse; width:100%;' cellpadding='10'>";

		echo "
		<tr>
		<td colspan='7'>VENTAS DESDE: $desde HASTA: $hasta
		<a href='exportar_venta.php?desde=$desde&&hasta=$hasta' target='_blank' style='float:right;'>Exportar a Excel</a>
		</td>
		</tr>
		";
		echo "<tr>
		<td>FECHA</td>
		<td>CLIENTE</td>
		<td>CANTIDAD</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>			
		<td>TOTAL</td>
		<td>DOCUMENTO</td>
		
			
		</tr>";
			$totalf=0;
			while($fq=$q->FETCH(PDO::FETCH_ASSOC))
			{
				$articulo=$fq['Articulo'];
				$cantidad=$fq['CANTIDAD'];
				$total=$fq['totalv'];
				$documento=$fq['documento_inv'];
				$cliente=$fq['cliente'];
				$fecha=$fq['fecha'];
				$ca=$conexion1->query("select * from consny.articulo where articulo='$articulo'")or die($conexion1->error());
				$fca=$ca->FETCH(PDO::FETCH_ASSOC);
				echo "<tr>
					<td>$fecha</td>
					<td>$cliente</td>
					<td>$cantidad</td>
					<td>$articulo</td>
					<td>".$fca['DESCRIPCION']."</td>					
					<td>$total</td>
					<td>$documento</td>
					
					
				</tr>";
				$totalf=$totalf+$total;
			}
			echo "<tr>
			<td colspan='5'>TOTAL</td>
			<td>$totalf</td><td></td>

			</tr>";
		

	}
}
?>
<script>
	window.close();
</script>
</body>
</html>
