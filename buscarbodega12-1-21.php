<?php
if($_POST)
{
	extract($_REQUEST);
	include("conexion.php");
if($op==1)
 {
	$c=$conexion1->query("select * from consny.bodega where bodega='$codb' and nombre not like '%(N)%'")or die($conexion1->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO LA BODEGA $cod O NO SE ENCUENTRA DISPONIBLE')</script>";
		echo"<script>location.replace('traslados.php')</script>";
	}else
	{
		echo"<script>location.replace('traslados.php?bcod=$codb')</script>";

	}

 }else if($op==2)
 {
 	$origenes=$_SESSION['origen'];
 	if($origenes==$codb)
 	{
 		echo "<script>alert('TRASLADO NO VALIDO LA BODEGA ORIGEN ES LA MISMA BODEGA DESTINO')</script>";
 		echo "<script>location.replace('traslados.php')</script>";
 	}
 		$c=$conexion1->query("select * from consny.BODEGA where BODEGA='$codb' and NOMBRE='$nomb' and NOMBRE NOT LIKE '%(N)%'")or die($conexion1->error);
 		$n=$c->rowCount();
 		if($n==0)
 		{
 			echo "<script>alert('BODEGA $codb $nomb NO SE ENCUENTRA NINGUN REGISTRO O NO ESTA ACTIVO')</script>";
 			echo"<script>location.replace('traslados.php?bcod=$codb')</script>";
 		}else
 		{
 			if($_SESSION['doc']=="")
 			{
 				/*$co=$conexion1->query("select SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='TRASLADO'")or die($conexion1->error);
 			$fco=$co->FETCH(PDO::FETCH_ASSOC);
 			$conse=$fco['SIGUIENTE_CONSEC'];
 			$_SESSION['doc']=$conse;*/
 			$co=$conexion2->query("select MAX(sessiones) as session from traslado")or die($conexion2->error);
 			$fco=$co->FETCH(PDO::FETCH_ASSOC);
 			$doc=$fco['session']+ 1;
 			$_SESSION['doc']=$doc;

 			echo "<script>location.replace('traslados.php?i=2&&bcod=$codb')</script>";
 			}else
 			{
 				echo "<script>location.replace('traslados.php?i=2&&bcod=$codb')</script>";
 			}
 	
 		}
 }
}

	//para sacar el documento yincremetar uno mas
 		//$sum=explode("-",$conse);
 		//$consek=$sum[1] + 1;

 		//$consek=str_pad($consek,10,"0",STR_PAD_LEFT);
//funcion para que agrege los cero a la izquierda

 		//echo "sum:$sum[1],esta:$conse queda:$sum[0]-$consek";

?>