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
	include("conexion.php");{
		$tipo=$_SESSION['tipo'];
	
	
	if($tipo!=1 )
	{
		echo "<script>location.replace('desglose.php')</script>";
	}
	?>
<a href="inventario.php">
<button style="background-color: rgb(0, 0, 255,0.6); color:white; padding-top: 0.5%; margin-left: -3.8%; padding-bottom: 0.5%; border: none; cursor: pointer;">+ NUEVO INVENTARIO</button></a>

<a href="con_inventario.php">
<button style="background-color: black; color:white; padding-top: 0.5%; margin-left: 0.8%; padding-bottom: 0.5%; border: none; cursor: pointer;">INVENTARIOS SIN FINALIZAR</button></a>
<hr>
<form method="POST">
	BODEGA:
	<input type="text" name="bod" class="text" style="width: 18%;" placeholder="BODEGA">
	FECHA:
	<input type="date" name="fecha" class="text" style="width: 22%;">
	<input type="submit" name="" value="MOSTRAR" class="boton2">
<?php
if($_POST)
{
	extract($_REQUEST);
	if($bod!='' and $fecha!='')
	{
		$c=$conexion2->query("select sessiones from inventario where bodega='$bod' and fecha='$fecha' group by sessiones")or die($conexion2->error());

	}else if($bod!='' and $fecha=='')
	{
		$c=$conexion2->query("select sessiones from inventario where bodega='$bod' group by sessiones")or die($conexion2->error());
	}else if($bod=='' and $fecha!='')
	{
		$c=$conexion2->query("select sessiones from inventario where fecha='$fecha' group by sessiones")or die($conexion2->error());
	}

$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE ENCONTRO NINGUN REGISTRO</h2>";
}else
{
	echo "<br><br><table class='tabla' border='1' cellpadding='10'>";
	echo "<tr>
			<td>BODEGA</td>
			<td>FECHA</td>
			<td>DIGITADO</td>
			<td>USUARIO</td>
			<td width='10%'>OPCION</td>
		</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$inv=$f['sessiones'];
			$con=$conexion2->query("select top 1 * from inventario where sessiones='$inv'")or die($conexion2->error());
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$inv=$fcon['sessiones'];
			$bodega=$fcon['bodega'];
			$fechai=$fcon['fecha'];
			$digita=$fcon['digita'];
			$usuario=$fcon['usuario'];

			echo "<tr>
			<td>$bodega</td>
			<td>$fechai</td>
			<td>$digita</td>
			<td>$usuario</td>
			<td>
			<a href='ver_inventario.php?inv=$inv&&usuario=$usuario&&fecha=$fecha&&bo=$bod' style='text-decoration:none;'>
			<img src='consultar.png' width='18%' height='10%' title='VER INVENTARIO'>
			</a>
			<a href='exportar_inv.php?inv=$inv&&usuario=$usuario'>
			<img src='excel.png' width='18%' height='10%' title='EXPORTAR A EXCEL'></a>
			</td>
		</tr>";


		}
}
}else
{
	$fecha=$_GET['fecha'];
	$bod=$_GET['bod'];
if($bod!='' and $fecha!='')
	{
		$c=$conexion2->query("select sessiones from inventario where bodega='$bod' and fecha='$fecha' group by sessiones")or die($conexion2->error());

	}else if($bod!='' and $fecha=='')
	{
		$c=$conexion2->query("select sessiones from inventario where bodega='$bod' group by sessiones")or die($conexion2->error());
	}else if($bod=='' and $fecha!='')
	{
		$c=$conexion2->query("select sessiones from inventario where fecha='$fecha' group by sessiones")or die($conexion2->error());
	}

$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE ENCONTRO NINGUN REGISTRO</h2>";
}else
{
	echo "<br><br><table class='tabla' border='1' cellpadding='10'>";
	echo "<tr>
			<td>BODEGA</td>
			<td>FECHA</td>
			<td>DIGITADO</td>
			<td>USUARIO</td>
			<td width='10%'>OPCION</td>
		</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$inv=$f['sessiones'];
			$con=$conexion2->query("select top 1 * from inventario where sessiones='$inv'")or die($conexion2->error());
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$inv=$fcon['sessiones'];
			$bodega=$fcon['bodega'];
			$fechai=$fcon['fecha'];
			$digita=$fcon['digita'];
			$usuario=$fcon['usuario'];

			echo "<tr>
			<td>$bodega</td>
			<td>$fechai</td>
			<td>$digita</td>
			<td>$usuario</td>
			<td>
			<a href='ver_inventario.php?inv=$inv&&usuario=$usuario&&fecha=$fecha&&bo=$bod' style='text-decoration:none;'>
			<img src='consultar.png' width='18%' height='10%' title='VER INVENTARIO'>
			</a>
			<a href='exportar_inv.php?inv=$inv&&usuario=$usuario'>
			<img src='excel.png' width='18%' height='10%' title='EXPORTAR A EXCEL'></a>
			</td>
		</tr>";


		}
}


}
}
?>
</form>
</body>
</html>