<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<script>
		function cerrar()
		{
			window.close();
		}
	</script>
</head>
<body>
<div style="display: none;">
	<?php
	include("conexion.php");
	$id=base64_decode($_GET['id']);
	if($id=='')
	{
		echo "<script>window.close();</script>";
	}
	?>
</div>
<div class="detalle">
	<a href="#" style="color: white; float: right; margin-right: 1.5%; text-decoration: none;" onclick="cerrar()">CERRAR X</a>
	<div class="adentro" style="margin-left: 3%; height: 92%;">
	<?php
		$c=$conexion2->query("select * from detalle_mesa where mesa='$id'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
		}else
		{
			echo "<table border='1' style='border-collapse:collapse; width:95%; height:auto; margin-left:3%; margin-buttom:3%;'>";
			echo "<tr>
				<td>#</td>
				<td>CODIGO BARRA</td>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
				<td>PESO</td>
				<td>COMENTARIO</td>

			</tr>";
			$num=1;
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$idr=$f['registro'];
				$comentario=$f['comentario'];
				$cr=$conexion2->query("select eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,(isnull(registro.lbs,0) + isnull(registro.peso,0)) as peso,registro.barra from registro inner join eximp600.consny.articulo on eximp600.consny.articulo.articulo=registro.codigo where id_registro='$idr'")or die($conexion2->error());

			$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
			echo "<tr>
				<td>$num</td>
				<td>".$fcr['barra']."</td>
				<td>".$fcr['articulo']."</td>
				<td>".$fcr['descripcion']."</td>
				<td>".$fcr['peso']."</td>
				<td>$comentario</td>

			</tr>";
			$num++;
			}
			echo "</table><br>";

		}
	?>
	</div>	

</div>
</body>
</html>