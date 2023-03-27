<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	$(document).ready(function(){
		$("#b").focus();
	});
</script>

</head>
<body>
<?php
include("conexion.php");
$tipo=$_SESSION['tipo'];
$usuario=$_SESSION['usuario'];
if($usuario=='harias' or $tipo==3)
{
	$tipo=1;
}
if($tipo!=1)
{
	echo "<script>alert('NO DISPONIBLE')</script>";
	echo "<script>location.replace('desglose.php')</script>";
}
?>
<form method="POST">
	CODIGO DE BARRA O DOCUMENTO_INV<br>
<input type="text" name="b" id="b" placeholder="" class="text" style="width: 40%;">
<input type="submit" name="" value="MOSTRAR" class="boton2">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	echo "<hr>";
	$c=$conexion2->query("select registro.id_registro,traslado.origen,traslado.destino,traslado.sessiones,traslado.fecha,traslado.usuario,traslado.paquete,traslado.documento_inv from registro inner join traslado on traslado.registro=registro.id_registro where registro.barra='$b' or traslado.documento_inv='$b' order by id 
")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE ENCONTRO NINGUN REGISTRO</h2>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CODIGO BARRA</td>
		<td>FECHA TRASLADO</td>
		<td>ORIGEN</td>
		<td>DESTINO</td>
		<td>USUARIO</td>
		<td>DOCUMENTO_INV</td>
	</tr>";

$n=1;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$destino=$f['destino'];
	$origen=$f['origen'];
	$idr=$f['id_registro'];
	$usuario=$f['usuario'];
	$paque=$f['paquete'];
	$fecha=$f['fecha'];
	$docu=$f['documento_inv'];

	$cr=$conexion2->query("select  * from registro where id_registro='$idr'")or die($conexion2->error());
	$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
	$barra=$fcr['barra'];
	$cod=$fcr['codigo'];
	$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
	$art=$fca['ARTICULO'];
	$de=$fca['DESCRIPCION'];

	$cbd=$conexion1->query("select * from consny.BODEGA where BODEGA='$destino'")or die($conexion1->error());
	$fbd=$cbd->FETCH(PDO::FETCH_ASSOC);
	$destino=$fbd['NOMBRE'];
	$cbo=$conexion1->query("select * from consny.BODEGA where BODEGA='$origen'")or die($conexion1->error());
	$fbo=$cbo->FETCH(PDO::FETCH_ASSOC);
	$origen=$fbo['NOMBRE'];
echo "<tr>
		<td>$art</td>
		<td>$de</td>
		<td>$barra</td>
		<td>$fecha</td>
		<td>$origen (".$fbo['BODEGA'].")</td>
		<td>$destino (".$fbd['BODEGA'].")</td>
		<td>$usuario</td>
		<td>$docu</td>

	</tr>";
}
echo "</table>";


}
echo "<hr>";
}
?>
</body>
</html>