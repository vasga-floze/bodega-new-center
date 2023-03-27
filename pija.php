<?php
include("conexion.php");
if($_SESSION['usuario']=='apopa1')
{
	//$_SESSION['liquidacion']=3212;
	echo "<script>location.replace('liquidaciones.php')</script>";
}

$c=$conexion2->query("select registro.barra,EXIMP600.consny.ARTICULO.CLASIFICACION_1,EXIMP600.consny.ARTICULO.CLASIFICACION_2  from registro inner join EXIMP600.consny.articulo on registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO
where CONVERT(date,registro.fecha_eliminacion)='2021-10-29' and registro.bodega='CA22' and EXIMP600.consny.ARTICULO.CLASIFICACION_1='INSUMO'")or die($conexion2->error());
$n=$c->fetchAll();
$n=count($n);

echo $n;
?>