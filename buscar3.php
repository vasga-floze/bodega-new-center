<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#op").val('');
		});
		function cambiar()
		{
			$("#op").val('1');
		}
		function enviar()
		{
			document.form.submit();
		}
		function enviar1()
		{
			$("#op").val('2');
			document.form1.submit();
		}
	</script>
</head>
<body>
	<?php
	error_reporting(0);
	include("conexion.php");
		if($_SESSION['tipo']!=1)
		{
			echo "<script>alert('NO TIENES AUTORIZACION')</script>";
			echo "<script>location.replace('desglose.php')</script>";

		}
	$art=$_GET['art'];
	$c=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$art=$f['ARTICULO'];
	$de=$f['DESCRIPCION'];
	?>
<form method="POST" name="form">
	<input type="text" name="codigo" placeholder="ARTICULO" class="text" style="width: 20%;" onkeypress="cambiar()" onchange="enviar()" value='<?php echo "$art";?>'>

	<input type="text" name="nom" placeholder="DESCIPCION" class="text" style="width: 40%;" value='<?php echo "$de";?>'>
	<input type="hidden" name="op" id="op">
</form>
<a href="buscar3.php">
<button>VACIAR
</button></a><br>
<!--form para articulo-->
<form method="POST" name="form1">
DESDE: <input type="date" name="d" class="text" style="width: 15%;">
HASTA: <input type="date" name="h" class="text" style="width: 15%;">
CODIGO DE BARRA: <input type="text" name="barra" placeholder="" class="text" style="width: 18%; ">
<input type="hidden" name="codigo" value='<?php echo "$art";?>'>
<select name="clasificacion" class="text" style="width: 10%;">
	<option value="">CLSIFICACION</option>
	<?php
	$c=$conexion2->query("select categoria from registro where tipo='P' group by categoria")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$cate=$f['categoria'];
		echo "<option>$cate</option>";
	}
	?>
</select>
<input type="submit" name="btn" value="BUSCAR" class="boton3" onclick="enviar1()">
</form>

<?php
if($_POST)
{
	extract($_REQUEST);
	if($op==1)
	{
		$c=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$codigo'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO ARTICULO $codigo')";
			echo "<script>location.replace('buscar3.php')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$art=$f['ARTICULO'];
			echo "<script>location.replace('buscar3.php?art=$art')</script>";
		}
	//finaiza llave de primer form	
	}
	if($btn=='BUSCAR')
	{
		if($barra!='')
		{
			$c=$conexion2->query("select * from registro where barra='$barra' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($barra=='' and $codigo!='' and $d!='' and $h!='')
		{
			$c=$conexion2->query("select * from registro where codigo='$codigo' and fecha_documento between '$d' and '$h' and tipo='P' order by registro.codigo")or die($conexion2->error());

		}else if($barra=='' and $d!='' and $h=='' and $codigo!='')
		{
			$c=$conexion2->query("select * from registro where codigo='$codigo' and fecha_documento ='$d' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($barra=='' and $d=='' and $h!='' and $codigo!='')
		{
			$c=$conexion2->query("select * from registro where codigo='$codigo' and fecha_documento ='$h' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($barra=='' and $codigo=='' and $d!='' and $h!='')
		{
			$c=$conexion2->query("select * from registro where fecha_documento between '$d' and '$h' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($codigo=='' and $barra=='' and $d!='' and $h=='')
		{
			$c=$conexion2->query("select * from registro where fecha_documento ='$d' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($codigo=='' and $barra=='' and $h!='' and $d=='')
		{
			$c=$conexion2->query("select * from registro where fecha_documento ='$h' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($codigo!='' and $barra=='' and $d=='' and $h=='')
		{
			$c=$conexion2->query("select * from registro where codigo='$codigo' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}

		if($d!='' and $h!='' and $clasificacion!='')
		{
			$c=$conexion2->query("select * from registro where categoria='$clasificacion' and fecha_documento between '$d' and '$h' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($d=='' and $h=='' and $clasificacion!='')
		{
			$c=$conexion2->query("select * from registro where categoria='$clasificacion' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}
		if($codigo=='' and $d=='' and $h=='' and $barra=='' and $clasificacion=='')
		{
			$c=$conexion2->query("select * from registro where tipo='P' order by registro.codigo")or die($conexion2->error());
		}
	}
}else
{
	$codigo=$_GET['codigo'];
	$clasificacion=$_GET['clasi'];
	$d=$_GET['d'];
	$h=$_GET['h'];
	$barra=$_GET['barra'];

if($barra!='')
		{
			$c=$conexion2->query("select * from registro where barra='$barra' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($barra=='' and $codigo!='' and $d!='' and $h!='')
		{
			$c=$conexion2->query("select * from registro where codigo='$codigo' and fecha_documento between '$d' and '$h' and tipo='P' order by registro.codigo")or die($conexion2->error());

		}else if($barra=='' and $d!='' and $h=='' and $codigo!='')
		{
			$c=$conexion2->query("select * from registro where codigo='$codigo' and fecha_documento ='$d' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($barra=='' and $d=='' and $h!='' and $codigo!='')
		{
			$c=$conexion2->query("select * from registro where codigo='$codigo' and fecha_documento ='$h' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($barra=='' and $codigo=='' and $d!='' and $h!='')
		{
			$c=$conexion2->query("select * from registro where fecha_documento between '$d' and '$h' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($codigo=='' and $barra=='' and $d!='' and $h=='')
		{
			$c=$conexion2->query("select * from registro where fecha_documento ='$d' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($codigo=='' and $barra=='' and $h!='' and $d=='')
		{
			$c=$conexion2->query("select * from registro where fecha_documento ='$h' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($codigo!='' and $barra=='' and $d=='' and $h=='')
		{
			$c=$conexion2->query("select * from registro where codigo='$codigo' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}

		if($d!='' and $h!='' and $clasificacion!='')
		{
			$c=$conexion2->query("select * from registro where categoria='$clasificacion' and fecha_documento between '$d' and '$h' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($d=='' and $h=='' and $clasificacion!='')
		{
			$c=$conexion2->query("select * from registro where categoria='$clasificacion' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}
		if($codigo=='' and $d=='' and $h=='' and $barra=='' and $clasificacion=='')
		{
			//$c=$conexion2->query("select * from registro where tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($d!='' and $clasificacion!='' and $h==''  and $codigo=='' and $barra=='')
		{
			$c=$conexion2->query("select * from registro where categoria='$clasificacion' and fecha_documento='$d' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}else if($h!='' and $clasificacion!='' and $codigo=='' and $barra=='' and $d=='')
		{
			$c=$conexion2->query("select * from registro where categoria='$clasificacion' and fecha_documento='$h' and tipo='P' order by registro.codigo")or die($conexion2->error());
		}
		
}

$n=$c->rowCount();
if($n==0)
{
	echo "<h2>NO SE OBTUVO NINGUN RESULTADO DE LA BUSQUEDA...</h2>";
}else
{
	$num=0;
	
	
	$t=0; $tf=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$codigo=$f['codigo'];
		$fecha=$f['fecha_documento'];
		$idr=$f['id_registro'];
		$usu=$f['usuario'];
		$estado=$f['estado'];
		$bodega=$f['bodega'];
		$fecha_t=$f['fecha_traslado'];
		$nom=$f['subcategoria'];
		$barras=$f['barra'];
		$categoria=$f['categoria'];
		$lbs=$f['lbs'];
		$und=$f['und'];
		$numf=$f['numero_fardo'];
		$cd=$conexion2->query("select * from detalle where registro='$idr'")or die($conexion2->error());
		$tf=0; $t=0;
		echo "<table class='tabla' border='1' cellpadding='10'>";
		echo "<tr>
		<td colspan='5'>$codigo: $nom | BARRA:<u>$barras</u><br>
		UND: $und | LBS: $lbs | NUMERO FARDO: $numf</td>
		</tr>";
		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>PRECIO</td>
			<td>CANTIDAD</td>
			<td>TOTAL</td>
		</tr>";
		while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
		{

			$art=$fcd['articulo'];
			$cant=$fcd['cantidad'];
			$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$precio=$fca['PRECIO_REGULAR'];
			$artd=$fca['ARTICULO'];
			$desd=$fca['DESCRIPCION'];
			$t=$cant * $precio;
			$tf=$tf + $t;
				echo "<tr>
			<td>$artd</td>
			<td>$desd</td>
			<td>$precio</td>
			<td>$cant</td>
			<td>$t</td>
		</tr>";
		}
		echo "<tr>
			<td colspan='4'>TOTAL:</td>
			<td>$tf</td>
		</tr></table>";

		if($estado==0)
		{
			$estado="EN PROCESO";
		}else
		{
			$estado='FINALIZADO';
		}
		if($fecha_t=='')
		{
			$fecha_t='- -';
		}
		

	}
}
?>


</body>
</html>