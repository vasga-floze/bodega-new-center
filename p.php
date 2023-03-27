<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST">
	<input type="text">
	<input type="submit" name="btn" value="enviar">
</form>
<?php
if($_POST)
{
	//echo "<script>alert('post renviado')</script>";
	extract($_REQUEST);
	$ct=$conexion2->query("select id_registro,'INGRESO POR PRODUCCION' REF,'E' AS TIPO,codigo,1 cantidad,IsNULL(lbs,0) PESO,fecha_documento,usuario,paquete,bodega_produccion,'registro' tabla,documento_producion from registro where fecha_documento='2022-03-16' and estado='1' and 
tipo='p' and usuario='mcampos'")or die($conexion2->error());
$nct=$ct->rowCount();
echo "$nct <--";
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

}
?>
</body>
</html>