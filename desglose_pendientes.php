<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			//alert();
		})
		function enviar()
		{
			//alert();
			$("#form").submit();
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
?>
<h2 style="text-align: center; text-decoration: underline;">VER DESGLOSES PENDIENTES DE FINALIZAR</h2>
<form method="POST" id='form'>
SELECCIONA BODEGA: 
<select name="bodega" class="text" style="width: 35%;" onchange="enviar()">
	<option value=''>BODEGAS</option>
	<?php
	$cb=$conexion1->query("select bodega,nombre from consny.bodega where bodega not like 'SM%' and nombre Not  like '%(N)%' and BODEGA!='CA00'")or die($conexion1->error());
	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$fcb['bodega'];
		$nom=$fcb['nombre'];
		echo "<option value='$bod'>$bod: $nom</option>";
	}
	?>
</select>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);

$c=$conexion2->query("select id_registro,barra,codigo,CONCAT(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION) articulo,CONCAT(EXIMP600.consny.BODEGA.BODEGA,':',EXIMP600.consny.BODEGA.NOMBRE) bodega from registro inner join desglose on id_registro=desglose.registro inner join EXIMP600.consny.ARTICULO on EXIMP600.consny.ARTICULO.ARTICULO=codigo inner join EXIMP600.consny.BODEGA on pruebabd.dbo.registro.bodega=EXIMP600.consny.BODEGA.BODEGA where fecha_desglose='' or fecha_desglose is null and registro.activo is null and registro.bodega='$bodega' group by barra,codigo,EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,EXIMP600.consny.BODEGA.BODEGA,EXIMP600.consny.BODEGA.NOMBRE,registro.id_registro
")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN DESGLOSE PENDIENTE DE FINALIZAR</h3>";
}else
{
	echo "<table border='1' cellpadding='10' width='98%' style='border-collapse:collapse; margin-top: 2%;'>";
	echo "<tr>
		<td>BODEGA</td>
		<td>CODIGO DE BARRA</td>
		<td>ARTICULO</td>
		<td>FECHA Y HORA INICIO</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$f['id_registro'];
		$cd=$conexion2->query("select top 1 *  from desglose where registro='$idr'")or die($conexion2->error());
		$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
		<td>".$f['bodega']."</td>
		<td>".$f['barra']."</td>
		<td>".$f['articulo']."</td>
		<td>".$fcd['fecha']."</td>
	</tr>";
	}
}


}
?>


</body>
</html>