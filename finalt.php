<?php
include("conexion.php");
if($_SESSION['doc']=="")
{
	echo "<script>location.replace('traslados.php')</script>";
}
$doc=$_SESSION['doc'];
if($_POST)
{
	extract($_REQUEST);
	$us=$_SESSION['usuario'];
$qu=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$us' and estado='0'")or die($conexion2->error);



$nqu=$qu->rowCount();
if($nqu==0)
{
	echo "<script>alert('SE PRODUJO UN ERROR INICIE NUEVAMENTE EL TRASLADO')</script>";
	echo("<script>location.replace('traslados.php')</script>");
}
$qu=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$us' and estado='0'")or die($conexion2->error);
$num=1;
while ($fqu=$qu->FETCH(PDO::FETCH_ASSOC)) 
	{
		if($num==1)
		{
			$destino=$fqu['destino'];
			$num=0;
		}
		
		$origen=$fqu['origen'];
		if($origen==$destino)
		{
			$error=1;
		}
	}
	$cor=$conexion2->query("select count(origen) from traslado where sessiones='$doc' and usuario='$us' group by origen
")or die($conexion2->error());
	$vali=0;
	while($f1=$cor->FETCH(PDO::FETCH_ASSOC))
	{
		$vali=$vali +1;
	}
	if($vali>1)
	{
		$error=1;
	}
	//echo "<script>alert('$error')</script>";
	if($error==1)
	{
		echo "<script>alert('NO SE PUEDE FINALIZAR El TRASLADO PORQUE HAY ARTICULOS QUE LA BODEGA DE ORIGEN ES LA MISMA BODEGA DE DESTINO O HAY MAS DE UNA BODEGA DE ORIGEN')</script>";
		$origen=$destino;
		echo "<script>location.replace('traslados.php?bcod=$destino')</script>";
	}else
	{
	$num=1;
	$error=0;
	//$c=$conexion1->query("SELECT CONSECUTIVO,SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='TRASLADO'")or die($conexion1->error);
	$c=$conexion1->query("SELECT CONSECUTIVO,SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='TRASLADO'")or die($conexion1->error);

$f=$c->FETCH(PDO::FETCH_ASSOC);
$conse=$f['SIGUIENTE_CONSEC'];
$ex=explode("-", $conse);
$queda=$ex[1]+1;
$queda=str_pad($queda,10,"0",STR_PAD_LEFT);
$queda="$ex[0]-$queda";//-------------descomentat<-----------
$conexion1->query("update consny.CONSECUTIVO_CI set SIGUIENTE_CONSEC='$queda' where CONSECUTIVO='TRASLADO'")or die($conexion1->error); 



$k=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$us'")or die($conexion2->error);
$fqu1=$k->FETCH(PDO::FETCH_ASSOC);
$paquete=$_SESSION['paquete'];
$usuario=$_SESSION['usuario'];
$destino=$fqu1['destino'];
$cb=$conexion1->query("select * from consny.BODEGA where BODEGA='$destino'")or die($conexion1->error);
	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
	$destino=$fcb['NOMBRE'];

//echo "<script>alert('$conse - d: $destino - u: $usuario f: $fecha')</script>";
$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$conse','TRASLADO','$destino (B.$doc), $comentario',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error);
$cns=$conexion1->query("select COUNT(*) as ns from consny.LINEA_DOC_INV where DOCUMENTO_INV='$conse'")or die($conexion1->error);
$fcns=$cns->FETCH(PDO::FETCH_ASSOC);
$ns=$fcns['ns'] + 1;//<--numero de secuencia
}




	

	/*echo "<script>
	if(confirm('SEGURO QUE LA BODEGA $codboedega ES EL DESTINO DEL TRASLADO'))
	{
		
	}else
	{
		location.replace('traslados.php');
	}
	</script>";*/
	



//echo "<script>alert('$conse - $queda')</script>";
$doc=$_SESSION['doc'];


$paque=$_SESSION['paquete'];

$conexion2->query("update traslado set documento_inv='$conse',estado='1',fecha='$fecha',observacion='$comentario' where sessiones='$doc' and usuario='$us' and estado='0'")or die($conexion2->error);
$corta=explode('-', $conse);
$sum=$corta[1] + 1;
$queda=str_pad($sum,10,"0",STR_PAD_LEFT);
$queda="$corta[0]-$queda";
//actualizar la fech de traslado tabla de registrobd pruebabd

$q=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$us' and estado='1'")or die($conexion2->error);
while($fq=$q->FETCH(PDO::FETCH_ASSOC))
{
	$id=$fq['registro'];
	$paquete=$_SESSION['paquete'];
	$usuario=$_SESSION['usuario'];
	$articulo=$fq['articulo'];
	$destino=$fq['destino'];
	$origen=$fq['origen'];


//echo "<script>alert('$conse - d: $destino - u: $usuario f: $fecha o: $origen a: $articulo p:$paquete 22')</script>";
	$conexion1->query("insert into consny.LINEA_DOC_INV(PAQUETE_INVENTARIO,
DOCUMENTO_INV,
LINEA_DOC_INV,
AJUSTE_CONFIG,
ARTICULO,
BODEGA,
TIPO,SUBTIPO,
SUBSUBTIPO,
CANTIDAD,
COSTO_TOTAL_LOCAL,
COSTO_TOTAL_DOLAR,
PRECIO_TOTAL_LOCAL,
PRECIO_TOTAL_DOLAR,
BODEGA_DESTINO,
NoteExistsFlag) 
values('$paquete',
'$conse',
'$ns','~TT~',
'$articulo',
'$origen',
'T',
'D',
'',
'1',
'1',
'0',
'0',
'0',
'$destino',
'0')")or die($conexion1->error);
$ns++;
//se cambio costo_total_local de 0.0 a 1 10-01-20

	$conexion2->query("update registro set fecha_traslado='$fecha',bodega='$destino' where id_registro='$id'")or die($conexion2->error);
}
$usu=$_SESSION['usuario'];
//inicio correo
/*function traslado($doc,$usu)
{
	 try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
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
$doc=$_SESSION['doc'];
$usu=$_SESSION['usuario'];

	//revisar correos de traslados

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
$doc=$_SESSION['doc'];
$usu=$_SESSION['usuario'];
$tablas=traslado($doc,$usu);
$c2=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$usu'")or die($conexion2->error());
	$fc2=$c2->FETCH(PDO::FETCH_ASSOC);
	$fecha=$fc2['fecha'];
	$origen=$fc2['origen'];
	$destino=$fc2['destino'];
	$cb=$conexion1->query("select * from usuariobodega where bodega='$destino' and caja is not null")or die($conexion1->error());
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

}else
{
	echo "<script>alert('CORREO NO PUDO SER ENVIADO!!!')</script>";
}




//fin correo
*/
echo "<script>location.replace('imprimir_traslado.php?doc=$doc')</script>";




}else
{
	echo "<script>location.replace('traslados.php')</script>";
}
?>