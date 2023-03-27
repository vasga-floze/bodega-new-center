<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			//$("#me").hide();
			//window.print();
		});
	</script>
</head>
<body>
<?php
include("conexion.php");


if($_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='GJURADO' or $_SESSION['usuario']=='gjurado' or $_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='OCAMPOS' OR $_SESSION['usuario']=='mfuentes' or $_SESSION['usuario']=='MFUENTES' or $_SESSION['usuario']=='mcampos' or $_SESSION['usuario']=='MCAMPOS')
{

}else
{
	echo "<script>alert('NO DISPONIBLE PARA TI')</script>";
	echo "<script>location.replace('index.php')</script>";
}
?>
<form method="POST">
<input type="text" class="text" name="b" style="width: 30%;" required placeholder="CODIGO DE BARRA">
<input type="submit" name="" value="BUSCAR" class="boton3">
</form><br>
<?php
//include("conexion.php");

if($_SESSION['usuario']=='gjurado' or $_SESSION['usuario']=='GJURADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='OCAMPOS' OR $_SESSION['usuario']=='mfuentes' or $_SESSION['usuario']=='MFUENTES' or $_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='mcampos' or $_SESSION['usuario']=='MCAMPOS')
{

}else
{
	echo "<script>location.replace('buscar4.php')</script>";
}
$hoy=date("Y-m-d");
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select * from registro where barra='$b' and estado!=2 and usuario!='staana3'")or die($conexion2->error());
}else
{
	$c=$conexion2->query("select * from registro where tipo='P' and fecha_documento='$hoy' and estado!='2' and usuario!='staana3' order by id_registro desc")or die($conexion2->error());
}

echo "<table border='1' class='tabla' cellpadding='5'>";
echo "<tr>
<td>CODIGO BARRA</td>
<td>ARTICULO</td>
<td>DESCRIPCION</td>
<td>LBS</td>
<td>UND</td>
<td>ESTADO</td>
<td>USUARIO</td>
<td>FECHA DOCUMENTO</td>
<td width='8%'>IMPRIMIR</td>
</tr>";
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$cod=$f['codigo'];
	$lbs=$f['lbs'];
	$und=$f['und'];
	$estado=$f['estado'];
	$usuario=$f['usuario'];
	$fecha=$f['fecha_documento'];
	$barra=$f['barra'];
	$ca=$conexion1->query("select * from consny.ARTICULO where articulo='$cod'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
	$des=$fca['DESCRIPCION'];
	$art=$fca['ARTICULO'];

	if($estado==0)
	{
		$estado='SIN FINALIZAR';
	}else
	{
		$estado='FINALIZADO';
	}
	$barras=base64_encode($barra);
	echo "<tr class='tre'>
	<td>$barra</td>
	<td>$art</td>
	<td>$des</td>
	<td>$lbs</td>
	<td>$und</td>
	<td>$estado</td>
	<td>$usuario</td>
	<td>$fecha</td>
	<td>
	<a href='imprimir_otra.php?b=$barra' target='_blank' style='text-decoration:none;'>
		<img src='imprimir.png' width='35%' height='5%' style='margin-left:5%;'>
		</a>
		<a href='vineta_produccion.php?barra=$barras' target='_blank' style='text-decoration:none;'>
		<img src='viñeta.png' width='35%' height='5%' style='margin-left:5%;'>
		</a>
	</td>
</tr>";

}
echo "</table>";
?>
</body>
</html>