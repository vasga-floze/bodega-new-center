<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<style>
		.tr
		{
			background-color: white .5s;
			cursor: pointer;
		}
		.tr:hover
		{
			background-color: rgb(0,0,0,0.3);
			color: white;
		}
	</style>
	<script>
		$(document).ready(function(){
			$("#b").focus();
		})
		function cerrar()
		{
			//alert();
			location.replace('cuadrar.php?tipo_trans='+$("#tipo_trans").val()+'&&tipo_doc='+$("#tipo_doc").val()+'&&concepto='+$("#concepto").val()+'&&monto_linea='+$("#monto_linea").val()+'&&num_doc='+$("#num_doc").val());
		}
		function seleccion(e)
		{
			location.replace('cuadrar.php?id_proveedor='+e+'&&tipo_trans='+$("#tipo_trans").val()+'&&tipo_doc='+$("#tipo_doc").val()+'&&concepto='+$("#concepto").val()+'&&monto_linea='+$("#monto_linea").val()+'&&num_doc='+$("#num_doc").val());

		}
	</script>
</head>
<body>
<?php
include("conexion.php");
$tipo_trans=$_GET['tipo_trans'];
$tipo_doc=$_GET['tipo_doc'];
$num_doc=$_GET['num_doc'];
$monto_linea=$_GET['monto_linea'];
$concepto=$_GET['concepto'];
//echo "<script>alert('$tipo_trans->$tipo_doc->$num_doc->$concepto->$monto_linea')</script>";
echo "<input type='hidden' name='tipo_trans' id='tipo_trans' value='$tipo_trans'>";
echo "<input type='hidden' name='tipo_doc' id='tipo_doc' value='$tipo_doc'>";
echo "<input type='hidden' name='concepto' id='concepto' value='$concepto'>";
echo "<input type='hidden' name='monto_linea' id='monto_linea' value='$monto_linea'>";
echo "<input type='hidden' name='num_doc' id='num_doc' value='$num_doc'>";




 ?>
<div class="detalle" style="margin-top: -3.5%;">
	<a href="#" onclick="cerrar()" style="text-decoration: none;color: white; float: right; margin-right: 0.5%; margin-top: 0.5%;">CERRAR X</a><br>
	<div class="adentro" style="margin-left: 2%; height: 93%;">
	<form method="POST" style="margin-left: 25%;">
	<input type="text" name="b" id="b" placeholder="NOMBRE PROVEEDOR" class="text" style="padding: 0.5%; width: 35%;">
	<input type="submit" name="btn" class="boton3" value="BUSCAR">
	</form><br>
	<?php
	if($_POST)
	{
		extract($_REQUEST);
		$c=$conexion1->query("select * from librosiva.dbo.Agentes where esproveedor='1' and nombreagente like '%$b%'")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("
			select * from librosiva.dbo.Agentes where esproveedor='1'")or die($conexion1->error());
	}
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN PROVEEDOR. INFORMA AL <u>DEPARTAMENTO DE CONTABILIDAD</u> PARA QUE AGREGUEN UN NUEVO PROVEEDOR</h3>";
	}else
	{
		echo "<table border='1' cellpadding='10' style='border-collapse:collapse; margin-left:0.5%;'>";
		echo "<tr>
			<td>CODIGO</td>
			<td>NIT</td>
			<td>NOMBRE PROVEEDOR</td>
		</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$cod=$f['CodigoAgente'];
			$nit=$f['NIT'];
			$id=$f['IdAgente'];
			echo "<tr onclick='seleccion($id)' class='tr'>
			<td>$cod</td>
			<td>$nit</td>
			<td>".$f['NombreAgente']."</td>
		</tr>";
		}
	}
	?>
	</div>
</div>

</body>
</html>