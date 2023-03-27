<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
	include("conexion.php");
	?>
<form method="POST">
<input type="text" name="cod" required>
<input type="submit" name="">	
</form><hr><br><br><br>
<?php

if($_POST)
{
	extract($_REQUEST);
	echo "$cod <br>";
	$c=$conexion2->query("select * from registro where codigo='$cod' and bodega='SM00' and activo is null and year(fecha_documento)='2019'")or die($conexion2->error());
	$k=1;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$f['id_registro'];
		$ci=$conexion2->query("select * from inventario where registro='$idr'")or die($conexion2->error());
		$nci=$ci->rowCount();
		if($nci==0)
		{
			echo "".$f['barra']."-".$f['id_registro']."- $k - $idr<br>";
			$k++;

			//no usar hay fechas mayores producion k inventario<-----


		//$conexion2->query("update registro set bodega='0000',activo='0',observacion='INACTIVO R' where id_registro='$idr'")or die($conexion2->error());
		}else
		{
		}
	}
}
?>
</body>
</html>