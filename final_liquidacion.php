<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#div").hide();
		})
	</script>
	
</head>
<body>
<div style="position: fixed; width: 100%; height: 100%; background-color: white;" id="div">
	<img src="loadf.gif" style="margin-left: 45%; margin-top: 5%;">
</div>



<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	//consecutivo de consumo

	$q=$conexion1->query("select siguiente_consec from consny.consecutivo_ci where consecutivo='LIQ-ajn'")or die($conexion1->error());
	$fq=$q->FETCH(PDO::FETCH_ASSOC);
	$consumo=$fq['siguiente_consec'];
	$ecomsumo=explode('L-AJN-', $consumo);
	$num=$ecomsumo[1] +1;
	$num=str_pad($num,7,"0",STR_PAD_LEFT);
	$qconsumo="L-AJN-$num";
	$k=1;
	while($k==1)
	{
		$cv=$conexion1->query("select * from consny.documento_inv where documento_inv='$consumo'")or die($conexion1->error());
		$ncv=$cv->rowCount();
		if($ncv!=0)
		{
			$num++;
			$num=str_pad($num,7,"0",STR_PAD_LEFT);
			$consumo="L-AJN-$num";
			$num++;
			$num=str_pad($num,7,"0",STR_PAD_LEFT);
			$qconsumo="L-AJN-$num";
			$k=1;

		}else
		{
			$k=0;
		}
	}
	echo "$consumo | CON-$qconsumo<br>";

	//fin cosecutivo consumo

//consecutivo ingreso
	$q1=$conexion1->query("select siguiente_consec from consny.consecutivo_ci where consecutivo='LIQ-ajp'")or die($conexion1->error());
	$fq1=$q1->FETCH(PDO::FETCH_ASSOC);
	$ing=$fq1['siguiente_consec'];
	$eing=explode('L-AJP-', $ing);
	$num1=$eing[1] + 1;
	$num1=$num1=str_pad($num1,7,"0",STR_PAD_LEFT);
	$qing="L-AJP-$num1";
	$k=1;
	while($k==1)
	{
		$cvi=$conexion1->query("select * from consny.documento_inv where documento_inv='$ing'")or die($conexion1->error());
		$ncvi=$cvi->rowCount();
		if($ncvi!=0)
		{
			$num1++;
			$num1=str_pad($num1,7,"0",STR_PAD_LEFT);

			$ing="L-AJP-$num1";
			$k=1;
			$num1++;
			$num1=str_pad($num1,7,"0",STR_PAD_LEFT);
			$qing="L-AJP-$num1";

		}else
		{
			$k=0;
		}
	}
//fin consecutivo ingreso
	$session=$_SESSION['liquidacion'];
	$usuario=$_SESSION['usuario'];
	$paquete=$_SESSION['paquete'];
	$cb=$conexion1->query("select bodega from usuariobodega where usuario='$usuario' ")or die($conexion1->error());
	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
	$bodega=$fcb['bodega'];
	//echo "$session | $usuario | digita | $obs | $consumo |$qcomsumo | $ing | $qing | $bodega";
	$con=$conexion2->query("select top 1 * from liquidaciones where sessiones='$session' and usuario='$usuario'")or die($conexion2->error());
	$ncon=$con->rowCount();
	if($ncon!=0)
	{

	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
		$fecha=$fcon['fecha'];
		$autoriza=$fcon['autoriza'];

	$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$consumo','LIQ-AJN','CONSUMO POR LIQUIDACION AUTORIZADA POR: $autoriza',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error);

	$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$ing','LIQ-AJP','INGRESO POR LIQUIDACION AUTORIZADA POR: $autoriza',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error);

	$c=$conexion2->query("select id_liquidacion,art_origen,cantidad,bodega,usuario,paquete from liquidaciones where sessiones='$session' and usuario='$usuario'")or die($conexion2->error());
	$ns=1;//numero linea
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$articulo=$f['art_origen'];
		$cantidad=$f['cantidad'];
		$id=$f['id_liquidacion'];
		$bodega=$f['bodega'];
		$user=$f['usuario'];
		$paquete=$f['paquete'];
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
'$consumo',
'$ns','~CC~',
'$articulo',
'$bodega',
'C',
'D',
'N',
'$cantidad',
'1',
'1',
'0',
'0',
'0')")or die($conexion1->error);
	$ns++;

	}
//fin comsumos

	$cq=$conexion2->query("select id_liquidacion,art_destino,cantidad,bodega,usuario,paquete from liquidaciones where sessiones='$session' and usuario='$usuario'")or die($conexion2->error());
	$ns=1;
	while($fi=$cq->FETCH(PDO::FETCH_ASSOC))
	{
		$articulo=$fi['art_destino'];
		$cantidad=$fi['cantidad'];
		$bodega=$fi['bodega'];
		$usuario=$fi['usuario'];
		$paquete=$fi['paquete'];

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
'$ing',
'$ns','~OO~',
'$articulo',
'$bodega',
'O',
'D',
'L',
'$cantidad',
'1',
'1',
'0',
'0',
'0')")or die($conexion1->error);
		$ns++;
	}
	$session=$_SESSION['liquidacion'];
	$usuario=$_SESSION['usuario'];
	$conexion2->query("update liquidaciones set documento_inv_consumo='$consumo',documento_inv_ing='$ing',estado='1',digitado='$digita',observacion='$obs' where sessiones='$session' and usuario='$usuario'")or die($conexion2->error());
	$conexion1->query("update consny.consecutivo_ci set siguiente_consec='$qconsumo' where consecutivo='LIQ-AJN'")or die($conexion1->error());
	$conexion1->query("update consny.consecutivo_ci set siguiente_consec='$qing' where consecutivo='LIQ-AJP'")or die($conexion1->error());

	//envio correo
	$qc=$conexion2->query("select art_origen,convert(decimal(10,2),precio_origen) as precio_origen,art_destino,convert(decimal(10,2),precio_destino) as precio_destino,cantidad,fecha,bodega,documento_inv_consumo,documento_inv_ing,estado from liquidaciones where usuario='$usuario' and sessiones='$session'")or die($conexion2->error());
	$k=0;// para armar el encabezadp en la 
	while($fqc=$qc->FETCH(PDO::FETCH_ASSOC))
	{
		if($k==0)
		{
			$tabla="<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
			$tabla.="<tr>
					<td>FECHA:LIQUIDACION:<BR>".$fqc['fecha']."</td>
					<td>BODEGA: <br>".$fqc['bodega']."</td>
					<td COLSPAN='3'>DOCUMENTOS:<BR>".$fqc['documento_inv_consumo'].", ".$fqc['documento_inv_ing']."</td>
			</tr>";
			$tabla.="<tr>
				<td>CANTIDAD</td>
				<td>ARTICULO ORIGEN</td>
				<td>PRECIO ORIGEN</td>
				<td>ARTICULO DESTINO</td>
				<td>PRECIO DESTINO</td>
			</tr>";
			$k=1;
		}
		$art_origen=$fqc['art_origen'];
		$art_destino=$fqc['art_destino'];
		$qarto=$conexion1->query("select * from consny.articulo where articulo='$art_origen'")or die($conexion1->error());
		$fqarto=$qarto->FETCH(PDO::FETCH_ASSOC);

		$qartd=$conexion1->query("select * from consny.articulo where articulo='$art_destino'")or die($conexion1->error());
		$fqard=$qartd->FETCH(PDO::FETCH_ASSOC);

			$tabla.="<tr>
				<td>".$fqc['cantidad']."</td>
				<td>".$fqarto['ARTICULO'].": ".$fqarto['DESCRIPCION']."</td>
				<td>".$fqc['precio_origen']."</td>
				<td>".$fqc['art_destino'].": ".$fqard['DESCRIPCION']."</td>
				<td>".$fqc['precio_destino']."</td>
			</tr>";
			

	

	}
	$tabla.="</table>";

		$usuarioc=$_SESSION['usuario'];
$qcorreo=$conexion1->query("select * from usuariobodega where usuario='$usuarioc'")or die($conexion1->error());
$fqcorreo=$qcorreo->FETCH(PDO::FETCH_ASSOC);
$correo=$fqcorreo['CORREOTIENDA'];
if($correo=='')
{
	$correo='notienecoreo';
}
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

//dirección del remitente
$headers .= "From: $usuarioc <$correo@newyorkcentersadcv.com>\r\n";


//ruta del mensaje desde origen a destino
$headers .= "Return-path: $correo@newyorkcentersadcv.com\r\n";

$asunto="*SISTEMA* LIQUIDACION $bodega";
if(mail("jlainez@newyorkcentersadcv.com,ocampos@newyorkcentersadcv.com,$correo@newyorkcentersadcv.com,auditoria3@newyorkcentersadcv.com,auditoria2@newyorkcentersadcv.com,auditoria1@newyorkcentersadcv.com", $asunto, $tabla,$headers))
{

}else
{
	ECHO "<script>alert('LIQUIDACION FINALIZADO CORRECTAMENTE, PERO NO SE PUDO GENERAR EL CORREO NOTIFICA A OSMIN CAMPOS')</script>";
}

	//fin correo

	echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";

	$_SESSION['liquidacion']='';
	$_SESSION['piezas_liquidacion']='';
	echo "<script>location.replace('liquidaciones.php')</script>";


	}



}else
{
	echo "<script>location.replace('liquidaciones.php')</script>";
}
?>

</body>
</html>