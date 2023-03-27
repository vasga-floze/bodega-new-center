<!DOCTYPE html>
<html>
<head>
	<title></title>
		<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#load").hide();
		})
	</script>

</head>
<body>
<img src="load.gif" id="load" width="110%" height="110%" style="position: fixed; margin-top: -5%; margin-left: -6%; ">
<?php
//error_reporting(0);
include("conexion.php");
$session=$_SESSION['pedidos'];

$usuario=$_SESSION['usuario'];
//echo "<script>alert('$session - $usuario')</script>";
$c=$conexion2->query("select * from pedidos where estado='SOLI...' and usuario='$usuario' and sessiones='$session'")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<script>alert('NO SE PUDO FINALIZAR EL PEDIDO CONTACTA A LOS ENCARGADOS DE BODEGA QUE VERIFIQUEN SI LES LLEGO LA INFORMACION')</script>";
}else
{
	$msj="<table border='1' style='border-collapse:collapse;' cellspadding='5'>";
	$msj.="<tr>
	<td>CLASIFICACION</td>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	<td>EXISTENCIA</td>
	<td>CANT. SOLICITADA</td>
	</tr>";
	//clasificaciones
	$cc=$conexion1->query("select clasificacion_2 from consny.articulo where clasificacion_1!='detalle' and clasificacion_2 is not null group by clasificacion_2")or die($conexion1->error());
	while($fcc=$cc->FETCH(PDO::FETCH_ASSOC))
	{
		$clasi=$fcc['clasificacion_2'];
		$msj.= "<tr>
		<td>".$fcc['clasificacion_2']."</td>
		<td>- - - </td>
		<td>- - - </td>
		<td>- - - </td>
		<td>- - - </td>
		</tr>";
		$car=$conexion1->query("select articulo,descripcion from consny.ARTICULO where clasificacion_2='$clasi' and clasificacion_1!='detalle' and activo='S'")or die($conexion1->error());
		//articuls por categoria
		$bodega=$_SESSION['bodega'];
		while($fcar=$car->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fcar['articulo'];
			$desc=$fcar['descripcion'];
			//existencia
			$cr=$conexion2->query("select COUNT(*) cantidad from registro where (fecha_desglose='' or fecha_desglose is null) and bodega='$bodega' and codigo='$art' and activo is null")or die();
			$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
			$existencia=$fcr['cantidad'];

			//cantidad solicitada
			$session=$_SESSION['pedidos'];
			$usuario=$_SESSION['usuario'];
			$cp=$conexion2->query("select sum(cantidad_tienda) cantidad from pedidos where sessiones='$session' and usuario='$usuario' and articulo='$art'")or die($conexion2->error());
			$fcp=$cp->FETCH(PDO::FETCH_ASSOC);
			$solicitada=$fcp['cantidad'];
			if($existencia>0 or $solicitada>0)
			{
			$msj.="<tr>
				<td>- - </td>
				<td>$art</td>
				<td>$desc</td>
				<td>$existencia</td>";
				if($solicitada>0)
				{
					//echo "<script>alert('$solicitada <------')</script>";
					$msj.= "<td style='background-color:#49803F; color: white;'>$solicitada</td>";
				}else
				{
					$msj.= "<td>$solicitada</td>";
				}
				
			echo "</tr>";
		}

		}
	}
	$msj.="</table>";
}
	//echo "$msj <---";

	$usuarioc=$_SESSION['usuario'];
$qcorreo=$conexion1->query("select * from usuariobodega where usuario='$usuarioc'")or die($conexion1->error());
$fqcorreo=$qcorreo->FETCH(PDO::FETCH_ASSOC);
$correo=$fqcorreo['CORREOTIENDA'];
$session=$_SESSION['pedidos'];
$usuario=$_SESSION['usuario'];
$bodega=$_SESSION['bodega'];
$ci=$conexion2->query("SELECT CONVERT(date,pedidos.fecha) AS fecha,CONCAT(EXIMP600.consny.BODEGA.BODEGA,': ',EXIMP600.consny.BODEGA.NOMBRE) as bodega from
pedidos inner join EXIMP600.consny.BODEGA on pedidos.tienda=EXIMP600.consny.BODEGA.BODEGA where pedidos.usuario='$usuario' and
pedidos.tienda='$bodega' and pedidos.sessiones='$session' group by CONVERT(date,pedidos.fecha),CONCAT(EXIMP600.consny.BODEGA.BODEGA,': ',EXIMP600.consny.BODEGA.NOMBRE)")or die($conexion2->error());
$fci=$ci->FETCH(PDO::FETCH_ASSOC);
$fecha=$fci['fecha'];
$bodegas=$fci['bodega'];

$asunto="*SISTEMA* SOLICITUD PEDIDO FECHA: $fecha DE $bodegas";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

//dirección del remitente
$headers .= "From: $bodegas <$correo@newyorkcentersadcv.com>\r\n";


//ruta del mensaje desde origen a destino
$headers .= "Return-path: $correo@newyorkcentersadcv.com\r\n";


$conexion2->query("update pedidos set estado='SOLICITUD' where sessiones='$session' and usuario='$usuario'")or die($conexion2->error());

if(mail("jlainez@newyorkcentersadcv.com,,bodega@newyorkcentersadcv.com,everm1@hotmail.com,nerym1@hotmail.com", $asunto, $msj,$headers))
{
	echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";
}else
{
	echo "<script>alert('FINALIZADO CORRECTAMENTE, PERO NO SE PUDO GENERAR EL CORREO DEL PEDIDO, NOTIFICA A BODEGA')</script>";

}
$_SESSION['pedidos']='';
echo "<script>location.replace('pedidos.php')</script>";	

?>
</body>
</html>