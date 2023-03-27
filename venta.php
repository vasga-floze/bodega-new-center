<!DOCTYPE html>
<html>
<head>
<?php
error_reporting(0);
include("conexion.php");



	if($_SESSION['tipo']!=1)
		{
			echo "<script>alert('NO TIENES AUTORIZACION')</script>";
			echo "<script>location.replace('desglose.php')</script>";

		}
$peso=$_GET['p'];
$barra=$_GET['bara'];
if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' OR $_SESSION['usuario']=='staana3')
{
	//$_SESSION['venta']=593;
}

if($_SESSION['venta']!="")
{
	if($barra!='')
	{
	$a=2;
	}else
	{
		$a=1;
	}

	$n=$_SESSION['venta'];
	$q=$conexion2->query("select * from venta where sessiones='$n'")or die($conexion2->error);

	$fq=$q->FETCH(PDO::FETCH_ASSOC);
	$nom=$fq['cliente'];
	$hoy=$fq['fecha'];
	$estado=$fq['documento_inv'];
	$bodegav=$fq['bodega_venta'];
	//echo "<script>alert('$estado')</script>";
	if($estado!='')
	{
		echo "<script>alert('LA VENTA YA FUE FINALIZADA')</script>";
		$_SESSION['venta']='';
		echo "<script>location.replace('venta.php')</script>";
	}

}else
{
	$hoy=date("Y-m-d");
}

$con=$conexion1->query("select * from consny.CONSECUTIVO_CI where CONSECUTIVO='venta'")or die($conexion1->error());
$fcon=$con->FETCH(PDO::FETCH_ASSOC);
$conse=$fcon['SIGUIENTE_CONSEC'];

?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			// alert($("#a").val());
			if($("#a").val()==1)
			{
			  $("#form1").show();
			  $("#barra").focus();
			}else if($("#a").val()==2)
			{
				$("#precio").focus();
			}else
			{
				$("#form1").hide();
			}

		
		});
		function prueba()
		{
			return false;
		}
		function enviar()
		{
			//alert('entra');
			if($("#precio").val()=='')
			{
				alert('AGREGE EL PRECIO DEL FARDO');
				$("#precio").focus();
			}else
			{
				$("#form1").submit();
			}

			
		}
		function final()
		{
			if(confirm('SEGURO DESEA FINALIZAR LA VENTA'))
			{
				location.replace('final_venta.php');
			}
			
		}
		function busqueda(){
			var b = $("#barra").val();
			//alert(b);
			location.replace('librasventa.php?b='+ b);
		}
	</script>
</head>
<body>
	<?php
	echo "CONSECUTIVO: $conse<br>";
?>
	

	<input type="hidden"  name="a" id="a" value='<?php echo "$a";?>'>
<form method="POST" name="form">
<input type="text" class="text" name="cliente" class="text" placeholder="CLIENTE" style="width: 33%;" value='<?php echo "$nom";?>' required>

<input type="date" name="fecha" class="text" style="width: 19%;" value='<?php echo "$hoy";?>' class='text'>
<select required class="text" name="bodega_venta" style="width: 18%;">
	
<?php
if($bodegav=='')
{
	echo "<option value=''>BODEGA VENTA</option>";
}else
{
	echo "<option>$bodegav</option>";
}
$cb=$conexion1->query("select * from consny.bodega where bodega like'SM%'")or die($conexion1->error());
while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
{
 $bo=$fcb['BODEGA'];
 $nom=$fcb['NOMBRE'];
 echo "<option>$bo</option>";
}

?>
</select>
<input type="submit" name="btn" value="SIGUIENTE" class="boton2">

</form><br>
<hr>
<form method="POST" id="form1" name="form1">
CODIGO DE BARRA: <input type="text" name="barra" id="barra" class="text" style="width: 25%;" onchange="busqueda()" onkeyup="prueba()"  id="barra" value='<?php echo "$barra";?>'>

LIBRAS: <input type="text"  name="libras"  class="text" style="width: 10%;" required id="libras" value='<?php echo "$peso";?>'>
PRECIO: <input type="number" step="any" lang="en" min="0" name="precio"  class="text" style="width: 10%;" required id="precio" >
<input type="text" name="btn" value="Add" class="boton2" style="width: 3%;" readonly onclick="enviar()">
<hr>	
</form>
<?php
$v=$_SESSION['venta'];
$q=$conexion2->query("select * from venta where sessiones='$v' and registro!='' and precio!='' order by id desc")or die($conexion2->error);
$nq=$q->rowCount();
if($nq!=0)
{
	$qn=$conexion2->query("select count(*) as total from venta where sessiones='$v' and registro!='' and precio!=''")or die($conexion2->error);
	$fqn=$qn->FETCH(PDO::FETCH_ASSOC);
	$total=$fqn['total'];


	echo "<table class='tabla' border='1' cellpadding='10'>
	<tr>
	<td colspan='6'><b>TOTAL FARDO: $total</b></td>
	</tr>
	<tr>
		<td>CODIGO BARRA</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>LIBRAS</td>
		<td>PRECIO</td>
		<td width='10%'>QUITAR</td>
	</tr>";
	$t=0;
	while($fq=$q->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$fq['registro'];
		$precio=$fq['precio'];
		$id=$fq['id'];
		$ca=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error);
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$ba=$fca['barra'];
		$art=$fca['codigo'];
		$libras=$fca['lbs'] + $fca['peso'];
		$car=$conexion1->query("select DESCRIPCION from consny.ARTICULO WHERE consny.ARTICULO.ARTICULO='$art'")or die($conexion1->error);
		$fcar=$car->FETCH(PDO::FETCH_ASSOC);
		$des=$fcar['DESCRIPCION'];
		echo "<tr>
		<td>$ba</td>
		<td>$art</td>
		<td>$des</td>
		<td>$libras</td>
		<td>$precio</td>
		<td>
		<a href='elimina_venta.php?iden=$id'>
		<img src='eliminar.png'  style='width:15%; height:5%; cursor:pointer;'></a></td>

	</tr>";
	$t=$t + $precio;
	}
	echo "<tr>
		<td colspan='4'>TOTAL</td>
		<td>$t</td>
		<td></td>
	</tr>";
	echo "<tr>
		<td colspan='6'>
			<form method='POST' action='final_venta.php'>
			CONSECUTIVO DE VENTA: 
			<input type='text' name='consecutivo' value='$conse' class='text' style='width:10%;'>
			<input type='text' class='text' name='observacion' style='width:20%;'placeholder='OBSERVACION'  required>
			<input type='submit' value='FINALIZAR' class='btnfinal' style='padding-bottom:0.5%; padding-top:0.5%; margin-bottom:-2%;'>
		</td>
	</tr>";
	echo "</table>";
}
?>


<?php
if($_POST)
{
	extract($_REQUEST);
	$paquete=$_SESSION['paquete'];
	$usuario=$_SESSION['usuario'];
	if($btn=="SIGUIENTE")
	{
		if($_SESSION['venta']=="")
		{
		$c=$conexion2->query("select max(sessiones) as numero FROM venta")or die($conexion2->error);
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$numero=$f['numero'] + 1;
		
		$ns=1;
		while($ns!=0)
		{
			$k=$conexion2->query("select * from venta where sessiones='$numero'")or die($conexion2->error);
			$nk=$k->rowCount();
			if($nk!=0)
			{
				$numero=$numero+1;
				$ns=1;
			}else
			{
				$ns=0;
			}
		}
		$_SESSION['venta']=$numero;
		
		$conexion2->query("insert into venta(cliente,fecha,paquete,usuario,sessiones,fecha_ingreso,bodega_venta) values('$cliente','$fecha','$paquete','$usuario','$numero',getdate(),'$bodega_venta')")or die($conexion2->error);
		echo "<script>location.replace('venta.php')</script>";
	}else
	{
	$venta=$_SESSION['venta'];
	$conexion2->query("update venta set cliente='$cliente',fecha='$fecha' where sessiones='$venta'")or die($conexion2->error);
		echo "<script>location.replace('venta.php')</script>";

	}
}else if($btn=="Add")
	{
		//echo "<script>alert('f')</script>";
		if($precio=="")
		{
			$precio=0;
		}
		$con=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error);
		$ncon=$con->rowCount();
		if($ncon==0)
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO')</script>";
			echo "<script>location.replace('venta.php')</script>";
		}else
		{
			$numero=$_SESSION['venta'];
			$k=$conexion2->query("select top 1 * from venta where sessiones='$numero'")or die($conexion2->error);
			$nk=$k->rowCount();
			if($nk==0)
			{
				echo "<script>alert('ERROR! INTENTELO NUEVAMENTE')</script>";
				echo "<script>location.replace('venta.php')</script>";
			}else
			{
				 $fcon=$con->FETCH(PDO::FETCH_ASSOC);
		 $idr=$fcon['id_registro'];
		 $activo=$fcon['activo'];
		 $bodega_r=$fcon['bodega'];
		 $fecha_documento=$fcon['fecha_documento'];
		 $fk=$k->FETCH(PDO::FETCH_ASSOC);
		 $cliente=$fk['cliente'];
		 $fecha=$fk['fecha'];
		 $bodega_venta=$fk['bodega_venta'];
		$con=$conexion2->query("select * from venta where sessiones='$numero' and registro='$idr'")or die($conexion2->error);
		$ncon=$con->rowCount();
		
if($ncon==0)
{
	if($activo=='0')
	{
		echo "<script>alert('FARDO NO DISPONIBLE: YA FUE TRABAJADO EN MESA O YA FUE VENDIDO')</script>";
		echo "<script>location.replace('venta.php')</script>";
	}else
	{
		if($bodega_venta!=$bodega_r)
		{
			echo "<script>alert('FARDO NO SE ENCUENTRA EN LA BODEGA $bodega_venta')</script>";
			echo "<script>location.replace('venta.php')</script>";
		}else
		{
			if($fecha<$fecha_documento)
			{
				echo "<script>alert('ERROR: LA FECHA DE PRODUCCION DEL FARDO ES MAYOR A LA FECHA DE VENTA')</script>";
			}else
			{

			$vali=validacion_disponible($barra);
					if($vali=='FARDO NO SE PUEDE USAR POR:')
					{
			$conexion2->query("insert into venta(cliente,fecha,paquete,usuario,sessiones,registro,precio,fecha_ingreso,bodega_venta) values('$cliente','$fecha','$paquete','$usuario','$numero','$idr','$precio',getdate(),'$bodega_venta')")or die($conexion2->error);
			echo "<script>location.replace('venta.php')</script>";
					}else
					{
						echo "<script>alert('$vali')</script>";
			echo "<script>location.replace('venta.php')</script>";
						
					}
			}
		}
		
	}
	
}else
{
	echo "<script>alert('ARTICULO YA FUE AGREGADO ANTES')</script>";
	echo "<script>location.replace('venta.php')</script>";
}
		
			}
		
		}
		echo "<script>location.replace('venta.php?po')</script>";
	}

}
?>
</body>
</html>