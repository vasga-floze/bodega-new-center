<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#detalle").hide(100);
		});
	</script>
	<meta charset="utf-8">
	<style>
		 .preloader {
  width: 70px;
  height: 70px;
  border: 10px solid #eee;
  border-top: 10px solid skyblue;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 3s;
  animation-iteration-count: infinite;

}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
	</style>
</head>
<body>
	
<div class="detalle" id="detalle" style="background-color: rgb(211,211,211,0.5); margin-top: -0.5%; width: 110%; margin-left: -5%;  ">
	<div class="preloader" id="preloader" style="margin-top: 15%; margin-left: 45%;">
	</div>
	</div>
<?php
include("conexion.php");
?>
<form method="POST">
<input type="date" name="d" class="text" style="width: 20%;">
<input type="date" name="h" class="text" style="width: 20%;">
<input type="submit" name="" class="boton4" value="BUSCAR">
	
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select registro.id_registro,desglose.usuario,registro.fecha_desglose from registro inner join desglose on registro.id_registro=desglose.registro where registro.fecha_desglose between '$d' and '$h'group by registro.id_registro,desglose.usuario,registro.fecha_desglose order by registro.fecha_desglose,desglose.usuario
")or die($conexion2->error());
	echo "<hr>";
	echo "<table border='1' class='tabla' cellpadding='10' style='margin-left:1%;'>";
	echo "<tr>
	<td colspan='8'><a href='expor_desgloses.php?d=$d&&h=$h' style='float:right; margin-right:0.5%;'>EXPORTAR A EXCEL</a></td>
	</tr>";
	echo "<tr>
	<td>USUARIO</td>
	<td>FECHA DESGLOSE</td>
	<td>CODIGO</td>
	<td>SUBCATEGORIA</td>
	<td>CANTIDAD</td>
	<td>TOTAL</td>
	<td>DOCUMENTO CONSUMO</td>
	<td>DOCUMENTO ING</td>
	</tr>";
	$n=$c->rowCount();
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$f['id_registro'];
		$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$fecha=$fcr['fecha_desglose'];
	$doc_consumo=$fcr['documento_inv_consumo'];
		$doc_ing=$fcr['documento_inv_ing'];
		$cod=$fcr['codigo'];
		$cd=$conexion2->query("select * from desglose where registro='$idr'") or die($conexion2->error());
	$cantidad=0;
	$total=0;
	$subtotal=0;
	while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
	{
		$dart=$fcd['articulo'];
		$cantidad=$cantidad + $fcd['cantidad'];
		$subtotal=$fcd['cantidad'] * $fcd['precio'];
		$total=$total+$subtotal;
		$usuario=$fcd['usuario'];
	}
	



		$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$arti=$fca['ARTICULO'];
		$des=$fca['DESCRIPCION'];
		echo "<tr>
	<td>$usuario</td>
	<td>$fecha</td>
	<td>$arti</td>
	<td>$des</td>
	<td>$cantidad</td>
	<td>$total</td>
	<td>$doc_consumo</td>
	<td>$doc_ing</td>
	</tr>";
	}
	echo "</table>";
}
?>
</body>
</html>