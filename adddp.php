<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<script>
		function cerrar()
		{
			location.replace('ingreso.php?i=2');
		}
	</script>
</head>
<body>
<
<div class="detalle">
	<button style="float: right; margin-right: 0.5%;" onclick="cerrar()">CERRAR X</button>
<div class="adentro" style="margin-left: 2.5%; width: 95%; margin-top: 1.5%;">
<form method="POST">
	<input type="text" name="b" placeholder="CODIGO O NOMBRE" class="text" style="width: 30%; float: center; margin-left: 1%;">
	<input type="submit" name="btn" value="BUSCAR" class="boton3">
<?php
echo "<div style='display:none;'>";
 include("conexion.php");
 $barra=$_SESSION['cbarra'];
 $q=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
 $fq=$q->FETCH(PDO::FETCH_ASSOC);
 $boart=$fq['bodega'];
 $boton=$fq['categoria'];
 echo "</div>";
 //echo "<script>alert('$boart - $boton - $barra')</script>";
 if($_POST)
 {
 	extract($_REQUEST);
 	//echo "<script>alert('$b')</script>";
 	
 	if($b!="")
 	{
 		//echo "<script>alert('$boart - $boton - $barra')</script>";
 		$c=$conexion1->query("select consny.articulo.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO AND consny.EXISTENCIA_BODEGA.BODEGA='$boart' where consny.ARTICULO.ARTICULO='$b' or consny.ARTICULO.DESCRIPCION LIKE (SELECT '%'+REPLACE('$b',' ','%')+'%') and consny.ARTICULO.ACTIVO='S' and consny.EXISTENCIA_BODEGA.BODEGA='$boart' AND consny.ARTICULO.CLASIFICACION_1='DETALLE' AND consny.ARTICULO.CLASIFICACION_2='$boton'
")or die($conexion1->error());
 	}else
 	{
 		$c=$conexion1->query("select consny.articulo.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO where  consny.ARTICULO.ACTIVO='S' and consny.EXISTENCIA_BODEGA.BODEGA='$boart' AND consny.ARTICULO.CLASIFICACION_1='DETALLE' AND consny.ARTICULO.CLASIFICACION_2='$boton'

")or die($conexion1->error());
 	}
 	
 }else
 {
 	$c=$conexion1->query("select consny.articulo.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO where  consny.ARTICULO.ACTIVO='S' and consny.EXISTENCIA_BODEGA.BODEGA='$boart' AND consny.ARTICULO.CLASIFICACION_1='DETALLE' AND consny.ARTICULO.CLASIFICACION_2='$boton'

")or die($conexion1->error());
 }
 $n=$c->rowCount();
 if($n==0)
 {
 	echo "<h3>NO SE ENCONTRO REGISTRO</h3>";
 }else
 {
 	echo "<table class='tabla' border='1' cellpadding='10'>";
 	echo "<tr>
 		<td>ARTICULO</td>
 		<td>DESCRIPCION</td>
 	</tr>";
 	while($f=$c->FETCH(PDO::FETCH_ASSOC))
 	{
 		$art=$f['ARTICULO'];
 		$des=$f['DESCRIPCION'];
 		echo "<tr>
 		<td><a href='ingreso.php?i=5&&condd=$art&&i=2' class='aref'>$art</a></td>
 		<td><a href='ingreso.php?i=5&&condd=$art&&i=2' class='aref'>$des</a></td>
 	</tr>";

 	}
 }
 
 {

 }
?>

</form>
</div>
	
</div>
</body>
</html>