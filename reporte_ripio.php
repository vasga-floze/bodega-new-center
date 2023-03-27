<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<body>
<?php
include("conexion.php");
?>
<form method="POST">
DESDE: <input type="date" name="desde" class="text" style="width: 20%;" required>
HASTA: <input type="date" name="hasta" class="text" style="width: 20%;" required>
<label><input type="radio" name="op" value="1" required>RESUMEN</label>
<label><input type="radio" name="op" value="2" required>DETALLE</label>

<input type="submit" name="btn"  value="GENERAR" class="boton2">	

</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($op==1)
	{
		$c=$conexion2->query("select clasificacion,sum(peso) as peso from ripio where fecha_documento between '$desde' and '$hasta' group by clasificacion")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select clasificacion,peso from ripio where fecha_documento between '$desde' and '$hasta'")or die($conexion2->error());
	}
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
		}else
		{
			echo "<table border='1' cellpadding='10' style='border-collapse:collapse; margin-top:1%;'>";
			echo "<tr>
				     <td>CLASIFICACION</td>
				     <td>PESO</td>
			     </tr>";
			$tpeso=0;
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				echo "<tr>
				<td>".$f['clasificacion']."</td>
				<td>".$f['peso']."</td>
			</tr>";
			$tpeso=$tpeso + $f['peso'];
			}
			echo "<tr><td>PESO</td><td>$tpeso</td></tr></table>";
		}
	
}
?>
</body>
</html>