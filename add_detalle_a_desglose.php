<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	$idr=$_SESSION['registro'];
	echo "$idr - $id_detalle";
	$cd=$conexion2->query("select * from detalle where id='$id_detalle'")or die($conexion2->error());
	$ncd=$cd->rowCount();
	if($ncd==0)
	{
		echo "<script>alert('NO SE PUDO REALIZAR LA TRANFERENCIA DE LOS DATOS')</script>";
	echo "<script>location.replace('desglose.php')</script>";

	}else
	{
		$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
		$art=$fcd['articulo'];
		$cant=$fcd['cantidad'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$precio=$fca['PRECIO_REGULAR'];
		$cdes=$conexion2->query("select * from desglose where articulo='$art' and registro='$idr'")or die($conexion2->error());
		$ncdes=$cdes->rowCount();
		if($ncdes==0)
		{
			$paquete=$_SESSION['paquete'];
			$usuario=$_SESSION['usuario'];
		$conexion2->query("insert into desglose(registro,articulo,cantidad,paquete,usuario,fecha,precio)values('$idr','$art','$cant','$paquete','$usuario',getdate(),'$precio')")or die($conexion2->error());
	echo "<script>location.replace('desglose.php')</script>";
		}else
		{
		  echo"<script>alert('NO SE PUEDE AGREGAR ARTICULO: $art YA FUE AGREGADOS AL DESGLOSE ANTES')</script>";
		echo "<script>location.replace('desglose.php')</script>";


		}
	}
}else
{
	echo "<script>location.replace('desglose.php')</script>";
}
?>