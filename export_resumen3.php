<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
ini_set('max_execution_time', 9000);

    try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }

    try {
        $conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("ERROR SE PERDIO CONEXION CON EL SERVIDOR: " . $e->getMessage());
    }
    session_start();

$d=$_GET['d'];
$h=$_GET['h'];
$codigo=$_GET['codigo'];
$barra=$_GET['barra'];
$barra1=$barra;
if($codigo!='' and $d!='' and $h!='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro,registro.fecha_desglose from registro inner join desglose on registro.id_registro=desglose.registro where registro.codigo='$codigo' and registro.fecha_desglose between '$d' and '$h' and registro.bodega='$bode_r'  group by desglose.registro,registro.fecha_desglose ORDER BY registro.fecha_desglose desc
")or die($conexion2->error());
		$b='';
	}else if($codigo=='' and $d!='' and $h!='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro,registro.fecha_desglose,registro.codigo from registro inner join desglose on registro.id_registro=desglose.registro where  registro.fecha_desglose between '$d' and '$h' and registro.bodega='$bode_r'  group by desglose.registro,registro.fecha_desglose,registro.codigo ORDER BY registro.fecha_desglose,registro.codigo")or die($conexion2->error());
		$b='';
	}else if($codigo=='' and $d=='' and $h=='' and $barra!='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.barra='$barra'  and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
		$b='';
	}else if($barra=='' and $codigo=='' and $d=='' and $h=='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro,registro.fecha_desglose,registro.codigo from registro inner join desglose on registro.id_registro=desglose.registro where registro.bodega='$bode_r' group by desglose.registro,registro.fecha_desglose,registro.codigo ORDER BY registro.fecha_desglose,registro.codigo
")or die($conexion2->error());
		$b='';
	}else if($codigo=='' and $barra==''and $h=='' and $d!='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.fecha_desglose='$d' and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}else if($codigo!='' and $barra!='' and $d!='' and $h!='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.barra='$barra'  and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}else if($codigo!='' and $barra!='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.barra='$barra'  and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
		$b='';
	}else if($codigo!='' and $d=='' and $h=='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro,registro.fecha_desglose,registro.codigo from registro inner join desglose on registro.id_registro=desglose.registro where registro.codigo='$codigo'  and registro.bodega='$bode_r'  group by registro.fecha_desglose,registro.codigo,desglose.registro ORDER BY registro.fecha_desglose,registro.codigo
")or die($conexion2->error());
		$b='';
	}
	if($barra!='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.barra='$barra'  and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}
	if($codigo!='' and $d!='' and $h=='')
	{
		$bode_r=$_GET['bodega'];
		$c=$conexion2->query("select desglose.registro from registro inner join desglose on registro.id_registro=desglose.registro where registro.fecha_desglose='$d'  and registro.codigo='$codigo' and registro.bodega='$bode_r'  group by desglose.registro ORDER BY desglose.registro desc
")or die($conexion2->error());
	}else if($h!='' and $codigo=='' and $barra=='' and $d=='')
	{
		$bode_r=$_GET['bodega'];
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


$n=$c->rowCount();
if($n==0)
{
	echo "<script>NO SE ENCONTRO NINGUN DESGLOSE</script>";
}else
{

	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=RESUMEN-DESGLOSES-'.$d.'-'.$h.'BODEGA: '.$_GET['bodega'].'.xls');

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
		<td>$art</td>
		<td>$des</td>
		<td>$fecha</td>
		<td>$desglosado</td>
		<td>$digitado</td>
		<td>$barra</td>
	</tr>";
	}
}
?>

<script>
	window.close();
</script>
</body>
</html>