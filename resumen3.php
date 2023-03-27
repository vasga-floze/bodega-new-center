<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function enviar()
		{
			document.form.submit();
		}
		function vaciar()
		{
			location.replace('resumen3.php');
		}
		function load()
		{
			$("#loader").show();
			$("#loader").hide();
		}
	</script>
</head>
<img src="load.gif" width="110%" id="loader" height="110%;" style="position: fixed; margin-left: -4%; margin-top: -1%; display: none;">
<?php
error_reporting(0);
$b=$_GET['b'];
$d=$_GET['d'];
$h=$_GET['h'];
$codigo=$_GET['codigo'];
$barra=$_GET['barra'];
$art=$_GET['art'];
include("conexion.php");
$art=$_GET['art'];
$con=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
$fcon=$con->FETCH(PDO::FETCH_ASSOC);
$arti=$fcon['ARTICULO'];
$de=$fcon['DESCRIPCION'];

$user=$_SESSION['usuario'];
$ct=$conexion1->query("select consny.bodega.nombre from usuariobodega inner join consny.bodega on usuariobodega.bodega=consny.bodega.bodega where usuariobodega.usuario='$user'")or die($conexion1->error());
$fct=$ct->FETCH(PDO::FETCH_ASSOC);
$tienda=$fct['nombre'];
?>
<body>
	<h3 style="text-align: center; text-decoration: underline;">REPORTE DESGLOSES DE: <?php echo "$tienda";?> </h3>
	<button onclick="vaciar()">VACIAR</button>
<form method="POST" action="buscadesglo.php" name="form" >
	<input type="text" name="codi" class="text" style="width: 17%;" placeholder="ARTICULO" onchange="enviar()" value='<?php echo "$arti";?>'>
	<input type="text" name="nom" class="text" style="width: 40%;" placeholder="DESCRIPCION" value='<?php echo "$de";?>'>
</form>


<form method="POST" style="margin-top: 2.5%;">
	<input type="hidden" name="codigo" value='<?php echo "$art";?>'>
	DESDE: <input type="date" name="d" class="text" style="width: 20%;">
	HASTA: <input type="date" name="h" class="text" style="width: 20%;">

	<input type="text" name="barra" class="text" style="width: 20%;" placeholder="CODIGO DE BARRA">
	<input type="submit" name="btn" value="BUSCAR" onclick="load()" class="boton3">
</form><br>

<?php
$usu=$_SESSION['usuario'];
if($_POST)
{
	extract($_REQUEST);
	$barra1=$barra;
	if($codigo!='' and $d!='' and $h!='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro,registro.fecha_desglose from registro inner join desglose on registro.id_registro=desglose.registro where registro.codigo='$codigo' and registro.fecha_desglose between '$d' and '$h' and registro.bodega='$bode_r'  group by desglose.registro,registro.fecha_desglose ORDER BY registro.fecha_desglose desc
")or die($conexion2->error());
		$b='';
	}else if($codigo=='' and $d!='' and $h!='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro,registro.fecha_desglose,registro.codigo from registro inner join desglose on registro.id_registro=desglose.registro where  registro.fecha_desglose between '$d' and '$h' and registro.bodega='$bode_r'  group by desglose.registro,registro.fecha_desglose,registro.codigo ORDER BY registro.fecha_desglose,registro.codigo")or die($conexion2->error());
		$b='';
	}else if($codigo=='' and $d=='' and $h=='' and $barra!='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.barra='$barra'  and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
		$b='';
	}else if($barra=='' and $codigo=='' and $d=='' and $h=='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro,registro.fecha_desglose,registro.codigo from registro inner join desglose on registro.id_registro=desglose.registro where registro.bodega='$bode_r' group by desglose.registro,registro.fecha_desglose,registro.codigo ORDER BY registro.fecha_desglose,registro.codigo
")or die($conexion2->error());
		$b='';
	}else if($codigo=='' and $barra==''and $h=='' and $d!='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.fecha_desglose='$d' and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}else if($codigo!='' and $barra!='' and $d!='' and $h!='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.barra='$barra'  and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}else if($codigo!='' and $barra!='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.barra='$barra'  and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
		$b='';
	}else if($codigo!='' and $d=='' and $h=='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro,registro.fecha_desglose,registro.codigo from registro inner join desglose on registro.id_registro=desglose.registro where registro.codigo='$codigo'  and registro.bodega='$bode_r'  group by registro.fecha_desglose,registro.codigo,desglose.registro ORDER BY registro.fecha_desglose,registro.codigo
")or die($conexion2->error());
		$b='';
	}
	if($barra!='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.barra='$barra'  and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}
	if($codigo!='' and $d!='' and $h=='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.fecha_desglose='$d'  and registro.codigo='$codigo' and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}else if($h!='' and $codigo=='' and $barra=='' and $d=='')
	{
		$bode_r=$_SESSION['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.fecha_desglose='$h' and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}
	/*if($b=="")
	}
	/*if($b=="")
	}





	/*if($b=="")
	}
	
	{
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where  desglose.usuario='$usu'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}*/
}

$n=$c->rowCount();
if($n==0)
{
	echo "alert('NO SE ENCONTRO NINGUN DESGLOSE')";
}else
{
	echo "<a href='export_resumen3.php?codigo=$codigo&&d=$d&&h=$h&&barra=$barra&&bodega=$bode_r' target='_blank' style='background-color: green; color: white; padding:0.5%; border:double; border-color:red; margin-left:2%;'>Exportar a Excel</a><br><br>";
	echo "<table class='tabla' border='1' cellpadding='10' class='10' style='margin-left:2%;'>";
	echo "<tr>
		<td>#</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>FECHA DESGLOSE</td>
		<td>DESGLOSADO POR</td>
		<td>DIGITADO POR</td>
		<td>CODIGO BARRA</td>
	</tr>";
	$b='';
	$numero=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$f['registro'];
		$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$cod=$fcr['codigo'];
		$fecha=$fcr['fecha_desglose'];
		$desglosado=$fcr['desglosado_por'];
		$digitado=$fcr['digita_desglose'];
		$barra=$fcr['barra'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$numero++;
		$art=$fca['ARTICULO'];
		$des=$fca['DESCRIPCION'];
		echo "<tr>
		<td>$numero</td>
		<td><a href='verdesglo.php?id=$idr&&barra=$barra1' style='color:black; text-decoration:none;'  target='_blank'>$art</a></td>
		<td><a href='verdesglo.php?id=$idr&&barra=$barra1' style='color:black; text-decoration:none;'  target='_blank'>$des</a></td>
		<td>$fecha</td>
		<td>$desglosado</td>
		<td>$digitado</td>
		<td>$barra</td>
	</tr>";
	}
}
?>
</body>
</html>