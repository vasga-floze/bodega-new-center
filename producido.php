<!DOCTYPE html>
<html>
<head>
	<?php
	error_reporting(0);
		include("conexion.php");
		//echo "<script>alert('NO DISPONIBLE')</script>";
//ECHO "<script>location.replace('salir.php')</script>";
		if($_SESSION['tipo']==2)
	{
		echo "<script>alert('NO TIENES AUTORIZACION PARA PRODUCCION')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}else if($_SESSION['tipo']==3)
	{
		echo "<script>location.replace('consultar.php')</script>";
	}
		if($_SESSION['dia']=="")
		{
			$hoy=date("Y-m-d");
		}else
		{
			$hoy=$_SESSION['dia'];
		}
		if($_SESSION['fechar']!="")
		{
			$hoy=$_SESSION['fechar'];
		}
	?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>

<body>
<form name="form" method="POST">
	PRODUCCION DE LA FECHA:
<input type="date" name="fecha" value='<?php echo "$hoy"; ?>' class='text' style='width: 20%;'>
	USUARIO<select name="busu" class="text" style="width: 20%;">
		<option>TODOS</option>

<?php
$c=$conexion2->query("select usuario from registro  group by (registro.usuario)")or die($conexion2->error);
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$usu=$f['usuario'];
	echo "<option>$usu</option>";
}
?>
	</select>
	<input type="submit" name="" value="MOSTRAR" class="boton2"><br><hr>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$_SESSION['fechar']=$fecha;
	$usua=$busu;
	if($busu=="TODOS")
	{
		$con=$conexion2->query("select * from registro where fecha_documento='$fecha' and tipo='P' and estado='0' order by 1 desc")or die($conexion2->error);
	$dia=$fecha;
}else
{
	$con=$conexion2->query("select * from registro where fecha_documento='$fecha' and usuario='$busu' and tipo='P' and estado='0' order by 1 desc")or die($conexion2->error);
	$dia=$fecha;
}
	
}else
{
	$con=$conexion2->query("select * from registro where fecha_documento='$hoy' and tipo='P' and estado='0' order by 1 desc")or die($conexion2->error);
	$dia=$hoy;
	$usua="TODOS";
}
$ncon=$con->rowCount();
if($ncon==0)
{
	echo "<h2>NO SE ENCONTRO PRODUCCION POR FINALIZAR DE LA FECHA: $dia DEL USUARIO: $busu</h2>";
}else
{
	echo '<table class="tabla" border="1" cellpadding="10">
	<tr>';
	echo "
	<td colspan='8'><a href='producido_expor.php?dia=$hoy&&usu=$usua'>EXPORTAR A EXCEL</a></td>
	</tr>";
	echo '
	<tr style="background-color:rgb(133,133,137,0.8); color:white; width:100%;">
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>PESO</td>
		<td>FECHA PRODUCCION</td>
		<td>EMPACADO</td>
		<td>PRODUCIDO POR</td>
		<td>CODIGO BARRA</td>
		<td>BODEGA PRODUCCION</td>
		<td>USUARIO</td>
	</tr>';
	$to=0;
	while($fcon=$con->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$fcon['codigo'];
		$des=$fcon['subcategoria'];
		$lbs=$fcon['lbs'];
		$cbarra=$fcon['barra'];
		$usu=$fcon['usuario'];
		$t=$fcon['tipo'];
		$empacado=$fcon['empacado'];
		$producido=$fcon['producido'];
		$bodega_pro=$fcon['bodega_produccion'];
		echo "<tr>
		<td>$art</td>
		<td>$des</td>
		<td>$lbs</td>
		<td>$dia</td>
		<td>$empacado</td>
		<td>$producido</td>
		<td>$cbarra</td>
		<td>$bodega_pro</td>
		<td>$usu</td>
	</tr>";
	$to++;
	}
	if($usua=="TODOS")
	{
		echo "<tr>
		<td colspan='8'>TOTAL FARDOS PRODUCIDOS: $to
		<form method='POST' action='finaldia1.php' style='float:right;'>
		<input type='hidden' name='dia' value='$dia'>
		</td>
	</tr>";
	}else
	{
		echo "<tr>
		<td colspan='8'>TOTAL FARDOS PRODUCIDOS: $to
		<form method='POST' action='finaldia1.php' style='float:right;'>
		<input type='hidden' name='dia' value='$dia'>
		<input type='hidden' name='usua' value='$usua'>
		<input type='submit' class='btnfinal' value='Finalizar Produccion'>
		</td>
	</tr>";
	}
	
}
?>

</body>
</html>