<!DOCTYPE html>
<html>
<head>
	<?php
	error_reporting(0);
		include("conexion.php");
		//echo "<script>alert('NO DISPONIBLE')</script>";
//ECHO "<script>location.replace('salir.php')</script>";
		if($_SESSION['tipo']==2)
	{
		echo "<script>alert('NO TIENES AUTORIZACION PARA PRODUCCION')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}else if($_SESSION['tipo']==3)
	{
		/*echo "<script>location.replace('reportedesglosesresumenpiezas.php')</script>";*/
	}
		if($_SESSION['dia']=="")
		{
			$hoy=date("Y-m-d");
		}else
		{
			$hoy=$_SESSION['dia'];
		}
		if($_SESSION['fechar']!="")
		{
			$hoy=$_SESSION['fechar'];
		}
	?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>

<body>
<form name="form" method="POST">
	FECHA INICIAL:
<input type="date" name="FI" class='text' style='width: 20%;'>
	FECHA FINAL:
<input type="date" name="FF" class='text' style='width: 20%;'>
	<input type="submit" name="" value="MOSTRAR" class="boton2"><br><hr>
</form>
<?php
/*if($_POST)
{
	extract($_REQUEST);
	$_SESSION['fechar']=$fecha;
	$usua=$busu;
	if($busu=="TODOS")
	{
		
		$con=$conexion2->query("select  REGISTRO.BODEGA,ex1.nombre, registro.codigo, ex.DESCRIPCION,
ex.CLASIFICACION_2 categoria,registro.barra, CONVERT(date,desglose.fecha) FECHA,desglose.articulo,exa1.descripcion,SUM(desglose.cantidad)Cantidad
 FROM            desglose INNER JOIN
                         registro ON desglose.registro = registro.id_registro
						 INNER JOIN
						 EXIMP600.consny.articulo ex on ex.articulo = registro.codigo
						 inner join
						 EXIMP600.CONSNY.BODEGA EX1 ON EX1.BODEGA=REGISTRO.bodega
						 inner join
						 EXIMP600.CONSNY.ARTICULO EXA1 ON EXA1.ARTICULO=desglose.articulo
						  where (convert(date,desglose.fecha) BETWEEN '$FI' AND '$FF') AND (desglose.registro IN
                             (SELECT        id_registro
                               FROM            registro AS registro_1
                               WHERE (convert(date,fecha_desglose) BETWEEN '$FI' AND '$FF'))) GROUP BY REGISTRO.BODEGa,registro.barrA,ex1.nombre, registro.codigo, registro.subcategoria,registro.categoria,CONVERT(date,desglose.fecha),
ex.DESCRIPCION,ex.CLASIFICACION_2,desglose.articulo,exa1.descripcion
order by 1,7,6")or die($conexion2->error);
	$dia=$fecha;
}else
{
	
	$con=$conexion2->query("select  REGISTRO.BODEGA,ex1.nombre, registro.codigo, ex.DESCRIPCION,
ex.CLASIFICACION_2 categoria,registro.barra, CONVERT(date,desglose.fecha) FECHA,desglose.articulo,exa1.descripcion,SUM(desglose.cantidad)Cantidad
 FROM            desglose INNER JOIN
                         registro ON desglose.registro = registro.id_registro
						 INNER JOIN
						 EXIMP600.consny.articulo ex on ex.articulo = registro.codigo
						 inner join
						 EXIMP600.CONSNY.BODEGA EX1 ON EX1.BODEGA=REGISTRO.bodega
						 inner join
						 EXIMP600.CONSNY.ARTICULO EXA1 ON EXA1.ARTICULO=desglose.articulo
						  where (convert(date,desglose.fecha) BETWEEN '$FI' AND '$FF') AND (desglose.registro IN
                             (SELECT        id_registro
                               FROM            registro AS registro_1
                               WHERE (convert(date,fecha_desglose) BETWEEN '$FI' AND '$FF'))) GROUP BY REGISTRO.BODEGa,registro.barrA,ex1.nombre, registro.codigo, registro.subcategoria,registro.categoria,CONVERT(date,desglose.fecha),
ex.DESCRIPCION,ex.CLASIFICACION_2,desglose.articulo,exa1.descripcion
order by 1,7,6")or die($conexion2->error);
	
}
	
}else
{
	$con=$conexion2->query("select  REGISTRO.BODEGA,ex1.nombre, registro.codigo, ex.DESCRIPCION,
ex.CLASIFICACION_2 categoria,registro.barra, CONVERT(date,desglose.fecha) FECHA,desglose.articulo,exa1.descripcion,SUM(desglose.cantidad)Cantidad
 FROM            desglose INNER JOIN
                         registro ON desglose.registro = registro.id_registro
						 INNER JOIN
						 EXIMP600.consny.articulo ex on ex.articulo = registro.codigo
						 inner join
						 EXIMP600.CONSNY.BODEGA EX1 ON EX1.BODEGA=REGISTRO.bodega
						 inner join
						 EXIMP600.CONSNY.ARTICULO EXA1 ON EXA1.ARTICULO=desglose.articulo
						  where (convert(date,desglose.fecha) BETWEEN '$FI' AND '$FF') AND (desglose.registro IN
                             (SELECT        id_registro
                               FROM            registro AS registro_1
                               WHERE (convert(date,fecha_desglose) BETWEEN '$FI' AND '$FF'))) GROUP BY REGISTRO.BODEGa,registro.barrA,ex1.nombre, registro.codigo, registro.subcategoria,registro.categoria,CONVERT(date,desglose.fecha),
ex.DESCRIPCION,ex.CLASIFICACION_2,desglose.articulo,exa1.descripcion
order by 1,7,6")or die($conexion2->error);
	
	$usua="TODOS";
}
$ncon=$con->rowCount();*/
if($ncon==0)
{
	echo "<h2>NO SE ENCONTRÓ INFORMACIÓN</h2>
	<center><IMG SRC='1.PNG'></center>";
}else
{
	echo '<h3>REPORTE HISTORIAL DE FARDOS DE TIENDAS</h3><br><table class="tabla" border="1" cellpadding="5">
	<tr>';
	echo "
	<td colspan='8'><a href=''></a></td>
	</tr>";
	echo '
	<tr style="background-color:rgb(133,133,137,0.8); color:white; width:100%;">
		<td>BODEGA</td>
		<td>NOMBRE</td>
		<td>CODIGO</td>
		<td>DESCRIPCION</td>
		<td>CATEGORIA</td>
		<td>CODIGO BARRA</td>
		<td>FECHA DESGLOSE</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION2</td>
		<td>CANTIDAD</td>
	</tr>';
	$to=0;
	while($fcon=$con->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$fcon['BODEGA'];
		$nom=$fcon['nombre'];
		$cod=$fcon['codigo'];
		$desc=$fcon['DESCRIPCION'];
		$cat=$fcon['categoria'];
		$barr=$fcon['barra'];
		$fec=$fcon['FECHA'];
		$art=$fcon['articulo'];
		$desc2=$fcon['descripcion'];
		$cantidad=$fcon['Cantidad'];
		echo "<tr>
		<td>$bod</td>
		<td>$nom</td>
		<td>$cod</td>
		<td>$desc</td>
		<td>$cat</td>
		<td>$barr</td>
		<td>$fec</td>
		<td>$art</td>
		<td>$desc2</td>
		<td>$cantidad</td>
	</tr>";
	$to++;
	}
		
}
?>

</body>
</html>