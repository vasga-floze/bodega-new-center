<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");

function traslado($doc,$usu)
{
	 try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftlandkj"); //<------------------para dar error
    }
    catch(PDOException $e) {
        die("!!ERROR!! SE PERDIO CONEXION CON EL SERVIDOR: " );
    }

    try {
        $conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }

	$c=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$usu'")or die($conexion2->error());

	$c1=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$usu'")or die($conexion2->error());
	$fc1=$c1->FETCH(PDO::FETCH_ASSOC);
	$doc=$fc1['documento_inv'];
	$paquete=$fc1['paquete'];
	$origen=$fc1['origen'];
	$destino=$fc1['destino'];
	$fecha=$fc1['fecha'];

	$tabla="<table border='1' style='border-collapse:collapse; width:95%;' cellpadding='6'>";
	$tabla.="<tr>
		<td colspan='4'>DOCUMENTO_INV: $doc<br>PAQUETE: $paquete<br>
		ORIGEN: $origen<br>
		DESTINO: $destino<br>
		FECHA: $fecha</td>
	</tr>";
	$tabla.="<tr>
		<td>#</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CODIGO BARRA</td>
	</tr>";
	$k=1;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$f['registro'];
		$bodega=$f['destino'];
		$doc=$f['documento_inv'];
		$paquete=$f['paquete'];

		$cr=$conexion2->query("select barra,codigo from registro where id_registro='$idr'")or die($conexion2->error());

		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$barra=$fcr['barra'];
		$codigo=$fcr['codigo'];

		$ca=$conexion1->query("select * from consny.articulo where articulo='$codigo'")or die($conexion1->error());

		$fca=$ca->FETCH(PDO::FETCH_ASSOC);

		$art=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];

		$tabla.="<tr>
		<td>$k</td>
		<td>$art</td>
		<td>$desc</td>
		<td>$barra</td>
	</tr>";
	$k++;

	}

	$tabla.="</table>";

	return $tabla;
}
$doc="3599";
$usu="GJURADO";

$tablas=traslado($doc,$usu);
$c2=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$usu'")or die($conexion2->error());
	$fc2=$c2->FETCH(PDO::FETCH_ASSOC);
	$fecha=$fc2['fecha'];
	$origen=$fc2['origen'];
	$destino=$fc2['destino'];
	$cb=$conexion1->query("select * from usuariobodega where bodega='$destino'")or die($conexion1->error());
	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);

	$correo_tienda=$fcb['CORREOTIENDA'];
	$v=substr($origen, 0);
	$bo="$v[0]$v[1]";
	if($bo=='SM')
	{
		$nom="SONIA ALVARADO";
		$correo='salvarado';
	}else
	{
		$nom="OSMIN OCAMPOS";
		$correo='ocampos';
	}

	if($correo_tienda=='' or $destino=='SM00')
	{
		$correo_tienda='salvarado';
	}
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

//dirección del remitente
$headers .= "From: $nom <$correo@newyorkcentersadcv.com>\r\n";
$headers .= "Return-path: $correo@newyorkcentersadcv.com\r\n";
$hoy=date("Y-m-d H:i:s");
if(mail("jlainez@newyorkcentersadcv.com,$correo_tienda@newyorkcentersadcv.com,$correo@newyorkcentersadcv.com", "TRASLADO $fecha",$tablas,$headers))
{
	echo 	$tablas;

}else
{
	echo "<script>alert('CORREO NO PUDO SER ENVIADO!!!')</script>";
}


?>
</body>
</html>