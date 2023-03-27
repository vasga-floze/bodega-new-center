<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
	$(document).ready(function(){
		if($("#ver").val()==0)
		{
			$("#articulos").hide();
		}else
		{
			$("#articulos").show();

		}
		if($("#art").val()!='')
		{
			$("#canti").focus();
		}
	})
		function enviar()
		{
			//alert($("#bodega").val());
			$("#op").val('1');
			$("#form").submit();
			//alert($("#bodega").val());
		}

		function enviar1()
		{
			$("#op").val('2');
			$("#form").submit();
		}
		function enviar2()
		{
			$("#op").val('3');
		}
		function enviar3()
		{
			$("#op").val('4');
			$("#form").submit();
		}
		function enviar4()
		{
			$("#canti").attr('required',false);
			$("#op").val('5');
			$("#form").submit();
		}
		function enviar5()
		{
			$("#canti").attr('required',false);
			$("#op").val('6');
			$("#form").submit();
		}

		function suma()
		{
			var n=$("#n").val();
			var num=0;
			var total=0;
			while(num<n)
			{
				var variable="#cantidad"+num;
				var cantida=$(variable).val();
				total=total +parseInt(cantida);
				num++;
				
			}
			num=2
			$("#totalf").text(total);
		}
		setInterval(suma,1000);
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

<form name="form" method="POST" id="form">
	<input type="hidden" name="op" id="op">
	<!--<select required class="text" name="bodegas" id="bodega" style="width: 25%;" onchange="enviar()">!-->

		
	<?php
	$bodega=$_GET['bodega'];

	/*if($bodega=='')
	{
		echo "<option value=''>BODEGA</option>";
		$cb1=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%' order by bodega")or die($conexion1->error());
		while($fcb1=$cb1->FETCH(PDO::FETCH_ASSOC))
		{
			$bod=$fcb1['BODEGA'];
			$nom=$fcb1['NOMBRE'];
			echo "<option value='$bod'>$bod: $nom</option>";
		}
	}else
	{
		//echo "<script>alert('$bodega')</script>";
		$cbo=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
		$fcbo=$cbo->FETCH(PDO::FETCH_ASSOC);
		$bode=$fcbo['BODEGA'];
		$nomb=$fcbo['NOMBRE'];

		echo "<option value='$bode'>$nomb</option>";

		$cb1=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%' order by bodega")or die($conexion1->error());
		while($fcb1=$cb1->FETCH(PDO::FETCH_ASSOC))
		{
			$bode=$fcb1['BODEGA'];
			$nomb=$fcb1['NOMBRE'];
			echo "<option value='$bode'>$bode: $nomb</option>";
		}
	}*/
		
		$art=$_GET['art'];
		$cart=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fcart=$cart->FETCH(PDO::FETCH_ASSOC);
	?>
	<!--</select>-->
	<br><br>
	<div id='articulos' style="display: none;">
	<a href="#" style="padding: 0.5%; background-color: #82DCA7; color: white; margin-bottom: 1%; text-decoration: none; padding-bottom: 0%; margin-left:-4%;" onclick="enviar3()">ARTICULOS</a><BR>
	<input type="text" name="art" id="art" placeholder="ARTICULO" class="text" style="width: 20%;" onchange="enviar1()" value='<?php echo $fcart['ARTICULO']?>'>
	<input type="text" name="desc" placeholder="DESCRIPCION" class="text" style="width: 40%;" value='<?php echo $fcart['DESCRIPCION']?>'>
	<input type="number" name="canti" id="canti" placeholder="CANTIDAD" class="text" style="width: 20%;" required>
	<input type="submit" name="btn" value="AGREGAR" class="submit" style="padding: 0.4%; background-color: #82DCA7; color: black; border-color: blue;" onclick="enviar2()">
	</div>


<br><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	//echo "<script>alert('$bodegas | $op')</script>";
	//echo $op;
	if($op==1)
	{
		//busqueda de bodega
		
		echo "<script>location.replace('pedidos_confirma.php?bodega=$bodegas')</script>";
	}else if($op==2)
	{
		$k=0;
		while($k<=$n)
		{

			$ide=$id[$k];
			$cantidades=$cantidad[$k];
			if($ide!='')
			{
				$conexion2->query("update pedidos set cantidad_enviada='$cantidades' where pedido='$ide'")or die($conexion2->error());
		//echo "<script>alert('$ide $cantidades')</script>";

			}else
			{
		//echo "<script>alert('nel')</script>";

			}
			$k++;
			//echo "<script>alert('actualizado')</script>";
		}

		$ca=$conexion1->query("select * from consny.articulo where articulo='$art' and activo='S'")or die($conexion1->error());
		$nca=$ca->rowCount();
		//echo "<script>alert('$nca')</script>";
		if($nca==0)
		{
			echo "<script>alert('NO SE ENCONTRO ARTICULO: $art O NO SE ENCUENTRA ACTIVO')</script>";
			echo "<script>location.replace('pedidos_confirma.php?bodega=$bodega')</script>";

		}else
		{
			echo "<script>location.replace('pedidos_confirma.php?bodega=$bodega&&art=$art')</script>";
		}
	}else if($op==3)
	{
		//SACO CLASIFICACION DEL ARTICULO
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$clasi=$fca['CLASIFICACION_2'];
		//FIN CLASIFICACION
		//SACO EXISTENCIA DEL ARTICULO
		$cr=$conexion2->query("declare @cantidad int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose='' and codigo='$art' group by codigo)
declare @cantidad1 int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose is null and codigo='$art' group by codigo)

declare @total int = (isnull(@cantidad,0) + isnull(@cantidad1,0))

select @total as total
")or die($conexion2->error());
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$exis=$fcr['total'];
		//FIN EXISTENCIAS
		//SACO FECHA Y SESSIONES ULTIMAS
		$q=$conexion2->query("declare @id int=(select  max(pedido) from pedidos where tienda='$bodega' and estado='SOLICITUD')
			SELECT fecha,sessiones from pedidos where pedido=@id
")or die($conexion2->error());
		$fq=$q->FETCH(PDO::FETCH_ASSOC);
		$fecha=$fq['fecha'];
		$sessiones=$fq['sessiones'];
		//FIN FECHA Y SESSIONES
		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		//VERIFICO QUE EL ARTCULO NO ESTE AGREGADO EN EL PEDIDO ANTES
		$qv=$conexion2->query("select * from pedidos where articulo='$art' and tienda='$bodega' and estado='SOLICITUD' AND despacho='N' ")or die($conexion2->error());
		$nqv=$qv->rowCount();
		if($nqv==0)
		{

			//INSERT NUEVO ARTICULO AL PEDIDO
		$conexion2->query("insert into pedidos(tienda,usuario,paquete,fecha,articulo,existencia_tienda,cantidad_tienda,cantidad_enviada,estado,sessiones,clasificacion,despacho) values('$bodega','$usuario','$paquete','$fecha','$art','$exis','0','$canti','SOLICITUD','$sessiones','$clasi','N')")or die($conexion2->error());
		//echo "<script>alert('GUARDADO CORRECTA')</script>";

		echo "<script>location.replace('pedidos_confirma.php?bodega=$bodega')</script>";
	}else
	{
		echo "<script>alert('ARTICULO: $art YA ESTA EN EL PEDIDO')</script>";
		echo "<script>location.replace('pedidos_confirma.php?bodega=$bodega')</script>";
	}
		//echo "$id[0] | $n $cantidad[0]";

		$k=0;
		while($k<=$n)
		{
			$ide=$id[$k];
			$cantidades=$cantidad[$k];
			if($ide!='')
			{
				$conexion2->query("update pedidos set cantidad_enviada='$cantidades' where pedido='$ide'")or die($conexion2->error());
			}
			$k++;
		}
	}else if($op==4)
	{
		//echo "<script>alert('$op')</script>";
		$k=0;
		while($k<=$n)
		{

			$ide=$id[$k];
			$cantidades=$cantidad[$k];
			if($ide!='')
			{
				$conexion2->query("update pedidos set cantidad_enviada='$cantidades' where pedido='$ide'")or die($conexion2->error());
		//echo "<script>alert('$ide $cantidades')</script>";

			}else
			{
		//echo "<script>alert('nel')</script>";

			}
			$k++;
			//echo "<script>alert('actualizado')</script>";
		}
		echo "<script>location.replace('art_pedidos_confirmado.php?bodega=$bodega')</script>";
	}else if($op==5)
	{

		$k=0;
		$usuario=$_SESSION['usuario'];
		$fecha=date("Y-m-d H:i:s");
		//echo "<script>alert('$fecha')</script>";
		while($k<$n)
		{

			$ide=$id[$k];
			$cantidades=$cantidad[$k];
			if($ide!='')
			{
				$conexion2->query("update pedidos set cantidad_enviada='$cantidades',usuario_confirma='$usuario',fecha_hora_confirma='$fecha',estado='CONFIRMADO' where pedido='$ide'")or die($conexion2->error());
			}
			$k++;
		}
		echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('final_confirma_pedido.php?fecha=$fecha&&bodega=$bodega&&usuario=$usuario')</script>";

	}else if($op==6)
	{
		$k=0;
		$usuario=$_SESSION['usuario'];
		$fecha=date("Y-m-d H:i:s");
		//echo "<script>alert('$fecha')</script>";
		while($k<$n)
		{

			$ide=$id[$k];
			$cantidades=$cantidad[$k];
			if($ide!='')
			{
				$conexion2->query("update pedidos set cantidad_enviada='$cantidades' where pedido='$ide'")or die($conexion2->error());
			}
			$k++;
	}
	echo "<script>alert('HECHO!')</script>";
	echo "<script>location.replace('pedidos_confirma.php')</script>";
}

}else//fin _POST
{
	$bodega=$_GET['bodega'];
	$c=$conexion2->query("select * from pedidos where tienda='$bodega' and estado='SOLICITUD' order by pedido desc")or die($conexion2->error());
		$n=$c->rowCount();
		echo "<input type='hidden' name='ver' id='ver' value='$n'>";
		if($n==0)
		{
			if($bodega!='')
			{
				echo "<h3>NO SE ENCONTRO NINGUN PEDIDO DE LA BODEGA: $bodega</h3>";
			}
			
		}else
		{
			echo "<table border='1' cellpadding='10' style='width:98%; border-collapse:collapse;'>";
			//echo "<form method='POST' action='final_confirma_pedido.php'>";

			echo "<tr>
				<td width='20%'>BODEGA</td>
				<td>CLASIFICACION</td>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
				<td>EXISTENCIA TIENDA</td>
				<td>CANTIDAD SOLICITADA</td>
				<td>CANTIDAD A ENVIAR</td>
			</tr>";
			$n=0;
			//total tienda<---------------
			$total_tienda=0;
			$total_confirma=0;
			$totalC=0;
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$existencia=$f['existencia_tienda'];
				$cant=$f['cantidad_tienda'];
				$art=$f['articulo'];
				$clasi=$f['clasificacion'];
				$pedido=$f['pedido'];
				$cantidad1=$f['cantidad_enviada'];
			
				if($cant=='')
				{
					$cant=0;
				}
				$total_tienda=$total_tienda +$cant;
				$total_confirma=$total_confirma+$cantidad1;
				
				$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
				$fca=$ca->FETCH(PDO::FETCH_ASSOC);

				$cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
				$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
				$tienda="".$fcb['BODEGA'].": ".$fcb['NOMBRE']."";

				echo "<tr>
				<td>$tienda</td>
				<td>$clasi</td>
				<td>".$fca['ARTICULO']."</td>
				<td>".$fca['DESCRIPCION']."</td>
				<td>$existencia</td>
				<td>$cant</td>
				<td>";
				if($cantidad1!='')
				{
					$cant=$cantidad1;
				}
				echo"
				<input type='hidden' name='id[$n]' value='$pedido'>
				<input type='number' name='cantidad[$n]' id='cantidad$n' value='$cant' class='text' style='padding:3%;'>
				</td>
			</tr>";
			$n++;
			$totalC=$totalC+$cant;
			}
			echo "<tr>
			<td colspan='5'>TOTAL</td>
			<td>$total_tienda</td>
			<td id='totalf'>$totalC</td>
			</tr>";
			echo "<tr><td colspan='7'>";
			echo "<input type='hidden' name='n' id='n' value='$n'>";
			echo "<input type='hidden' name='bodega' value='$bodega'>";

			echo "<input type='submit' value='CONFIRMAR' class='btnfinal' style='background-color:#39C442; color:white; padding:0.5%; margin-bottom:0.1%; float:right; margin-right:0.5;' onclick='enviar4()'>";

			echo "<input type='submit' value='DEJAR PENDIENTE' class='btnfinal' style='background-color:#057A36; color:white; padding:0.5%; margin-bottom:0.1%; float:left; margin-right:0.8%;' onclick='enviar5()'>";
			echo "</td></tr></form>";


		}
}

?>
</body>
</html>