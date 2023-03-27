<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function enviar()
		{
			document.form.submit();
		}
	</script>
</head>
<body>
	<?php
	error_reporting(0);
	include('conexion.php');
	?>
<form method="POST" name="form">
<input type="date" name="desde" class="text" style="width: 20%;">
<input type="date" name="hasta" class="text" style="width: 20%;">
<input type="submit" name="" value="MOSTRAR" class="boton3">
</form><br>
<?php

if($_POST)
{
	extract($_REQUEST);
	if($desde!='' and $hasta!='')
	{
		$c=$conexion2->query("select sessiones from  traslado where fecha between'$desde' and '$hasta' and (origen like 'SM%' or origen='CA00') group by sessiones")or die($conexion2->error());
	}else if($desde!='' and $hasta=='')
	{
		$c=$conexion2->query("select sessiones from  traslado where fecha='$desde' and origen like 'SM%' group by sessiones")or die($conexion2->error());
	}else if($desde=='' and $hasta!='')
	{
		$c=$conexion2->query("select sessiones from  traslado where fecha='$hasta' and origen like 'SM%' group by sessiones")or die($conexion2->error());
	}
	
	
}else
{
	$desde=$_GET['d'];
	$hasta=$_GET['h'];
	if($desde=='' and $hasta=='')
	{
		$fecha=date("Y-m-d");
	$c=$conexion2->query("select sessiones from  traslado where fecha='$fecha' and origen like 'SM%' group by sessiones")or die($conexion2->error());
}else if($desde!='' and $hasta!='')
{
	$c=$conexion2->query("select sessiones from  traslado where fecha between'$desde' and '$hasta' and origen like 'SM%' group by sessiones")or die($conexion2->error());
}else if($desde!='' and $hasta=='')
{
	$c=$conexion2->query("select sessiones from  traslado where fecha='$desde' and origen like 'SM%' group by sessiones")or die($conexion2->error());
}else if($desde=='' and $hasta!='')
{
	$c=$conexion2->query("select sessiones from  traslado where fecha='$hasta' and origen like 'SM%' group by sessiones")or die($conexion2->error());
}
	
}
$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO DE LA FECHA: $fecha</h3>";
	}else
	{
		echo "<table border='1' class='tabla' cellpadding='10'>";
		echo "<tr>
			<td>DOUMENTO</td>
			<td>ORIGEN</td>
			<td>DESTINO</td>
			<td>FECHA TRASLADO</td>
			<td>USUARIO</td>
			<td>TOTAL ARTICULO</td>
			<td>TOTAL PESO</td>
			
		</tr>
		<form method='POST' action='totalpeso.php'>";
		$k=0;

			$tta=0;
			$ttp=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$doc=$f['sessiones'];
			$ct=$conexion2->query("select * from traslado where sessiones='$doc'")or die($conexion2->error());
			$ta=0;
			$tp=0;
			
			while($fct=$ct->FETCH(PDO::FETCH_ASSOC))
			{
				$idr=$fct['registro'];
				$documento=$fct['documento_inv'];
				$origen=$fct['origen'];
				$destino=$fct['destino'];
				$fecha=$fct['fecha'];
				$usuario=$fct['usuario'];
				$cr=$conexion2->query("select lbs,peso from registro where id_registro='$idr'")or die($conexion2->error());
				$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
				$tp=$tp + $fcr['lbs'] + $fcr['peso'];
				$ta++;

			}
			$tta=$tta +$ta;
			$ttp=$ttp +$tp;
			echo "<tr>
			<td>
			<label><input type='checkbox' value='$documento' name='op[$k]'> $documento</label>
		
			</td>
			<td>$origen</td>
			<td>$destino</td>
			<td>$fecha</td>
			<td>$usuario</td>
			<td>$ta</td>
			<td>$tp</td>
			
		</tr>";
		$k++;
		}
		echo "<td colspan='5'>TOTAL</td><td>$tta</tta><td>$ttp</td>";
		echo "</table>
		<input type='hidden' value='$k' name='k'>
		<input type='hidden' name='desde' value='$desde'>
		<input type='hidden'  name='hasta' value='$hasta'>
		<input type='submit' value='CALCULAR' class='boton2' style='float:right; margin-right:5%;'></form><br><br><br>";
	}

?>
</body>
</html>