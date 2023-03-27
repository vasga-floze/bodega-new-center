<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		a:hover{
			color: white;
		}
		a{
			text-decoration: none;
			color: black;
		}
		
	</style>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			
			$("#load").hide();

		})
		function enviar()
		{
			$("#load").show();
			$("#load").hide();
		}
	</script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
?>
<img src="load2.gif" id="load" width="110%" height="120%" style="position: fixed; margin-top: -5%; margin-left: -10%;">
<h3 style="text-align: center; margin-top: -0.5%;">REPORTE CUADRO VENTA</h3>
<form method="POST" style="text-align: center;">
<select name="bodega" class="text" style="padding: 0.5%; width: 20%;">
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
<input type="date" name="desde" class="text" style="padding: 0.5%; width: 20%;" required>
<input type="date" name="hasta" class="text" style="padding: 0.5%; width: 20%;" required>
<input type="submit" name="btn" value="GENERAR" class="btn" style="padding: 0.5%; background-color: #D7E9DC; cursor: pointer;" onclick="enviar()">

</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	
		if($bodega=='')
		{
			$c=$conexion1->query("select * from cuadro_venta where fecha between '$desde' and '$hasta' order by fecha,bodega")or die($conexion1->error());
		}else if($bodega!='' and $desde!='' and $hasta!='')
		{
		//echo "<script>alert('$desde $hasta $bodega')</script>";

			$c=$conexion1->query("select * from cuadro_venta where fecha between '$desde' and '$hasta' and bodega='$bodega'  order by fecha,bodega")or die($conexion1->error());
		}else if($bodega!='' and $desde=='' and $hasta=='')
		{
			$c=$conexion1->query("select * from cuadro_venta where bodega='$bodega' order by fecha,bodega")or die($conexion1->error());
		}

		$n=$c->rowCount();
		//echo "<script>alert('$n')</script>";
		if($n!=0)
		{
			echo "<HR style='width:130%;'><table border='1' cellpadding='7' style='width: 130%; border-collapse:collapse;'>";
			echo "<tr>
				<td width='8%'>FECHA</td>
				<td width='20%'>BODEGA</td>
				<td>MONTO TIENDA</td>
				<td>MONTO SISTEMA</td>
				<td>TOTAL INGRESOS</td>
				<td>TOTAL SALIDAS</td>
				<td>MONTO LIQUIDO</td>
				<td># FARDOS VENDIDOS</td>
				<td>MONTO FARDOS</td>
				<td>TOTAL DESCUENTO</td>
				<td>TOTAL LIQUIDACIONES</td>
				<td>FARDOS ABIERTOS</td>
				<td>TOTAL DESGLOSE</td>
				<td>TOTAL AVERIA</td>
				<td>TOTAL MERCADERIA NO VENDIBLE</td>
			</tr>";
				$k=0;
						while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$k++;
				if($k==15)
				{
					echo "<tr cellpadding='20' style='background-color:#94A89A; color:white; margin-left: -1.5%;'>
				<td width='8%'>FECHA</td>
				<td width='20%'>BODEGA</td>
				<td>MONTO TIENDA</td>
				<td>MONTO SISTEMA</td>
				<td>TOTAL INGRESOS</td>
				<td>TOTAL SALIDAS</td>
				<td>MONTO LIQUIDO</td>
				<td># FARDOS VENDIDOS</td>
				<td>MONTO FARDOS</td>
				<td>TOTAL DESCUENTO</td>
				<td>TOTAL LIQUIDACIONES</td>
				<td>FARDOS ABIERTOS</td>
				<td>TOTAL DESGLOSE</td>
				<td>TOTAL AVERIA</td>
				<td>TOTAL MERCADERIA NO VENDIBLE</td>
			</tr>";
			$k=0;
				}
				$fecha=$f['FECHA'];
				$id=$f['ID'];
				$monto_tienda=$f['MONTO_USUARIO'];
				$monto_sistema=$f['MONTO_SISTEMA'];
				$bodega=$f['BODEGA'];
				$monto_liquido=$f['MONTO_LIQUIDO'];
				$descuento=$f['DESCUENTO'];
				$liquidaciones=$f['MONTO_LIQUIDACIONES'];
				$fardos=$f['TOTAL_FARDO'];
				$estado=$f['ESTADO'];
				if($monto_liquido=='')
				{
					$monto_liquido="0.00";
				}
				if($fardos=='')
				{
					$fardos="0.00";
				}
				
				$caja=$f['CAJA'];

				$ef=explode(".", $fardos);
				if($ef[0]=='')
				{
					$fardos="0.$ef[1]";
				}
				//--------------------------
		
		
			$cant_fardos=$f['FARDOS_VENDIDOS'];
		

				///--------------------------
				$ef=explode(".", $cant_fardos);
				if($ef[0]=='')
				{
					if($ef[1]=='')
					{
						$cant_fardos="0.00";
					}else
					{
					$cant_fardos="0.$ef[1]";

					}
					
				}
				$cl=$conexion2->query("select isnull(convert(decimal(10,2),sum((cantidad*precio_origen)-(cantidad * precio_destino))),0) as total from liquidaciones where bodega='$bodega' and fecha='$fecha'")or die($conexion2->error());
				$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
				$liquidaciones=$fcl['total'];
				$ef=explode(".", $liquidaciones);
				if($ef[0]=='')
				{
					$liquidaciones="0.$ef[1]";
				}

				$ef=explode(".", $descuento);
				if($ef[0]=='')
				{
					if($ef[1]=='')
					{
						$descuento="0.00";
					}else
					{
						$descuento="0.$ef[1]";
					}
					
				}
				
			$c1=$conexion1->query("select sum(monto) as monto,tipo_transaccion from cuadro_venta_detalle where cuadro_venta='$id' and tipo_transaccion='INGRESO' GROUP BY tipo_transaccion")or die($conexion1->error());
			$fc1=$c1->FETCH(PDO::FETCH_ASSOC);
			$monto_ingreso=$fc1['monto'];
			$c1=$conexion1->query("select sum(monto) as monto,tipo_transaccion from cuadro_venta_detalle where cuadro_venta='$id' and tipo_transaccion='SALIDA' GROUP BY tipo_transaccion")or die($conexion1->error());
			$fc1=$c1->FETCH(PDO::FETCH_ASSOC);
			$monto_salida=$fc1['monto'];

			$cd=$conexion2->query("select(select count(*) as cantidad from registro where bodega='$bodega' and fecha_desglose='$fecha')as cantidad, 
(select sum(cantidad*precio)as total from desglose where registro in(select id_registro from registro where bodega='$bodega' and fecha_desglose='$fecha'))as total
")or die($conexion2->error());

			$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
			$cant_desglose=$fcd['cantidad'];
			$t_desglose=$fcd['total'];
			if($t_desglose=='')
			{
				$t_desglose="0";
			}
			if($monto_salida=='')
			{
				$monto_salida="0.00";
			}
			if($monto_ingreso=='')
			{
				$monto_ingreso="0.00";
			}
			$caveria=$conexion2->query("select isnull(sum(precio * cantidad),0)as total from averias where bodega='$bodega' and fecha='$fecha' and tipo='AVERIA'")or die($conexion2->error());

			$fcvaeria=$caveria->FETCH(PDO::FETCH_ASSOC);
			$total_averia=$fcvaeria['total'];
			$ea=explode(".", $fcvaeria['total']);
			if($ea[0]=='')
			{
				$total_averia="0.$ea[1]";
			}

			$cb=$conexion1->query("select concat(bodega,': ',nombre) as bodega from consny.bodega where bodega='$bodega' ")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bodegas=$fcb['bodega'];
			$cm=$conexion2->query("select isnull(sum(precio * cantidad),0)as total from averias where bodega='$bodega' and fecha='$fecha' and tipo='MERCADERIA NO VENDIBLE'")or die($conexion2->error());
			$fcm=$cm->FETCH(PDO::FETCH_ASSOC);
			$total_merca=$fcm['total'];
			$e=explode(".",$total_merca);
			if($e[0]=='')
			{
				$total_merca="0.$e[1]";
			}
			$monto_liquido=$monto_tienda;
			$monto_liquido=$monto_liquido+ $monto_ingreso - $monto_salida;
			if($estado==0)
			{
				echo "<tr class='tre' style='background-color: red;'>";
			}else
			{
				echo "<tr class='tre'>";
			}

			
			echo "	<td width='8%'>$fecha</td>
				<td width='20%'>$bodegas</td>
				<td>$monto_tienda</td>
				<td><a href='detalle_monto_sistema.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$monto_sistema</a></td>
				<td><a href='detalle_cuadro_venta.php?fecha=$fecha&&bodega=$bodega&&tipo=INGRESO' target='_blank'>$monto_ingreso</a></td>
				<td><a href='detalle_cuadro_venta.php?fecha=$fecha&&bodega=$bodega&&tipo=SALIDA' target='_blank'>$monto_salida</a></td>
				<td><a href='detalle_cuadro_venta.php?fecha=$fecha&&bodega=$bodega&&tipo=liquido' target='_blank'>$monto_liquido</a></td>
				<TD><a href='detalle_fardos.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$cant_fardos</a></TD>
				<td><a href='detalle_fardos.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$fardos</a></td>
				<td><a href='detalle_descuento.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$descuento</a></td>
				<td><a href='detalle_liquidaciones.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$liquidaciones</a></td>
				<td><a href='detalle_desgloses.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$cant_desglose</a></td>
				<td><a href='detalle_desgloses.php?fecha=$fecha&&bodega=$bodega' target='_blank'>$t_desglose</a></td>
				<td><a href='detalle_averia.php?fecha=$fecha&&bodega=$bodega&&tipo=AVERIA' target='_blank'>$total_averia</a></td>
				<td><a href='detalle_averia.php?fecha=$fecha&&bodega=$bodega&&tipo=MERCA' target='_blank'>$total_merca</a></td>
			</tr>";

		
			}
			

		}else
		{
			echo "<h3>NO SE HA ENCONTRADO</h3>";
		}
	
}
?>
</body>
</html>