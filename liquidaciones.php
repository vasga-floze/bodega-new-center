<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<script>
		$(document).ready(function(){
			$("#div").hide();
			if($("#autoriza").val()=='')
			{
	
			$("#form1").hide();
			$("#tabla").hide();
			}else
			{
			$("#form1").show();
			$("#tabla").show();	
			
			}
			if($("#autoriza").val()!='' && $("#art").val()=='')
			{
				$("#art").focus();
			}

			if($("#art").val()!='' && $("#autoriza").val()!='')
			{
				$("#art1").focus();
			}
			
			if($("#art").val()!='' && $("#art1").val()!='' && $("#autoriza").val()!='')
			{
				//alert();
				$("#cantidad").focus();
			}

		})

		function barticulo()
		{
			$("#op").val('1');
			$("#form1").submit();
		}
		function barticulo1()
		{
			$("#op").val('2');
			$("#form1").submit();
		}
		function enviar()
		{
			$("#op").val('3');
			$("#art").attr('required',true);
			$("#art1").attr('required',true);

		}
		function articulos()
		{
			//alert('vfdfvfdv');
			var art=$("#art").val();
			var art1=$("#art1").val();
			var autoriza=$("#autoriza").val();
			var fecha=$("#fecha").val();
			location.replace('art_liquidaciones.php?art='+art+'&&art1='+art1+'&&tipo=1&&autoriza='+autoriza+'&&fecha='+fecha+'');
		}
		function articulos1()
		{
			var art=$("#art").val();
			var art1=$("#art1").val();
			var autoriza=$("#autoriza").val();
			var fecha=$("#fecha").val();
			location.replace('art_liquidaciones.php?art='+art+'&&art1='+art1+'&&tipo=2&&autoriza='+autoriza+'&&fecha='+fecha+'');
		}

		function finaliza()
		{
			var tl=$("#ingresoliq").text();
			var pie=$("#pieza").val();
			//alert(tl);
			if(tl!=pie)
			{
				alert('TOTAL DE PIEZAS NO COINCIDEN ');
				$("#formF").submit(false);
				location.replace('liquidaciones.php');
			}else
			{
				//alert('liquidacion valida');
				$("#formF").submit(true);
				//location.replace('liquidaciones.php');
			}
		}
	</script>
</head>
<body>
<div  id="div" style="width: 100%; height: 100%; background-color: white; position: fixed; opacity: 0.6;">
	<img src="loadf.gif" style="margin-left: 45%; margin-top: 8%; ">
	
</div>
<?php
error_reporting(0);
include("conexion.php");
$art=$_GET['art'];
$art1=$_GET['art1'];
$tipo=$_GET['tipo'];
if($_SESSION['tipo']!=2)
{
	echo "<script>location.replace('salir.php')</script>";
}
if($tipo==1)
{
	$art=$_GET['articulo'];
}else if($tipo==2)
{
	$art1=$_GET['articulo'];
}

$c=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
$f=$c->FETCH(PDO::FETCH_ASSOC);

$c1=$conexion1->query("select * from consny.articulo where articulo='$art1'")or die($conexion1->error());
$f1=$c1->FETCH(PDO::FETCH_ASSOC);
$autoriza=$_GET['autoriza'];
$fecha=$_GET['fecha'];
if($fecha=='')
{
	$fecha=date("Y-m-d");
}
if($_SESSION['liquidacion']!='')
{

	$sessiones=$_SESSION['liquidacion'];

	$usuario=$_SESSION['usuario'];

	$cl=$conexion2->query("select top 1 autoriza,fecha,paquete from liquidaciones where sessiones='$sessiones' and usuario='$usuario'")or die($conexion2->error());
	$n=$cl->rowCount();
	//echo"<script>alert('$sessiones | $usuario | $n')</script>";
	if($n!=0)
	{
		$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
		$autoriza=$fcl['autoriza'];
		$fecha=$fcl['fecha'];
	}
}
$hoy=date("Y-m-d");
$fecha1=date("Y-m-d",strtotime($hoy."- 8 days"));

?>
<h2 style="text-align: center; text-decoration: underline;">LIQUIDACIONES</h2>
<form method="POST" style="margin-left: 4%;">
	<input type="text" name="autoriza" id="autoriza" class="text" style="width: 30%;" placeholder="LIQUIDACION AUTORIZADA POR" style="width: 60%;" required value='<?php echo "$autoriza";?>'>
	FECHA: <input type="date" name="fecha" id='fecha' class="text" style="width: 20%;" required value='<?php echo "$fecha";?>' max='<?php echo "$fecha";?>' min='<?php echo "$fecha1"; ?>' required>

	TOTAL PIEZAS: <input type="number" name="pieza" id="pieza" class="text" required style="width: 15%;"
	value="<?php $pie=$_SESSION['piezas_liquidacion']; echo $pie;?>">
	<input type="submit" name="btn" value="SIGUIENTE" style="background-color:#70B299; padding: 0.5%; border-color: #0B7CFB; cursor: pointer;" >	
	</form>
	<form method="POST" id="form1">
<table border="0" width="100%" id="tabla" style="margin-left: 4%;">
	<br>
<tr>
	<td>
<a href="#" style=" padding: 0.5%; background-color: #A3B7BC; border-color: black; text-decoration: none; color: white;" onclick="articulos()">
	ARTICULOS
</a>
<a href="#" style=" padding: 0.5%; background-color: #A3B7BC; border-color: black; text-decoration: none; color: white; margin-left: 32.7%;" onclick="articulos1()">
	ARTICULOS
</a>

</td>
</tr>
<tr>
	<td>

<input type="text" name="art" id="art" onchange="barticulo()" placeholder="ART. ORIGEN" class="text" style="width: 12%;" value='<?php echo "".$f['ARTICULO']."";?>'>

<input type="text" name="desc" placeholder="DESCRIPCION" class="text" style="width: 26.3%;" value='<?php echo "".$f['DESCRIPCION'].""?>'>

<input type="text" name="art1" id="art1" placeholder="ART. DESTINO" onchange="barticulo1()" class="text" style="width: 12%;" value='<?php echo "".$f1['ARTICULO']."";?>'>

<input type="text" name="desc" placeholder="DESCRIPCION" class="text" style="width: 26.3%;" value='<?php echo "".$f1['DESCRIPCION'].""?>'>
<input type="number" name="cantidad" id="cantidad"  class="text" style="width: 9%;" placeholder="CANTIDAD" required>
<input type="hidden" name="op" id="op">
<input type="submit" name="btn" class="btn" value="ADD" onclick="enviar()" style="background-color:#70B299; padding: 0.5%; border-color:#0B7CFB;">
</td></tr>

</form>
</table>
<?php

$sessiones=$_SESSION['liquidacion'];

$usuario=$_SESSION['usuario'];
$query=$conexion2->query("select * from liquidaciones where sessiones='$sessiones' and usuario='$usuario' order by id_liquidacion desc")or die($conexion2->error());
$nquery=$query->rowCount();
if($nquery!=0)
{
	$str ='1';

	echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:100%; margin-top:1%;'>";
	echo "<tr>
		<td>ARTICULO ORIGEN</td>
		<td>ARTICULO DESTINO</td>
		<td>CANTIDAD</td>
		<td>ELIMINAR</td>
		</tr>";
		$tc=0;
	while($fila=$query->FETCH(PDO::FETCH_ASSOC))
	{
		$arti1=$fila['art_origen'];
		$arti2=$fila['art_destino'];
		$ca1=$conexion1->query("select * from consny.articulo where articulo='$arti1'")or die($conexion1->error());
		$ca2=$conexion1->query("select * from consny.articulo where articulo='$arti2'")or die($conexion1->error());
		$fca1=$ca1->FETCH(PDO::FETCH_ASSOC);
		$fca2=$ca2->FETCH(PDO::FETCH_ASSOC);
		$arti1="".$fca1['ARTICULO'].": ".$fca1['DESCRIPCION']."";
		$arti2="".$fca2['ARTICULO'].": ".$fca2['DESCRIPCION']."";
		$id=$fila['id_liquidacion'];
		$cantidad=$fila['cantidad'];
		$str= base64_encode($id);
		$id=$str;
		echo "<tr>
		<td>$arti1</td>
		<td>$arti2</td>
		<td>$cantidad</td>
		<td> <a href='eli_liquiaciones.php?id=$id'>ELIMINAR </a></td>
		</tr>";
		$tc=$tc+$cantidad;

	}
	echo "<tr><td colspan='2'>TOTAL PIEZAS LIQUIDACION</td><td id='ingresoliq'>$tc</td></tr>";
	echo "<tr>
		<td colspan='4'>
		<form method='POST' name='formF' id='formF' action='final_liquidacion.php'>
		<input type='text' class='text' name='digita' id='digita' placeholder='DIGITADO POR' style='width:30%;' required>
		<input type='text' class='text' name='obs' id='obs' placeholder='OBSERVACIONES'  style='width:30%;'>
		
		<button class='btnfinal' style='padding:0.5%; float:right; margin-bottom:-0.5%; ' onclick='finaliza()'>FINALIZAR</button></form>
		
		</td>
	</tr>";
}else
{
	//$_SESSION['liquidacion']='';
}

?>



<?php
if($_POST)
{
	extract($_REQUEST);
	if($op==1)
	{
		$c=$conexion1->query("select * from consny.articulo where articulo='$art' and activo='S' and clasificacion_1='DETALLE'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN ARTICULO: $art NO DISPONIBLE O CLASIFICACION NO VALIDA')</script>";
			echo "<script>location.replace('liquidaciones.php?art1=$art1&&autoriza=$autoriza&&fecha=$fecha')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$articulo=$f['ARTICULO'];
			echo "<script>location.replace('liquidaciones.php?art=$articulo&&art1=$art1&&autoriza=$autoriza&&fecha=$fecha')</script>";
		}
	}else if($op==2)
	{
		$c=$conexion1->query("select * from consny.articulo where articulo='$art1' and activo='S' and clasificacion_1='DETALLE'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN ARTICULO: $art NO DISPONIBLE O CLASIFICACION NO VALIDA')</script>";
			echo "<script>location.replace('liquidaciones.php?art=$art&&autoriza=$autoriza&&fecha=$fecha')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$articulo=$f['ARTICULO'];
			echo "<script>location.replace('liquidaciones.php?art1=$articulo&&art=$art&&autoriza=$autoriza&&fecha=$fecha')</script>";
		}
	}else if($op==3)
	{
		


		$c=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$c1=$conexion1->query("select * from consny.articulo where articulo='$art1'")or die($conexion1->error());
		$f1=$c1->FETCH(PDO::FETCH_ASSOC);
		$precio1=$f['PRECIO_REGULAR'];
		$precio2=$f1['PRECIO_REGULAR'];
		
		if($f['CLASIFICACION_2']==$f1['CLASIFICACION_2'])
		{

			if($_SESSION['liquidacion']=='')
			{
			$c=$conexion2->query("select max(sessiones) as sessiones from liquidaciones")or die($conexion2->error());

			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$sessiones=$f['sessiones'] + 1;
			$k=1;
			while($k==1)
			{
				$q=$conexion2->query("select * from liquidaciones where sessiones='$sessiones'")or die($conexion2->error());
				$n=$q->rowCount();
				if($n==0)
				{
					$k=0;
				}else
				{
					$sessiones++;
					$k=1;
				}

			}
			$_SESSION['liquidacion']=$sessiones;
			
		}

			$usuario=$_SESSION['usuario'];
			$paquete=$_SESSION['paquete'];
			$sessiones=$_SESSION['liquidacion'];
			//echo "<script>alert('$precio1 | $precio2 | $bodega | $usuario')</script>";
			$cb=$conexion1->query("select bodega from usuariobodega where usuario='$usuario'")or die($conexion1->error());

			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bodega=$fcb['bodega'];
			//echo "<script>alert('$bodega')</script>";
			if($art!=$art1)
			{
				if($precio2<$precio1)
				{
					$conexion2->query("insert into liquidaciones(autoriza,fecha,fechaingreso,usuario,paquete,bodega,art_origen,art_destino,cantidad,sessiones,estado,precio_origen,precio_destino) values('$autoriza','$fecha',getdate(),'$usuario','$paquete','$bodega','$art','$art1','$cantidad','$sessiones','0','$precio1','$precio2')")or die($conexion2->error());
			echo "<script>location.replace('liquidaciones.php')</script>";
				}else
				{
					echo "<script>alert('LIQUIDACION NO VALIDA')</script>";
					echo "<script>location.replace('liquidaciones.php?autoriza=$autoriza&&fecha=$fecha')</script>";
				}
			
			}else
			{
				echo "<script>alert('ERROR: !DATOS NO VALIDOS INTENTALO NUEVAMENTE!')</script>";
			}


		
		}else
		{
			echo "<script>alert('ERROR: !ARTICULOS NO PERTENECEN A LA MISMA CLASIFICACION!')</script>";

			echo "<script>location.replace('liquidaciones.php?autoriza=$autoriza&&fecha=$fecha')</script>";
		}

	}

	if($btn=='SIGUIENTE')
	{
		//echo "<script>alert('pfu')</script>";
		if($_SESSION['liquidacion']=='')
		{
			$_SESSION['piezas_liquidacion']=$pieza;
			echo "<script>location.replace('liquidaciones.php?autoriza=$autoriza&&fecha=$fecha')</script>";
		}else
		{
			$sessiones=$_SESSION['liquidacion'];
			$usuario=$_SESSION['usuario'];
			$_SESSION['piezas_liquidacion']=$pieza;
			$conexion2->query("update liquidaciones set autoriza='$autoriza',usuario='$usuario' where sessiones='$sessiones' and usuario='$usuario'")or die($conexion2->error());
			echo "<script>location.replace('liquidaciones.php')</script>";
		}
		
	}
}
?>
</body>
</html>