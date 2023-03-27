<?php
include("conexion.php");
if($_POST)
{
extract($_REQUEST);
if($_SESSION['inv']!='')
{
$cr=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
$ncr=$cr->rowCount();
if($ncr==0)
{
	echo "<script>alert('NO SE ENCONTRO REGISTRO DE: $barra')</script>";
}else
{
	$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
	$idr=$fcr['id_registro'];
	$activo=$fcr['activo'];
	$u=$_SESSION['usuario'];
	$cv=$conexion2->query("select * from inventario where registro='$idr' and usuario='$u' and sessiones='$inv'")or die($conexion2->error());
	$ncv=$cv->rowCount();
	if($ncv==0)
	{
		$inv=$_SESSION['inv'];
		$bodega_actual=$fcr['bodega'];
		$u=$_SESSION['usuario'];
		$ci=$conexion2->query("select top 1 * from inventario where sessiones='$inv' and usuario='$u'")or die($conexion2->error());
		$fci=$ci->FETCH(PDO::FETCH_ASSOC);
		$bodega=$fci['bodega'];

		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		//echo "<script>alert('$bodega_actual $idr')</script>";
		if($activo==0 and $activo!='')
		{
			echo "<script>alert('FARDO NO SE ENCUENTRA DISPONIBLE YA FUE TRABAJADO O VENDIDO')</script>";
			$conexion2->query("insert into inventario(bodega,digita,registro,sessiones,fecha_ingreso,estado,usuario,paquete,bodega_actual) values('$bodega','','$idr','$inv',getdate(),'0','$usuario','$paquete','$bodega_actual')")or die($conexion2->error());
			echo "<script>location.replace('inventario.php')</script>";
		}else
		{

			$conexion2->query("insert into inventario(bodega,digita,registro,sessiones,fecha_ingreso,estado,usuario,paquete,bodega_actual) values('$bodega','','$idr','$inv',getdate(),'0','$usuario','$paquete','$bodega_actual')")or die($conexion2->error());
		echo "<script>location.replace('inventario.php')</script>";
		}
		

	}else
	{
		echo "<script>alert('$barra YA FUE AGREGADO ANTES!')</script>";
		$inv=$_SESSION['inv'];
		$bodega=$fci['bodega'];
			$bodega_actual=$fcr['bodega'];
		$cr2=$conexion2->query("select bodega from registro where id_registro='$idr'")or die($conexion2->error());
		$fcr2=$cr2->FETCH(PDO::FETCH_ASSOC);
		$bodega_actual=$fcr2['bodega'];
		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		$conexion2->query("insert into inventario(bodega,digita,registro,sessiones,fecha_ingreso,estado,usuario,paquete,bodega_actual) values('$bodega','','$idr','$inv',getdate(),'0','$usuario','$paquete','$bodega_actual')")or die($conexion2->error());
		echo "<script>location.replace('inventario.php')</script>";
	}
}



}else
{
	echo "<script>alert('ERROR: INTENTELO NUEVAMENTE')</script>";
	echo "<script>location.replace('inventario.php')</script>";
}
//fin post
}else
{
	echo "<script>location.replace('inventario.php')</script>";
}
?>