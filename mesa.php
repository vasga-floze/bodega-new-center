<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="moment.min.js"></script>
	<script>
		$(document).ready(function(){
		$("#op2").hide();
		$("#hora").val(moment().format("YYYY-MM-DD H:mm:ss"));
		$(".detalle").hide();
		if($("#a").val()==1)
		{
			$("#op2").show();
			$("#barra").focus();
		}
		});
		function enter()
		{
			
		}
		function cerrar()
		{
			$(".detalle").hide();
		}
		function activa()
		{
			$(".detalle").show();
		}

		function enviar()
		{
			//alert('sd');
			document.form.submit();
		}
		function fo()
		{
			$("#buton").focus();
		}
		function mueve()
		{
			var fechas=$("#fecha").val();
			$("#fe").val(fechas);
		}

		function pruebas()
		{
			var fecha1 = moment($("#hoy").val());
		var fecha2 = moment($("#fecha").val());
		var dia =fecha2.diff(fecha1, 'days');
		

		if(dia>0)
		{
			alert('FECHA NO VALIDA');
			$("#fecha").val('');
			$("#fecha").val('');
			$("#fecha").attr("required",true);
			
		}
		

			
		}

		function fechahora()
  {
  	 var horas=moment().format("YYYY-MM-DD H:mm:ss");
  	 //alert(horas);
 	 $("#actual").val(horas);
 	 var h=$("#hora").val();
 	 //alert(h);
 	 var minutos=moment(horas).diff(moment(h),'minutes') ;
 	 //alert(minutos);
 	 if(minutos>=10)
 	 {
 	 	alert('CERRADO POR INACTIVIDAD');
 	 	location.replace('salir.php');
 	 }

 	 return horas;
  }
 setInterval(fechahora,1000);
		
	</script>
	<?php
	error_reporting(0);
		include("conexion.php");
		 //echo "<script>alert('NO DISPONIBLE')</script>";
		 //eCHO "<script>location.replace('salir.php')</script>";

		if($_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO')
		{
			//$_SESSION['id']=2559;
		}

		if($_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='gjurado' or $_SESSION['usuario']=='GJURADO')
		{
			//$_SESSION['id']=1677;
		}
		if($_SESSION['usuario']=='mfuentes' or $_SESSION['usuario']=='MFUENTES')
		{
			//$_SESSION['id']=1760;
		}
		if($_SESSION['usuario']=='mcampos' or $_SESSION['usuario']=='MCAMPOS' or $_SESSION['usuario']=='staana3')
		{
			//$_SESSION['id']=2812;
		}
		if($_SESSION['tipo']!=1)
		{
			echo "<script>alert('NO TIENES AUTORIZACION')</script>";
			echo "<script>location.replace('desglose.php')</script>";

		}
		$t=$_GET['t'];
		$fe=$_GET['fecha'];
		$hoy=date("Y-m-d");
		$idr=$_SESSION['id'];
		//echo "<script>alert('$idr')</script>";
		if($idr!="")
		{
			$a=1;
			$k=$conexion2->query("select * from mesa where id='$idr'")or die($conexion2->error);
			$fk=$k->FETCH(PDO::FETCH_ASSOC);
			$hoy=$fk['fecha'];
			$pro=$fk['producido'];
			$me=$fk['mesa'];
			$boder=$fk['bodega'];

		}
		if($fe!='')
		{
			$hoy=$fe;
		}

		$ke=$conexion2->query("select count(*) as total from detalle_mesa where mesa='$idr'")or die($conexion2->error);
		$fke=$ke->FETCH(PDO::FETCH_ASSOC);
		$to=$fke['total'];

	?>
<body>
	<input type="hidden" name="actual" id="actual">
	<input type="hidden" name="hora" id="hora">

<div class="detalle" style="margin-top: -4.7%; display: none;">
	<button style="float: right; margin-right: 0.5%; border:none; color: white; background-color: rgb(130,130,137,0.4); cursor: pointer; margin-top: 0.5%" onclick="cerrar()">Cerrar X</button>
	<div class="adentro" style="width: 60%; height: 90%; margin-left: 25%; margin-top: 0.5%; padding-bottom: 1.5%;">
	<?php
$consu=$conexion1->query("select * from dbo.PRODUCCION_ACCPERSONAL where PRODUCE='1' and activo='1'")or die($conexion1->error);
$n=1;
echo "<form method='POST'>";
while($fconsu=$consu->FETCH(PDO::FETCH_ASSOC))
{
		$nom=$fconsu['NOMBRE'];
		echo "<label><input type='checkbox' name='op[$n]' value='$nom'>$nom</label><br><br>";
		$n++;

}
echo "<label><input type='checkbox' name='op[$n]' value='BODEGA SAN MIGUEL'>BODEGA SAN MIGUEL</label><br><br>";
$n++;
echo "<input type='hidden' name='n' value='$n'>
<input type='hidden' name='fe' id='fe' >";
echo "<input type='submit' name='btn' value='OK' name='btn' class='boton1-1' style='float:right; margin-top:3%; margin-right:2%;'></form>";
$ahora1=date("Y-m-d");
$cv=$conexion2->query("select convert(date,DATEADD(DAY,-8,'$ahora1')) as fecha")or die($conexion2->error());
$fcv=$cv->FETCH(PDO::FETCH_ASSOC);
$ahora=$fcv['fecha'];
	?>
	<br><br><br><br>

		</div></div>

<input type="hidden" name="a" id="a" value='<?php echo "$a"; ?>'>
<form method="POST">
	<table class="tabla">
	<tr>
		<td width="5%">
	FECHA:<br>
	<input type="hidden" name="hoy" id="hoy" value='<?php echo "$hoy";?>'>
	<input type="date" name="fecha" id="fecha" value='<?php echo "$hoy";?>' min='<?php if($_SESSION['usuario']!='staana3'){echo "$ahora";}?>' max='<?php echo "$hoy";?>' min='<?php //echo "$ahora";?>'  class='text' style='padding-top: 3%; padding-bottom: 3%;' onchange='mueve()'>
		</td>
		<td width="30%">
	PRODUCIDO:<br>
	<input type="text" name="producido" class='text' ondblclick="activa()" style='padding-top: 1%; padding-bottom: 1%;' value='<?php if($t!=""){ echo "$t";}else{ echo "$pro";}?>' autocomplete="off" required>
		</td>
	<td width="8%">
	MESA:<br>
	<input type="text" name="mesa" class='text' style='padding-top: 3%; padding-bottom: 3%;' value='<?php echo "$me";?>'>
	</td>
	<td>
		<br>
		<select name="bodega" class="text" style="padding-bottom: 1.5%; padding-top: 1.5%;" required>
	<?php
	//echo "<script>alert('$to')</script>";
	if($boder=='')
	{
		//echo "<option value=''>BODEGA</option>";
		$cb=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%' order by bodega")or die($conexion1->error());
	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bi=$fcb['BODEGA'];
		echo "<option value='$bi'>$bi: ".$fcb['NOMBRE']."</option>";
	}
	}else
	{
		if($to>0)
		{
				$bo=$fk['bodega'];
			//echo "<script>alert('$bo')</script>";
			$qb=$conexion1->query("select * from consny.bodega where bodega='$bo'")or die($conexion1->error());
			$fqb=$qb->FETCH(PDO::FETCH_ASSOC);
			$qbo=$fqb['BODEGA'];
			$qbnom=$fqb['NOMBRE'];
		echo "<option value='$qbo'>$qbnom</option>";

		}else
		{
			$bo=$fk['bodega'];
			//echo "<script>alert('$bo')</script>";
			$qb=$conexion1->query("select * from consny.bodega where bodega='$bo'")or die($conexion1->error());
			$fqb=$qb->FETCH(PDO::FETCH_ASSOC);
			$qbo=$fqb['BODEGA'];
			$qbnom=$fqb['NOMBRE'];
		echo "<option value='$qbo'>$qbnom</option>";
		$cb=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%' order by bodega")or die($conexion1->error());
	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bi=$fcb['BODEGA'];
		echo "<option value='$bi'>$bi: ".$fcb['NOMBRE']."</option>";
	}

		}

	}
	?>

			
<?php


?>
		</select>
	</td>
	<td><br>
		<input type="submit" name="btn" value="SIGUIENTE" class="boton2" style="padding:5%;">
	</td>
	</table>
</form>
<div id="op2">
<hr>
<form method="POST" action="" name="form">
	CODIGO DE BARRA:<input type="text" name="barra" id="barra" class="text" style="width: 20%;" onchange="fo()" onkeypress="enter()" >
	OBSERVACIONES:<input type="text" name="obs" class="text" id="obs" style="width: 35%;">
	<input type="text" name="btn" value="AGREGAR" class="boton2" id="buton" onkeypress='enviar()' style="width: 5%;" onclick="enviar()" readonly>
	
</form>
<?php echo "<u><b>TOTAL FARDOS: $to</b></u>";?>
<hr>
</div>
<?php
$totallbs=0;
if ($idr!='')
{
$q=$conexion2->query("select * from detalle_mesa where mesa='$idr' order by id desc")or die($conexion2->error);
$nq=$q->rowCount();
if($nq!=0)
{
echo "<table class='tabla' border='1' cellpadding='10'>";
echo "<tr>
	<td>BARRA</td>
		<td>ARTICULO</td>
		<td>OBSERVACIONES</td>
		<td>CANTIDAD</td>
		<td>PESO</td>
		<td>QUITAR</td>
</tr>";
// probar mesa porque dejo de funcionar <---

while($fq=$q->FETCH(PDO::FETCH_ASSOC))
{
	$idr=$fq['registro'];
	$ob=$fq['comentario'];
	$iddm=$fq['id'];
	$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
	$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
	$cod=$fcr['codigo'];
	$lbs=$fcr['lbs'];
	$peso=$fcr['peso'];
	$tlbs=$lbs + $peso;
	$barras=$fcr['barra'];
	$ca=$conexion1->query("select * from consny.ARTICULO where articulo='$cod'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
	$ar=$fca['ARTICULO'];
	$de=$fca['DESCRIPCION'];
	echo "<tr>
		<td>$barras</td>
		<td>$ar: $de</td>
		<td>$ob</td>
		<td>1</td>
		<td>$tlbs</td>
		<td><a href='elimina_mesa.php?id=$iddm'>eliminar</a></td>
</tr>";
$totallbs=$totallbs + $tlbs;
}
}
if($to>0)
{
	echo "<tr>
<td colspan='4'><u><b>TOTAL FARDOS: $to</b></u></td>
<td>$totallbs</td>
<td><form method='POST'><input type='submit' name='btn' value='FINALIZAR' class='btnfinal' style='float:right; padding:4%; margin-bottom:-0.5%;'></form></td>
</tr>";
}

echo "</table>";
}
?>


</body>
</html>

<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='SIGUIENTE')
	{

	if($_SESSION['id']=="")
	{
	$c=$conexion2->query("select max(id) as id from mesa")or die($conexion2->error);
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$id=$f['id']+1;
	$num=1;
	while($f=$num !=0)
	{
		$qk=$conexion2->query("select * from mesa where id='$id'")or de($conexion2->error);
		$nqk=$qk->RowCount();
		if($nqk!=0)
		{
			$id=$id+1;
			$num=1;
		}else
		{
			$num=0;
		}
	}
	$usuario=$_SESSION['usuario'];
	$paquete=$_SESSION['paquete'];
	$_SESSION['id']=$id;
	$conexion2->query("insert into mesa(id,producido,mesa,fecha,usuario,paquete,fecha_ingreso,bodega) values('$id','$producido','$mesa','$fecha','$usuario','$paquete',getdate(),'$bodega')")or die($conexion2->error);
	echo "<script>location.replace('mesa.php?fecha=$fecha')</script>";
}else
{
	$idr=$_SESSION['id'];
	$conexion2->query("update mesa set producido='$producido',mesa='$mesa',fecha='$fecha',bodega='$bodega' where id='$idr'")or die($conexion2->error());
	echo "<script>location.replace('mesa.php')</script>";
}
}else if($btn=='AGREGAR')
{

	$c=$conexion2->query("select isnull(fecha_traslado,fecha_documento) as fecha_documento,barra,activo,bodega,id_registro from registro where  barra='$barra' and estado='1' and id_registro not in(select registro from traslado where estado=0)")or die($conexion2->error);
	$n=$c->RowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO DE: $barra O ESTA SIENDO TRASLADADO\ O SU CONTENEDOR O FECHA PRODUCCION NO SE HA FINALIZADO')</script>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$art=$f['id_registro'];
		$bodegar=$f['bodega'];
		$activo=$f['activo'];
		$fecha_documento=$f['fecha_documento'];
		$cv=$conexion2->query("select registro.id_registro,mesa.estado from registro inner join detalle_mesa on registro.id_registro=detalle_mesa.registro inner join mesa on detalle_mesa.mesa=mesa.id  where registro.id_registro='$art'")or die($conexion2->error);
		$ncv=$cv->rowCount();
		$fcv=$cv->FETCH(PDO::FETCH_ASSOC);

		if($fcv['estado']=='T' or $fcv['estado']=='1')
		{
			echo "<script>alert('BULTO YA FUE TRABAJADO ANTES')</script>";
			echo "<script>location.replace('mesa.php')</script>";
		}else
		{
			$idr=$_SESSION['id'];
		$consu=$conexion2->query("select * from detalle_mesa where registro='$art' and mesa='$idr'")or die($conexion2->error());
		$nconsu=$consu->rowCount();
		if($nconsu==0)
		{
			$query=$conexion2->query("select * from mesa where id='$idr'")or die($conexion2->error());
		$fquery=$query->FETCH(PDO::FETCH_ASSOC);
		$bodegam=$fquery['bodega'];
		$fecha=$fquery['fecha'];
		if(strtoupper($bodegam)!=strtoupper($bodegar))
		{
			echo "<script>alert('FARDO NO ESTA EN LA BODEGA $bodegam')</script>";
			echo "<script>location.replace('mesa.php')</script>";
		}else{
			if($activo=='0')
			{
				echo "<script>alert('FARDO NO DISPONIBLE YA FUE TRABAJADO O VENDIDO')</script>";
				echo "<script>location.replace('mesa.php')</script>";
			}else{
				//aqui irira validacion de fechas...
				//echo "<script>alert('$fecha -> $fecha_documento')</script>";
				if($fecha<$fecha_documento)
				{
					echo "<script>alert('LA FECHA DEL FARDO ES MAYOR A LA FECHA DEL BULTO')</script>";
					echo "<script>location.replace('mesa.php')</script>";
				}else
				{
					$vali=validacion_disponible($barra);
					if($vali=='FARDO NO SE PUEDE USAR POR:')
					{
						$conexion2->query("insert into detalle_mesa(mesa,registro,comentario,fecha_hora) values('$idr','$art','$obs',getdate())")or die($conexion2->error());
					}else
					{
						echo "<script>alert('$vali')</script>";
					echo "<script>location.replace('mesa.php')</script>";
						
					}
				
				}
			}
		}
			
	echo "<script>location.replace('mesa.php')</script>";

}else
{
	echo "<script>alert('YA FUE AGREGADO ANTES!.')</script>";
	echo "<script>location.replace('mesa.php')</script>";
}
			}
		
	}
	
}else if($btn=="FINALIZAR")
{
	$idr=$_SESSION['id'];
	$conexion2->query("update mesa set estado=1 where id='$idr'")or die($conexion2->error);
	$_SESSION['id']='';
	echo "<script>alert('HECHO!')</script>";
	echo "<script>location.replace('mesa.php')</script>";

}

if($btn=='OK')
{
	$k=1;
		$nn=1;
		while($nn<=$n)
		{
			if($op[$n]!="" and $k==1)
			{
				$t="$op[$n]";
				$k=0;
			}else if($op[$n]!="" and $k==0)
			{
				$t="$t,$op[$n]";
			}
			
			$n--;
		}
		if($pro!="")
		{
			$t="$pro,$t";
		}
		echo "<script>location.replace('mesa.php?t=$t&&fecha=$fe')</script>";
}

//final post
}
?>