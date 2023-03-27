<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<div style="display: none;">
<?php
include("conexion.php");
?>
</div>
<?php
	$inv=$_SESSION['inv'];
	$usu=$_SESSION['usuario'];
	$c=$conexion2->query("select EXIMP600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,inventario.bodega,inventario.bodega_actual,registro.barra,inventario.id,registro.activo
from EXIMP600.consny.articulo inner join registro on registro.codigo=eximp600.consny.articulo.articulo inner join
inventario on inventario.registro=registro.id_registro where inventario.sessiones='$inv' and
inventario.usuario='$usu' order by inventario.id desc")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h2>NO SE HA AGREGADO NINGUN FARDO AL INVENTARIO</h2>";
	}else
	{
		echo "<table border='1' style='border-collapse:collapse; width:98%' cellpadding='10'>";
		echo "<tr>
		<td>#</td>
		<td>CODIGO DE BARRA</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>BODEGA INVENTARIO</td>
		<td>BODEGA FARDO</td>
		<td>QUITAR</td>

		</tr>";
		$num=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$num++;
			$id=$f['id'];
			$barra=$f['barra'];

			$cn=$conexion2->query("select count(*) as total from inventario where sessiones='$inv' and usuario='$usu' and registro in(select id_registro from registro where barra='$barra')")or die($conexion2->error());
			$fcn=$cn->FETCH(PDO::FETCH_ASSOC);
			if($f['bodega']!=$f['bodega_actual'])
			{
				echo "<tr style='background-color:red;'>";
			}else
			{
				echo "<tr>";
			}
			echo "
			<td>$num</td>";
			if($fcn['total']>1)
			{
				echo "<td style='background-color:yellow; color:black;'>";
			}else
			{
				echo "<td>";
			}
		echo " ".$f['barra']."</td>
		<td>".$f['articulo']."</td>
		<td>".$f['descripcion']."</td>
		<td>".$f['bodega']."</td>
		<td>".$f['bodega_actual']."</td>
		<td><a href='eli_inventario.php?id=$id'>Quitar</a></td>
		</tr>";
		}
	}
?>
</body>
</html>