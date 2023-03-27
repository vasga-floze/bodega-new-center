<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			if($("#digitado").val()!='')
			{
				$("#form1").show();

			}else
			{
				$("#form1").hide();
			}
			if($("#art").val()!='')
			{
				$("#cantidad").focus();
			}

		})
		function buscar()
		{
			$("#op").val('1');
			$("#form1").submit();
		}
		function enviar()
		{
			$("#art").attr('required',true);
			$("#op").val('2');

		}

		function articulo()
		{
			var digitado=$("#digitado").val();
			var fecha =$("#fecha").val();
			location.replace('art_insumo.php?digitado='+digitado+'&&fecha='+fecha);
		}
	</script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");

$art=$_GET['art'];
$usuario=$_SESSION['usuario'];


$cb=$conexion1->query("select bodega from usuariobodega where usuario='$usuario'")or die($conexion1->error());
$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
$bodega=$fcb['bodega'];

$ca=$conexion1->query("select consny.articulo.articulo,consny.articulo.descripcion,consny.existencia_bodega.cant_disponible as cantidad,consny.existencia_bodega.bodega from consny.articulo  inner join consny.existencia_bodega on consny.articulo.articulo=consny.existencia_bodega.articulo where consny.articulo.articulo='$art' and consny.existencia_bodega.bodega='$bodega' and consny.articulo.activo='S'
")or die($conexion1->error());
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$cantidad=$fca['cantidad'];
$digitado=$_GET['digitado'];
$fecha=$_GET['fecha'];
if($fecha=='')
{
	$fecha=date("Y-m-d");
}

$insumo=$_SESSION['insumo'];
$ci=$conexion2->query("select top 1 * from insumo where session='$insumo' and usuario='$usuario'")or die($conexion2->error());
$nci=$ci->rowCount();
if($nci!=0)
{
	$fci=$ci->FETCH(PDO::FETCH_ASSOC);
	$digitado=$fci['digitado'];
	$fecha=$fci['fecha'];
}

?>
<form method="POST">
<input type="text" name="digitado" id="digitado" class="text" style="width: 40%;" placeholder="DIGITADO POR" required value='<?php echo "$digitado";?>'>
FECHA: <input type="date" name="fecha" id="fecha" class="text" style="width: 15%;" value='<?php echo "$fecha";?>'>
<input type="submit" name="btn" value="SIGUIENTE" style="padding: 0.5%; cursor: pointer; background-color: #96C7A6;" class="btnfinal">
</form>
<form method="POST" id="form1" style="margin-left: 4%; margin-top: -5%;">
	<a href="#" style="text-decoration: none; background-color: #96C7A6; color: white; padding-top: 0.5%; padding-bottom: 0.5%;" onclick="articulo()">INSUMOS</a><br>
	<input type="text" name="art" id="art" class="text" style="width: 20%;" placeholder="ARTICULO" onchange="buscar()" value='<?php echo "".$fca['articulo']."";?>'>
	<input type="text" name="desc" class="text" style="width:50%;" placeholder="DESCRIPCION" value='<?php echo "".$fca['descripcion']."";?>'>
	<input type="number" name="cantidad" id="cantidad" class="text" style="width: 20%;" placeholder="CANTIDAD">
	<input type="hidden" name="op" id="op">
	<input type="submit" name="btn" value="ADD" class="btnfinal" style="padding: 0.5%; cursor: pointer; background-color: #96C7A6;" onclick="enviar()">
</form>
<?php
$insumo=$_SESSION['insumo'];
$usuario=$_SESSION['usuario'];

$c=$conexion2->query("select * from insumo where session='$insumo' and usuario='$usuario'")or die($conexion2->error());
$n=$c->rowCount();
if($n!=0)
{
	echo "<table border='1' width='100%' cellpadding='10' style='border-collapse:collapse;'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>ELIMINAR</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$articulo=$f['articulo'];
		$cantidad=$f['cantidad'];
		$ide=$f['insumo'];
		$iden=base64_encode($ide);
		$id=$iden;
		$ca=$conexion1->query("select articulo,descripcion from consny.articulo where articulo='$articulo'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
		<td>".$fca['articulo']."</td>
		<td>".$fca['descripcion']."</td>
		<td>$cantidad</td>
		<td><a href='eli_insumo.php?id=$id&&digitado=$digitado&&fecha=$fecha' style='text-decoration:none; color:red;'>ELIMINAR</a></td>
	</tr>";
	}
}
?>

<?php
if($_POST)
{
	extract($_REQUEST);
	if($op==1)
	{
		$c=$conexion1->query("select * from consny.articulo where articulo='$art' and activo='S' and clasificacion_1='INSUMO' and clasificacion_1='INSUMO'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('ARTICULO NO ESTA DISPONIBLE O NO SE ENCUENTRA EN CLASIFICACION DE INSUMO')</script>";
			echo "<script>location.replace('insumo.php?digitado=$digitado&&fecha=$fecha')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$art=$f['ARTICULO'];
			echo "<script>location.replace('insumo.php?art=$art&&digitado=$digitado&&fecha=$fecha')</script>";
		}
	}else if($op==2)
	{
		if($_SESSION['insumo']=='')
		{
			$c=$conexion2->query("select max(session) as session from insumo")or die($conexion2->error());
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$session=$f['session']+1;
			$k=1;
			while($k==1)
			{
				$c=$conexion2->query("select * from insumo where session='$session'")or die($conexion2->error());
				$n=$c->rowCount();
				if($n==0)
				{
					$k=0;
				}else
				{
					$session++;
					$k=1;
				}
			}
			$_SESSION['insumo']=$session;
			//echo "<script>alert('$session')</script>";
		}

		$ca=$conexion1->query("select consny.articulo.articulo,consny.articulo.descripcion,consny.existencia_bodega.cant_disponible as cantidad,consny.existencia_bodega.bodega from consny.articulo  inner join consny.existencia_bodega on consny.articulo.articulo=consny.existencia_bodega.articulo where consny.articulo.articulo='$art' and consny.existencia_bodega.bodega='$bodega' and consny.articulo.activo='S' and consny.articulo.clasificacion_1='insumo'
")or die($conexion1->error());
		$nca=$ca->rowCount();
		if($nca==0)
		{
			echo "<script>alert('$art: NO DISPONIBLE O NO SE ENCUENTRA EN LA CLASIFICACION DE INSUMOS')</script>";
			echo "<script>location.replace('insumo.php?digitado=$digitado&&fecha=$fecha')</script>";

		}else
		{
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			//$fca['cantidad']=5;
			if($fca['cantidad']<$cantidad)
			{
				echo "<script>alert('NO CUENTA CON UNIDADES SUFICIENTES PARA INGRESAR ESTE INSUMO')</script>";
				echo "<script>location.replace('insumo.php?digitado=$digitado&&fecha=$fecha')</script>";

			}else
			{
				$insumo=$_SESSION['insumo'];
				$usuario=$_SESSION['usuario'];
				$cb=$conexion1->query("select bodega from usuariobodega where usuario='$usuario'")or die($conexion1->error());
				$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
				$bodega=$fcb['bodega'];
				$paquete=$_SESSION['paquete'];

				$conexion2->query("insert into insumo(digitado,fecha,fechaingreso,usuario,paquete,bodega,estado,articulo,cantidad,session) values('$digitado','$fecha',getdate(),'$usuario','$paquete','$bodega','0','$art','$cantidad','$insumo')")or die($conexion2->error());
				echo "<script>location.replace('insumo.php')</script>";
			}
		}

	}
	if($btn=='SIGUIENTE')
	{
		if($_SESSION['insumo']=='')
		{
			echo "<script>location.replace('insumo.php?digitado=$digitado&&fecha=$fecha')</script>";
		}else
		{
			$session=$_SESSION['insumo'];
			$user=$_SESSION['usuario'];
			$conexion2->query("update insumo set digitado='$digitado',fecha='$fecha' where session='$session' and usuario='$user'")or die($conexion2->error());
			echo "<script>location.replace('insumo.php')</script>";
		}
		
	}
}
?>
</body>
</html>