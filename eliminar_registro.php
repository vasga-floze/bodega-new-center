<!DOCTYPE html>
<html>
<head>
	<title>NEW YORK <CENTER></CENTER></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function enviar()
		{
			if(confirm('SEGURO DESEA ELIMINAR EL REGISTRO?'))
			{
				$("#form").submit();
			}else
			{
				$("#form").submit(false);

			}
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
$hoy = date("Y-m-d");
if($_SESSION['tipo']==1 or $_SESSION['usuario']=='staana3')
{

}else
{
	
	echo "<script>location.replace('conexiones.php')</script>";

}
?>
<h3 style="text-align: center;">ELIMINAR CODIGO DE BARRA</h3>
<form method="POST" id="form">
	<input type="text" name="barra" class="text" placeholder="CODIGO DE BARRA" style="width: 20%;" required>
	<input type="date" name="fecha" class="text" style="width: 15%;" value="<?php echo "$hoy";?>" required>

<input type="button" name="btn" class="boton3" style="background-color: #6d858c;" value="ELIMINAR" onclick="enviar()">
</form>

<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select * from registro where barra='$barra' and estado='0'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO O FECHA PRODUCCION YA FUE FINALIZADA')</script>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		if($f['activo']=='0')
		{
			echo "<script>alert('CODIGO DE BARRA NO SE ENCUENTRA DISPONIBLE')</script>";
		}else
		{
			if($f['estado']==0)
			{
				$est_cambio=2;
			}else
			{
				$est_cambio=$f['estado'];
			}

			$usuario=$_SESSION['usuario'];
			//echo "<script>alert('$usuario $barra $fecha $est_cambio')</script>";
			$vali=validacion_disponible($barra);
				if($vali=='FARDO NO SE PUEDE USAR POR:')
				{
					$conexion2->query("update registro set activo='0',estado='$est_cambio',fecha_eliminacion='$fecha 00:00:00.000',usuario_eliminacion='$usuario',observacion='ELIMINADO SYS...' where barra='$barra'")or die($conexion2->error());
				}else
				{
					echo "<script>alert('$vali')</script>";
					echo "<script>location.replace('eliminar_registro.php')</script>";
				}
			

			echo "<script>alert('ELIMINADO CORRECTAMENTE')</script>";
			echo "<script>location.replace('eliminar_registro.php')</script>";
		}
	}
}else
?>
</body>
</html>