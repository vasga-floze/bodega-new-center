<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<script>
		$(document).ready(function(){
			$("#img").show();

			$("#img").hide();
		})
		function articulos()
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
	<div style="width: 110%; height: 110%; position: fixed; float: center; margin-left: -5%; margin-top:-2%; background-color: white;" id="img">
<img src="loadf.gif" style="float: center; margin-left: 40%; margin-top: 15%; width: 20%; height: 22%;">
</div>
	<?php
	/*session_start();
	if($_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='OCAMPOS')
	{
		echo '<img src="load.gif" width="105%" id="img" height="100%" style="position: fixed; margin-left: -5%; margin-top: -1%;">';
	}else
	{
		echo '<img src="load.gif" width="105%" id="img" height="100%" style="position: fixed; margin-left: -5%; margin-top: -1%;">';
	}*/
	?>

<?php
error_reporting(0);
include("conexion.php");


?>
	<h3 style="text-align: center; text-decoration: underline;">REPORTE DESGLOSES</h3>

<form method="POST" onautocomplete="off()">
DESDE: <input type="date" name="desde" class="text" style="width: 15%; padding: 0.5%;" required>
HASTA: <input type="date" name="hasta" class="text" style="width: 15%; padding: 0.5%;" required>

<select name="bodega" class="text" style="width: 20%;">
	<option value="">BODEGA</option>


<?php
	$cb=$conexion1->query("select * from consny.bodega where bodega not like 'SM%' and nombre not like '%(N)%' order by bodega")or die($conexion1->error());

	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$fcb['BODEGA'];
		$nom=$fcb['NOMBRE'];

		echo "<option value='$bod'>$bod: $nom</option>";
	}

?>
</select><!--<br><br>
<a href="#" onclick="articulos()" style="background-color: #5C8E70; padding: 0.5%; text-decoration: none; color: white;">ARTICULOS</a>!-->
<input type="text" name="art" placeholder="ARTICULO" list="articulos1" class="text" style="width: 20%;" onautocomplete="off()">
<datalist id="articulos1">
<?php 
$q=$conexion1->query("select concat(descripcion,'(',articulo,')') as articulo from consny.articulo where clasificacion_1!='DETALLE' and activo='S' order by articulo")or die($conexion1->error());
while($fq=$q->FETCH(PDO::FETCH_ASSOC))
{
	$arti=$fq['articulo'];
	echo "<option>$arti</option>";
}
?>
</datalist>
<input type="submit" name="btn" value="BUSCAR" class="btnfinal" style="padding: 0.5%; background-color: #D7E9DC; color: black;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
		$ar=explode("(", $art);
		if($ar[0]==$art)
		{
			$art=$ar[0];
		}else
		{
			$art=str_replace(")", '', $ar[1]);
		}
		//echo "<script>alert('$art')</script>";
	if($bodega!='')
	{
		if($art=='')
		{
			$c=$conexion2->query("select * from registro where fecha_desglose between '$desde' and '$hasta' and bodega='$bodega' order by bodega,fecha_desglose,codigo ")or die($conexion2->error());	
		}else
		{
			$c=$conexion2->query("select * from registro where fecha_desglose between '$desde' and '$hasta' and bodega='$bodega' and codigo='$art' order by bodega,fecha_desglose,codigo")or die($conexion2->error());
		}

		
	}else
	{
		if($art=='')
		{
			$c=$conexion2->query("select * from registro where fecha_desglose between '$desde' and '$hasta' order by bodega,fecha_desglose,codigo")or die($conexion2->error());
		}else
		{
			$c=$conexion2->query("select * from registro where fecha_desglose between '$desde' and '$hasta' and codigo='$art' order by bodega,fecha_desglose,codigo")or die($conexion2->error());
		}
		
	}
	
	$n=$c->rowCount();
	if($n==0)
	{
		$msj='<h3>NO SE ENCONTRO NINGUN DESGLOSE</h3>';
	}


	if($n!=0)
	{

	echo "<a href='export_desglose_reporte.php?desde=$desde&&hasta=$hasta&&art=$art&&bodega=$bodega' target='_blank'>Exportar a Excel</a>";
	echo "<table border='1' cellpadding='6' style=' border-collapse:collapse; width:100%;'>";
	echo "<tr>
	<td>#</td>
	<td>FAMILIA</td>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	<td>CODIGO DE BARRA</td>
	<td>PESO</td>
	<td>BODEGA</td>
	<td>FECHA DESGLOSE</td>
	<td>FECHA INGRESO (TRASL)</td>";
	if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['tipo']!=2)
	{
		echo "<td>UNIDADES</td>
		<td>TOTAL</td>";
	}
	echo"</tr>";
	}
	$t=0;
	$num=0;
	$tcantidad=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$num++;
		$art=$f['codigo'];
		$barra=$f['barra'];
		$fecha=$f['fecha_desglose'];
		$fechat=$f['fecha_traslado'];
		$bodega=$f['bodega'];
		if($f['tipo']=='P')
		{
			$peso=$f['lbs'];
		}else
		{
			$peso=$f['peso'];
		}
		$idr=base64_encode($f['id_registro']);
		$id=$f['id_registro'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);

		$art=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];
		$familia=$fca['CLASIFICACION_2'];
		$cb=$conexion1->query("select concat(bodega,': ',nombre) as bodega from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bodegas=$fcb['bodega'];
		echo "<tr class='tre'>
		<td>$num</td>
		<td><a target='_blank' href='detalle_desglose_reporte.php?id=$idr&&desde=$desde&&hasta=$hasta&&bodega=$bodega' style='text-decoration:none; color:black;'>$familia</a></td>
	<td><a target='_blank' href='detalle_desglose_reporte.php?id=$idr&&desde=$desde&&hasta=$hasta&&bodega=$bodega' style='text-decoration:none; color:black;'>$art</a></td>
	<td><a target='_blank' href='detalle_desglose_reporte.php?id=$idr&&desde=$desde&&hasta=$hasta&&bodega=$bodega' style='text-decoration:none; color:black;'>$desc</a></td>
	<td><a target='_blank' href='detalle_desglose_reporte.php?id=$idr&&desde=$desde&&hasta=$hasta&&bodega=$bodega' style='text-decoration:none; color:black;'>$barra</a></td>
	<td><a target='_blank' href='detalle_desglose_reporte.php?id=$idr&&desde=$desde&&hasta=$hasta&&bodega=$bodega' style='text-decoration:none; color:black;'>$peso</a></td>
	<td><a target='_blank' href='detalle_desglose_reporte.php?id=$idr&&desde=$desde&&hasta=$hasta&&bodega=$bodega' style='text-decoration:none; color:black;'>$bodegas</a></td>
	<td><a target='_blank' href='detalle_desglose_reporte.php?id=$idr&&desde=$desde&&hasta=$hasta&&bodega=$bodega' style='text-decoration:none; color:black;'>$fecha</a></td>
	<td><a target='_blank' href='detalle_desglose_reporte.php?id=$idr&&desde=$desde&&hasta=$hasta&&bodega=$bodega' style='text-decoration:none; color:black;'>$fechat</a></td>";
	if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['tipo']!=2)
	{
		$cd=$conexion2->query("select convert(decimal(10,2),sum(precio * cantidad)) as total,SUM(cantidad) as cantidad from desglose where registro='$id' group by registro
")or die($conexion2->error());
		$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
		$total=$fcd['total'];
		$cantidadd=$fcd['cantidad'];
		$t=$t+$total;
		echo "<td>$cantidadd</td>";
		echo "<td>$$total</td>";
		$tcantidad=$tcantidad+$cantidadd;
	}

	echo "</tr>";

	}

	if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALAVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' and $t>0)
	{
		echo "<tr>
					<td colspan='9'>TOTAL</td>
					<td>$tcantidad</td>
					<td>$$t</td>
			</tr>
			  </table>";
	}
}
?>
</body>
</html>