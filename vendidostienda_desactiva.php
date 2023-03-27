<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body style="background-color: #878C94;">
<div style="display: none;">
<?php
	include("conexion.php");
	echo "</div>";
	$barra=$_GET['barra'];
	if($barra=='')
	{
		echo "<script>location.replace('vendidostienda.php')</script>";
	}else
	{
		$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>location.replace('vendidostienda.php')</script>";
		}
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		{
			$art=$f['codigo'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion2->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$art="".$fca['ARTICULO'].": ".$fca['DESCRIPCION']."";
		}
	}

?>

<div class="adentro" style="width: 30%; border-color: black; border-collapse: collapse; border-radius: 5px; text-align: center; margin-left: 30%; margin-top: 15%; border-style: solid; border-color: black; border-width: 3px; ">
<?php
echo "CONFIRME LA VENTA DEL FARDO: $art ($barra)";
$horas=date("Y-m-d");
?>
<form method="POST">
	<input type="hidden" name="barra" value='<?php echo "$barra";?>'><br>
	INGRESE FECHA Y HORA SEGUN TIKECT<br><br>
		FECHA:<input type="date" name="fecha"  value='<?php echo $horas;?>' required style='width: 28%;'>
	
	<select name="hora" required>
		<option value="">HORA</option>
		<?php
		$h=1;
		while($h<=12)
		{
			echo "<option>$h</option>";
			$h++;
		}
		?>
	</select>
	<select name="minutos">
		<option>MIN.</option>
		<?php
			$min=0;
			while($min<=60)
			{
				$min=str_pad($min,2,"0",STR_PAD_LEFT);
				echo "<option>$min</option>";
				$min++;
			}
		?>
	</select>
	<select name='tipo' required>
		<option value="">AM/PM</option>
		<option>AM</option>
		<option>PM</option>
	</select>
	<br><br>
	<label>REFERENCIA DE VENTA
		<input type="text" name="referencia" class="text" required>
	</label><br><br><br>

	<input type="submit" name="btn" value="CONFIRMAR" class="btnfinal" style="padding: 1.5%; border-radius: 0px; border-color: black; color: white; background-color: #067C4D;">
	<input type="submit" name="btn" value="CANCELAR" class="btnfinal" style="padding: 1.5%; border-radius: 0px; border-color: black; color: black; background-color: white;">
</form>
	
</div>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='CANCELAR')
	{
		echo "<script>location.replace('vendidostienda.php')</script>";
	}else if($btn=='CONFIRMAR')
	{
		$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
		
			$hoy=date("Y-m-d");
		//echo "<script>alert('$tipo')</script>";
		
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$obs=$f['observacion'];
		$idr=$f['id_registro'];
		$obs1="$obs| DESACTIVADO POR VENTA EN TIENDA $fecha";
		//echo "<script>alert('$obs - $obs1')</script>";
		if($tipo=='PM' and $hora<12)
		{
			$hora=$hora+12;
			//echo "<script>alert('$hora <-')</script>";


		}
		if($hora==24)
		{
			$hora="00";
		}
		$fecha_venta="$fecha $hora:$minutos:00";
			//echo "<script>alert('$fecha_venta $referencia - $hora $minutos')</script>";
			//echo "<script>alert('$fecha_venta $referencia')</script>";
			if($conexion2->query("update registro set observacion='$obs1',activo='0',fecha_hora_venta='$fecha_venta',referencia_venta='$referencia' where id_registro='$idr'")or die($conexion2->error()))
			{
				echo "<script>alert('($barra) HA SIDO DESACTIVADO CORRECTAMENTE')</script>";
				echo "<script>location.replace('vendidostienda.php')</script>";
			}else
			{
				echo "<script>alert('error en la desactivacion')</script>";
			}
		}

		
		
		

		
	

}
?>
</body>
</html>