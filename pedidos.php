<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
	$(document).ready(function(){
		if($("#art").val()!='')
			{
				$("#cantidad").focus();
			}
			//$("#load").show();
	})


		function buscar()
		{
			if($("#clasificacion").val()=='')
			{
				alert("SELECCIONA UNA CLASIFICACION");
			}else
			{
location.replace('art_pedidos.php?clasificacion='+$("#clasificacion").val());
			}
			
		}
		function enviar()
		{
			if($("#clasificacion").val()!='')
			{
				$("#op").val('1');
				$("#form1").submit();
			}else
			{
				alert('SELECCIONA UN CLASIFICACION');
				$("#art").val('');
				$("#clasificacion").focus();
			}
		}
		function enviar1()
		{
			$("#op").val("2");
		}

		function finalizar()
		{
			$("#final").hide();
			$("#finalp").hide();
			$("#load").show();
			location.replace('final_pedido.php');
		}

	</script>
</head>
<body>

<?php
error_reporting(0);
include("conexion.php");


echo "<div id='load'  style='width:110%; height:110%; background-color:white; position:fixed; margin-top:-5%; margin-left:-2%; display:none;'>";

echo "<img src='load.gif' width='110%' height='130%' style=' margin-left:-10%; margin-top:-20%;' id='load1'>";
echo "</div>";
echo '<h3 style="text-align: center; text-decoration: underline;">SOLICITUD DE ARTICULOS</h3>';
$p=base64_decode($_GET['p']);
if($p=='si')
{
	$_SESSION['pedidos']='';
	echo "<script>alert('PEDIDO DEJADO PENDIENTE CORRECTAMENTE PARA CONTINUARLO BUSCALO EN PENDIDOS PENDIENTES')</script>";
	echo "<script>location.replace('pedidos.php')</script>";
}
$clasi=$_GET['clasificacion'];
$art=$_GET['art'];
$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
$fca=$ca->FETCH(PDO::FETCH_ASSOC);

?>
<form method="POST" id="form1">
<a href="#" onclick="buscar()" style="padding: 0.5%; background-color: #A2C8B2; color: white; text-decoration:none; margin-left: 10.7%;">ARTICULOS</a><br>
<select name="clasificacion" id="clasificacion" class="text" style="width: 10%;" required>

	
<?php
if($clasi!='')
{
	echo "<option>$clasi</option>";
}else
{
	echo "<option value=''>CLASIFICACION</option>";
}
$c=$conexion1->query("select clasificacion_2 from consny.articulo where clasificacion_2 is not null group by clasificacion_2")or die($conexion1->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	echo "<option>".$f['clasificacion_2']."</option>";
}
?>
</select>
<input type="text" name="art" id="art" placeholder="ARTICULO" class="text" style="width: 15%;" value='<?php echo "".$fca['ARTICULO']."";?>' onchange='enviar()' required >
<input type="hidden" id='op' name="op">
<input type="text" name="desc" placeholder="DESCRIPCION" class="text" style="width: 50%;" value='<?php echo "".$fca['DESCRIPCION']."";?>' readonly>
<input type="number" name="cantidad" id="cantidad" placeholder="CANTIDAD" class="text" style="width: 10%;" required>
<input type="submit" name="btnfinal" value="AGREGAR" onclick="enviar1()" style="padding: 0.5%; background-color: #80D1A2; color: white;" >
</form>
<?php
$pedido=$_SESSION['pedidos'];
$usuario=$_SESSION['usuario'];
//echo "<script>alert('$pedido $usuario')</script>";
$cr=$conexion2->query("select * from pedidos where sessiones='$pedido' and usuario='$usuario' and estado='SOLI...' order by pedido desc")or die($conexion2->error());
$ncr=$cr->rowCount();
if($ncr!=0)
{
	echo "<table border='1' style='border-collapse:collapse; width:100%; margin-top:1%;' cellpadding='10'>";
	echo "<tr>
		<td>CLASIFICACION</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>EXISTENCIAS</td>
		<td>CANTIDAD</td>
		<td>OPCION</td>
	</tr>";
	while($fcr=$cr->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$fcr['articulo'];
		$exis=$fcr['existencia_tienda'];
		$cantidad=$fcr['cantidad_tienda'];
		$pedido=base64_encode($fcr['pedido']);

		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
		<td>".$fca['CLASIFICACION_2']."</td>
		<td>".$fca['ARTICULO']."</td>
		<td>".$fca['DESCRIPCION']."</td>
		<td>$exis</td>
		<td>$cantidad</td>
		<td><a href='eli_pedidos.php?pedido=$pedido' style='padding:1.3%; background-color:red; color:white; text-decoration:none; border-collapse:collapse; border-radius:3px; border-color:black;'>ELIMINAR</a></td>
	</tr>";

	}
	$i=base64_encode("si");
	echo "<tr>
	<td colspan='6'>

	<input type='submit' value='FINALIZAR' id='final' class='btnfinal' style='padding:0.5%; margin-bottom:-0.5%; float:right; margin-right:0.5%; border-color:white;' onclick='finalizar()'>
	<a href='pedidos.php?p=$i'>
	<input type='submit' value='DEJAR PENDIENTE' id='finalp' class='btnfinal' style='padding:0.5%; margin-bottom:-0.5%; float:right; margin-right:0.5%; border-color:white; background-color:#17924B;'>
	</a>
	</td>
	</tr>";
}


if($_POST)
{
	extract($_REQUEST);

	//echo "<script>alert('$pedido')</script>";
	if($op==1)
	{

		$c=$conexion1->query("select * from consny.articulo where articulo='$art' and clasificacion_2='$clasificacion' and clasificacion_1!='DETALLE' and activo='S'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO ARTICULO: $art DISPONIBLE O NO ESTA EN LA CLASIFICACION DE: $clasificacion')</script>";
			echo "<script>location.replace('pedidos.php?clasificacion=$clasificacion')</script>";



		}else
		{
			echo "<script>location.replace('pedidos.php?clasificacion=$clasificacion&&art=$art')</script>";

		}

	}else if($op==2)
	{
		//echo "<script>alert('$pedido')</script>";
		if($_SESSION['pedidos']=='')
		{
			$c=$conexion2->query("select max(sessiones) as sessiones from pedidos")or die($conexion2->error());
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$sessiones=$f['sessiones'] + 1;
			$k=1;
			while($k==1)
			{
				$cv=$conexion2->query("select * from pedidos where sessiones='$sessiones'")or die($conexion2->error());
				$ncv=$cv->rowCount();
				if($ncv==0)
				{
					$k=0;
				}else
				{
					$k=1;
					$sessiones++;
				}
			}
			$_SESSION['pedidos']=$sessiones;

		}

		$sessiones=$_SESSION['pedidos'];
		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		$bodega=$_SESSION['bodega'];
		//saco existencia del articulo
		$q=$conexion2->query("declare @cantidad int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose='' and codigo='$art' group by codigo)
declare @cantidad1 int=(select count(codigo) as cantidad from registro where bodega='$bodega' and activo is null and fecha_desglose is null and codigo='$art' group by codigo)

declare @total int = (isnull(@cantidad,0) + isnull(@cantidad1,0))

select @total as total
")or die($conexion2->error());
		$fq=$q->FETCH(PDO::FETCH_ASSOC);
		$existencia=$fq['total'];
		if($existencia=='')
		{
			$existencia=0;
		}
		$con=$conexion2->query("select sum(cantidad_tienda) as cantidad from pedidos where articulo='$art' and sessiones='$sessiones' and usuario='$usuario'")or die($conexion2->error());
		$fcon=$con->FETCH(PDO::FETCH_ASSOC);
		if($fcon['cantidad']!='')
		{
			$cantidad=$cantidad+$fcon['cantidad'];
			$conexion2->query("update pedidos set cantidad_tienda='$cantidad' where sessiones='$sessiones' and usuario='$usuario' and articulo='$art'")or die($conexion2->error());
		}else
		{
			$qa=$conexion1->query("select * from consny.articulo where articulo='$art' and clasificacion_2='$clasificacion'")or die($conexion1->error());
			$nqa=$qa->rowCount();
			if($nqa==0)
			{
				echo "<script>alert('ARTICULO NO PERTENECE A LA CLASIFICACION: $clasificacion')</script>";
				echo "<script>location.replace('pedidos.php?clasificacion=$clasificacion')</script>";
			}else
			{
				$conexion2->query("insert into pedidos(tienda,usuario,paquete,fecha,articulo,existencia_tienda,cantidad_tienda,estado,sessiones,clasificacion,despacho) values('$bodega','$usuario','$paquete',getdate(),'$art','$existencia','$cantidad','SOLI...','$sessiones','$clasificacion','N')")or die($conexion2->error());
			}
			
		}
		
		echo "<script>location.replace('pedidos.php?clasificacion=$clasificacion')</script>";

	}
}
?>
</body>
</html>