<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".detalle").hide();
		})
		function articulos()
		{
			//alert();
			$("#op").val('1');
			$("#form").submit();
		}

		function cerrarP()
		{
			//alert();
			$("#articuloP").hide();
		}

		function buscarP()
		{
			$("#op").val('1');
			$("#formP").submit();
		}
		function articulopro(e)
		{
			var  art=$("#art"+e).text();
			var desc=$("#desc"+e).text();
			$("#articulo").val(art);
			$("#descripcion").val(desc);
			$("#articuloP").hide();
		}
	</script>
</head>
<body>
<div class="detalle" style="background-color: white;">
<img src="loadf.gif" style="margin-left: 45%; margin-top: 10%;">
	
</div>
<?php
include("conexion.php");
//$_SESSION['cbarra']='GP290411022';

if($_POST)
{
	extract($_REQUEST);
	echo "<script>$(document).ready(function(){
		$('#articulo').val('$articulo');
		$('#descripcion').val('$desc');
	})</script>";
	if($op==1)
	{
		
		echo "<div style='position:fixed; width:90%; height:90%; background-color:white; overflow:auto; margin-left:3%; margin-top:-2.5%;' id='articuloP'>";

		echo "<button style='float:right; background-color:red; color:white; border-color:white; paddding:2%; cursor:pointer; position: sticky; top:0%;' onclick='cerrarP()'>X</button><br>";
		$barra=$_SESSION['cbarra'];
		$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$famlia=$f['categoria'];
	$bodega=$f['bodega_produccion'];
	echo "<form method='POST' name='formP' id='formP' >

	<input type='hidden' name='op' id='op'>

	<input type='text' class='text' placeholder='ARTICULO O DESCRIPCION' style='width:35%;' name='b' id='b'>

	<input type='submit' onclick='buscarP()' value='BUSCAR' class='boton2'>
	</form>";
	
	if($b=='')
	{
		$cart=$conexion1->query("select top 200 consny.articulo.articulo,consny.articulo.descripcion from consny.articulo inner join consny.existencia_bodega on consny.articulo.articulo=consny.existencia_bodega.articulo where clasificacion_2='$famlia' and consny.articulo.activo='S'
			and consny.EXISTENCIA_BODEGA.BODEGA='$bodega' and consny.ARTICULO.CLASIFICACION_1!='DETALLE'")or die($conexion1->error());

	}else
	{

		$cart=$conexion1->query("select consny.articulo.articulo,consny.articulo.descripcion from consny.articulo inner join consny.existencia_bodega on consny.articulo.articulo=consny.existencia_bodega.articulo where (consny.articulo.articulo='$b' or consny.ARTICULO.DESCRIPCION LIKE (SELECT '%'+REPLACE('$b',' ','%')+'%')) and clasificacion_2='$famlia' and consny.articulo.activo='S'
			and consny.EXISTENCIA_BODEGA.BODEGA='$bodega' and consny.ARTICULO.CLASIFICACION_1!='DETALLE'")or die($conexion1->error());
	}
	$ncart=$cart->rowCount();
	if($ncart==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN ARTICULO DISPONIBLE</h3>";
	}else
	{
		echo "<table border='1' style='border-collapse:collapse; width:98%;' cellspadding='8'>";
		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
		</tr>";
		$num=0;
		while($fcart=$cart->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr class='tre' style='cursor:pointer;' onclick='articulopro($num)'>
			<td id='art$num'>".$fcart['articulo']."</td>
			<td id='desc$num'>".$fcart['descripcion']."</td>
		</tr>";
		$num++;
		}
		echo "<input type='hidden' name='numP' id='numP' value='$num'>";
		echo "</table></div>";
	}

	}
	
}
?>
<label class="boton3" onclick="articulos()">ARTICULO</label><br>
<form method="POST" name="form" id="form">
	<input type='hidden' name='op' id='op'>
<input type="text" name="articulo" id="articulo" class="text" style="width: 15%;" placeholder="ARTICULO">
<input type="text" name="desc" id="descripcion" class="text" style="width: 50%;" placeholder="DESCRIPCION">
<input type="submit" name="btn" value="SIGUIENTE" class="boton2">
</form>
</body>
</html>