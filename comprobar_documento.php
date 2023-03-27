<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="moment.min.js"></script>
  <script>
  	$(document).ready(function(){

  		$("#hora").val(moment().format("YYYY-MM-DD H:mm:ss"));
  	})
  
  function fechahora()
  {
  	 var horas=moment().format("YYYY-MM-DD H:mm:ss");
  	 //alert(horas);
 	 $("#actual").val(horas);
 	 var h=$("#hora").val();
 	 //alert(h);
 	 var minutos=moment(horas).diff(moment(h),'minutes') ;
 	 //alert(minutos);
 	 if(minutos>=15)
 	 {
 	 	alert('CERRADO POR INACTIVIDAD');
 	 	location.replace('salir.php');
 	 }

 	 return horas;
  }
 setInterval(fechahora,1000);
  </script>	
</head>
<body style="width: 130%; padding-bottom: 40%;">
<?php
include("conexion.php");
?>
<h3 style="text-align: center; text-decoration: underline; color: black; border: groove; border: blue;">COMPARACION DE DOCUMENTOS</h3>
<form method="POST" style="margin-top: 2%;">

	<label>
		DOCUMENTO:<BR>
		<input type="text" name="doc" class="text" style="width:35%;">
		<input type="submit" name="btn" value="GENERAR" class="boton2">
	</label>
</form>
<BR><BR>
<input type="hidden" name="hora" id="hora">
<input type="hidden" name="actual" id="actual">

<?php
if($_POST)
{
	extract($_REQUEST);
	error_reporting(0);
	$c=$conexion2->query("declare @doc varchar(20)='$doc'

SELECT '' barra,'' codigo1,mesa.documento_inv doc,'' doc_2,mesa.fecha,registro.codigo,count(registro.codigo)
as cantidad,sum(isnull(registro.lbs,0)+isnull(registro.peso,0)) peso,registro.bodega,
'BULTOS TRBAJADOS' AS referencia,'MESA' tipo from registro inner join detalle_mesa on 
registro.id_registro=detalle_mesa.registro inner join mesa on mesa.id=
detalle_mesa.mesa where mesa.documento_inv=@doc group by mesa.documento_inv,
registro.codigo,mesa.fecha,registro.bodega

union all
--venta
select '' barra,registro.codigo,venta.documento_inv doc,'' doc_2,venta.fecha,registro.codigo articulo,COUNT(registro.codigo)
cantidad,SUM(ISNULL(registro.lbs,0)+ isnull(registro.peso,0)) peo,registro.bodega,
'VENTA EN BODEGA' AS referencia,'VENTA' TIPO from registro inner join venta on venta.registro=
registro.id_registro where venta.documento_inv=@doc group by venta.documento_inv,
registro.codigo,registro.bodega,venta.fecha
union all
-- traslado
select '',registro.codigo,traslado.documento_inv doc,'' doc_2,traslado.fecha,traslado.articulo,COUNT
(traslado.articulo) cantidad,SUM(ISNULL(registro.lbs,0)+isnull(registro.peso,0)) peso,
CONCAT(traslado.origen,'>',traslado.destino) bodega,concat('TRASLADO DE: ',traslado.origen,
' PARA: ',traslado.destino) referencia,'TRASLADO' TIPO from traslado inner join registro on
traslado.registro=registro.id_registro where traslado.documento_inv=@doc group by
traslado.documento_inv,traslado.fecha,traslado.articulo,traslado.origen,traslado.destino,registro.codigo

union all
--desglose
SELECT registro.barra,registro.codigo,registro.documento_inv_consumo doc,registro.documento_inv_ing doc_2,registro.fecha_desglose,desglose.articulo,SUM(desglose.cantidad) cantidad,
sum(ISNULL(registro.peso,0)+ISNULL(registro.lbs,0)) peso,registro.bodega,'DESGLOSE DE FARDO EN TIENDA' referencia,'DESGLOSE' TIPO
FROM registro INNER JOIN desglose ON registro.id_registro=desglose.registro WHERE 
documento_inv_consumo=@doc or documento_inv_ing=@doc group by

registro.documento_inv_consumo,registro.documento_inv_ing,desglose.articulo,registro.bodega,
registro.fecha_desglose,registro.codigo,registro.barra 

union all
select '' barra,registro.codigo codigo_1,case tipo when 'P' then documento_producion when 'CD' then
documento_inv_contenedor end  doc,'' doc_2,fecha_documento fecha,'' codigo,COUNT(codigo) cantidad,
case tipo when 'P' then sum(lbs) when 'CD' then sum(peso) end peso,bodega_produccion, case tipo
when 'P' then CONCAT('PRODUCCION DE:',FECHA_DOCUMENTO) WHEN 'CD' THEN CONCAT('REGISTRO DE CONTENEDOR: ',contenedor) end referencia  , case tipo
when 'P' then 'PRODUCCION' when 'CD' then 'CONTENEDOR'  end
as tipo from registro where (documento_inv_contenedor=@doc or documento_producion=@doc) and tipo!='C1'
group by codigo,tipo,documento_producion,documento_inv_contenedor,fecha_documento,bodega_produccion,contenedor

union all
select '' barra,art_origen codigo1,documento_inv_consumo doc,documento_inv_ing doc_2,
fecha,art_destino codigo,cantidad,0 peso,bodega,'liquidaciones' referencia, 'LIQUIDACION' TIPO FROM liquidaciones
WHERE documento_inv_consumo=@doc or documento_inv_ing=@doc
union all
select'','',documento_inv doc,'' doc_2,fecha,articulo,cantidad,precio,bodega,tipo,'AVERIAS'  from averias
WHERE DOCUMENTO_INV=@doc
order by 2
")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN RESULTADO DEL DOCUMENTO: $doc</h3>";
	}else
	{
		$k=0;
		echo "<table style='border-collapse:collapse; width:50%; float:left;' border='1' cellspadding='8'>";
		echo "<tr><td colspan='8'>INFORMACION SISTEMITA</td></tr>";
		$cantidad=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{

			if($k==0)
			{
				if($f['tipo']=='AVERIAS')
				{
					$total=$f['cantidad'] * $f['peso'];
					
					echo "<tr>
					<td>DOCUMENTO</td>
					<td>FECHA</td>
					<td>BODEGA</td>
					<td>TIPO</td>
					<td>ARTICULO</td>
					<td>CANTIDAD</td>
					<td>PRECIO</td>
					</tr>";
					$cod=$f['codigo'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					echo "<tr>
					<td>".$f['doc']."</td>
					<td>".$f['fecha']."</td>
					<td>".$f['bodega']."</td>
					<td>".$f['referencia']."</td>
					<td>".$fca['articulo']." </td>
					<td>".$f['cantidad']."</td>
					<td>".$f['peso']."</td>

					</tr>";
					$cantidad=$cantidad+$f['cantidad'];
				}else if($f['tipo']=='PRODUCCION' or $f['tipo']=='CONTENEDOR')
				{
					
					echo "<tr>
					<td>FECHA</td>
					<td>DOCUMENTO</td>
					<td>ARTICULO</td>
					<td>CANTIDAD</td>
					<td>PESO</td>
					<td>BODEGA</td>
					</tr>";
					$cod=$f['codigo1'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$articulo=$fca['articulo'];
					echo "<tr>
					<td>".$f['fecha']."</td>
					<td>".$f['doc']."</td>
					<td>$articulo</td>
					<td>".$f['cantidad']."</td>
					<td>".$f['peso']."</td>
					<td>".$f['bodega']."</td>
					</tr>";
					$cantidad=$cantidad+$f['cantidad'];
				}else if($f['tipo']=='DESGLOSE')
				{
					echo "<tr>
						<td>DOCUMENTO</td>
						<td>BARRA</td>
						<td>ARTICULO</td>
						<td>CANTIDAD</td>
						<td>BODEGA</td>
					</tr>";
					$cod=$f['codigo1'];
						$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
						$fca=$ca->FETCH(PDO::FETCH_ASSOC);
						$articulo=$fca['articulo'];
					echo "<tr>
						<td>".$f['doc']."</td>
						<td>".$f['barra']."</td>
						<td>$articulo</td>
						<td>1</td>
						<td>".$f['bodega']."</td>
					</tr>";
					$cod=$f['codigo'];
						$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
						$fca=$ca->FETCH(PDO::FETCH_ASSOC);
						$articulo=$fca['articulo'];
					echo "<tr>
						<td>".$f['doc_2']."</td>
						<td></td>
						<td>$articulo</td>
						<td>".$f['cantidad']."</td>
						<td></td>
					</tr>";
					$cantidad=$cantidad+$f['cantidad'];

				}else if($f['tipo']=='VENTA')
				{
					echo "<tr>
						<td>DOCUMENTO</td>
						<td>FECHA</td>
						<td>BODEGA</td>
						<td>ARTICULO</td>
						<td>CANTIDAD</td>
						<td>PESO</td>
					</tr>";
					$cod=$f['codigo1'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$articulo=$fca['articulo'];

					echo "<tr>
						<td>".$f['doc']."</td>
						<td>".$f['fecha']."</td>
						<td>".$f['bodega']."</td>
						<td>$articulo</td>
						<td>".$f['cantidad']."</td>
						<td>".$f['peso']."</td>
					</tr>";
					$cantidad=$cantidad+$f['cantidad'];

				}else if($f['tipo']=='TRASLADO')
				{
					echo "<tr>
						<td>DOCUMENTO</td>
						<td>FECHA</td>
						<td>BODEGAS</td>
						<td>ARTICULO</td>
						<td>CANTIDAD</td>
					</tr>";
					$cod=$f['codigo1'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$articulo=$fca['articulo'];

					echo "<tr>
						<td>".$f['doc']."</td>
						<td>".$f['fecha']."</td>
						<td>".$f['bodega']."</td>
						<td>$articulo</td>
						<td>".$f['cantidad']."</td>
					</tr>";
					$cantidad=$cantidad+$f['cantidad'];



				}else if($f['tipo']=='LIQUIDACION')
				{
					echo "<tr>
					<td>DOCUMENTO </td>
					<td>FECHA</td>
					<td>BODEGA</td>
					<td>ARTICULO</td>
					<td>CANTIDAD</td>
					</tr>";
					$cod1=$f['codigo1'];
					$ca1=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod1'")or  die($conexion1->error());
					$fca1=$ca1->FETCH(PDO::FETCH_ASSOC);
					$articulo1=$fca1['articulo'];
					$cod=$f['codigo'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$articulo=$fca['articulo'];
					//validacion liquidacion ajn o ajp

					$cla=$conexion2->query("declare @doc varchar(20)='$doc' select case when @doc like 'L-AJN-%' then 'consumo' else 'ingreso'end")or die($conexion2->error());
					$fcla=$cla->FETCH(PDO::FETCH_ASSOC);
					if($fcla['tipo']=='consumo')
					{
						echo "<tr>
					<td>".$f['doc']."</td>
					<td>".$f['fecha']."</td>
					<td>".$f['bodega']."</td>
					<td>$articulo</td>
					<td>".$f['cantidad']."</td>
					</tr>";
					}else
					{
						echo "<tr>
					<td>".$f['doc_2']."</td>
					<td>".$f['fecha']."</td>
					<td>".$f['bodega']."</td>
					<td>$articulo1</td>
					<td>".$f['cantidad']."</td>
					</tr>";
					}
					
					$cantidad=$cantidad+$f['cantidad'];
				}else if($f['tipo']=='MESA')
				{
					echo "<tr>
					<td>FECHA</td>
					<td>DOCUMENTO</td>
					<td>BODEGA</td>
					<td>ARTICULO</td>
					<td>CANTIDAD</td>
					<td>PESO</td>
					</tr>";
					$cod=$f['codigo'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$articulo=$fca['articulo'];
					echo "<tr>
					<td>".$f['fecha']."</td>
					<td>".$f['doc']."</td>
					<td>".$f['bodega']."</td>
					<td>$articulo</td>
					<td>".$f['cantidad']."</td>
					<td>".$f['peso']."</td>
					</tr>";

					$cantidad=$cantidad+$f['cantidad'];
				}

				

					//echo "<script>alert('llega')</script>";
				$k=1;
				}else if($k==1)
				{
					//Secho "entra<hr>";
					if($f['tipo']=='AVERIAS')
					{
						//echo "<script>alert('llega')</script>";
						$total=$f['cantidad']*$f['peso'];
						$cod=$f['codigo'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
						echo "<tr>
					<td>".$f['doc']."</td>
					<td>".$f['fecha']."</td>
					<td>".$f['bodega']."</td>
					<td>".$f['referencia']."</td>
					<td>".$fca['articulo']."</td>
					<td>".$f['cantidad']."</td>
					<td>".$f['peso']."</td>

					</tr>";
					$cantidad =$cantidad + $f['cantidad'];
					}else if($f['tipo']=='PRODUCCION' or $tipo=='CONTENEDOR')
					{
						$cod=$f['codigo1'];
						$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
						$fca=$ca->FETCH(PDO::FETCH_ASSOC);
						$articulo=$fca['articulo'];
						echo "<tr>
						<td>".$f['fecha']."</td>
						<td>".$f['doc']."</td>
						<td>$articulo</td>
						<td>".$f['cantidad']."</td>
						<td>".$f['peso']."</td>
						<td>".$f['bodega']."</td>
						</tr>";
						$cantidad=$cantidad+$f['cantidad'];
					}else if($tipo=='DESGLOSE')
					{
						$cod=$f['codigo'];
						$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
						$fca=$ca->FETCH(PDO::FETCH_ASSOC);
						$articulo=$fca['articulo'];
						echo "<tr>
						<td>".$f['doc_2']."</td>
						<td></td>
						<td>$articulo</td>
						<td>".$f['cantidad']."</td>
						<td></td>
					</tr>";
					$cantidad=$cantidad+$f['cantidad'];
					
					}else if($tipo=='VENTA')
					{
						$cod=$f['codigo1'];
						$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
						$fca=$ca->FETCH(PDO::FETCH_ASSOC);
						$articulo=$fca['articulo'];

						echo "<tr>
							<td>".$f['doc']."</td>
							<td>".$f['fecha']."</td>
							<td>".$f['bodega']."</td>
							<td>$articulo</td>
							<td>".$f['cantidad']."</td>
							<td>".$f['peso']."</td>
						</tr>";
						$cantidad=$cantidad +$f['cantidad'];
						}else if($tipo=='TRASLADO')
						{
							$cod=$f['codigo1'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$articulo=$fca['articulo'];

					echo "<tr>
						<td>".$f['doc']."</td>
						<td>".$f['fecha']."</td>
						<td>".$f['bodega']."</td>
						<td>$articulo</td>
						<td>".$f['cantidad']."</td>
					</tr>";
					$cantidad=$cantidad+$f['cantidad'];
						}else if($f['tipo']=='LIQUIDACION')
						{

					$cod1=$f['codigo1'];
					$ca1=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod1'")or  die($conexion1->error());
					$fca1=$ca1->FETCH(PDO::FETCH_ASSOC);
					$articulo1=$fca1['articulo'];
					$cod=$f['codigo'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$articulo=$fca['articulo'];
					//validacion liquidacion ajn o ajp

					$cla=$conexion2->query("declare @doc varchar(20)='$doc' select case when @doc like 'L-AJN-%' then 'consumo' else 'ingreso'end")or die($conexion2->error());
					$fcla=$cla->FETCH(PDO::FETCH_ASSOC);
					if($fcla['tipo']=='consumo')
					{
						echo "<tr>
					<td>".$f['doc']."</td>
					<td>".$f['fecha']."</td>
					<td>".$f['bodega']."</td>
					<td>$articulo</td>
					<td>".$f['cantidad']."</td>
					</tr>";
					}else
					{
						echo "<tr>
					<td>".$f['doc_2']."</td>
					<td>".$f['fecha']."</td>
					<td>".$f['bodega']."</td>
					<td>$articulo1</td>
					<td>".$f['cantidad']."</td>
					</tr>";
					}
								$cantidad=$cantidad+$f['cantidad'];
						}else if($f['tipo']=='MESA')
						{
							$cod=$f['codigo'];
					$ca=$conexion1->query("select concat(articulo,': ',descripcion) articulo from consny.articulo where articulo='$cod'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$articulo=$fca['articulo'];
					echo "<tr>
					<td>".$f['fecha']."</td>
					<td>".$f['doc']."</td>
					<td>".$f['bodega']."</td>
					<td>$articulo</td>
					<td>".$f['cantidad']."</td>
					<td>".$f['peso']."</td>
					</tr>";
					$cantidad=$cantidad+$f['cantidad'];
						}


					
				}
				$tipo=$f['tipo'];
			$doc=$f['doc'];
			$doc1=$f['doc_2'];
			}
			
		}//fin bucle sistemita
		if($tipo=='DESGLOSE')
		{
			echo "<tr><td colspan='3'>TOTAL ING</td><td>$cantidad</td></tr>";

		}else if($tipo=='VENTA' or $tipo=='TRASLADO')
		{
			echo "<tr><td colspan='4'>TOTAL</td><td>$cantidad</td></tr>";
		}else if($tipo=='LIQUIDACION')
		{
			echo "<tr><td colspan='4'>TOTAL</td><td>$cantidad</td></tr>";
		}else if($tipo=='MESA')
		{
			echo "<tr><td colspan='4'>TOTAL</td><TD>$cantidad</td></tr>";

		}else if($tipo=='AVERIAS')
		{
			echo "<tr><td colspan='5'>TOTAL</td><TD>$cantidad</td></tr>";
		}else 
		{
			echo "<tr><td colspan='3'>TOTAL</td><td>$cantidad</td></tr>";
		}
		
		//echo "sistemita $tipo $doc<hr>";
		echo "</table>";
			echo"<table border='1' cellspadding='8' style='width:49%; border-collapse:collapse; float:left; margin-left:0.01%;'>";
		echo "<tr>
		<td colspan='6'>INFORMACION EXACTUS</td>
		</tr>";
		if($tipo=='AVERIAS')
		{	//inicio exactus
		
		echo "<tr>
		<td>DOCUMENTO</td>
		<td>BODEGA</td>
		<td>ARTICULO</td>
		<td>CANTIDAD</td>
		<td>USUARIO</td>
		<td>ESTADO</td>
		</tr>";
		//echo "<script>alert('')</script>";
		$ce=$conexion1->query("declare @doc varchar(20)='$doc'

select consny.documento_inv.documento_inv,consny.linea_doc_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,convert(int,consny.linea_doc_inv.cantidad) cantidad,
consny.documento_inv.usuario,'SIN APLICAR' estado from consny.documento_inv inner join
consny.linea_doc_inv on consny.documento_inv.documento_inv=consny.linea_doc_inv.documento_inv
inner join consny.articulo on consny.linea_doc_inv.articulo=consny.articulo.articulo
where consny.documento_inv.documento_inv=@doc

union all
select audit_trans_inv.aplicacion documento_inv,consny.transaccion_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,
convert(int,consny.transaccion_inv.cantidad) cantidad,consny.audit_trans_inv.usuario_apro usuario,
'APLICADO' estado from consny.audit_trans_inv inner join consny.transaccion_inv on 
consny.audit_trans_inv.audit_trans_inv=consny.transaccion_inv.audit_trans_inv  inner 
join consny.articulo on consny.articulo.articulo=consny.transaccion_inv.articulo where
consny.audit_trans_inv.aplicacion=@doc
")or die($conexion->error());
	$nce=$ce->rowCount();
	if($nce==0)
	{
		echo "<tr>
		<td colspan='6'>DOCUMENTO BORRADO DE EXACTUS</td>
		</tr>";
	}else
	{
		$cantidadex=0;
		while($fce=$ce->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
		<td>".$fce['documento_inv']."</td>
		<td>".$fce['bodega']."</td>
		<td>".$fce['articulo']."</td>
		<td>".$fce['cantidad']."</td>
		<td>".$fce['usuario']."</td>
		<td>".$fce['estado']."</td>
		</tr>";
		$cantidadex=$cantidadex+$fce['cantidad'];
		}
		echo "<tr><td colspan='3'>TOTAL</td><td>$cantidadex</td></tr>";
	}

}//fin averias
else if($tipo=='PRODUCCION' or $tipo=='CONTENEDOR')
{
	$ce=$conexion1->query("declare @doc varchar(20)='$doc'

select consny.documento_inv.documento_inv,consny.linea_doc_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,sum(convert(int,consny.linea_doc_inv.cantidad)) cantidad,
consny.documento_inv.usuario,'SIN APLICAR' estado from consny.documento_inv inner join
consny.linea_doc_inv on consny.documento_inv.documento_inv=consny.linea_doc_inv.documento_inv
inner join consny.articulo on consny.linea_doc_inv.articulo=consny.articulo.articulo
where consny.documento_inv.documento_inv=@doc group by consny.DOCUMENTO_INV.DOCUMENTO_INV,
consny.LINEA_DOC_INV.BODEGA,concat(consny.articulo.articulo,': ',consny.articulo.descripcion),
consny.DOCUMENTO_INV.USUARIO

union all
select audit_trans_inv.aplicacion documento_inv,consny.transaccion_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,
sum(convert(int,consny.transaccion_inv.cantidad)) cantidad,consny.audit_trans_inv.usuario_apro usuario,
'APLICADO' estado from consny.audit_trans_inv inner join consny.transaccion_inv on 
consny.audit_trans_inv.audit_trans_inv=consny.transaccion_inv.audit_trans_inv  inner 
join consny.articulo on consny.articulo.articulo=consny.transaccion_inv.articulo where
consny.audit_trans_inv.aplicacion=@doc group by concat(consny.articulo.articulo,': ',consny.articulo.descripcion),
consny.AUDIT_TRANS_INV.APLICACION,consny.TRANSACCION_INV.BODEGA,consny.AUDIT_TRANS_INV.USUARIO_APRO
")or die($conexion1->error());

	$nce=$ce->rowCount();
	if($nce==0)
	{
		echo "<tr>
			<td colspan='4'>DOCUMENTO FUE BORRADO DE EXACTUS</td>
		</tr>";
	}else
	{
		echo "<tr>
			<td>DOCUMENTO</td>
			<td>ARTICULO</td>
			<td>CANTIDAD</td>
			<td>USUARIO</td>
			<td>ESTADO</td>
		</tr>";
		$cantidadex=0;
		while($fce=$ce->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
			<td>".$fce['documento_inv']."</td>
			<td>".$fce['articulo']."</td>
			<td>".$fce['cantidad']."</td>
			<td>".$fce['usuario']."</td>
			<td>".$fce['estado']."</td>
		</tr>";
		$cantidadex=$cantidadex+$fce['cantidad'];
		}
		echo "<tr><td colspan='2'>TOTAL</td><td>$cantidadex</td></tr>";
		//echo "<script>alert('$cantidad - $cantidadex')</script>";
	}
}else if($tipo=='DESGLOSE')
{
	
	echo "<tr>
	<td>DOCUMENTO</td>
	<td>ARTICULO</td>
	<td>CANTIDAD</td>
	<td>BODEGA</td>
	<td>USUARIO</td>
	<td>ESTADO</td>
	</tr>";
	$ce=$conexion1->query("declare @doc varchar(20)='$doc'

select consny.documento_inv.documento_inv,consny.linea_doc_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,sum(convert(int,consny.linea_doc_inv.cantidad)) cantidad,
consny.documento_inv.usuario,'SIN APLICAR' estado from consny.documento_inv inner join
consny.linea_doc_inv on consny.documento_inv.documento_inv=consny.linea_doc_inv.documento_inv
inner join consny.articulo on consny.linea_doc_inv.articulo=consny.articulo.articulo
where consny.documento_inv.documento_inv=@doc group by consny.DOCUMENTO_INV.DOCUMENTO_INV,
consny.LINEA_DOC_INV.BODEGA,concat(consny.articulo.articulo,': ',consny.articulo.descripcion),
consny.DOCUMENTO_INV.USUARIO

union all
select audit_trans_inv.aplicacion documento_inv,consny.transaccion_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,
sum(convert(int,consny.transaccion_inv.cantidad)) cantidad,consny.audit_trans_inv.usuario_apro usuario,
'APLICADO' estado from consny.audit_trans_inv inner join consny.transaccion_inv on 
consny.audit_trans_inv.audit_trans_inv=consny.transaccion_inv.audit_trans_inv  inner 
join consny.articulo on consny.articulo.articulo=consny.transaccion_inv.articulo where
consny.audit_trans_inv.aplicacion=@doc group by concat(consny.articulo.articulo,': ',consny.articulo.descripcion),
consny.AUDIT_TRANS_INV.APLICACION,consny.TRANSACCION_INV.BODEGA,consny.AUDIT_TRANS_INV.USUARIO_APRO
")or die($conexion1->error());

	$nce=$ce->rowCount();
	if($nce==0)
	{
		echo "<tr>
			<td colspan='6'>DOCUMENTO FUE BORRADO DE EXACTUS</td>
		</tr>";
	}else
	{
		$fce=$ce->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
			<td>".$fce['documento_inv']."</td>
			<td>".$fce['articulo']."</td>
			<td>".$fce['cantidad']."</td>
			<td>".$fce['bodega']."</td>
			<td>".$fce['usuario']."</td>
			<td>".$fce['estado']."</td>
		</tr>";

	}

//ing exactus
//echo "<script>alert('$doc1 <--')</script>";
$ce=$conexion1->query("declare @doc varchar(20)='$doc1'

select consny.documento_inv.documento_inv,consny.linea_doc_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,sum(convert(int,consny.linea_doc_inv.cantidad)) cantidad,
consny.documento_inv.usuario,'SIN APLICAR' estado from consny.documento_inv inner join
consny.linea_doc_inv on consny.documento_inv.documento_inv=consny.linea_doc_inv.documento_inv
inner join consny.articulo on consny.linea_doc_inv.articulo=consny.articulo.articulo
where consny.documento_inv.documento_inv=@doc group by consny.DOCUMENTO_INV.DOCUMENTO_INV,
consny.LINEA_DOC_INV.BODEGA,concat(consny.articulo.articulo,': ',consny.articulo.descripcion),
consny.DOCUMENTO_INV.USUARIO

union all
select audit_trans_inv.aplicacion documento_inv,consny.transaccion_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,
sum(convert(int,consny.transaccion_inv.cantidad)) cantidad,consny.audit_trans_inv.usuario_apro usuario,
'APLICADO' estado from consny.audit_trans_inv inner join consny.transaccion_inv on 
consny.audit_trans_inv.audit_trans_inv=consny.transaccion_inv.audit_trans_inv  inner 
join consny.articulo on consny.articulo.articulo=consny.transaccion_inv.articulo where
consny.audit_trans_inv.aplicacion=@doc group by concat(consny.articulo.articulo,': ',consny.articulo.descripcion),
consny.AUDIT_TRANS_INV.APLICACION,consny.TRANSACCION_INV.BODEGA,consny.AUDIT_TRANS_INV.USUARIO_APRO order by 3
")or die($conexion1->error());

	$nce=$ce->rowCount();
	if($nce==0)
	{
		echo "<tr>
			<td colspan='6'>DOCUMENTO FUE BORRADO DE EXACTUS</td>
		</tr>";
	}else
	{
		$cantidadex=0;
		while($fce=$ce->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
			<td>".$fce['documento_inv']."</td>
			<td>".$fce['articulo']."</td>
			<td>".$fce['cantidad']."</td>
			<td>".$fce['bodega']."</td>
			<td>".$fce['usuario']."</td>
			<td>".$fce['estado']."</td>
		</tr>";
		$cantidadex=$cantidadex+$fce['cantidad'];

		}
		echo "<tr><td colspan='2'>TOTAL ING</td><td>$cantidadex</td></tr>";
		
}
}else if($tipo=='VENTA')
	{

		echo "<tr>
		<td>DOCUMENTO</td>
		<td>BODEGA</td>
		<td>ARTICULO</td>
		<td>CANTIDAD</td>
		<td>USUARIO</td>
		<td>ESTADO</td>
		</tr>";
		$ce=$conexion1->query("declare @doc varchar(20)='$doc'

select consny.documento_inv.documento_inv,consny.linea_doc_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,sum(convert(int,consny.linea_doc_inv.cantidad)) cantidad,
consny.documento_inv.usuario,'SIN APLICAR' estado from consny.documento_inv inner join
consny.linea_doc_inv on consny.documento_inv.documento_inv=consny.linea_doc_inv.documento_inv
inner join consny.articulo on consny.linea_doc_inv.articulo=consny.articulo.articulo
where consny.documento_inv.documento_inv=@doc group by consny.DOCUMENTO_INV.DOCUMENTO_INV,
consny.LINEA_DOC_INV.BODEGA,concat(consny.articulo.articulo,': ',consny.articulo.descripcion),
consny.DOCUMENTO_INV.USUARIO 

union all
select audit_trans_inv.aplicacion documento_inv,consny.transaccion_inv.bodega,
concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,
sum(convert(int,consny.transaccion_inv.cantidad)) cantidad,consny.audit_trans_inv.usuario_apro usuario,
'APLICADO' estado from consny.audit_trans_inv inner join consny.transaccion_inv on 
consny.audit_trans_inv.audit_trans_inv=consny.transaccion_inv.audit_trans_inv  inner 
join consny.articulo on consny.articulo.articulo=consny.transaccion_inv.articulo where
consny.audit_trans_inv.aplicacion=@doc group by concat(consny.articulo.articulo,': ',consny.articulo.descripcion),
consny.AUDIT_TRANS_INV.APLICACION,consny.TRANSACCION_INV.BODEGA,consny.AUDIT_TRANS_INV.USUARIO_APRO
")or die($conexion1->error());
	$nce=$ce->rowCount();
	if($nce==0)
	{
		echo "<tr><td>DOCUMENTO BORRADO DE EXACTUS</td></tr>";
	}else
	{
		$cantidadex=0;
		while($fce=$ce->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
			<td>".$fce['documento_inv']."</td>
			<td>".$fce['bodega']."</td>
			<td>".$fce['articulo']."</td>
			<td>".$fce['cantidad']."</td>
			<td>".$fce['usuario']."</td>
			<td>".$fce['estado']."</td>
			<td>SIN APLICAR</td>
			</tr>";
			$cantidadex=$cantidadex+$fce['cantidad'];
		}
		echo "<tr><td colspan='3'>TOTAL</td><td>$cantidadex</td></tr>";
		

	}



}else if($tipo=='TRASLADO')
{
	echo "<tr>
	<td>DOCUMENTO</td>
	<td>BODEGAS</td>
	<td>ARTICULO</td>
	<td>CANTIDAD</td>
	<td>USUARIO</td>
	<td>ESTADO</td>
	</tr>";
	$ce=$conexion1->query("select consny.documento_inv.documento_inv,concat(consny.linea_doc_inv.bodega,'>',
consny.linea_doc_inv.bodega_destino) bodega,concat(consny.articulo.articulo,': ',
consny.articulo.descripcion) articulo,convert(int,sum(consny.linea_doc_inv.cantidad)) cantidad,'SIN APLICAR' estado,CONSNY.DOCUMENTO_INV.usuario from 
consny.documento_inv inner join consny.linea_doc_inv on consny.documento_inv.
documento_inv=consny.linea_doc_inv.documento_inv inner join consny.articulo on
consny.articulo.articulo=consny.linea_doc_inv.articulo where
consny.documento_inv.documento_inv='$doc' group by consny.documento_inv.
documento_inv,consny.linea_doc_inv.bodega,consny.linea_doc_inv.bodega_destino,
consny.articulo.articulo,consny.articulo.descripcion,CONSNY.DOCUMENTO_INV.usuario order by consny.articulo.articulo
")or die($conexion1->error());
	$nce=$ce->rowCount();
	if($nce==0)
	{
		$qe=$conexion1->query("select bodega,naturaleza from consny.transaccion_inv where audit_trans_inv in
(select audit_trans_inv from consny.audit_trans_inv where aplicacion='$doc')
group by bodega,naturaleza order by naturaleza")or die($conexion1->error());
		$nqe=$qe->rowCount();
		if($nqe==0)
		{
			echo "<tr><td colspan='6'>DOCUMENTO ELIMINADO DE EXACTUS</td></tr>";
		}else
		{
			$bodegae=''; $bodegas=''; $cantidadex=0;
			while($fqe=$qe->FETCH(PDO::FETCH_ASSOC))
			{
				$naturaleza=$fqe['naturaleza'];
				if($fqe['naturaleza']=='E')
				{
					$bodegae=$fqe['bodega'];
			//echo "<script>alert('$bodegae entrada')</script>";
				}else
				{
					$bodegas=$fqe['bodega'];
			//echo "<script>alert('$bodegas salida')</script>";
				}
			//echo "<script>alert('/ $bodegas - $bodegae% $naturaleza')</script>";
				
			}
			$bodega="$bodegas > $bodegae";
			//echo "<script>alert('$bodega')</script>";
			$ce=$conexion1->query("select concat(consny.articulo.articulo,': ',consny.articulo.descripcion) articulo,
sum(convert(int,consny.transaccion_inv.cantidad)) cantidad,consny.audit_trans_inv.usuario,
consny.audit_trans_inv.aplicacion,'$bodega' bodega from consny.audit_trans_inv inner join consny.
transaccion_inv on consny.audit_trans_inv.audit_trans_inv=consny.transaccion_inv.
audit_trans_inv inner join consny.articulo on consny.articulo.articulo=
consny.transaccion_inv.articulo where consny.audit_trans_inv.aplicacion='$doc'
and consny.transaccion_inv.naturaleza='E'
group by consny.articulo.articulo,consny.articulo.descripcion,consny.audit_trans_inv.usuario,
consny.audit_trans_inv.aplicacion,consny.transaccion_inv.cantidad order by
consny.articulo.articulo")or die($conexion1->error());
			while($fce=$ce->FETCH(PDO::FETCH_ASSOC))
			{
				echo "<tr>
					<td>".$fce['aplicacion']."</td>
					<td>$bodega</td>
					<td>".$fce['articulo']."</td>
					<td>".$fce['cantidad']."</td>
					<td>".$fce['usuario']."</td>
					<td>APLICADO</td>
					</tr>";
					$cantidadex=$cantidadex+$fce['cantidad'];
			}
		}
	}else
	{
		
		$cantidadex=0;
		while($fce=$ce->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
			<td>".$fce['documento_inv']."</td>
			<td>".$fce['bodega']."</td>
			<td>".$fce['articulo']."</td>
			<td>".$fce['cantidad']."</td>
			<td>".$fce['usuario']."</td>
			<td>SIN APLICAR</td>
		</tr>";
		$cantidadex=$cantidadex+$fce['cantidad'];
		}
	}

	echo "<tr><td colspan='3'>TOTAL</td><td>$cantidadex</td></tr>";

}else if($tipo=='LIQUIDACION')//fin traslado exactus
{
	echo "<tr>
	<td>DOCUMENTO</td>
	<td>FECHA</td>
	<td>USUARIO</td>
	<td>ARTICULO</td>
	<td>CANTIDAD</td>
	<td>BODEGA</td>
	<td>APLICADO</TD>
	</tr>";
	$ce=$conexion1->query("declare @doc varchar(20)='$doc'

SELECT consny.documento_inv.documento_inv,convert(date,consny.DOCUMENTO_INV.FECHA_DOCUMENTO) fecha,
consny.DOCUMENTO_INV.usuario,concat(consny.ARTICULO.ARTICULO,': ',consny.ARTICULO.
DESCRIPCION) articulo,sum(CONVERT(int,consny.linea_doc_inv.cantidad)) cantidad,
consny.LINEA_DOC_INV.BODEGA,'NO' aplicado from consny.DOCUMENTO_INV inner join
consny.LINEA_DOC_INV on consny.DOCUMENTO_INV.DOCUMENTO_INV=consny.LINEA_DOC_INV.
DOCUMENTO_INV inner join consny.ARTICULO on consny.ARTICULO.ARTICULO=consny.LINEA_DOC_INV.
ARTICULO where consny.DOCUMENTO_INV.DOCUMENTO_INV=@doc group by consny.DOCUMENTO_INV.
DOCUMENTO_INV,consny.DOCUMENTO_INV.FECHA_DOCUMENTO,consny.DOCUMENTO_INV.USUARIO,
consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.LINEA_DOC_INV.BODEGA

union all
select consny.audit_trans_inv.aplicacion,convert(date,consny.transaccion_inv.fecha),consny.audit_trans_inv.USUARIO_APRO usuario,
concat(consny.ARTICULO.ARTICULO,': ',consny.ARTICULO.DESCRIPCION) articulo,sum(convert(int,
consny.transaccion_inv.cantidad)),consny.TRANSACCION_INV.BODEGA,'SI' from consny.AUDIT_TRANS_INV
inner join consny.TRANSACCION_INV on consny.AUDIT_TRANS_INV.AUDIT_TRANS_INV=consny.TRANSACCION_INV.
AUDIT_TRANS_INV inner join consny.ARTICULO on consny.ARTICULO.ARTICULO=consny.TRANSACCION_INV.
ARTICULO where consny.AUDIT_TRANS_INV.APLICACION=@doc group by consny.AUDIT_TRANS_INV.
AUDIT_TRANS_INV,consny.TRANSACCION_INV.FECHA,consny.AUDIT_TRANS_INV.USUARIO_APRO,
consny.TRANSACCION_INV.ARTICULO,consny.TRANSACCION_INV.BODEGA,consny.AUDIT_TRANS_INV.
APLICACION,consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION

")or die($conexion1->error());
$nce=$ce->rowCount();
if($nce==0)
{
echo "<tr><td colspan='7'>DOCUMENTO ELIMINADO DE EXACTUS</td></tr>";
}else
{
	$tl=0;
	while($fce=$ce->FETCH(PDO::FETCH_ASSOC))
	{
		echo "<tr>
	<td>".$fce['documento_inv']."</td>
	<td>".$fce['fecha']."</td>
	<td>".$fce['usuario']."</td>
	<td>".$fce['articulo']."</td>
	<td>".$fce['cantidad']."</td>
	<td>".$fce['BODEGA']."</td>
	<td>".$fce['aplicado']."</TD>
	</tr>";
	$tl=$tl+$fce['cantidad'];
	}
	echo "<tr><td colspan='4'>TOTAL</td><td>$tl</td>";
}
	
}else if($tipo=='MESA')
{
	$ce=$conexion1->query("declare @doc varchar(20)='$doc'

select consny.DOCUMENTO_INV.DOCUMENTO_INV documento,consny.DOCUMENTO_INV.USUARIO,
CONCAT(consny.ARTICULO.ARTICULO,': ',consny.ARTICULO.DESCRIPCION) articulo,
'SIN APLICAR' estado,
sum(convert(int,consny.linea_doc_inv.cantidad)) as cantidad,consny.LINEA_DOC_INV.bodega
from consny.DOCUMENTO_INV inner join consny.LINEA_DOC_INV on consny.DOCUMENTO_INV.
DOCUMENTO_INV=consny.LINEA_DOC_INV.DOCUMENTO_INV inner join consny.ARTICULO on
consny.ARTICULO.ARTICULO=consny.LINEA_DOC_INV.ARTICULO where consny.DOCUMENTO_INV.
DOCUMENTO_INV=@doc group by consny.DOCUMENTO_INV.DOCUMENTO_INV,consny.DOCUMENTO_INV.
USUARIO,consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.LINEA_DOC_INV.BODEGA



union all
select consny.AUDIT_TRANS_INV.APLICACION documento,consny.AUDIT_TRANS_INV.USUARIO_APRO usuario,
CONCAT(consny.ARTICULO.ARTICULO,': ',consny.ARTICULO.DESCRIPCION) articulo,'APLICADO'
estado,SUM(CONVERT(int,consny.transaccion_inv.cantidad)) cantidad,consny.TRANSACCION_INV.
bodega from consny.AUDIT_TRANS_INV inner join consny.TRANSACCION_INV on
consny.AUDIT_TRANS_INV.AUDIT_TRANS_INV=consny.TRANSACCION_INV.AUDIT_TRANS_INV inner
join consny.ARTICULO on  consny.TRANSACCION_INV.ARTICULO=consny.ARTICULO.ARTICULO
where consny.AUDIT_TRANS_INV.APLICACION=@doc group by consny.AUDIT_TRANS_INV.APLICACION,
consny.AUDIT_TRANS_INV.USUARIO,consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,
consny.TRANSACCION_INV.BODEGA,consny.AUDIT_TRANS_INV.USUARIO_APRO

")or die($conexion1->error());
	$nce=$ce->rowCount();
	if($nce==0)
	{
		echo "<tr><td colspan='6'>DOCUMENTO FUE ELIMINADO DE EXACTUS</td></tr>";
	}else
	{
		echo "<tr>
			<td>DOCUMENTO</td>
			<td>BODEGA</td>
			<td>ARTICULO</td>
			<td>CANTIDAD</td>
			<td>USUARIO</td>
			<td>ESTADO</td>
		</tr>";
		$cantidadex=0;
		while($fce=$ce->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
			<td>".$fce['documento']."</td>
			<td>".$fce['bodega']."</td>
			<td>".$fce['articulo']."</td>
			<td>".$fce['cantidad']."</td>
			<td>".$fce['USUARIO']."</td>
			<td>".$fce['estado']."</td>
		</tr>";
		$cantidadex=$cantidadex+$fce['cantidad'];
		}
		echo "<tr><td colspan='3'>TOTAL</td><td>$cantidadex</td></tr>";
	}
}

}//FIN POST

?>
</body>
</html>