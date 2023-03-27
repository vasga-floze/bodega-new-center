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
<form method="POST" autocomplete="off">
<input type="text" name="persona" class="text" style="width: 35%;" placeholder="PRODUCIDA POR" list="producido">
<datalist id="producido">
	<?php
	$cp=$conexion1->query("select * from PRODUCCION_ACCPERSONAL where PRODUCE=1")or die($conexion1->error());
	while($f=$cp->FETCH(PDO::FETCH_ASSOC))
	{
		echo "<option>".$f['NOMBRE']."</option>";
	}
	?>
</datalist>
<input type="date" name="desde" class="text" style="width: 18%;">
<input type="date" name="hasta" class="text" style="width: 18%;">	
<input type="submit" name="btn" value="GENERAR" class="boton3" style="background-color: #a3bcc3; color: white;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select pruebabd.dbo.registro.producido,pruebabd.dbo.registro.fecha_documento,
EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,EXIMP600.consny.ARTICULO.CLASIFICACION_2,
SUM(pruebabd.dbo.registro.lbs) as peso,COUNT(EXIMP600.consny.ARTICULO.ARTICULO) as cantidad from 
pruebabd.dbo.registro inner join EXIMP600.consny.ARTICULO on pruebabd.dbo.registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO
where pruebabd.dbo.registro.tipo='p' and pruebabd.dbo.registro.fecha_documento between '$desde' and '$hasta' and pruebabd.dbo.registro.producido like '%$persona%' and pruebabd.dbo.registro.fecha_eliminacion is null and pruebabd.dbo.registro.observacion!='ELIMINADO SYS...'  group by 
pruebabd.dbo.registro.producido,pruebabd.dbo.registro.fecha_documento,EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,EXIMP600.consny.ARTICULO.CLASIFICACION_2 order by pruebabd.dbo.registro.producido,pruebabd.dbo.registro.fecha_documento,EXIMP600.consny.ARTICULO.CLASIFICACION_2,EXIMP600.consny.ARTICULO.ARTICULO


")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE OBTUVO NINGUN RESULTADO</h3>";
	}else
	{
		echo "<br><a href='export_reporte_peoducido.php?persona=$persona&&desde=$desde&&hasta=$hasta' target='_blank' style='float:right;'>Exportar a Excel</a><br>";
		echo "<table border='1' style='width:100%; border-collapse:collapse;'>";
		echo "<tr>
			<td>PERSONA</td>
			<td>FECHA</td>
			<td>CATEGORIA</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
			<td>PESO</td>
		</tr>";
		$tpeso=0; $tcant=0;
		while($fc=$c->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
			<td>".$fc['producido']."</td>
			<td>".$fc['fecha_documento']."</td>
			<td>".$fc['CLASIFICACION_2']."</td>
			<td>".$fc['ARTICULO']."</td>
			<td>".$fc['DESCRIPCION']."</td>
			<td>".$fc['cantidad']."</td>
			<td>".$fc['peso']."</td>
		</tr>";
		$tcant=$tcant + $fc['cantidad'];
		$tpeso=$tpeso + $fc['peso'];

		}

		echo "<tr>
			<td colspan='5'>TOTAL</td>
			<td>$tcant</td><td>$tpeso</td>
		</tr> </table>";
	}
}
?>
</body>
</html>