<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
if($_SESSION['usuario']!='staana3' and $_SESSION['usuario']!='ocampos' and $_SESSION['usuario']!='OCAMPOS')
{
	echo "<script>alert('NO DISPONIBLE')</script>";
	echo "<script>location.replace('index.php')</script>";
}
$hoy = date("Y-m-d");
?>
<form method="POST">
<input type="number" name="desde" placeholder=" DESDE CORELATINO" class="text" style="width: 15%;">

<input type="number" name="hasta" placeholder=" HASTA EL CORELATINO" class="text" style="width: 15%;">

<select required name="asignado" class="text" style=" width: 30%;">
	<option value="">USUARIO</option>
	<?php
	$c=$conexion1->query("select * from dbo.usuariobodega where bodega like 'SM%'")or die($conexion1->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{

		$usuario=$f['USUARIO'];
		echo "<option>$usuario</option>";
	}
	?>
	<option>HARIAS</option>
	<option>staana3</option>
</select>
<input type="date" name="fecha"  style="width: 20%; padding-bottom: 0.2%; padding-top: 0.2%;" value='<?php echo "$hoy";?>'>
<input type="submit" name="btn" value="ASIGNAR" class="boton2">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
		if($desde>$hasta)
	{
		echo "<script>alert('DATOS NO VALIDOS')</script>";
	}else
	{
		$con=$conexion2->query("select * from averia where desde between '$desde' and '$hasta' or hasta between '$desde' and '$hasta'")or die($conexion2->error());
		$ncon=$con->rowCount();
		if($ncon!=0)
		{
			echo "<script>alert('!ERROR!: EN EL RANGO($desde - $hasta) hay CORELATIVO QUE YA FUERON ASIGNADOS')</script>";

		}else
		{
	
	if($_SESSION['a_averia']=='')
	{


	$c=$conexion2->query("select max(sessiones) as sessiones from averia where tipo='A'")or die($conexion2->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$sessiones=$f['sessiones'] + 1;
	$n=1;
	while($n!=0)
	{
		$c=$conexion2->query("select * from averia where sessiones='$sessiones' and tipo='A'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n!=0)
		{
			$sessiones++;
			$n=1;
		}else
		{
			$n=0;
		}
	}
	$_SESSION['a_averia']=$sessiones;
	}else
	{
		$sessiones=$_SESSION['a_averia'];
	}
	$d=$desde;
	while($desde <=$hasta)
	{
		
		$corelativo=$desde;
		$corelativo=str_pad($corelativo,7,"0",STR_PAD_LEFT);
		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
	 $conexion2->query("insert into averia(corelativo,fecha_documento,tipo,desde,hasta,asignado,usuario,paquete,sessiones,estado,fecha_ingreso)  values('$corelativo','$fecha','A','$d','$hasta','$asignado','$usuario','$paquete','$sessiones','0',getdate())")or die($conexion2->error());
	 $desde++;
	}
	echo "<script>alert('AGREGADO CORRECTAMENTE')</script>";
	echo "<script>location.array_replace('asignar_averia.php')</script>";
	}
}
}
?>
</body>
</html>