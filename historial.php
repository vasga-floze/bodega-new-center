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
<input type="date" name="desde">
<input type="date" name="hasta">
<input type="submit" name="btn" value="BUSCAR">

</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select  EXIMP600.consny.articulo.articulo as codigo,min(registro.fecha_documento) as minima from registro inner join 
EXIMP600.consny.articulo on registro.codigo=eximp600.consny.articulo.ARTICULO where EXIMP600.consny.ARTICULO.CLASIFICACION_2='ROPA'
and registro.tipo!='C1' group by EXIMP600.consny.ARTICULO.ARTICULO")or die($conexion2->error());
	echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
	echo "<tr>
	<td>FECHA</td>
	<td>CLASIFICACION</td>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	<td>CANTIDAD</td>
	<td>TOTAL PESO</td>
	<td></td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['codigo'];
		$min=$f['minima'];

		$c1=$conexion2->query("declare @desde date='$min'
declare @hasta date='$desde'
declare @art varchar(10)='$art'
declare @bod varchar(10)='SM00'
--select MIN(fecha_documento) from registro where codigo='$art'

declare @peso_mesa decimal(10,2)=(select sum(ISNULL(registro.lbs,0)+ISNULL(registro.peso,0)) from detalle_mesa inner join registro on registro.id_registro=
detalle_mesa.registro inner join mesa on mesa.id=detalle_mesa.mesa where registro.codigo=@art and
registro.fecha_documento between @desde and @hasta and registro.bodega=@bod)

declare @pro decimal(10,2)=(select COUNT(*) from registro where codigo=@art and bodega_produccion=@bod and
fecha_documento between @desde and @hasta)

declare @peso_pro decimal(10,2)=(select sum(ISNULL(lbs,0)+ISNULL(peso,0)) from registro where codigo=@art and bodega_produccion=@bod and
fecha_documento between @desde and @hasta)

declare @origen decimal(10,2)=(select COUNT(*) from traslado where articulo=@art and origen=@bod and fecha between @desde and 
@hasta)

declare @destino decimal(10,2)=(select COUNT(*) from traslado where articulo=@art and destino=@bod and 
fecha between @desde and @hasta)

declare @peso_destino decimal(10,2)=(select SUM(ISNULL(registro.lbs,0)+ISNULL(registro.peso,0)) from traslado inner join registro
on registro.id_registro=traslado.registro where articulo=@art and destino=@bod and 
fecha between @desde and @hasta)

declare @mesa decimal(10,2)=(select COUNT(registro.barra) from detalle_mesa inner join registro on registro.id_registro=
detalle_mesa.registro inner join mesa on mesa.id=detalle_mesa.mesa where registro.codigo=@art and
registro.fecha_documento between @desde and @hasta and registro.bodega=@bod)



declare @venta decimal(10,2)=(select COUNT(registro.barra) from venta inner join registro on venta.registro=registro.id_registro where
registro.codigo=@art and registro.bodega=@bod and venta.fecha between @desde and @hasta)

 declare @eliminado decimal(10,2) =(select COUNT(*) from registro where bodega=@bod and CONVERT(date,fecha_eliminacion) between 
 @desde and @hasta and codigo=@art)

  declare @peso_eliminado decimal(10,2) =(select SUM(isnull(lbs,0)+ISNULL(peso,0)) from registro where bodega=@bod and CONVERT(date,fecha_eliminacion) between 
 @desde and @hasta and codigo=@art)

declare @peso_venta decimal(10,2)=(select sum(ISNULL(registro.lbs,0)+ISNULL(registro.peso,0)) from venta inner join registro on venta.registro=registro.id_registro where
registro.codigo=@art and registro.bodega=@bod and venta.fecha between @desde and @hasta)




 declare @cancelado decimal(10,2)=(select COUNT(*) from registro where observacion='cancelado sys' and
 fecha_documento between @desde and @hasta and bodega=@bod and codigo=@art)

  declare @peso_cancelado decimal(10,2)=(select SUM(isnull(lbs,0)+ISNULL(peso,0)) from registro where observacion='cancelado sys' and
 fecha_documento between @desde and @hasta and bodega=@bod and codigo=@art)

 declare @peso_origen decimal(10,2)=(select SUM(ISNULL(registro.lbs,0)+ ISNULL(registro.peso,0)) from traslado inner join registro
 on traslado.registro=registro.id_registro where articulo=@art and origen=@bod and fecha between @desde and 
@hasta)

--select @pro as pro,@peso_pro as peso_producccion,@origen  as origen,@peso_origen as peso_origen,
--@destino as destino,@peso_destino as peso_destino,@mesa as mesa,@peso_mesa as peso_mesa,@venta --as venta
--,@peso_venta as peso_venta, @eliminado as eliminado,@peso_eliminado as peso_eliminado,@cancelado --as cancelado,@peso_cancelado as peso_cancelado

declare @cant_entrada decimal(10,2)=@pro+@destino;

declare @peso_entrada decimal(10,2)=isnull(@peso_pro,0)+isnull(@peso_destino,0)

declare @cant_salida decimal(10,2)=isnull(@origen,0)+isnull(@mesa,0)+isnull(@eliminado,0)+isnull(@venta,0)+isnull(@cancelado,0)

declare @peso_salida decimal(10,2)=isnull(@peso_origen,0)+isnull(@peso_mesa,0)+isnull(@peso_venta,0)+isnull(@peso_eliminado,0)+isnull(@peso_cancelado,0)

declare @cantidad decimal(10,2)=@cant_entrada-@cant_salida
declare @peso decimal(10,2)=@peso_entrada -@peso_salida

select @cantidad as cantidad,@peso as peso")or die($conexion2->error());
		$fc1=$c1->FETCH(PDO::FETCH_ASSOC);
		$cantidad=$fc1['cantidad'];
		$peso=$fc1['peso'];
		echo "<tr>
			<td>$desde</td>
			<td>CLASIFICACION</td>
			<td>$art</td>
			<td>DESCRIPCION</td>
			<td>$cantidad</td>
			<td>$peso</td>
			<td></td>
			</tr>";
	}
}
?>
</body>
</html>