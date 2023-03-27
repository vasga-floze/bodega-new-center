<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			var user=$("#user").val();
			var tipo=$("#tipo").val();

			if(user=='harias' || user=='HARIAS' || user=='staana3' || tipo==3)
			{
				$("#bodega").show();
			}else
			{
				$("#bodega").hide();
			}
		})
	</script>
</head>
<body>
<?php
include("conexion.php");
$usuario=$_SESSION['usuario'];
$paquete=$_SESSION['paquete'];
$tipo=$_SESSION['tipo'];
?>
<form method="POST">
	<input type="text" name="barras" class="text" style="width: 20%;" placeholder="CODIGO BARRA">
	<input type="hidden" name="user" id="user" value='<?php echo "$usuario";?>'>
	<input type="hidden" name="tipo" id="tipo" value='<?php echo "$tipo";?>'>
	<select name="bodega" id="bodega" class="text" style="padding: 0.5%; width: 
	20%;"> 
	<?php
	if($_SESSION['tipo']==3 or $usuario=='staana3')
	{
		$cb=$conexion1->query("select * from consny.bodega where bodega not like 'SM%' and nombre not like '%(N)%'")or die($conexion1->error());
		echo "<option value=''>BODEGA</option>";
		while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
		{
			$bod=$fcb['BODEGA'];
			$nom=$fcb['NOMBRE'];
			echo "<option value='$bod'>$bod: $nom</option>";

		}
	}else
	{
		$cb=$conexion1->query("select consny.bodega.BODEGA,consny.bodega.NOMBRE from consny.bodega inner join usuariobodega on consny.bodega.bodega=usuariobodega.bodega where usuariobodega.usuario='$usuario' ")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bod=$fcb['BODEGA'];
			$nom=$fcb['NOMBRE'];
			echo "<option value='$bod'>$bod: $nom</option>";

	}

	?>

	</select>
	<input type="submit" name="" value="BUSCAR" style="padding: 0.5%; border-color: blue; cursor: pointer; background-color: #D8EEF2;">
</form>
<?php
$cb=$conexion1->query("select * from usuariobodega where usuario='$usuario'")or die($conexion1->error());

$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
$bodega=$fcb['BODEGA'];
$ct=$conexion1->query("select nombre from consny.bodega where bodega='$bodega'")or die($conexion1->error());
$fct=$ct->FETCH(PDO::FETCH_ASSOC);
$tienda=$fct['nombre'];
//echo "<script>alert('$bodega')</script>";
if($_POST)
{
	extract($_REQUEST);
	if($barras=='')
	{
		if($bodega!='')
		{
			//echo "<script>alert('fdf $bodega')</script>";
			$c=$conexion2->query("select * from registro where bodega='$bodega' and activo='0' and observacion like '%| DESACTIVADO%' order by id_registro desc")or die($conexion2->error());
			$k=$c->rowCount();
			//echo "<script>alert('$k')</script>";
		}
		
	}else
	{
		if($bodega!='')
		{
			//echo "<script>alert('1 $bodega')</script>";
			$c=$conexion2->query("select * from registro where bodega='$bodega' and activo='0' and observacion like '%| DESACTIVADO%' AND barra='$barras' order by id_registro desc")or die($conexion2->error());

		}else
		{
			//echo "<script>alert('2')</script>";
			$c=$conexion2->query("select * from registro where  activo='0' and observacion like '%| DESACTIVADO%' and barra='$barras' order by id_registro desc")or die($conexion2->error());

		}
		
	}
	if($barras=='' and $bodega=='')
	{
		//echo "<script>alert('ddd')</script>";
		$c=$conexion2->query("select * from registro where activo='0' and observacion like '%| DESACTIVADO%'")or die($conexion2->error());
	}else
	{
		/*echo "<script>alert('bvjkbe')</script>";
		$c=$conexion2->query("select * from registro where bodega='$bodega' and barra='$barras' and activo='0' and observacion like '%| DESACTIVADO%'")or die($conexion2->error());*/

	}

	


}else
{
	if($_SESSION['usuario']=='HARIAS' or $_SESSION['usuario']=='harias' or $_SESSION['usuario']=='staana3')
	{
		//echo "<script>alert('vdfvf fff')</script>";
		$c=$conexion2->query("select * from registro where activo='0' and observacion like '%| DESACTIVADO%' order by id_registro desc")or die($conexion2->error());
	}else
	{
			//echo "<script>alert('tt')</script>";

		$c=$conexion2->query("select * from registro where bodega='$bodega' and activo='0' and observacion like '%| DESACTIVADO%' order by id_registro desc")or die($conexion2->error());
	}
	
}

$n=$c->rowCount();
			//echo "<script>alert('$k | $n')</script>";

if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN CODIGO DE BARRA VENDIDO</h3>";
}else
{
	echo "<table border='1' style='width:100%; border-collapse:collapse;' cellpadding='8'>";

	echo "<tr style='color:black; text-decoration:underline;'>
		<td colspan='5'>REPORTE DE FARDOS VENDIDOS EN TIENDAS</td>
	</tr>";
	echo "<tr>
		<td>TIENDA</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CODIGO BARRA</td>
		<td>FECHA DESACTIVADO</td>

	</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$e=explode('| DESACTIVADO POR VENTA EN TIENDA', $f['observacion']);
		$fecha=$e[1];
		$art=$f['codigo'];
		$bodegas=$f['bodega'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
		<td>$bodegas</td>
		<td>".$fca['ARTICULO']."</td>
		<td>".$fca['DESCRIPCION']."</td>
		<td>".$f['barra']."</td>
		<td>$fecha</td>
	</tr>";

	}
}

?>
</body>
</html>