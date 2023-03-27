<?php

 function querys()
 {
 	include("conexion.php");
 	$usuario='ahua1';
 	$session=3;
 	$bodega='CA03';
 	$resultado.="<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
 	$resultado.="<tr>
<td>CLASIFICACION</td>
<td>ARTICULO</td>
<td>DESCRIPCION</td>
<td>EXISTENCIAS</td>
<td>CANTIDAD SOLICITADA</td>

</tr>";

$c=$conexion1->query("select clasificacion_2 from consny.articulo where clasificacion_1!='DETALLE' and clasificacion_2 is not null group by clasificacion_2
")or die($conexion1->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$clasificacion=$f['clasificacion_2'];
	if($clasificacion=='')
	{

	}else
	{
	$resultado.="<tr>
<td>$clasificacion</td>
<td></td>
<td></td>
<td></td>
<td></td>

</tr>";
	}
	

	$cp=$conexion2->query("select * from pedidos where sessiones='$session' and usuario='$usuario' and clasificacion='$clasificacion'")or die($conexion2->error());
	$k=1;
	while($fcp=$cp->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$fcp['articulo'];
		$cant=$fcp['cantidad_tienda'];
		$bodega=$fcp['tienda'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$articulo=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];
		
		$cr=$conexion2->query("
declare @cantidad int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose='' and codigo='$art' group by codigo)
declare @cantidad1 int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose is null and codigo='$art' group by codigo)

declare @total int = (isnull(@cantidad,0) + isnull(@cantidad1,0))

select @total as total
")or die($conexion2->error());
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$exi=$fcr['total'];
		if($exi=='')
		{
			$exi=0;
		}
		if($exi>0 or $cant>0)
		{
			$resultado.= "<tr>
				<td></td>
				<td>$articulo</td>
				<td>$desc $bodega</td>
				<td>$exi</td>
				<td>$cant</td></tr>";


		
		}
				
	}

	$qa=$conexion1->query("select *from consny.articulo where clasificacion_2='$clasificacion' and clasificacion_1!='DETALLE'")or die($conexion1->error());
	while($fqa=$qa->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$fqa['ARTICULO'];
		$desc=$fqa['DESCRIPCION'];
		$qv=$conexion2->query("select * from pedidos where articulo='$art' and sessiones='$session' and usuario='$usuario'")or die($conexion2->error());
		$nqv=$qv->rowCount();
		if($nqv==0)
		{
			$cr=$conexion2->query("
declare @cantidad int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose='' and codigo='$art' group by codigo)
declare @cantidad1 int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose is null and codigo='$art' group by codigo)

declare @total int = (isnull(@cantidad,0) + isnull(@cantidad1,0))

select @total as total
")or die($conexion2->error());
			$ncr=$cr->rowCount();
			if($ncr!=0)
			{
				$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$exi=$fcr['total'];
		if($exi>0)
		{
			$resultado.= "<tr>
				<td></td>
				<td>$art</td>
				<td>$desc</td>
				<td>$exi</td>
				<td>0</td></tr>";
		}
		
			}
		
		}

		
		
	}

}
$resultado.="</table>";

	return $resultado;


 }//fin funcion
$msj=querys();

//echo $msj;
$mail = "jlainez@newyorkcentersadcv.com";
$header = 'From: ' . $mail . " \r\n";
$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
$header .= "Mime-Version: 1.0 \r\n";
$header .= "Content-Type: text/html";
$email='jlainez@newyorkcentersadcv.com';
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

//dirección del remitente
$headers .= "From: soporte <eortez@newyorkcentersadcv.com>\r\n";

//ruta del mensaje desde origen a destino
$headers .= "Return-path: eortez@newyorkcentersadcv.com\r\n";

if(mail('jlainez@newyorkcentersadcv.com', "prueba final",$msj,$headers))
{
	echo "CORREO ENVIADO";
}else
{
	echo "NO SE PUDO ENVIAR EL CORREO";
}
?>