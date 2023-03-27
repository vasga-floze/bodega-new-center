<!DOCTYPE html>
<html>
<head>
<?php
error_reporting(0);
include("conexion.php");
$barra=$_GET['b'];
$bu=$_GET['bu'];
$a=$_GET['a'];
$arti=$_GET['arti'];
$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->eror());
$f=$c->FETCH(PDO::FETCH_ASSOC);
$art=$f['codigo'];
$contenedor=$f['contenedor'];
$cantidad=$f['cantidad'];
$peso=$f['peso'];
$fecha=$f['fecha_documento'];
$bodega=$f['bodega'];
$tipo=$f['tipo'];
$uni=$f['und'];
$libras=$f['lbs'];
if($arti!="")
{
	$art=$arti;
}
$k=$conexion1->query("select * from consny.ARTICULO where consny.ARTICULO.ARTICULO='$art'")or die($conexion1->error);
$fk=$k->FETCH(PDO::FETCH_ASSOC);
$nart=$fk['ARTICULO'];
$nnom=$fk['DESCRIPCION'];


?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

<script>
	$(document).ready(function(){
		if($("#a").val()!="")
		{
			$(".detalle").show();
		}else
		{
			$(".detalle").hide();
		}
	
	});

	function mostrar()
	{
		$(".detalle").show();
	}
	function cerrar()
	{
		$(".detalle").hide();
	}
</script>
</head>
<body>
	<input type="hidden" name="a" id="a" value='<?php echo "$a";?>'>
<div class="detalle" style="margin-top: -4.8%;">
<button onclick="cerrar()" style="float: right; margin-right: 0.5%;" onclick="cerrar()">Cerrar X</button>
<div class="adentro" style="margin-left: 2.3%;">
	<form method="POST">
		<input type="text" name="bu" class="text" style="width: 30%; margin-left: 2%;">
		<input type="submit" name="btn" value="BUSCAR" class="boton3">
	</form><br><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	echo "<script>location.replace('nuevobarra.php?b=$barra&&bu=$bu&&a=1')</script>";
}else
{
	$bu=str_replace(" ", "%",$bu);
	$cbu=$conexion1->query("select * from consny.ARTICULO where consny.ARTICULO.ARTICULO='$bu' or consny.ARTICULO.DESCRIPCION like '%$bu%'")or die($conexion1->error);
	$ncbu=$cbu->rowCount();
	if($ncbu==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
	}else
	{
		echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:2.5%;'>";
		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
		</tr>";
		while($fcbu=$cbu->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fcbu['ARTICULO'];
			$de=$fcbu['DESCRIPCION'];
			echo "<tr>
			<td><a href='nuevobarra.php?b=$barra&&arti=$art'>$art</a></td>
			<td><a href='nuevobarra.php?b=$barra&&arti=$art'>$de</a></td>
		</tr>";
		}
		echo "</table>";
	}
}
?>


</div>	

</div>
<button onclick="mostrar();">ARTICULO:</button>
<form method="POST" action="generar.php">
 <br> <input type="text" class="text" name="articulo" style="width: 30%;" value='<?php echo "$nart";?>'>
 <input type="text" name="des" class="text" style="width: 60%;" value='<?php echo "$nnom";?>'><br>

<hr>
CONTENEDOR:<br> <SELECT name='contenedor' class='text'>
<?php
echo "<option>$contenedor</option>";
$q=$conexion2->query("select contenedor from registro where contenedor!='' and contenedor!='$contenedor' group by contenedor")or die($conexion2->error());
while($fq=$q->FETCH(PDO::FETCH_ASSOC))
{
	$contenedors=$fq['contenedor'];
	echo "<option>$contenedors</option>";
}
?>
</SELECT></br><hr>
CANTIDAD
<input type="text" class="text" name="cantidad" value='<?php echo "1";?>'><br>
<hr>
PESO
<input type="text" class="text" name="peso" value='<?php echo "$peso"; ?>'><br>
<hr>
FECHA: <BR>
<input type="DATE" class="text" name="fecha" value='<?php echo "$fecha";?>'><br>
<hr>
BODEGA:<BR>
<select name="bodega" class="text">
<?php
$ck=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
$fck=$ck->FETCH(PDO::FETCH_ASSOC);
$bo=$fck['BODEGA'];
$nomb=$fck['NOMBRE'];
echo "<option value='$bo'>$bo: $nomb</option>";

$con=$conexion1->query("select * from consny.bodega where BODEGA like 'S%' and nombre not like '%(N)%'")or die($conexion1->error());
while($fcon=$con->FETCH(PDO::FETCH_ASSOC))	
{
	$bo=$fcon['BODEGA'];
$nomb=$fcon['NOMBRE'];
echo "<option value='$bo'>$bo: $nomb</option>";
}

?>
	
</select>
<input type="hidden" name="tipo" value='<?php echo "$tipo";?>'>
<input type="hidden" name="barra" value='<?php echo "$barra";?>'>
<br><br>
<input type="text" name="op" id="op">
<input type="submit" name="btn" value="GENERAR NUEVO" onclick="enviar()" class="boton3" style="float: right; margin-right: 9%;">


	
</form>




</body>
</html>