<!DOCTYPE html>
<html>
<head>
	<?php
		include("conexion.php");
		//$_SESSION['doc']=70;
		$hoy=date("Y-m-d");
		if($_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['salvarado'])
		{
			
		
	?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<form method="POST">
	<input type="date" name="b"  class="text" value='<?php echo "$hoy";?>' >
	<input type="submit" name="" value="BUSCAR" class="boton3">
</form>
<hr><br>
<?php
$hoy=date("Y-m-d");
if($_POST)
{

	extract($_REQUEST);
	//echo "<script>alert('$b')</script>";
	$c=$conexion2->query("select * from registro where fecha_documento='$b'  and tipo='P' order by id_registro desc")or die($conexion2->error());
}else
{
	$c=$conexion2->query("select * from registro where tipo='P' and fecha_documento='$hoy' order by id_registro desc")or die($conexion2->error());
}
$n=$c->rowCount();
//echo "<script>alert('$b - o $n')</script>";
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN REGISTRO!</h3>";
}else
{
	echo "<table class='tabla' border='1' cellpadding='10' style='width:100%;'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>TOTAL</td>
		<td>LBS</td>
		<td>UND</td>
		<td># FARDO</td>
		<td>FECHA PRODUCCION</td>
		<td>PAQUETE</td>
		<td>USUARIO</td>
		<td>ESTADO</td>
		<td>BODEGA</td>
		<td>BARRA</td>
		<td>FECHA TRASLADO</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['codigo'];
		$des=$f['subcategoria'];
		$lbs=$f['lbs'];
		$und=$f['und'];
		$n_fardo=$f['numero_fardo'];
		$fecha_i=$f['fecha_documento'];
		$paque=$f['paquete'];
		$usu=$f['usuario'];
		$est=$f['estado'];
		$bodega=$f['bodega'];
		$fecha_t=$f['fecha_traslado'];
		$barra=$f['barra'];
		$idr=$f['id_registro'];
		if($est==0)
		{
			$est='EN PROCESO';
		}else
		{
			$est='FINALIZADO';
		}
		if($fecha_t=='')
		{
			$fecha_t='- -';
		}

		$k=$conexion2->query("select * from detalle where registro='$idr'")or die($conexion2->error());
		$t=0;
		$cant=0;
		while($fk=$k->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fk['articulo'];
			$cant=$fk['cantidad'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$precio=$fca['PRECIO_REGULAR'];
			$m=$cant * $precio;
			$t=$t +$m;
		}

			echo "<tr>
		
		<td><a href='verbuscar1.php?id=$idr' style='text-decoration:none; color:black;'>$art</a></td>
		<td><a href='verbuscar1.php?id=$idr' style='text-decoration:none; color:black;'>$des</a></td>
		<td>$t</td>
		<td>$lbs</td>
		<td>$und</td>
		<td>$n_fardo</td>
		<td>$fecha_i</td>
		<td>$paque</td>
		<td>$usu</td>
		<td>$est</td>
		<td>$bodega</td>
		<td>$barra</td>
		<td>$fecha_t</td>
	</tr>";
		
		
	}
}
}else
		{
			echo "<script>alert('NO TIENES AUTORIZACION')</script>";
			echo "<script>location.replace('index.php')</script>";
		}
?>
</body>
</html>