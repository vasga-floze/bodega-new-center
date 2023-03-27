<?php
include("conexion.php");
$c=$conexion2->query("select * from venta where registro!='' and precio!=''")or die($conexion2->error());
echo "<table border='1' class='tabla' cellpadding='10'>";
echo "<tr>
	<td>REGISTRO</td>
	<td>ARTICULO</td>
	<td>CANTIDAD</td>
	<td>ACTIVO</td>
</tr>";
$k=1;
while($f=$c->fetch(PDO::FETCH_ASSOC))
{
	$idr=$f['registro'];
	$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());

	while($fcr=$cr->FETCH(PDO::FETCH_ASSOC))
	{
		$cod=$fcr['codigo'];
		$activo=$fcr['activo'];
		echo "<tr>
				<td>$idr</td>
				<td>$cod</td>
				<td>$k</td>
				<td>$activo</td>
			</tr>";
			$k++;
		//$conexion2->query("update registro set activo='0' where id_registro='$idr'")or die($conexion2->error());
	}
}
?>