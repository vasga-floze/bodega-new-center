<?php
include("conexion.php");

$averias=$_SESSION['averias'];
$usuario=$_SESSION['usuario'];
$paquete=$_SESSION['paquete'];
$bodega=$_SESSION['bodega'];
if($_POST and $averias>0 and $averias!='')
{
	extract($_REQUEST);
$c=$conexion1->query("select siguiente_consec from consny.consecutivo_ci where consecutivo='CON2'")or die($conexion1->error());
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$consecutivo=$f['siguiente_consec'];
		$cortar=explode( "CON2-",$consecutivo);
		$suma=$cortar[1] + 1;

		$suma=str_pad($suma,10,"0",STR_PAD_LEFT);
		$queda="CON2-$suma";
		//echo "$consecutivo | $queda";
$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$consecutivo','CON2','CONSUMO POR DESGLOSE DE $tipo en $bodega FECHA: $fecha',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error());

$c=$conexion2->query("select * from averias where usuario='$usuario' and session='$averias'")or die($conexion2->error());
$num=1;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['articulo'];
		$bodega=$f['bodega'];
		$cant=$f['cantidad'];
		$conexion1->query("insert into consny.linea_doc_inv(paquete_inventario,documento_inv,linea_doc_inv,ajuste_config,articulo,bodega,tipo,subtipo,subsubtipo,cantidad,costo_total_local,costo_total_dolar,precio_total_local,precio_total_dolar,noteexistsflag) values('$paquete',
	'$consecutivo','$num','AJN','$art','$bodega','C','D','N','$cant','0','0','0','0','0')")or die($conexion1->error());	
		$num++;

	}
	$conexion1->query("update consny.consecutivo_ci set siguiente_consec='$queda' where consecutivo='CON2'")or die($conexion1->error());
	
	$conexion2->query("update averias set observacion='$obs',tipo='$tipo',marchamo='$marchamo',digitado='$digita',documento_inv='$consecutivo',	estado='1',fecha='$fecha' where session='$averias' and usuario='$usuario'")or die($conexion2->error());

	//envio correo
	$qc=$conexion2->query("select concat(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION) as articulo,averias.precio,averias.cantidad,
(averias.cantidad*averias.precio) as total,averias.bodega,averias.tipo,averias.fecha from EXIMP600.consny.ARTICULO inner join 
averias on EXIMP600.consny.ARTICULO.ARTICULO=averias.articulo where averias.session='$averias' and averias.usuario='$usuario'")or die($conexion2->error());
	$tcantidad=0;
	$ttotal=0;
	$tabla="<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
	$tabla.="<tr>
		<td colspan='3'>$AVERIA/MERCADERIA NO VENDIBLE $bodega FECHA: $fecha</td>
		<td>DOCUMENTO: $consecutivo</td>
	</tr>";
	$tabla.="<tr>
		<td>ARTICULO</td>
		<td>CANTIDAD</td>
		<td>PRECIO</td>
		<td>TOTAL</td>
	</tr>";
	$ttotal=0;
	$tcantidad=0;
	while($fqc=$qc->FETCH(PDO::FETCH_ASSOC))
	{
		$articuloc=$fqc['articulo'];
		$cantidadc=$fqc['cantidad'];
		$precioc=$fqc['precio'];
		$totalc=$fqc['total'];
		$tcantidad=$tcantidad+$cantidadc;
		$ttotal=$ttotal+$totalc;
		$tabla.="<tr>
		<td>$articuloc</td>
		<td>$cantidadc</td>
		<td>$precioc</td>
		<td>$totalc</td>
	</tr>";

	}
	$tabla.="<tr>
		<td>TOTAL</td>
		<td>$tcantidad</td>
		<td></td>
		<td>$ttotal</td>
	</tr>";
	$usuarioc=$_SESSION['usuario'];
$qcorreo=$conexion1->query("select * from usuariobodega where usuario='$usuarioc'")or die($conexion1->error());
$fqcorreo=$qcorreo->FETCH(PDO::FETCH_ASSOC);
$correo=$fqcorreo['CORREOTIENDA'];
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

//dirección del remitente
$headers .= "From: $usuarioc <$correo@newyorkcentersadcv.com>\r\n";


//ruta del mensaje desde origen a destino
$headers .= "Return-path: $correo@newyorkcentersadcv.com\r\n";

$asunto="*SISTEMA* AVERIA/MERCADERIA NO VENDIBLE $bodega";
if(mail("jlainez@newyorkcentersadcv.com,ocampos@newyorkcentersadcv.com,$correo@newyorkcentersadcv.com,,auditoria1@newyorkcentersadcv.com,auditoria2@newyorkcentersadcv.com,auditoria3@newyorkcentersadcv.com", $asunto, $tabla,$headers))
{
	//echo "<script>alert('GUARDADO CORRECTAMENTE!')</script>";
}else
{
	echo "<script>alert('GUARDADO CORRECTAMENTE, PERO NO SE PUDO ENVIAR EL CORREO INFORMA A OSMIN CAMPOS')</script>";
}
	//fin envio correo

	$_SESSION['averias']='';
	echo "<script>alert('FINALIZADO CORRECTAMETE')</script>";
	echo "<script>location.replace('averias.php')</script>";

}
?>