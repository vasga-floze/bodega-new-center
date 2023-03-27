<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#barra").focus();
		})
	</script>
<?php
include("conexion.php");

if($_SESSION['tipo']==2)
	{

		echo "<script>alert('NO TIENES AUTORIZACION PARA RESUMEN')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}else if($_SESSION['tipo']==3)
	{
		echo "<script>location.replace('consultar.php')</script>";
	}
//habilitar para usuario harias que solo o pueda ver si ya lo desglosaron <----
?>
</head>
<body>
<form method="POST">
	<input type="text" name="barra" id="barra" placeholder="CODIGO DE BARRA" class="text" style="width: 20%;" required>
	<input type="submit" name="btn" value="Siguiente" class="boton2">
</form>
	<hr>
<?php
	if($_POST)
	{
		extract($_REQUEST);
		$c=$conexion2->query("select * from dbo.registro where barra='$barra' ")or die($conexion2->error);
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO REGISTRO O NO SE ENCUENTRA DISPONIBLE DE <u>$barra</u></hr>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$registro=$f['id_registro'];
			$numf=$f['numero_fardo'];
			$lb=$f['lbs'] + $f['peso'];
			$uni=$f['und'];
			$emp=$f['empacado'];
			$pro=$f['producido'];
			$dig=$f['digitado'];
			$fp=$f['fecha_documento'];
			$ft=$f['fecha_traslado'];
			$fd=$f['fecha_desglose'];
			$obs=$f['observacion'];
			$digd=$f['digita_desglose'];
			$deslosado=$f['desglosado_por'];
			$bodega=$f['bodega'];
			$cod=$f['codigo'];
			$empaque=$f['empaque'];

			$cb=$conexion1->query("select * from consny.BODEGA where BODEGA='$bodega'")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bo=$fcb['BODEGA'];
			$no=$fcb['NOMBRE'];
			$bodega="$bo: $no";
			$k=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error());
			$fk=$k->FETCH(PDO::FETCH_ASSOC);
			$sub=$fk['DESCRIPCION'];

			//detalle producion
			$cp=$conexion2->query("select * from dbo.detalle where registro='$registro' order by articulo")or die($conexion2->error);
			//detalle desglose
			$cd=$conexion2->query("select * from dbo.desglose where registro='$registro' order by articulo")or die($conexion2->error);
echo  "<table class='tabla' border='1' cellpadding='10' style='margin-left:4%;'>

	<tr>
		<td>NOMBRE FARDO<br> $cod $sub ( BARRA: $barra )</td>
		<td>LBS<BR>$lb</td>
		<td>UNID<br>$uni</td>
	</tr>
		<tr>
		<td>FECHA PRODUCCION:$fp</td>
		<td>FECHA TRASLADO:$ft</td>
		<td>FECHA DESGLOSE:$fd</td>
		</tr>
			<tr>
			<td colspan='3'>EMPACADO POR:$emp</td>
			</tr>
			<tr>
			<td colspan='3'>PRODUCIDO POR:$pro</td>
			</tr>
			<tr><td colspan='3'>DIGITADO POR:$dig</td></tr>
			</tr>
			<tr><td colspan='3'>DESGLOSE DIGITADO POR:$digd</td></tr>
			<tr><td colspan='3'>DESGLOSADO POR:$deslosado</td></tr>
			<tr><td colspan='3'>BODEGA DESGLOSE:$bodega</td></tr>
			<tr>
			<td colspan='3'>CONSUMO: ".$f['documento_inv_consumo']." | INGRESO: ".$f['documento_inv_ing']."</td>
			<tr><td colspan='3'>OBSERVACION:$obs</td></tr>
			</tr>
			<tr>
			<td colspan='3'>EMPAQUE: $empaque</td>
			</tr>
			</table>";
			echo "<table class='tabla' border='1'style='width:47.3%; float:left;	text-align:center; margin-left:4%;' cellpadding='10'>
			
			<tr style='background-color:rgb(133,133,137,0.5);'>
			  <td colspan='3'>DETALLE PRODUCCION</td>
			</tr>
			<tr>
			  <td>ARTICULO</td>
			  <td>DESCRIPCION</td>
			  <td>CANTIDAD</td>
			</tr>";

			$totalp=0;
			
		while($fp=$cp->FETCH(PDO::FETCH_ASSOC))
		{
			$ar=$fp['articulo'];
			$cant=$fp['cantidad'];
			$c=$conexion1->query("select * from consny.articulo where articulo='$ar'")or die($conexion1->error);
			$fc=$c->FETCH(PDO::FETCH_ASSOC);
			$cod=$fc['ARTICULO'];
			$nom=$fc['DESCRIPCION'];
			echo "<tr>
				<td>$cod</td>
				<td>$nom</td>
				<td>$cant</td>
			</tr>";
			$totalp=$totalp + $cant;
		}
		echo "
		<tr style='background-color:rgb(133,133,133,0.8); color:white;'>
			<td colspan='2'>TOTAL ARTICULOS</td>
			<td>$totalp</td>
		</tr>
		</table>";



			//detalle desglose
		echo "<table class='tabla' border='1'style='width:47.3%; float:left; margin-left:0.5%; text-align:center;' cellpadding='10'>
			<tr style='background-color:rgb(133,133,137,0.5);'>
			  <td colspan='3'>DETALLE DESGLOSE</td>
			  </tr>
			  <tr>
			  <td>ARTICULO</td>
			  <td>DESCRIPCION</td>
			  <td>CANTIDAD</td>
			</tr>";
			$totald=0;
		while ($fd=$cd->FETCH(PDO::FETCH_ASSOC)) 
		{
			$a=$fd['articulo'];
			$can=$fd['cantidad'];
			$ca=$conexion1->query("select * from consny.articulo where consny.articulo.articulo='$a'")or die($conexion1->error);
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$codigo=$fca['ARTICULO'];
			$nombre=$fca['DESCRIPCION'];

			echo "<tr>
			<td>$codigo</td>
			<td>$nombre</td>
			<td>$can</td>
			</tr>";
			$totald=$totald + $can;


		}
		echo "<tr style='background-color:rgb(133,133,133,0.8); color:white;'>
			<td colspan='2'>TOTAL ARTICULOS</td>
			<td>$totald</td>
		</tr></table>";

		}

	}
?>

</body>
</html>