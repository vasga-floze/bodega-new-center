<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function notificacion()
		{
			if($("#numero").val()==1)
			{
				$("#numero").val(0);
				$("#notificar").show();
			}else
			{
				$("#numero").val(1);
				$("#notificar").hide();
			}
			
		}

	</script>

	
	<?php
	include("conexion.php");
	?>
	


	<?php
	//include("conexion.php");
	if($_SESSION['buscar']!='')
	{
		$i=1;
	}
	$usu=$_SESSION['usuario'];
	if($usu=='staana3')
	{

	}else
	{
		echo "<script>location.replace('index.php')</script>";
	}
	?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			if($("#i").val()==1)
			{
				$(".detalle").hide();
			}else
			{
				$(".detalle").show();
			}
		});
		function enviar()
		{
			if($("#i").val()==1)
			{
				$(".detalle").hide();
			}else
			{
				$(".detalle").show();
			}
			//$(".detalle").hide();
		}
	</script>
</head>
<body>
	<input type="hidden" name="i" id="i" value='<?php echo "$i";?>'>
	<div class="detalle" style="width: 105%; height: 100%; margin-top: -7%; margin-left: -2%;">
		<div class="adentro" style="margin-left: 25%; width: 50%; height: 50%; float: center; background-color: white; margin-top: 14.5%; border-radius: 10px;">
		<form method="POST">
			<input type="hidden" name="i" id="i" value='<?php echo "$i";?>'>
			<input type="password" name="con" placeholder="CONTRASEÑA" class="text" style="padding-bottom: 1.3%; padding-top: 1.3%; width: 70%; text-align: center;">
			<input type="submit" name="btn" value="CONFIRMAR" class="boton1-1" style="margin-left: 43%; margin-top: 5%; border-radius: 10%; width: 20%; padding-bottom: 3%; padding-top: 3%; background-color: green;">
		</form>
			
		</div>
	</div>
<form method="POST">
	CODIGO DE BARRA: 
	<input type="text" name="buscar" class="text" style="width: 30%;">
	<input type="submit" name="btn" value="BUSCAR" class="boton1-1" onclick="enviar()">
</form><br>

<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='BUSCAR')
	{
	if($buscar=="")
	{
		$c=$conexion2->query("select * from registro where  tipo='P'  order by id_registro desc")or die($conexion2->error);
	}else
	{
	  $c=$conexion2->query("select * from registro where barra='$buscar' and tipo='P'  order by id_registro desc")or die($conexion2->error);
	}
	}else if($btn=='CONFIRMAR')
	{
		$con=base64_encode($con);
		if($con=='c3RhYW5hMw==')
		{
			$_SESSION['buscar']=1;
			echo "<script>location.replace('buscar.php')</script>";
		}else
		{
			echo "<script>alert('INCORECTOS')</script>";
			echo "<script>location.replace('buscar.php')</script>";
		}
	}
	
}else//FIN POST
{
	$hoy=date("Y-m-d");
	$c=$conexion2->query("select * from registro where tipo='P' and fecha_documento='$hoy' order by id_registro desc")or die($conexion2->error);
}

$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10' style='width:100%;'>
	
	<tr style='background-color:#180501; color:white;'>
		<td>CODIGO DE BARRA</td>
		<td>NOMBRE FARDO</td>
		<td>LIBRAS</td>
		<td>UNIDADES</td>
		<td>FECHA</td>
		<td>REGISTRADO POR</td>
		<td>ESTADO</td>
		<td>BODEGA</td>
		<td>OPCIONES</td>

	</tr>
	";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$barra=$f['barra'];
		$nombre=$f['subcategoria'];
		$lbs=$f['lbs'];
		$und=$f['und'];
		$reg=$f['usuario'];
		$fecha=$f['fecha_documento'];
		$id=$f['id_registro'];
		$estado=$f['estado'];
		$bodega=$f['bodega'];
		$nombre="".$f['codigo'].": $nombre";
	

		echo "<tr class='tre'>
			<td>$barra</td>
			<td>$nombre</td>
			<td>$lbs</td>
			<td>$und</td>
			<td>$fecha</td>
			<td>$reg</td>
			<td>$estado</td>
			<td>$bodega</td>
			<td>
			<a href='eliminarpro.php?b=$id&&id=$barra' style='text-decoration:none; color:white;'>
			//borrar</a>";
			echo "
			<a href='imprimir_otra.php?b=$barra' target='_blank' style='text-decoration:none; color:white;'>imprimir</a>
			</td>
		</tr>";

	}
	echo"</table>"; 
}



?>



</body>
</html>