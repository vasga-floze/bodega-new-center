<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<script>

		function chekedes()
		{
			
			var num=$("#num").val();
			var n=0;

			while(n<=num)
			{
				var nom="#chekede"+n;
				//alert(nom);
				if($(nom).is(':checked'))
				{
					//alert('falso');
					$(nom).attr('checked',false);
				}else
				{
					//alert('verdadero');
					$(nom).prop('checked',true);
				}
				
				n++;
			}
			
			
			
			
		}
	</script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
if($_SESSION['tipo']!=1)
{
	echo "<script>alert('NO DISPONIBLE')</script>";
	echo "<script>location.replace('desglose.php')</script>";
}
?>
<form method="POST">
<?php
$c=$conexion2->query("select tienda,fecha_hora_confirma,usuario_confirma from pedidos where estado='CONFIRMADO' and despacho='N'  group by tienda,fecha_hora_confirma,usuario_confirma")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN PEDIDO PENDIENTE DE DESPACHO</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='width:98%; border-collapse:collapse;'>";
	echo "<tr>
		<td  style='text-align:center;'>
		<label>
		<input type='checkbox' onclick='chekedes()' cheked>
		SELECCIONAR TODO
		</label>
		</td>
		<td>BODEGA</td>
		<td>FECHA PEDIDO</td>
		<td>FECHA CONFIRMACION</td>
		<td>CANTIDAD SOLICITADA</td>
		<td>CANTIDAD CONFIRMADA</td>
		</tr>";
		$num=0;

		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
		$bodega=$f['tienda'];
		$fechac=$f['fecha_hora_confirma'];
		$usuarioc=$f['usuario_confirma'];
		$q=$conexion2->query("select sum(cantidad_tienda) as tienda,sum(cantidad_enviada) as enviada from pedidos where fecha_hora_confirma='$fechac' and usuario_confirma='$usuarioc'")or die($conexion2->error());
		$fq=$q->FETCH(PDO::FETCH_ASSOC);
		$cantidadt=$fq['tienda'];
		$cantidade=$fq['enviada'];
		$query=$conexion2->query("select convert(date,fecha)as fecha,convert(date,fecha_hora_confirma) as fecha_confirma from pedidos where fecha_hora_confirma='$fechac' and usuario_confirma='$usuarioc' group by fecha,fecha_hora_confirma")or die($conexion2->error());
		$fquery=$query->FETCH(PDO::FETCH_ASSOC);
		$fechat=$fquery['fecha'];
		$fechaco=$fquery['fecha_confirma'];
		$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bodega="".$fcb['BODEGA'].":".$fcb['NOMBRE']."";
		echo "<tr>
		<td style='text-align:center;'><input type='checkbox' id='chekede$num' name='despacho[$num]' value='$fechac|$usuarioc'></td>
		<td><a href='detalle_pedido.php?fechac=$fechac&&usuarioc=$usuarioc' style='text-decoration:none; color:black;' target='_blank'>$bodega</a></td>
		<td><a href='detalle_pedido.php?fechac=$fechac&&usuarioc=$usuarioc' style='text-decoration:none; color:black;' target='_blank'>$fechat</a></td>
		<td><a href='detalle_pedido.php?fechac=$fechac&&usuarioc=$usuarioc' style='text-decoration:none; color:black;' target='_blank'>$fechaco</a></td>
		<td><a href='detalle_pedido.php?fechac=$fechac&&usuarioc=$usuarioc' style='text-decoration:none; color:black;' target='_blank'>$cantidadt</a></td>
		<td><a href='detalle_pedido.php?fechac=$fechac&&usuarioc=$usuarioc' style='text-decoration:none; color:black;' target='_blank'>$cantidade</a></td>
		</tr>";
		$num++;
		}
}
?>
<tr>
	<td colspan="6">
	<input type="hidden" name="num" id="num" value='<?php echo "$num";?>'>
<?php
if($n!=0)
{?>
<input type="submit" name="" value="DESPACHADOS" style="float: right; margin-right: 0.5%; padding: 0.5%; margin-bottom: -0.5%;" class="btnfinal">
<?php
}
?>
</td>
</tr>
</form>
</table>
<?php
if($_POST)
{
	extract($_REQUEST);
	$k=0;
	extract($_REQUEST);
	$fechad=date("Y-m-d h:i:s");
	$usuariod=$_SESSION['usuario'];
	while ($k<=$num) 
	{
		$e=explode("|",$despacho[$k]);
		$fecha=$e[0];
		$usuarioc=$e[1];
		
		if($fecha!='' and $usuarioc!='')
		{
			//echo "<script>alert('$fecha | $usuarioc |$fechad $usuariod')</script>";
			$conexion2->query("update pedidos set despacho='S',fecha_hora_despacho='$fechad',usuario_despacho='$usuariod' where fecha_hora_confirma='$fecha' and usuario_confirma='$usuarioc'")or die($conexion2->error());
			
		}
		
		$k++;
	}
	echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";
	echo "<script>location.replace('pedido_despacho.php')</script>";
	
}
	
?>
</body>
</html>