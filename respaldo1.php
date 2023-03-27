
<?php
include("conexion.php");
$conmysql=new mysqli("localhost",'root','','respaldo')or die(mysqli_error());
?>
<form method="POST">
<input type="submit" name="btn" value="CUADRO VENTA">
<input type="submit" name="btn" value="LIQUIDACIONES">	

</form>
<?php
if($_POST)
{
	$hoy=date("d-m-Y h:i:s");
	extract($_REQUEST);
	if($btn=='CUADRO VENTA')
	{


$c=$conexion1->query("select * from cuadro_venta")or die($conexion1->error());

while($f=$c->FETCH(PDO::FETCH_ASSOC))
{

	$id=$f['ID'];
	$caja=$f['CAJA'];
	$bodega=$f['BODEGA'];
	$usuario=$f['USUARIO'];
	$fecha=$f['FECHA'];
	$fecha_inicial=$f['FECHA_INICIAL'];
	$fecha_final=$f['FECHA_FINAL'];
	$fecha_hor_creacion=$f['FECHA_HOR_CREACION'];
	$estado=$f['ESTADO'];
	$session=$f['SESSION'];
	$monto_sistema=$f['MONTO_SISTEMA'];
	$monto_usuario=$f['MONTO_USUARIO'];
	$documento_inicial=$f['DOCUMENTO_INICIAL'];
	$documento_final=$f['DOCUMENTO_FINAL'];
	$monto_liquido=$f['MONTO_LIQUIDO'];
	$asiento=$f['ASIENTO'];
	$descuento=$f['DESCUENTO'];
	$total_fardo=$f['TOTAL_FARDO'];
	$monto_liquidaciones=$f['MONTO_LIQUIDACIONES'];
	$fardos_vendidos=$f['FARDOS_VENDIDOS'];

	$conmysql->query("insert into cuadro_venta(id,caja,bodega,usuario,fecha,fecha_inicial,fecha_final,fecha_hor_creacion,estado,session,monto_sistema,monto_usuario,documento_inicial,documento_final,monto_liquido,asiento,descuento,total_fardo,monto_liquidaciones,fardos_vendidos,idr,fecharespaldo) values('$id','$caja','$bodega','$usuario','$fecha','$fecha_inicial','$fecha_final','$fecha_hor_creacion','$estado','$session','$monto_sistema','$monto_usuario','$documento_inicial','$documento_final','$monto_liquido','$asiento','$descuento','$total_fardo','$monto_liquidaciones','$fardos_vendidos','','$hoy')")or die(mysqli_error());

}
echo"GUARDADO $hoy";

$c=$conexion1->query("select * from cuadro_venta_detalle")or die($conexion1->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$id=$f['ID'];
	$cuadro_venta=$f['CUADRO_VENTA'];
	$tipo_transaccion=$f['TIPO_TRANSACCION'];
	$tipo_documento=$f['TIPO_DOCUMENTO'];
	$concepto=$f['CONCEPTO'];
	$monto=$f['MONTO'];
	$fecha_hor_creacion=$f['FECHA_HOR_CREACION'];
	$usuario=$f['USUARIO'];
	$num_documento=$f['NUM_DOCUMENTO'];
	$conmysql->query("insert into cuadro_venta_detalle(id,cuadro_venta,tipo_transaccion,tipo_documento,concepto,monto,fecha_hor_creacion,usuario,num_documento,idr,fecharespaldo) values('$id','$cuadro_venta','$tipo_transaccion','$tipo_documento','$concepto','$monto','$fecha_hor_creacion','$usuario','$num_documento','','$hoy')")or die(myqli_error());



}
echo "<BR>GUARDADO 2";


}else if($btn=='LIQUIDACIONES')
{
	$c=$conexion2->query("select * from liquidaciones")or die($conexion1->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id_liquidacion=$f['id_liquidacion'];
		$autoriza=$f['autoriza'];
		$fecha=$f['fecha'];
		$fechaingreso=$f['fechaingreso'];
		$usuario=$f['usuario'];
		$paquete=$f['paquete'];
		$bodega=$f['bodega'];
		$art_origen=$f['art_origen'];
		$art_destino=$f['art_destino'];
		$cantidad=$f['cantidad'];
		$sessiones=$f['sessiones'];
		$documento_inv_consumo=$f['documento_inv_consumo'];
		$documento_inv_ing=$f['documento_inv_ing'];
		$estado=$f['estado'];
		$precio_origen=$f['precio_origen'];
		$precio_destino=$f['precio_destino'];
		$digitado=$f['digitado'];
		$observacion=$f['observacion'];

		$conmysql->query("insert into liquidaciones(id_liquidacion,autoriza,fecha,fechaingreso,usuario,paquete,bodega,art_origen,art_destino,cantidad,sessiones,documento_inv_consumo,documento_inv_ing,estado,precio_origen,precio_destino,digitado,observacion,fecharespaldo,id) values('$id_liquidacion','$autoriza','$fecha','$fechaingreso','$usuario','$paquete','$bodega','$art_origen','$art_destino','$cantidad','$sessiones','$documento_inv_consumo','$documento_inv_ing','$estado','$precio_origen','$precio_destino','$digitado','$observacion','$hoy','')")or die(mysqli_error());
	}
	echo "GUARDADO LIQUIDACIONES $hoy";

	}



}
?>