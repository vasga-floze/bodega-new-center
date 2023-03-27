<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST">
	
<input type="text" name="b" class="text" style="width: 20%; " placeholder="CODIGO DE BARRA">
<input type="submit" name="btn" value="DESACTIVAR GANCHOS/INSUMOS" class="boton3">

</form>
<?php
//echo $_SESSION['tipo'];
if($_POST)
{
	extract($_REQUEST);
	$bod=$_SESSION['bodega'];
	$usu=$_SESSION['usuario'];
	if($_SESSION['tipo']==1)
	{
		$bod='CA00';
		//echo $bod;
	}
	$c=$conexion2->query("select registro.id_registro,registro.barra,registro.bodega,registro.codigo,EXIMP600.consny.ARTICULO.DESCRIPCION from registro inner join EXIMP600.consny.ARTICULO on registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO where barra='$b' and (EXIMP600.consny.ARTICULO.CLASIFICACION_2='ganchos' or EXIMP600.consny.articulo.CLASIFICACION_1='INSUMO') and registro.activo is null and bodega='$bod'
")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE PUEDE DESACTIVAR PORQUE , PUEDE QUE\\n-YA ESTA ELIMINADO\\n-EL CODIGO DE BARRA NO ES DE GANCHOS NI INSUMO\\n-NO PERTENECE A TU BODEGA')</script>";
	}else
	{
		$hoy=date("Y-m-d h:i:s");
		$fecha=date("Y-m-d");
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$art=$f['codigo'];
		$bodega=$f['bodega'];
		$barra=$f['barra'];
		//inicia inser exactus
	$c=$conexion1->query("select siguiente_consec from consny.consecutivo_ci where consecutivo='CON2'")or die($conexion1->error());
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$consecutivo=$f['siguiente_consec'];
		$cortar=explode( "CON2-",$consecutivo);
		$suma=$cortar[1] + 1;
		$suma=str_pad($suma,10,"0",STR_PAD_LEFT);
		$queda="CON2-$suma";
		//echo "$consecutivo | $queda";
		$paquete=$_SESSION['paquete'];
		$usuario=$_SESSION['usuario'];
$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$consecutivo','CON2','CONSUMO DE GANCHOS/INSUMOS ($barra) EN $bodega FECHA: $fecha',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error());
$cant=1; $num=1;
$conexion1->query("insert into consny.linea_doc_inv(paquete_inventario,documento_inv,linea_doc_inv,ajuste_config,articulo,bodega,tipo,subtipo,subsubtipo,cantidad,costo_total_local,costo_total_dolar,precio_total_local,precio_total_dolar,noteexistsflag) values('$paquete',
	'$consecutivo','$num','AJN','$art','$bodega','C','D','N','$cant','0','0','0','0','0')")or die($conexion1->error());	

$conexion1->query("update consny.consecutivo_ci set siguiente_consec='$queda' where consecutivo='CON2'")or die($conexion1->error());
		//fin insert exactus

		$conexion2->query("update registro set activo='0',fecha_eliminacion='$hoy',usuario_eliminacion='$usu' where barra='$b'")or die($conexion2->error());

		echo "<script>alert('DESACTIVADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('desactivar_gancho.php')</script>";
	}
}
?>
</body>
</html>