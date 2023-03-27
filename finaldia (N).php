<?php
include("conexion.php");
error_reporting(0);
if($_POST)
{
	extract($_REQUEST);
	$_SESSION['dia']=$dia;
	$_SESSION['usua']=$usua;

	echo "<script>
	if(confirm('SEGURO DESEA FINALIZAR LA PRODUCION DEL DIA $dia'))
	{
		location.replace('finaldia.php?i=1');
	}else
	{
		location.replace('producido.php');
	}
	</script>
	";
}else
{

$i=$_GET['i'];
if($i==1)
{
	if($_SESSION['dia']!='' and $_SESSION['usua']!='')
	{
		$dia=$_SESSION['dia'];
		$usua=$_SESSION['usua'];
		$con=$conexion1->query("select * from dbo.USUARIOBODEGA where USUARIO='$usua'")or die($conexion1->error());
		$fcon=$con->FETCH(PDO::FETCH_ASSOC);
		$paque=$fcon['paquete'];
		//$paque=$_SESSION['paquete'];
$con=$conexion1->query("select * from consny.DOCUMENTO_INV where  FECHA_DOCUMENTO='$dia' and CONSECUTIVO='PRODUCCION' and PAQUETE_INVENTARIO='$paque'")or die($conexion1->error);
$ncon=$con->rowCount();
if($ncon==0)
{

$c=$conexion1->query("select CONSECUTIVO,SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='PRODUCCION'")or die($conexion1->error);
//saco documento para acetualizar documento_inv
$f=$c->FETCH(PDO::FETCH_ASSOC);
if($f['SIGUIENTE_CONSEC']!="")
{
	$esta=$f['SIGUIENTE_CONSEC'];



$a=date('y');
$ex=explode("-$a", $esta);
$queda=$ex[1] + 1;
$queda=str_pad($queda,4,"0",STR_PAD_LEFT);
$docp="$ex[0]-$a$queda"; //quedaria en consecutivo
$num=1;
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
		$ex=explode("-$a", $esta);
		$queda=$ex[1] + 1;
		$queda=str_pad($queda,4,"0",STR_PAD_LEFT);
		$docp="$ex[0]-$a$queda";
	}
}
$conexion1->query()or die($conexion1->error());

}
$num=1;
while($num!=0)
{
$consu=$conexion1->query("select * from consny.documento_inv where DOCUMENTO_INV='$esta'")or die($conexion1->error);
$num=$consu->rowCount();
if($num!=0)
{
	$n=explode("PRO-$a", $esta);
	$esta=$n[1] + 1;
	$queda=$n[1] + 2;
	$esta=str_pad($esta,4,"0",STR_PAD_LEFT);
	$queda=str_pad($queda,4,"0",STR_PAD_LEFT);
	$esta="$n[0]-$a$esta";
	$docp="$n[0]-$queda";
}

}
$conexion1->query("update consny.CONSECUTIVO_CI set SIGUIENTE_CONSEC='$docp' where CONSECUTIVO='PRODUCCION'")or die($conexion1->error);
$k=$conexion2->query("select * from registro where fecha_documento='$dia' and usuario='$usua' and estado='0' and tipo='P'")or die($conexion2->error);
$fk=$k->FETCH(PDO::FETCH_ASSOC);
$fecha_producion=$fk['fecha_documento'];
$paquete=$fk['paquete'];
$consecutivo='PRODUCCION';
$usuarios=$fk['usuario'];
$referencia='PRODUCION DEL DIA $fecha_producion';
	$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$esta','PRODUCCION','PRODUCCION DE LA FECHA: $fecha_producion',getdate(),'$fecha_producion','N','$usuarios','','N','0')")or die($conexion1->error);
}else
{
	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
	$esta=$fcon['DOCUMENTO_INV'];
}
//
		








$cns=$conexion1->query("select COUNT(*) as ns from consny.LINEA_DOC_INV where DOCUMENTO_INV='$esta'")or die($conexion1->error);
$fcns=$cns->FETCH(PDO::FETCH_ASSOC);
$ns=$fcns['ns'] + 1;//<--numero de secuencia


//echo "<script>alert('entra $usua - $dia 1')</script>";
$kk=$conexion2->query("select * from registro where fecha_documento='$dia' and usuario='$usua' and estado='0' and tipo='P'")or die($conexion2->error);
while($fk1=$kk->FETCH(PDO::FETCH_ASSOC))
{
	$usuar=$fk1['usuario'];
	$paquete=$fk1['paquete'];
	$articulo=$fk1['codigo'];
	

	$cbusu=$conexion1->query("select * from dbo.USUARIOBODEGA where USUARIO='$usuar'")or die($conexion1->error);
	$fcbusu=$cbusu->FETCH(PDO::FETCH_ASSOC);
	//$bode=$fcbusu['BODEGA'];
	
	$bode='SM00';//<-- DESCOMENTAR ARRIBA--------------

//echo "<script>alert('$paquete p - $articulo a - u $usuar-doc $esta - $bode - $ns 2')</script>";

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
}

//--------------------------------------------

	$conexion2->query("update registro set estado='1',documento_producion='$esta' where fecha_documento='$dia' and usuario='$usua' and estado='0' and tipo='P'")or die($conexion2->error);
}
		
		$_SESSION['dia']='';
		echo "<script>location.replace('producido.php')</script>";
	}



}
?>