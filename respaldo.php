<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".detalle").hide();
		});
	</script>
	<style>
	.preloader {
  width: 70px;
  height: 70px;
  border: 10px solid #eee;
  border-top: 10px solid skyblue;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 3s;
  animation-iteration-count: infinite;

}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
</head>
<body>

</head>
<center>
	<div class="detalle" style="background-color: black; width: 110%; height: 110%; margin-left: -4%;">

	

<div class="preloader" style="margin-top: 15%;">
</div>
</div>
</center>
<?php
include("conexion.php");
?>

<form method="POST" action="">
	<input type="text" name="v">
	<input type="submit" name="btn" class="boton1-1" value="REALIZAR RESPALDO" style="width: 20%;" onclick="envia()">

	<input type="submit" name="btn" class="boton1-1" value="TRABAJO" style="width: 15%;">
	<input type="submit" name="btn" class="boton1-1" value="AVERIA" style="width: 15%;">

	<input type="submit" name="btn" class="boton1-1" value="AVERIAS" style="width: 15%;">
</form>
<?php
date_default_timezone_set('America/El_Salvador');
$hoy=date("Y-m-d h:i a");
echo $hoy;
$conmysql=new mysqli("localhost",'root','','respaldo')or die(mysqli_error());

if($_POST)
{
	extract($_REQUEST);
if($btn=='REALIZAR RESPALDO')
{

	$c=$conexion2->query("select * from registro")or die($conexion2->error);
	
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		echo " ";
		$id_registro=$f['id_registro'];
		$categoria=$f['categoria'];
		$subcategoria=$f['subcategoria'];
		$numero_fardo=$f['numero_fardo'];
		$lbs=$f['lbs'];
		$und=$f['und'];
		$empacado=$f['empacado'];
		$producido=$f['producido'];
		$digitado=$f['digitado'];
		$fecha_documento=$f['fecha_documento'];
		$fecha_traslado=$f['fecha_traslado'];
		$fecha_desglose=$f['fecha_desglose'];
		$barra=$f['barra'];
		$codigo=$f['codigo'];
		$digita_desglose=$f['digita_desglose'];
		$bara=$f['barra'];
		$session=$f['session'];
		$documento_produccion=$f['documento_producion'];
		$estado=$f['estado'];
		$paquete=$f['paquete'];
		$usuario=$f['usuario'];
		$bodega=$f['bodega'];
		$tipo=$f['tipo'];
		$contenedor=$f['contenedor'];
		$cantidad=$f['cantidad'];
		$peso=$f['peso'];
		$observacion=$f['observacion'];
		$docu_consumo=$f['documento_inv_consumo'];
		$docu_ing=$f['documento_inv_ing'];
		$desglo_por=$f['desglosado_por'];
		$docu_conte=$f['documento_inv_contenedor'];
		$fechai=$f['fecha_ingreso'];
		$activo=$f['activo'];
		$bodega_produccion=$f['bodega_produccion'];
		$conmysql->query("insert into registro(id_registro,categoria,subcategoria,numero_fardo,lbs,und,empacado,producido,digitado,fecha_documento,fecha_traslado,fecha_desglose,barra,session,codigo,digita_desglose,documento_produccion,estado,paquete,usuario,bodega,tipo,contenedor,cantidad,peso,observacion,fecharespaldo,documento_inv_consumo,documento_inv_ing,desglosado_por,id,documento_inv_contenedor,fecha_ingreso,bodega_produccion,activo)values('$id_registro','$categoria','$subcategoria','$numero_fardo','$lbs','$und','$empacado','$producido','$digitado','$fecha_documento','$fecha_traslado','$fecha_desglose','$barra','$session','$codigo','$digita_desglose','$documento_produccion','$estado','$paquete','$usuario','$bodega','$tipo','$contenedor','$cantidad','$peso','$observacion','$hoy','$docu_consumo','$docu_ing','$desglo_por','','$docu_conte','$fechai','$bodega_produccion','$activo')")or die(mysqli_error());

echo " ";
	}
	echo "REGISTRO YA!<br><br>";
	$c=$conexion2->query("select * from detalle")or die($conexion2->error());

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$registro=$f['registro'];
		$articulo=$f['articulo'];
		$cantidad=$f['cantidad'];
		$conmysql->query("insert into detalle(id,registro,articulo,cantidad,fecharespaldo)values('$id','$registro','$articulo','$cantidad','$hoy')")or die(mysqli_error());
	}
	echo "DETALLE YA.<br><br>";

	

	$c=$conexion2->query("select * from mesa")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$producido=$f['producido'];
		$usuario=$f['usuario'];
		$paquete=$f['paquete'];
		$mesa=$f['mesa'];
		$fecha=$f['fecha'];
		$estado=$f['estado'];
		$conmysql->query("insert into mesa(id,producido,usuario,paquete,mesa,fecha,estado,fecharespaldo) values('$id','$producido','$usuario','$paquete','$mesa','$fecha','$estado','$hoy')")or die(mysqli_error());

	}
	echo "mesa ya. <br><br>";

	$c=$conexion2->query("select * from desglose")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$registro=$f['registro'];
		$articulo=$f['articulo'];
		$cantidad=$f['cantidad'];
		$paquete=$f['paquete'];
		$usuario=$f['usuario'];
		$fecha=$f['fecha'];
		$precio=$f['precio'];
		$conmysql->query("insert into desglose(id,registro,articulo,cantidad,paquete,usuario,fecharespaldo,fecha,precio)values('$id','$registro','$articulo','$cantidad','$paquete','$usuario','$hoy','$fecha','$precio')")or die(mysli_error());
	}
	echo "desglose ya <br><br>";
	$c=$conexion2->query("select * from traslado")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$registro=$f['registro'];
		$destino=$f['destino'];
		$origen=$f['origen'];
		$documento_inv=$f['documento_inv'];
		$paquete=$f['paquete'];
		$usuario=$f['usuario'];
		$estado=$f['estado'];
		$sessiones=$f['sessiones'];
		$articulo=$f['articulo'];
		$fecha=$f['fecha'];
		$fechai=$f['fecha_ingreso'];
		$conmysql->query("insert into traslado(id,registro,destino,origen,documento_inv,paquete,usuario,estado,sessiones,articulo,fecha,fecharespaldo,idc,fecha_ingreso)values('$id','$registro','$destino','$origen','$documento_inv','$paquete','$usuario','$estado','$sessiones','$articulo','$fecha','$hoy','','$fechai')")or die(mysqli_error());
		
	}
	echo "traslado ya.<br><br>";

	$c=$conexion2->query("select * from venta")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$cliente=$f['cliente'];
		$fecha=$f['fecha'];
		$paquete=$f['paquete'];
		$usuario=$f['usuario'];
		$registro=$f['registro'];
		$precio=$f['precio'];
		$sessiones=$f['sessiones'];
		$conmysql->query("insert into venta(idr,cliente,fecha,paquete,usuario,registro,precio,sessiones,id,fecharespaldo) values('','$cliente','$fecha','$paquete','$usuario','$registro','$precio','$sessiones','','$hoy')")or die(mysqli_error());
	}
	echo "venta ya.<br><br>";

	$c=$conexion2->query("select * from detalle_mesa")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$mesa=$f['mesa'];
		$registro=$f['registro'];
		$comentario=$f['comentario'];
		//echo "<script>alert('$registro - $comentario')</script>";
		$conmysql->query("insert into detalle_mesa(id,mesa,articulo,comentario,fecharespaldo) values('$id','$mesa','$registro','$comentario','$hoy')")or die(mysqli_error());

	}
	$c=$conexion2->query("select * from inventario")or die($conexion2->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$idr=$f['id'];
	$registro=$f['registro'];
	$bodega=$f['bodega'];
	$sessiones=$f['sessiones'];
	$fecha_ingreso=$f['fecha_ingreso'];
	$estado=$f['estado'];
	$usuario=$f['usuario'];
	$paquete=$f['paquete'];
	$fecha=$f['fecha'];
	$digita=$f['digita'];
	$bodega_actual=$f['bodega_actual'];

	$conmysql->query("insert into inventario(idr,id,bodega,digita,registro,sessiones,fecha_ingreso,estado,usuario,paquete,fecha,bodega_actual)values('','','$idr','$bodega','$digita','$registro','$sessiones','$fecha_ingreso','$usuario','$paquete','$fecha','$bodega_actual')")or die(mysqli_error());
	

}

		$fe=date("Y-m-d h:i a");
	
	echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
echo "inventario ya: $fe";


}else if($btn=="TRABAJO")
{
	$c=$conexion2->query("select * from trabajo")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$producido=$f['producido'];
		$mesa=$f['mesa'];
		$observacion=$f['observacion'];
		$deposito=$f['deposito'];
		$articulos=$f['articulos'];
		$peso=$f['peso'];
		$sessiones=$f['sessiones'];
		$fecha=$f['fecha'];
		$fechai=$f['fecha_ingreso'];
		$usuario=$f['usuario'];
		$paquete=$f['paquete'];
		$estado=$f['estado'];
		$cantidad=$f['cantidad'];
		$id=$f['id'];
		//probarboton de trabajo


		$conmysql->query("insert into trabajo(idr,id,producido,mesa,observacion,deposito,articulos,peso,sessiones,fecha,fecha_ingreso,usuario,paquete,estado,cantidad,fecharespaldo) values('$id','','$producido','$mesa','$observacion','$deposito','$articulos','$peso','$sessiones','$fecha','$fechai','$usuario','$paquete','$estado','$cantidad','$hoy')")or die(mysqli_error());
		//


	}
	echo "<br>trabajo ya..";
}else if($btn=='AVERIA')
{
	$c=$conexion2->query("select * from averia")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$corelativo=$f['corelativo'];
		$unidades=$f['unidades'];
		$fecha_documento=$f['fecha_documento'];
		$auditor=$f['auditor'];
		$encargado=$f['encargado'];
		$tienda=$f['tienda'];
		$observacion=$f['observacion'];
		$fecha_ingreso=$f['fecha_ingreso'];
		$usuario=$f['usuario'];
		$paquete=$f['paquete'];
		$tipo=$f['tipo'];
		$desde=$f['desde'];
		$hasta=$f['hasta'];
		$asignado=$f['asignado'];
		$sessiones=$f['sessiones'];
		$articulo=$f['articulo'];
		$cantidad=$f['cantidad'];
		$documento_inv=$f['documento_inv'];
		$desglosado_por=$f['desglosado_por'];
		$fecha_desglose=$f['fecha_desglose'];
		$estado=$f['estado'];
		$digita_desglose=$f['digita_desglose'];


	$conmysql->query("insert into averia(id,corelativo,unidades,fecha_documento,auditor,encargado,tienda,observacion,fecha_ingreso,usuario,paquete,tipo,desde,hasta,asignado,sessiones,articulo,cantidad,documento_inv,desglosado_por,fecha_desglose,estado,digita_desglose,fecha_respaldo)
	values('$id','$corelativo','$unidades','$fecha_documento','$auditor','$encargado','$tienda','$observacion','$fecha_ingreso','$usuario','$paquete','$tipo','$desde','$hasta
	','$asignado','$sessiones','$articulo','$cantidad','$documento_inv','$desglosado_por','$fecha_desglose','$estado','$digita_desglose','$hoy')")or die($conmysql->error());
	
	}
	echo "<br>averia ya";

}else if($btn=='AVERIAS')
{
	$c=$conexion2->query("select * from averias")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$usuario=$f['usuario'];
		$paquete=$f['paquete'];
		$bodega=$f['bodega'];
		$fecha=$f['fecha'];
		$fecha_hora_crea=$f['fecha_hora_crea'];
		$tipo=$f['tipo'];
		$estado=$f['estado'];
		$articulo=$f['articulo'];
		$precio=$f['precio'];
		$cantidad=$f['cantidad'];
		$marchamo=$f['marchamo'];
		$digitado=$f['digitado'];
		$observacion=$f['observacion'];
		$session=$f['session'];
		$documento_inv=$f['documento_inv'];
		$tipo=$f['tipo'];

		$conmysql->query("insert into averias(id,usuario,paquete,bodega,fecha,fecha_hora_crea,tipo,estado,articulo,precio,cantidad,marchamo,digitado,observacion,session,documento_inv,idr,fecharespaldo) values('$id','$usuario','$paquete','$bodega','$fecha','$fecha_hora_crea','$tipo','$estado','$articulo','$precio','$cantidad','$marchamo','$digitado','$observacion','$session','$documento_inv','','$hoy')")or die(mysqli_error());

		//aqui el insert
	}
	echo "<br>ya";

	
}
}

?>
</body>
</html>