<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function()
		{
			$(".detalle").hide();
		})
	</script>
</head>
<body>
<div class="detalle" style="background-color: white; margin-top: 0.5%; width: 100%; margin-left: -0.5%; opacity: 0.5;">
	<img src="load1.gif" width="10%" height="10%;" style="margin-left: 45%; margin-top: 18%;">
</div>
<?php
include("conexion.php");
?>
<form method="POST" action="">
DESDE: <input type="date" name="desde" class="text" style="width: 25%;">
HASTA: <input type="date" name="hasta" class="text" style="width: 25%;">
<select name="bodega" class="text" style="width: 20%; padding: 0.6%;">
<option>BODEGA</option>
<?php
$cb=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%'  and bodega not like 'SM%' and BODEGA!='CA00'")or die($conexion1->error());
while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
{
	$bod=$fcb['BODEGA'];
	$nom=$fcb['NOMBRE'];
	echo "<option value='$bod'>$nom</option>";
}
?>
</select>
<input type="submit" name="btn" class="boton2" value="BUSCAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	echo "<table border='1' cellpadding='10' style='border-collapse:collapse; margin-top:1%; width:98%;'>";
	echo "<tr>
	<td>BODEGA</td>
	<td>FECHA</td>
	<td>MONTO</td>
	<td>PARTIDA</td>
	</tr>";
	while($desde<=$hasta)
	{
		$cf=$conexion1->query("declare @new varchar(50)=( select DATEADD(DAY,1,'$desde'))
select CONVERT(date,@new) as fecha
")or die($conexion1->error());

		$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
		$qf=$conexion1->query("select * from cuadro_venta where fecha='$desde'  and bodega='$bodega'")or die($conexion1->error());
		$fqf=$qf->FETCH(PDO::FETCH_ASSOC);
		$cbo=$conexion1->query("select concat(bodega,': ',nombre) as bode from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcbo=$cbo->FETCH(PDO::FETCH_ASSOC);
		$bodegas=$fcbo['bode'];
		$nqf=$qf->rowCount();
		if($nqf==0)
		{
			$fqf['FECHA']=$desde;
			$fqf['MONTO_USUARIO']='- -';
			$fqf['ASIENTO']='- - ';
		}
		echo "<tr>
		<td>$bodegas</td>
		<td>".$fqf['FECHA']."</td>
		<td>".$fqf['MONTO_USUARIO']."</td>
		<td>".$fqf['ASIENTO']."</td>
		</tr>";
		$desde=$fcf['fecha'];

	}
}
?>
</body>
</html>