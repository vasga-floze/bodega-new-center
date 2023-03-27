<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	$c=strlen($consecutivo);
	$e=explode("ENV-", $consecutivo);

	echo "$e[1]<br>";
	$n=strlen($e[1]);
	$ee=substr($consecutivo,0);
	$l="$ee[0]$ee[1]$ee[2]$ee[3]";
	$k=$conexion1->query("select * from consny.documento_inv where documento_inv='$consecutivo'")or die($conexion1->error());
	$nk=$k->rowCount();
	if($nk!=0)
	{
		echo "<script>alert('CONSECUTIVO DE VENTA YA FUE UTILIZADO')</script>";
		echo "<script>location.replace('venta.php')</script>";
	}
	if($l=="ENV-")
	{
		if($n==7)
		{
			if(is_numeric($e[1]))
			{
				$nume=$e[1] + 1;
				$nume=str_pad($nume,7,"0",STR_PAD_LEFT);
				$nume="ENV-$nume";
				echo "$nume"; 
				$venta=$_SESSION['venta'];
				$c=$conexion2->query("select * from venta where sessiones='$venta'")or die($conexion2->error());
				$nc=$c->rowCount();
				if($nc==0)
				{
					echo "<script>alert('SE DIO UN ERROR INTENTELO NUEVAMENTE')</script>";
					echo "<script>location.replace('venta.php')</script>";
				}else
				{
					$fc=$c->FETCH(PDO::FETCH_ASSOC);
					$cliente=$fc['cliente'];
					$fecha=$fc['fecha'];
					$usuarios=$fc['usuario'];
					$paquete=$fc['paquete'];
					$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$consecutivo','VENTA','VENTA A $cliente',getdate(),'$fecha','N','$usuarios','','N','0')")or die($conexion1->error());

		$c=$conexion2->query("select * from venta where sessiones='$venta' and precio!='' and registro !=''")or die($conexion2->error());
		$ns=1;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			
			//echo "<script>alert('$ns')</script>";
			$idr=$f['registro'];
			$precio=$f['precio'];
			//echo "<script>alert('$precio')</script>";
		$k=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
		$nk=$k->rowCount();
		if($nk!=0)
		{
			
			$fk=$k->FETCH(PDO::FETCH_ASSOC);
			$articulo=$fk['codigo'];
			$usuario=$_SESSION['usuario'];
			$paquete=$_SESSION['paquete'];

			$bode=$fk['bodega'];

$conexion1->query("insert into consny.LINEA_DOC_INV(PAQUETE_INVENTARIO,
DOCUMENTO_INV,
LINEA_DOC_INV,
AJUSTE_CONFIG,
ARTICULO,
BODEGA,
TIPO,SUBTIPO,
SUBSUBTIPO,
CANTIDAD,
COSTO_TOTAL_LOCAL,COSTO_TOTAL_DOLAR,PRECIO_TOTAL_LOCAL,PRECIO_TOTAL_DOLAR,NoteExistsFlag) 
values('$paquete',
'$consecutivo',
'$ns','~VV~',
'$articulo',
'$bode',
'V',
'D',
'L',
'1',
'1',
'1',
'$precio',
'$precio',
'0')")or die($conexion1->error);
$ns=$ns + 1;
$conexion2->query("update registro set activo='0' where id_registro='$idr'")or die($conexion2->error());
		}
		}
	$conexion1->query("update consny.consecutivo_ci set SIGUIENTE_CONSEC='$nume' where consecutivo='venta'")or die($conexion1->error());
	$venta=$_SESSION['venta'];

	$conexion2->query("update venta set documento_inv='$consecutivo',observacion='$observacion' where sessiones='$venta'")or die($conexion2->error());
	//TRANSACCION_SYS
	$ct=$conexion2->query("select venta.registro,'SALIDA POR VENTA DE FARDO' REF,'S' tipo,registro.codigo,-1 cantidad,
(ISNULL(registro.lbs,0)+ISNULL(registro.peso,0))*-1 peso,venta.fecha,venta.usuario,
venta.paquete,registro.bodega,GETDATE() fecha_crea,'venta' tabla,venta.documento_inv from 
venta inner join registro on venta.registro=registro.id_registro where venta.sessiones='$venta'")or die($conexion2->error());
	while($fct=$ct->FETCH(PDO::FETCH_ASSOC))
	{
		$registro=$fct['registro'];
		$ref=$fct['REF'];
		$tipo=$fct['tipo'];
		$art=$fct['codigo'];
		$cant=$fct['cantidad'];
		$peso=$fct['peso'];
		$fecha=$fct['fecha'];
		$usu=$fct['usuario'];
		$paq=$fct['paquete'];
		$fecha_crea=$fct['fecha_crea'];
		$tabla=$fct['tabla'];
		$doc=$fct['documento_inv'];
		$bod=$fct['bodega'];
		$conexion2->query("insert into transaccion_sys(registro,referencia,tipo_transaccion,articulo,cantidad,peso,fecha_documento,usuario,paquete,bodega,fecha_creacion,tabla,documento_inv) values('$registro','$ref','$tipo','$art','$cant','$peso','$fecha','$usu','$paq','$bod','$fecha_crea','$tabla','$doc')")or die($conexion2->error());
	}

	//FIN TRANSACCION_SYS
	$_SESSION['venta']="";
echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
echo "<script>location.replace('imprimir_venta.php?id=$venta')</script>";

		}




			}else
			{
				echo "<script>alert('CONSECUTIVO DE VENTA NO VALIDO')</script>";
				echo "<script>location.replace('venta.php')</script>";
			}
		}else
			{
				echo "<script>alert('CONSECUTIVO DE VENTA NO VALIDO')</script>";
				echo "<script>location.replace('venta.php')</script>";
			}
	}else
			{
				echo "<script>alert('CONSECUTIVO DE VENTA NO VALIDO')</script>";
				echo "<script>location.replace('venta.php')</script>";
			}
}
?>