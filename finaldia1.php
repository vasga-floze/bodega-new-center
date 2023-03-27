<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	$_SESSION['usu']=$usua;
	$_SESSION['dia']=$dia;
	//echo "<script>alert('post')</script>";

	echo "<script language='JavaScript1.2' type='text/javascript'>
	if(confirm('SEGURO DESEA FINALIZAR LA PRODUCCION DEL DIA $dia'))
		{
			location.replace('finaldia1.php?i=2');
		}else
		{
			location.replace('producido.php');
		}
	</script>";

}else
{
	$usua=$_SESSION['usu'];
	$dia=$_SESSION['dia'];
	//echo "<script>alert('else $usua $dia')</script>";
	//echo "$usua - $dia 2";
	if($usua!='' and $dia!='')
	{
		$c=$conexion1->query("select * from consny.consecutivo_ci where consecutivo='PRODUCCION'")or die($conexion1->error());
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$consecutivo=$f['SIGUIENTE_CONSEC'];
		//saco consecutivo actualizar
		$a=date('y');
		$ex=explode("PRO-", $consecutivo);
		$queda=$ex[1] + 1;
		$queda=str_pad($queda,4,"0",STR_PAD_LEFT);
		$queda="PRO-$queda";
		/*$num=1;
		while($num!=0)
		{
		 $co=$conexion1->query("select * from consny.documento_inv where documento_inv='$consecutivo'")or die($conexion1->error());
		 $nco=$co->rowCount();
		 if($nco==0)
		 {
		 	$num=0;

		 }else
		 {
		 	$a=date('y');
			$ex=explode("-$a", $consecutivo);
			$queda=$ex[1] + 2;
			$queda=str_pad($queda,4,"0",STR_PAD_LEFT);
			$queda="$ex[0]-$a$queda";
			$a=date('y');
			$ex=explode("-$a", $consecutivo);
			$queda1=$ex[1] + 1;
			$queda1=str_pad($queda1,4,"0",STR_PAD_LEFT);
			$consecutivo="$ex[0]-$a$queda1";
			$num=1;

			//echo "<br>$consecutivo <- $queda";
		 }

		//fin bucle
		}*/
		$cr=$conexion2->query("select top 1 * from registro where fecha_documento='$dia' and estado='0' and usuario='$usua' and tipo='P' and codigo!=''")or die($conexion2->error());
	$ncr=$cr->rowCount();
	//echo "<script>alert('$ncr')</script>";
	if($ncr==0)
	{
		echo "<script>alert('ERROR!')</script>";
		echo "<script>location.replace('producido.php')</script>";
	}else
	{


	$conexion1->query("update consny.consecutivo_ci set SIGUIENTE_CONSEC='$queda' where consecutivo='PRODUCCION'")or die($conexion1->error());
	$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
	$usuario=$fcr['usuario'];
	$paquete=$fcr['paquete'];
	$fecha=$fcr['fecha_documento'];
		$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
		values('$paquete','$consecutivo','PRODUCCION','PRODUCCION DE LA FECHA: $fecha',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error());
	$cr1=$conexion2->query("select * from registro where fecha_documento='$dia' and estado='0' and usuario='$usua' and tipo='P' and codigo!=''")or die($conexion2->error());
	$ns=1;//linea_doc_inv
while($fcr1=$cr1->FETCH(PDO::FETCH_ASSOC))
{
	//echo "$ns,";
	$paquete=$fcr1['paquete'];
	$usuario=$fcr1['usuario'];
	$articulo=$fcr1['codigo'];
	$idr=$fcr1['id_registro'];
	$bode=$fcr1['bodega_produccion'];
	if($bode=='')
	{
	  $bode='SM00';
	}
	//$bode='SM00';//---CAMBIAR SI QUIEREN DONDE SE GUARDE
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
		'$ns','AJP',
		'$articulo',
		'$bode',
		'O',
		'D',
		'L',
		'1',
		'1',
		'1',
		'0',
		'0',
		'0')")or die($conexion1->error);
	$ns++;
	$conexion2->query("update registro set documento_producion='$consecutivo',estado='1' where id_registro='$idr'")or die($conexion2->error());
}
	
	//$conexion2->query("update registro set documento_producion='$consecutivo',estado='1' where usuario='$usua' and fecha_documento='$dia' and tipo='P' and estado='0'")or die($conexion2->error());
//insert transaccion_sys
$fechat=$_SESSION['dia'];
$usuariot=$_SESSION['usu'];
//echo "<script>alert('$fechat -$usuariot')</script>";
$ct=$conexion2->query("select id_registro,'INGRESO POR PRODUCCION' REF,'E' AS TIPO,codigo,1 cantidad,IsNULL(lbs,0) as PESO,fecha_documento,usuario,paquete,bodega_produccion,'registro' tabla,documento_producion from registro where fecha_documento='$fechat' and estado='1' and 
tipo='p' and usuario='$usuariot'")or die($conexion2->error());
$nct=$ct->rowCount();
while($fct=$ct->FETCH(PDO::FETCH_ASSOC))
{
	$registrot=$fct['id_registro'];
	$ref=$fct['REF'];
	$tipot=$fct['TIPO'];
	$pesot=$fct['PESO'];
	$fechat=$fct['fecha_documento'];
	$usuariot=$fct['usuario'];
	$paquetet=$fct['paquete'];
	$bodet=$fct['bodega_produccion'];
	$tablat=$fct['tabla'];
	$doc_invt=$fct['documento_producion'];
	$artt=$fct['codigo'];
	$conexion2->query("insert into transaccion_sys(registro,referencia,tipo_transaccion,articulo,cantidad,peso,fecha_documento,usuario,paquete,bodega,fecha_creacion,tabla,documento_inv) values('$registrot','$ref','$tipot','$artt','1','$pesot','$fechat','$usuariot','$paquetet','$bodet',getdate(),'$tablat','$doc_invt')")or die($conexion2->error());
}
//echo "<script>alert('transaccion_sys exito')</script>";
//fin insert transaccion_sys


$_SESSION['dia']='';
$_SESSION['usua']='';
echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
echo "<script>location.replace('producido.php')</script>";
	}//fin validacion de si hay registros
	}else//fin insert
	{
		echo "<script>('ERROR!')</script>";
		echo "<script>location.replace('producido.php')</script>";
	}
}
?>