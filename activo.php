<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
echo "<table class='tabla' border='1' cellpadding='10'>";
echo "<tr>
			<td>REGISTRO</td>
			<td>ARTICULO</td>
			<td>ACTIVO</td>
			<td>#</td>";
			$k=1;
$c=$conexion2->query("select * from mesa where estado='T'")or die($conexion2->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$idm=$f['id'];
	$cdm=$conexion2->query("select * from detalle_mesa where mesa='$idm'")or die($conexion2->error());
	while($fcdm=$cdm->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$fcdm['registro'];
		$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$cod=$fcr['codigo'];
		$activo=$fcr['activo'];
		echo "<tr>
			<td>$idr</td>
			<td>$cod</td>
			<td>$activo</td>
			<td>$k</td>";
			$k++;

			//$conexion2->query("update registro set activo='0' where id_registro='$idr'")or die($conexion2->error());
	}
}


?>
</body>
</html>