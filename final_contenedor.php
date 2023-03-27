<?php
include("conexion.php");
$contenedor=$_SESSION['contenedor'];
$fecha=$_SESSION['fecha'];
if($_SESSION['fecha']=="" or $_SESSION['contenedor']=='')
{
	echo "<script>location.replace('contenedor.php')</script>";
}else
{
$k=$conexion2->query("select * from registro  where fecha_documento='$fecha' and contenedor='$contenedor' and tipo='C1'")or die($conexion2->error);
$nk=$k->rowCount();
if($nk==0)
{
	echo "<scrtipt>alert('ERROR NO SE PUDO FINALIZAR EL CONTENEDOR')</script>";
	echo "<script>location.replace('contenedor.php')</script>";
}else
{
	$fk=$k->FETCH(PDO::FETCH_ASSOC);
	$usuario=$fk['usuario'];
	$paquete=$fk['paquete'];
	$bod=$fk['bodega'];
	$conse=$conexion1->query("select CONSECUTIVO,SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='COMPRA'
")or die($conexion1->error);
	$fconse=$conse->FETCH(PDO::FETCH_ASSOC);
	$esta=$fconse['SIGUIENTE_CONSEC'];
	$est=explode("-",$esta);
	$queda=$est[1] +1;
	$queda=str_pad($queda,10,"0",STR_PAD_LEFT);
	$queda="$est[0]-$queda";
	$num=1;
	while($num!=0)
	{
		$qu=$conexion1->query("select * from consny.documento_inv where documento_inv='$esta'")or die($conexion1->error);
		$nqu=$qu->rowCount();
		if($nqu!=0)
		{
			$esta=$est[1]+1;
			$queda=$est[1]+2;
			$esta=str_pad($esta,10,"0",STR_PAD_LEFT);
			$queda=str_pad($queda,10,"0",STR_PAD_LEFT);
		$esta="$est[0]-$esta";
		$queda="$est[0]-$queda";
		$num=1;

		}else
		{
			$num=0;
		}
	}
	$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,FECHA_HORA_APROB,APROBADO,NoteExistsFlag)
values('$paquete',
'$esta','COMPRA','CONTENEDOR $contenedor',getdate(),'$fecha','N','$usuario','','','N','0')")or die($conexion1->error);
	$conexion1->query("update consny.CONSECUTIVO_CI set SIGUIENTE_CONSEC='$queda' where CONSECUTIVO='COMPRA'")or die($conexion1->error);

	$query=$conexion2->query("select * from registro where fecha_documento='$fecha' and contenedor='$contenedor' and tipo='cd' and estado='0' 
")or die($conexion2->error);
	$ns=1;
	while($fquery=$query->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$fquery['codigo'];
		$cant=1;
		//$bod=$fquery['bodega'];
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
'$esta',
'$ns','~OO~',
'$art',
'$bod',
'O',
'D',
'L',
'$cant',
'1',
'1',
'0',
'0',
'0')")or die($conexion1->error);
$ns++;

	}








	$conexion2->query("update registro set estado='1',documento_inv_contenedor ='$esta' where contenedor='$contenedor' and fecha_documento='$fecha' and estado='0'")or die($conexion2->error);
	//insert transaccion_sys
	$ct=$conexion2->query("select id_registro,concat('INGRESO POR CONTENEDOR(',contenedor,')') referencia,'E' tipo,
codigo,1 cantidad,peso,fecha_documento,usuario,paquete,bodega_produccion,GETDATE(),'registro' as tabla,documento_inv_contenedor from registro
where tipo='CD' and contenedor='$contenedor' and fecha_documento='$fecha' and estado='1'")or die($conexion2->error());
	while($fct=$ct->FETCH(PDO::FETCH_ASSOC))
	{
		$registro=$fct['id_registro'];
		$ref=$fct['referencia'];
		$tipo=$fct['tipo'];
		$art=$fct['codigo'];
		$cant=$fct['cantidad'];
		$peso=$fct['peso'];
		$fecha=$fct['fecha_documento'];
		$usu=$fct['usuario'];
		$paq=$fct['paquete'];
		$bod=$fct['bodega_produccion'];
		$tabla=$fct['tabla'];
		$doc=$fct['documento_inv_contenedor'];
		$conexion2->query("insert into transaccion_sys(registro,referencia,tipo_transaccion,articulo,cantidad,peso,fecha_documento,usuario,paquete,bodega,fecha_creacion,tabla,documento_inv) values('$registro','$ref','$tipo','$art','$cant','$peso','$fecha','$usu','$paq','$bod',getdate(),'$tabla','$doc')")or die($conexion2->error());



	}
	//fin transaccion_sys

	$_SESSION['contenedor']="";
	$_SESSION['fecha']="";
	echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";
	echo "<script>location.replace('contenedor.php')</script>";

}

}

?>