<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<style>
		a{
			text-decoration: none;
		}
	</style>
	<script>
		$(document).ready(function(){
			$("#primera").hide();
			$("#img").hide();
			$("#primera").show();

		})
	</script>
</head>
<body>
	<div style="width: 110%; height: 110%; position: fixed; float: center; margin-left: -5%; margin-top:-2%; background-color: white;" id="img">
<img src="loadf.gif" style="float: center; margin-left: 40%; margin-top: 15%; width: 20%; height: 22%;">
</div>
<?php
include("conexion.php");
if($_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ' or $_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='OCAMPOS' or $_SESSION['tipo']==5 or $_SESSION['tipo']==3)
{

}else
{
	
	echo "<script>location.replace('conexiones.php')</script>";
}
?>
<h3 style="text-decoration: underline; text-align: center;">REPORTE CUADRO VENTA(DETALLE)</h3>
<form method="POST" style="text-align: center;">
<select name="bodega" class="text" style="width: 18.5%;">
	<option value="">BODEGA</option>
<?php
	$cb=$conexion1->query("select * from consny.bodega where bodega not like 'SM%' and nombre not like '%(N)%' order by bodega")or die($conexion1->error());
	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bod=$fcb['BODEGA'];
		$nom=$fcb['NOMBRE'];
		echo "<option value='$bod'>$bod: $nom</option>";
	}
?>
</select>	
<label>DESDE: <input type="date" name="desde" class="text" style="width: 10%;" required></label>
<label>HASTA: <input type="date" name="hasta" class="text" style="width: 10%;" required></label>
<input type="submit" name="btn" class="btnfinal" value="GENERAR" style="padding: 0.7%; background-color: #D7E9DC; cursor: pointer; color: #000;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($bodega=='')
	{
		$c=$conexion1->query("select usuariobodega.bodega from usuariobodega  inner join consny.bodega on usuariobodega.bodega=consny.bodega.bodega where usuariobodega.bodega like 'C%' or usuariobodega.bodega like 'E%' or usuariobodega.bodega like 'N%' and consny.bodega.nombre not like '%(N)%' group by usuariobodega.bodega order by usuariobodega.bodega")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("select bodega from usuariobodega where bodega='$bodega' group by bodega order by bodega")or die($conexion1->error());
	}
	echo "<a href='export_reporte_cuadro_venta.php?bodega=$bodega&&desde=$desde&&hasta=$hasta' target='_blank'>Exportar a Excel</a>";
echo "<table border='1' style='border-collapse:collapse; width:200%;margin-bottom:5%;'>";
echo "<tr style='position:sticky; top:0;  color:black; text-align:center; border-collapse:collapse; border-top-color:blue;' id='primera'>
	<td width='2%' style='background-color:white;'>FECHA</td>
	<td width='8%' style='background-color:white; width:10%;'>BODEGA</td>
	<td width='5.5%' style='background-color:white;'>MONTO TIENDA</td>
	<td width='5.5%' style='background-color:white;'>MONTO SISTEMA</td>
	<td width='5.5%' style='background-color:white;'>TOTAL INGRESOS</td>
	<td width='5.5%' style='background-color:white;'>TOTAL SALIDAS</td>
	<td width='5.5%' style='background-color:white;'>TOTAL DESCUENTOS</td>
	<td width='5.5%' style='background-color:white;'>MONTO LIQUIDO</td>
	<td width='5.5%' style='background-color:white;'>FARDOS VENDIDOS</td>
	<td width='5.5%' style='background-color:white;'>MONTO FARDOS</td>
	<td width='5.5%' style='background-color:white;'width='10%'>TOTAL LIQUIDACIONES</td>
	<td width='5.5%' style='background-color:white;'>FARDOS ABIERTOS</td>
	<td width='5.5%' style='background-color:white;'>TOTAL DESGLOSE</td>
	<td width='5.5%' style='background-color:white;'>TOTAL AVERIAS</td>
	<td width='5.5%' style='background-color:white; width:9%'>TOTAL MERCA. NO VENDIBLE</td>
</tr>";
$fecha1=$desde;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		
		$bodega=$f['bodega'];
		$desde=$fecha1;
		//echo "<script>alert('$bodega')</script>";
		while($desde<=$hasta)
		{
			$cantidad_desglose=0;
			$total_desglose=0;
			$liquidacion=0;
			$averias=0;
			$merca=0;
			$monto_fardo=0;
			$monto_liquido=0;
			$monto_usuario=0;
			$monto_sistema=0;
			$monto_fardo=0;
			$fardos_vendidos=0;
			$descuentos=0;
			$fecha=$desde;
			$q=$conexion1->query("declare @fecha date='$fecha'

select dateadd(day,1,@fecha) as fecha
")or die($conexion1->error());
			$fq=$q->FETCH(PDO::FETCH_ASSOC);
			$desde=$fq['fecha'];
	//inicio cuadro venta/detalle
		$cc=$conexion1->query("select * from cuadro_venta where bodega='$bodega' and fecha='$fecha'")or die($conexion1->error());
		$nc=$cc->rowCount();
		$fc=$cc->FETCH(PDO::FETCH_ASSOC);
		$id=$fc['ID'];
		$monto_usuario=$fc['MONTO_USUARIO'];
		$monto_sistema=$fc['MONTO_SISTEMA'];
		$monto_liquido=$fc['MONTO_LIQUIDO'];
		$estado=$fc['ESTADO'];
		$monto_fardo=$fc['TOTAL_FARDO'];
		$fardos_vendidos=$fc['FARDOS_VENDIDOS'];
		$descuentos=$fc['DESCUENTO'];
		$cd=$conexion1->query("select sum(convert(decimal(10,2),monto))as monto,tipo_transaccion from cuadro_venta_detalle where cuadro_venta='$id' group by tipo_transaccion")or die($conexion1->error());
		$ingreso=0; $salida=0;
	while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
	{
		$tipo=$fcd['tipo_transaccion'];
		$monto=$fcd['monto'];
		if($tipo=='SALIDA')
		{
			$salida=$salida + $monto;
		}else
		{
			$ingreso=$ingreso + $monto;
		}
	}
	$monto_liquido=$monto_usuario+$ingreso;
	$monto_liquido=$monto_liquido -$salida;
	//FIN cuadro venta/detalle

	//INICIO LIQUIDACIONES
	$cl=$conexion2->query("select isnull(convert(decimal(10,2),sum((precio_origen * cantidad)-(precio_destino * cantidad))),0.00) as liquidacion from liquidaciones where fecha='$fecha' and bodega='$bodega'")or die($conexion2->error());
	$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
	$liquidacion=$fcl['liquidacion'];
	//FIN LIQUIDACIONES

	//inicio desgloses
	$cd=$conexion2->query("select(select count(*) from registro where fecha_desglose='$fecha' and bodega='$bodega')as cantidad, (select sum(precio*cantidad)as total from desglose where registro in(select id_registro from registro where fecha_desglose='$fecha' and bodega='$bodega')) as total
")or die($conexion2->error());

	$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
	$cantidad_desglose=$fcd['cantidad'];
	$total_desglose=$fcd['total'];


	//fin desglose

	//inicio averias
	$ca=$conexion2->query("select sum(convert(decimal(10,2),(precio * cantidad))) as total,tipo from averias where fecha='$fecha' and bodega='$bodega' group by tipo
")or die($conexion2->error());
while($fca=$ca->FETCH(PDO::FETCH_ASSOC))
{
	$tipo=$fca['tipo'];
	if($tipo=='AVERIA')
	{
		$averias=$averias + $fca['total'];
	}else
	{
		$merca=$merca + $fca['total'];
	}
}

	//fin averias
	if($nc==0 and ($_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='jdelao' or $_SESSION['usuario']=='JDELAO' or $_SESSION['usuario']=='falvarez' or $_SESSION['usuario']=='FALVAREZ'))
		{
			echo "<tr style='background-color:#EAFD07;'>";

		}else
		{
			if($estado==0 and ($_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='OCAMPOS' or $_SESSION['usuario']=='JDELAO' OR $_SESSION['usuario']=='jdelao' OR $_SESSION['tipo']==5))
			{
				echo "<tr style='background-color:red;'>";
			}else
			{
				echo "<tr>";
			}
		}
		if($monto_usuario=='')
		{
			$monto_usuario="0.00";
		}
		if($monto_sistema=='')
		{
			$monto_sistema="0.00";
		}
		if($descuentos=='')
		{
			$descuentos="0.00";
		}
		if($fardos_vendidos=='')
		{
			$fardos_vendidos="0.00";
		}
		if($monto_fardo=='')
		{
			$monto_fardo="0.00";
		}
		if($total_desglose=='')
		{
			$total_desglose="0.00";
		}
		if($ingreso==0)
		{
			$ingreso="0.00";
		}
		if($salida==0)
		{
			$salida="0.00";
		}
		if($monto_liquido==0)
		{
			$monto_liquido="0.00";
		}

		$eliquidacion=explode(".", $liquidacion);
		if($eliquidacion[0]=='')
		{
			if($eliquidacion[1]=='')
			{
				$liquidacion="0.00";
			}else
			{
				$liquidacion="0.$eliquidacion[1]";
			}
		}

		$emonto_fardo=explode(".", $monto_fardo);
		if($emonto_fardo[0]=='')
		{
			if($emonto_fardo[1]=='')
			{
				$monto_fardo="0.00";
			}else
			{
				$monto_fardo="0.$emonto_fardo[1]";
			}
		}
		$efardos_vendidos=explode(".", $fardos_vendidos);
		if($efardos_vendidos[0]=='')
		{
			if($efardos_vendidos[1]=='')
			{
				$fardos_vendidos="0.00";
			}else
			{
				$fardos_vendidos="0.$efardos_vendidos[1]";
			}
		}

		$edescuento=explode(".", $descuentos);
		if($edescuento[0]=='')
		{
			if($edescuento[1]=='')
			{
				$descuentos="0.00";
			}else
			{
				$descuentos="0.$edescuento[1]";
			}
		}

		$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bod="".$fcb['BODEGA'].": ".$fcb['NOMBRE']."";
	if($bodega!='CA16')
	{


	echo "
	<td>$fecha</td>
	<td>$bod</td>
	<td>$monto_usuario</td>";
	if($monto_sistema==0 and ($_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='JDELAO' OR $_SESSION['usuario']=='jdelao' OR $_SESSION['tipo']==5))
	{

		echo "<td style='background-color:green; color:white;'>";
	}else
	{
		echo "<td>";
	}
	echo "<a href='detalle_monto_sistema.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$monto_sistema</a></td>
	<td><a href='detalle_cuadro_venta.php?fecha=$fecha&&bodega=$bodega&&tipo=INGRESO' target='_blank'>$ingreso</a></td>
	<td><a href='detalle_cuadro_venta.php?fecha=$fecha&&bodega=$bodega&&tipo=SALIDA' target='_blank'>$salida</a></td>
	<td><a href='detalle_descuento.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$descuentos</a></td>
	<td><a href='detalle_cuadro_venta.php?fecha=$fecha&&bodega=$bodega&&tipo=liquido' target='_blank'>$monto_liquido</a></td>
	<td><a href='detalle_fardos.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$fardos_vendidos</a></td>
	<td><a href='detalle_fardos.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$monto_fardo</a></td>
	<td><a href='detalle_liquidaciones.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$liquidacion</a></td>
	<td><a href='detalle_desgloses.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$cantidad_desglose</a></td>
	<td><a href='detalle_desgloses.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$total_desglose</a></td>
	<td><a href='detalle_averia.php?fecha=$fecha&&bodega=$bodega&&tipo=AVERIA' target='_blank'>$averias</a></td>
	<td><a href='detalle_averia.php?fecha=$fecha&&bodega=$bodega&&tipo=MERCA' target='_blank'>$merca</a></td>
</tr>";
}


		}
	}
}
?>
</body>
</html>