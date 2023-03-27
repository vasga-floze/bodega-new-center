<?php
include("conexion.php");
$paquete=$_SESSION['paquete'];
$usuario=$_SESSION['usuario'];
if($_POST)
{
	extract($_REQUEST);
	$doc=$_SESSION['doc'];
	if($doc=="")
	{
		/*$co=$conexion1->query("select SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='TRASLADO'")or die($conexion1->error);
 			$fco=$co->FETCH(PDO::FETCH_ASSOC);
 			$conse=$fco['SIGUIENTE_CONSEC'];
 			$_SESSION['doc']=$conse; ya no*/

 			$con=$conexion2->query("select MAX(sessiones) as session from traslado")or die($conexion2->error);
 			$fco=$con->FETCH(PDO::FETCH_ASSOC);
 			$doc=$fco['session'] +1;
 			$_SESSION['doc']=$doc;
	}else
	{

		$c=$conexion2->query("select * from dbo.registro where barra='$barra'")or die($conexion2->error);
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO DE $barra')</script>";
			echo "<script>location.replace('traslados.php?i=2&&bcod=$bodeg')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$id=$f['id_registro'];
			$art=$f['codigo'];
			$org=$f['bodega'];
			$activo=$f['activo'];


	$k=$conexion2->query("select * from traslado where registro='$id'")or die($conexion2->error);//si se imlementa traslado de tienda a tienda agregar and sessiones=$doc
	$cd=$conexion2->query("select * from desglose where registro='$id'")or die($conexion2->error);
	$ncd=$cd->rowCount();
	if($ncd!=0)
	{
		echo "<script>alert('REGISTRO YA FUE DESGLOSADO')</script>";
		echo "<script>location.replace('traslados.php?bcod=$bodeg')</script>";
	}else
	{
	$nk=$k->rowCount();
	if($nk!=0)
	{
		//echo "<script>alert('REGISTRO YA FUE AGREGADO')</script>";
		//echo "<script>location.replace('traslados.php')</script>";
	}
		$cveri=$conexion2->query("select * from traslado where registro='$id' and sessiones='$doc'")or die($conexion2->error);
		$ncveri=$cveri->rowCount();
		if($ncveri!=0)
		{
			echo "<script>alert('REGISTRO YA FUE AGREGADO')</script>";
			echo "<script>location.replace('traslados.php?pbcod=$bodeg')</script>";
		}else
		{
			$k=$conexion2->query("select registro.id_registro,mesa.estado from registro inner join detalle_mesa on registro.id_registro=detalle_mesa.registro inner join mesa on detalle_mesa.mesa=mesa.id  where registro.id_registro='$id'")or die($conexion2->error);
			$nk=$k->rowCount();
			$fkm=$k->FETCH(PDO::FETCH_ASSOC);
			if($fkm['estado']=='1' or $fkm['estado']=='T')
			{
				echo "<script>alert('REGISTRO YA NO ESTA DISPONIBLE')</script>";
				echo "<script>location.replace('traslados.php?pbcod=$bodeg')</script>";
			}else
			{
				if($bodeg=='')
				{
					$docs=$_SESSION['doc'];
					$k=$conexion2->query("select max(id) as idd from traslado where sessiones='$docs'")or die($conexion2-error());
					$fk=$k->FETCH(PDO::FETCH_ASSOC);
					$idd=$fk['idd'];
					$q=$conexion2->query("select destino from traslado where id='$idd'")or die($conexion2->error());
					$fq=$q->FETCH(PDO::FETCH_ASSOC);
					$bodeg=$fq['destino'];
				}
			if($org!=$_SESSION['origen'])
			{
				$origen=$_SESSION['origen'];
				echo "<script>alert('BARRA: $barra NO SE ENCUENTRA EN LA BODEGA: $origen')</script>";
				echo "<script>location.replace('traslados.php?bcod=$bodeg&&i=2')</script>";
			}else
			{

			if($activo=='0')
			{
				echo "<script>alert('FARDO NO DISPONIBLE')</script>";
				echo "<script>location.replace('traslados.php')</script>";
			}else
			{
				$conexion2->query("insert into dbo.traslado(registro,destino,origen,documento_inv,paquete,usuario,estado,sessiones,articulo,fecha_ingreso)values('$id','$bodeg','$org','- -','$paquete','$usuario','0','$doc','$art',getdate()) ")or die($conexion2->error);
			echo "<script>location.replace('traslados.php?bcod=$bodeg&&i=2')</script>";
			}
			
		}
		}
		}
		}
	}
	}
}else
{
	echo "<script>location.replace('traslados.php')</script>";
}
?>