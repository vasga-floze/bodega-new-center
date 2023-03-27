<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
		$("#loader").hide();


			if($("#rdetalle").val()==1)
			{
				$(".detalle").show();
				$("#opb").val($("#ropb").val());
				$("#origen").val($("#rorigen1").val());
				$("#nom_origen").val($("#rnomorigen1").val());
				$("#destino").val($("#rdestino1").val());
				$("#nom_destino").val($("#rnomdestino1").val());
				
				$("#rdestino").val($("#rdestino1").val());
				$("#rnomdestino").val($("#rnomdestino1").val());
				//alert($("#rnomdestino1").val());

			}else 
			{
				$(".detalle").hide();
				$("#origen").val($("#rorigen1").val());
				$("#nom_origen").val($("#rnomorigen1").val());
				$("#destino").val($("#rdestino1").val());
				$("#nom_destino").val($("#rnomdestino1").val());
				$("#rdestino").val($("#rdestino1").val());
				$("#rnomdestino").val($("#rnomdestino1").val());
			}

		})
		function borigen()
		{
			
			$("#opb").val(1);
			$("#opcion").val(0);
			$("#rorigen").val($("#origen").val());
			$("#rnomorigen").val($("#nom_origen").val());
			$("#rdestino").val($("#destino").val());
			$("#rnomdestino").val($("#nom_destino").val());
			if($("#destino").val()=='')
			{
				$("#rdestino").val($("#rdestino1").val());
			}
			if($("#nom_destino").val()=='')
			{
				$("#nom_destino").val($("#rnomdestino1").val());
			}
			//alert($("#rnomdestino").val());
			$("#formb").submit();
		}
		function cerrar()
		{
			//alert($("#rnomorigen1").val());
			$(".detalle").hide();
			$("#origen").val($("#rorigen1").val());
			$("#nom_origen").val($("#rnomorigen1").val());
			$("#destino").val($("#rdestino1").val());
			$("#nom_destino").val($("#rnomdestino1").val());


		}

		function bdestino()
		{
			//alert($("#origen").val());
			$("#opb").val(2);
			$("#opcion").val(0);
			$("#rorigen").val($("#origen").val());
			$("#rnomorigen").val($("#nom_origen").val());
			$("#rdestino").val($("#destino").val());
			$("#rnomdestino").val($("#nom_destino").val());
			$("#formb").submit();


		}

		function seleccion(e,o)
		{

			if(o==1)
			{
				$("#origen").val($("#bod"+e).text());
				$("#nom_origen").val($("#nom"+e).text());
				$("#destino").val($("#rdestino1").val());
				$("#nom_destino").val($("#rnomdestino1").val());
				$(".detalle").hide();
			}else if(o==2)
			{
				$("#destino").val($("#bod"+e).text());
				$("#nom_destino").val($("#nom"+e).text());
				$("#origen").val($("#rorigen1").val());
				$("#nom_origen").val($("#rnomorigen1").val());
				$(".detalle").hide();
			}
		}
		function buscar()
		{
			//alert($("888").val());
			$("#rorigen").val($("#origen").val());
			$("#rnomorigen").val($("#nom_origen").val());
			$("#rdestino").val()($("#destino").val());
			$("#rnomdestino").val($("#nom_destino").val());
			$("#opb").val($("#ropb").val());
			$("#formb").submit();

		}
		function enviar()
		{
			$("#opcion").val(1);
			$("opb").val(0);
		}
		

		function sorigen()
		{
			$("#opcion").val(2);
			$("#opb").val(0);
			$("#nom_origen").val('');
			$("#form").submit();
		}
		function sdestino()
		{

			$("#opcion").val(3);
			$("#opb").val(0);
			$("#nom_destino").val('');
			$("#form").submit();
		}
		function confirmar()
		{
			//alert();
			if($("#origen").val()!='' && $("#destino").val()!='')
			{
				$("#opcion").val(4);
				$("#opb").val(0);
				$("#form").submit(true);
			}else
			{
				alert('SELECCIONE LAS DOS BODEGAS');
				$("#form").submit(false);

			}
		}
		function prueba1()
		{
			$("#opcion").val(5);
			$("#opb").val(0);
			$("#form").submit();
		}
		function cerrar1()
		{
			$("#tabla").hide();
		}
	</script>
</head>
<body>
<div id="loader" style="width: 100%; height: 100%; position: fixed; background-color: white;">
	<img src="loadf.gif" style="margin-left: 45%; margin-top: 15%;">
</div>
<?php
include("conexion.php");
//echo "<script>alert('NO DISPONIBLE hasta las 11:59 am')</script>";
//ECHO "<script>location.replace('salir.php')</script>";
//if($_SESSION['usuario']!='ocampos')
//{
//	echo "<script>alert('NO DISPONIBLE')</script>";
	//ECHO "<script>location.replace('salir.php')</script>";

//}


error_reporting(0);
if($_SESSION['traslado']=='')
{
	//verificar traslado
	$usu=strtoupper($_SESSION['usuario']);
	//echo $usu;
	$tc=$conexion2->query("select * from traslado where usuario='$usu' and estado='0'")or die($conexion2->error());
	$ntc=$tc->rowCount();
	if($ntc!=0)
	{
		echo "<script>alert('YA ESTAS HACIENDO UN TRASLADO Y ABRISTE OTRA VENTANA(incognito), QUE NO TIENES QUE HACER, BUSCALO EN TRASLADO PENDIENTES!! Y FINALIZA ESE TRASLADO ANTE ALGP!!!')</script>";
		echo "<script>location.replace('salir.php')</script>";
	}
	//fin verificar traslado
}


//mostrar
extract($_REQUEST);
if($_POST and $opcion==5)
{
	$doc=$_SESSION['traslado'];
	$usu=strtoupper($_SESSION['usuario']);
	$qr=$conexion2->query("select registro.barra,registro.bodega,registro.observacion,traslado.origen,traslado.destino,CONCAT
(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION) as articulo,traslado.id from registro inner join traslado on
registro.id_registro=traslado.registro inner join EXIMP600.consny.ARTICULO on registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO
where traslado.sessiones='$doc' and traslado.usuario='$usu' order by traslado.id desc")or die($conexion2->error());
$nqr=$qr->rowCount();

	//echo "<script>alert('$nqr')</script>";
	echo "<div style='position:fixed; width:99%; height:100%; overflow:auto; position:fixed; background-color: white; margin-top:-3.5%;' id='reporte'>";
	?>
<button onclick="javascript:
	 			$('#reporte').hide();
	 			$('#divlineas').show(); 
	 			$('#barra').focus();
	 			//alert();
	 			" style="position: sticky; top: 0; right: 0.5; float: right; background-color: red; color: white; font-size: 120%; border: none; cursor: pointer;">X</button>
<table border="1" style="border-collapse: collapse; width: 98%; height: auto; margin-bottom: 1%; background-color: white;" cellpadding="5">
<tr>
	<td>#</td>
	<td>ARTICULO</td>
	<td>BARRA</td>
	<td>BODEGA FARDO</td>
	<td width="10%">OBSERVACION</td>
	<td>ORIGEN</td>
	<td>DESTINO</td>
	<td width="5%">OPCION</td>
</tr>

<?php
$num=0;
while($fqr=$qr->FETCH(PDO::FETCH_ASSOC))
{
	if($fqr['bodega']==$fqr['origen'])
	{
	echo "<tr class='tre'>";
	$color="white";

}else
{
	echo "<tr class='tre' style='background-color:red;'>";
	$color="#ffd733";
}
$num++;
	$idr=$fqr['id'];
	echo "<td>$num</td>
	<td>".$fqr['articulo']."</td>
	<td>".$fqr['barra']."</td>
	<td style='background-color:$color; color: black;'>".$fqr['bodega']."</td>
	<td>".$fqr['observacion']."</td>
	<td style='background-color:$color; color: black;'>".$fqr['origen']."</td>
	<td>".$fqr['destino']."</td>
	<td>
	<img src='eliminar.png' width='50%' height='50%'  style='cursor:pointer; background-color:white;' onclick=\"javascript:
	 						if (confirm('DESEA ELIMINAR EL FARDO DEL TRASLADO'))
	 						{
								$('#idr').val('$idr');
								$('#opcion').val('6');
								$('#form').submit();
	 						}else
	 						{
	 							
	 						}
				 \">
	</td>";
}

	echo "</tr></table></div>";
}
//fin mostrar
if($_SESSION['traslado']!='')
{
	$usuario=strtoupper($_SESSION['usuario']);
	$doc=$_SESSION['traslado'];
	$ctb=$conexion2->query("select origen,destino from traslado where sessiones='$doc' and usuario='$usuario' and estado='0' group by origen,destino")or die($conexion2->error());
	$fctb=$ctb->FETCH(PDO::FETCH_ASSOC);
	$origenes=$fctb['origen'];
	$destinos=$fctb['destino'];
	$cbo=$conexion1->query("select nombre from consny.bodega where bodega='$origenes'")or die($conexion1->error());
	$fcbo=$cbo->FETCH(PDO::FETCH_ASSOC);
	$nom_origenes=$fcbo['nombre'];

	$cbd=$conexion1->query("select nombre from  consny.bodega where bodega='$destinos'")or die($conexion1->error());
	$fcbd=$cbd->FETCH(PDO::FETCH_ASSOC);
	$nom_desinos=$fcbd['nombre'];
	echo "<input type='hidden' name='rorigen1' id='rorigen1' value='$origenes'>";
	echo "<input type='hidden' name='rnom_origen1' id='rnomorigen1' value='$nom_origenes'>";
	echo "<input type='hidden' name='rdestino1' id='rdestino1' value='$destinos'>";
	echo "<input type='hidden' name='rnomdestino1' id='rnomdestino1' value='$nom_desinos'>";
	echo "<script>
	$(document).ready(function(){
		$('#divlineas').show();
		$('#barra').focus();
		})
	</script>";

	
}else
{
	//$_SESSION['traslado']=20125;
	//echo "<script>locarion.replace('trasladob.php')</script>";
}


?>
<div class="detalle" style="margin-top: -5%; padding-left: 3%; display: none;">

	<button style="background-color: red; color:white; float: right; margin-right: 2.5%; border:none; border-color: white; cursor: pointer; margin-top: 1%;" onclick="cerrar()">X</button><br>
	<div class="adentro" style="height: 93%;">
		<form method="POST" name="formb" id="formb">
		<input type="hidden" name="opb" id="opb"readonly>
		<input type="text" name="b" id="b" class="text" style="width: 30%;">
		<input type="hidden" name="rorigen" id="rorigen" readonly>
		<input type="hidden" name="rnomorigen" id="rnomorigen" readonly>
		<input type="hidden" name="rdestino" id="rdestino" readonly placeholder="-<<<">
		<input type="hidden" name="rnomdestino" id="rnomdestino" readonly>
		<input type="submit" name="btn" id="btn" onclick="buscar()" value='BUSCAR'>
		</form>
		<?php
		

		
		?>



		<?php
		extract($_REQUEST);
			//echo "<script>alert('$opb')</script>";

		if($_POST and ($opb==1 or $opb==2 ))
		{
			$detalle=1;
			//echo "<script>alert('->$rorigen -> $rnomorigen<-')</script>";
			echo "<input type='hidden' name='ropb' id='ropb' value='$opb'>";
			echo "<input type='hidden' name='rorigen1' id='rorigen1' value='$rorigen'>";
			echo "<input type='hidden' name='rnomorigen1' id='rnomorigen1' value='$rnomorigen' readonly>";
			echo "<input type='hidden' name='rdestino1' id='rdestino1' value='$rdestino' readonly>";
			echo "<input type='hidden' name='rnomdestino1' id='rnomdestino1' value='$rnomdestino' readonly>";


			echo "<input type='hidden' name='rdetalle' id='rdetalle' value='$detalle'>";
			if($b=='')
			{

				$cb=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%'")or die($conexion1->error());
			}else
			{
				$cb=$conexion1->query("select * from consny.bodega where (nombre like '%$b%' or bodega='$b') and  nombre not like '%(N)%' order by nombre")or die($conexion1->error());
			}
			$ncb=$cb->rowCount();
			if($ncb==0)
			{
				echo "<h3>NO SE ENCONTRO BODEGA DISPONIBLE</h3>";
			
			}else
			{
		
				echo "<table border='1' style='border-collapse:collapse; width:98%;'>";
				echo "<tr>
				<td>BODEGA</td>
				<td>NOMBRE</td>
				</tr>";
				$num=0;
				while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
				{
					$nom=$fcb['NOMBRE'];
					$bod=$fcb['BODEGA'];
					echo "	<tr class='tre' onclick='seleccion($num,$opb)' style='cursor:pointer;'>
							<td id='bod$num'>$bod</td>
							<td id='nom$num'>$nom</td>
							</tr>";
							$num++;
				}
				echo "</table>";

			}
		}
		?>
	</div>
</div>
<br>

<form name="form" id="form" method="POST" autocomplete="off">
<input type="hidden" name="opcion" id="opcion" readonly>
<label style="margin-left: -4%;">ORIGEN:<BR>
<input type="text" name="origen" id="origen" class="text" style="width: 20%; margin-left: -0.5%;" ondblclick="borigen()" onchange="sorigen()" placeholder="BODEGA ORIGEN">
</label>
<input type="text" name="nom_origen" id="nom_origen" class="text" style="width: 55%;" readonly placeholder="NOMBRE BODEGA ORIGEN">
<br><br>
<label>DESTINO:<br>
<input type="text" name="destino" id="destino" class="text" style="width: 20%;" ondblclick="bdestino()" onchange="sdestino()" placeholder="BODEGA DESTINO">
</label>

<input type="text" name="nom_destino" id="nom_destino" class="text" style="width: 55%;" readonly placeholder="NOMBRE BODEGA DESTINO">

<input type="submit" name="btn" id="btn" value="CONFIRMAR" onclick="confirmar()" class="boton3">
<div  id="divlineas" style="display: none;">
<hr>
<input type="text" name="barra" id="barra" placeholder="COD. BARRA" class="text" style="width: 12%;" onkeydown="enviar1()">

<input type="hidden" name="idr" id="idr">
<?php
if($_SESSION['traslado']!='')
{
?>
<button style="background-color: green; color: white; float: right; margin-right: 0.5%;" class="boton4" onclick="javascript:
	 			if(confirm('SEGURO DESEA DEJAR PENDIENTE EL TRASLADO'))
	 			{
	 				$('#opcion').val(7);
	 				$('#form').submit();
	 				$('#opb').val(0);
	 			}else
	 			{
	 				$('#form').submit(false);
	 				location.replace('trasladob.php');
	 			}
				 ">PENDIENTE</button>
	 			
	 			
<button style="background-color: red; color: white; float: right; margin-right: 0.5%;" class="boton4" onclick="javascript:
	 			if(confirm('SEGURO DESEA ELIMINAR EL TRASLADO'))
	 			{
	 				$('#opcion').val(8);
	 				$('#form').submit();
	 				$('#opb').val(0);
	 			}else
	 			{
	 				$('#form').submit(false);
	 				location.replace('trasladob.php');
	 			}
				 ">CANCELAR</button>
				<?php }?>
<input type="submit" name="btn" id="btn" onclick="enviar()" value="AGREGAR" class="boton4">
<img src="expandir.png" id="expandir" width="2%" height="2%" style=" margin-left: 1%; cursor: pointer;" onclick="prueba1()">

<?php
//validar total fardos
$doc=$_SESSION['traslado'];
//echo "<script>alert('$doc')</script>";
$usuario=strtoupper($_SESSION['usuario']);



$cvf=$conexion2->query("select COUNT(*) AS total from traslado where usuario='$usuario' and sessiones='$doc' and estado='0'")or die($conexion2->error());
$fcvf=$cvf->FETCH(PDO::FETCH_ASSOC);
$cantidadf=$fcvf['total'];
if($cantidadf==0 or $cantidadf=='')
{
	echo "<script>$(document).ready(function(){
		$('#expandir').hide();
	})</script>";
}

//fin validar total fardos

//validar bodegas de fardos
$cvb=$conexion2->query("select COUNT(*) AS total from traslado inner join registro on registro.id_registro=traslado.registro where 
traslado.usuario='$usuario' and traslado.sessiones='$doc' and registro.bodega!=traslado.origen and traslado.estado='0'")or die($conexion2->error());
$fcvb=$cvb->FETCH(PDO::FETCH_ASSOC);
$error=$fcvb['total'];
//fin validar bodegas de fardos

?>

TOTAL FARDO: <?php echo $cantidadf;?> || ERRORES: <?php echo $error;?>
<?php

$doc=$_SESSION['traslado'];
$usu=strtoupper($_SESSION['usuario']);
$qc=$conexion1->query("select BODEGA from consny.BODEGA where (BODEGA like 'CA%' or BODEGA like 'E%' or  BODEGA like 'N%') and BODEGA!='CA00' and BODEGA in(select destino from pruebabd.dbo.traslado where sessiones='$doc' and usuario='$usu')")or die($conexion1->error());
$nqc=$qc->rowCount();
//echo "<script>alert('$nqc')</script>";
if($error==0 and $cantidadf>0)
{
	echo "<button class='boton4' style='float:right; margin-right:0.5%; background-color:black; color:white;' onclick=\"javascript:
	 			if($nqc==0)
	 			{
	 				$('#opcion').val('9');
	 				}else
	 				{
	 					$('#opcion').val('9');
	 					$('#comentario').prop('required',true);
	 				}
	 			
				 \">FINALIZAR</button>";



	echo "<input type='text' name='comentario' id='comentario' class='text' style='width: 10%; padding:0.5%; float:right; margin-right:0.5%;' placeholder='COMENTARIO'>";
	//fecha minima de traslado
	$doc=$_SESSION['traslado'];
	$usu=strtoupper($_SESSION['usuario']);
	$ctm=$conexion2->query("select max(isnull(fecha_traslado,fecha_documento)) as minima from registro inner join traslado on registro.id_registro=traslado.registro where traslado.sessiones='$doc' and traslado.usuario='$usu'

")or die($conexion2->error());
	$fctm=$ctm->FETCH(PDO::FETCH_ASSOC);
	$minima=$fctm['minima'];
	$hoy=date('Y-m-d');
	//fin fecha minima de traslado

echo "<input type='date' name='fecha' id='fecha' min='$minima' value='$hoy' class='text' style='float:right; margin-right:0.5%; width: 10%;'>";
}
?>
<hr>
</div>
	
</form>
<?php
//$_SESSION['traslado']=20117;
$doc=$_SESSION['traslado'];
		$user=strtoupper($_SESSION['usuario']);

		$ct=$conexion2->query("select EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,COUNT(EXIMP600.consny.ARTICULO.ARTICULO) as cantidad,
traslado.origen,traslado.destino from traslado inner join EXIMP600.consny.ARTICULO on traslado.articulo=
EXIMP600.consny.ARTICULO.ARTICULO where traslado.sessiones='$doc' and traslado.usuario='$user' and traslado.estado='0' GROUP BY
EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,
traslado.origen,traslado.destino")or die($conexion2->error());

		$nct=$ct->rowCount();
		if($nct!=0)
		{
			echo "<script>
			$(document).ready(function(){
				$('#barra').focus();
				})
			</script>";

		//echo "<script>alert('$doc - $user- $nct')</script>";
			echo "<table border='1' style='border-collapse:collapse; width:100%;'>";
			echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
			<td>ORIGEN</td>
			<td>DESTINO</td>
			</tr>";
			while($fct=$ct->FETCH(PDO::FETCH_ASSOC))
			{
				echo "<tr>
			<td>".$fct['ARTICULO']."</td>
			<td>".$fct['DESCRIPCION']."</td>
			<td>".$fct['cantidad']."</td>
			<td>".$fct['origen']."</td>
			<td>".$fct['destino']."</td>
			</tr>";
			}
			echo "</table>";
			//echo "<script>alert('$doc - $user- $nct')</script>";
		}
?>


<?php
if($_POST)
{
	extract($_REQUEST);
	

	if($opb==0)
	{
		if($opcion==4 and $barra!='')
	{
		$opcion=1;
	}
		if($opcion!=2 and $opcion!=3)
		{
			//echo "<script>alert('$nom_destino - $destino')</script>";
			echo "<input type='hidden' name='rorigen1' id='rorigen1' value='$origen'>";
			echo "<input type='hidden' name='rnomorigen1' id='rnomorigen1' value='$nom_origen'>";
			echo "<input type='hidden' name='rdestino1' id='rdestino1' value='$destino'>";
		echo "<input type='hidden' name='rnomdestino1' id='rnomdestino1' value='$nom_destino
		'>";
		}
		
		
	}
	if($opcion==1)//sifnifica que se insert del codigo de barra en traslado
	{
		if($_SESSION['traslado']=='')
		{
			//saco correlativo del traslado que se validara junto con el usuario logiado
			$cs=$conexion2->query("select MAX(sessiones)+1 as sessiones from traslado")or die($conexion2->error());
			$fcs=$cs->FETCH(PDO::FETCH_ASSOC);


			//validacion si el corelativo ya esta en uso <- esto no asegura al 100% que dos usuario tomen el mismo corelativo...
			$session=$fcs['sessiones'];
			$k=0;
			while($k=0)
			{
				$qv=$conexion2->query("select * from traslado where sessiones='$session'")or die($conexion2->error());
				$nqv=$qv->rowCount();
				if($nqv==0)
				{
					//$session++;
					$k=1;
				}else
				{
					$sessiones++;
					$k=0;
				}
			}
			$_SESSION['traslado']=$session;
			


		}//fin validacion corelativo

		$sessiones=$_SESSION['traslado'];
		//echo $sessiones;
		$usuario=strtoupper($_SESSION['usuario']);
		//echo "<script>alert('$sessiones - $usuario')</script>";

		$vali=validacion_disponible($barra);
		echo "<script>$(document).ready(function(){
				$('#divlineas').show();
				$('#barra').focus();
			})</script>";
		if($vali=='FARDO NO SE PUEDE USAR POR:')
		{
			//echo "<script>alert('insert $sessiones - $usuario - $origen - $destino')</script>";
			$cr=$conexion2->query("select * from registro where barra='$barra' and bodega='$origen' and id_registro not in(select registro from traslado where estado=0)")or die($conexion2->error());
			$ncr=$cr->rowCount();
			if($ncr==0)
			{
				echo "<script>alert('ERROR FARDO:\\n- NO DISPONIBLE\\N- ESTA SIENDO UTILIZADO EN OTRO TRASLADO\\n- NO SE ENCUENTRA EN LA BODEGA $origen')</script>";
			}else
			{
				$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
				$idr=$fcr['id_registro'];
				$art=$fcr['codigo'];
				$paquete=$_SESSION['paquete'];
				$destino=strtoupper($destino);
				$origen=strtoupper($origen);
				$usuario=strtoupper($_SESSION['usuario']);
				if($paquete!='' or $usuario!='')
				{
					$conexion2->query("insert into traslado(registro,destino,origen,documento_inv,
					paquete,usuario,estado,sessiones,articulo,fecha_ingreso) values('$idr','$destino','$origen','- -','$paquete','$usuario','0','$sessiones','$art',getdate())")or die($conexion2->error());
				echo "<script>location.replace('trasladob.php')</script>";
			}else
			{
				
				echo "<script>location.replace('salir.php')</script>";
				

			}
			}
			

		}else
		{
			echo "<script>alert('FARDO NO SE PUEDE USAR POR:\\n$vali')</script>";
		}
		// fin insert codigo de barra en traslado
	}else if($opcion==2)
	{
		//busqueda por el onchange de text origen
		$cb=$conexion1->query("select * from consny.bodega where bodega='$origen' and nombre not like '%(N)%'")or die($conexion1->error());
		$ncb=$cb->rowCount();
		if($ncb==0)
		{
			echo "<script>alert('NO SE ENCONTRO BODEGA $origen O NO SE ENCUENTRA DISPONIBLE')</script>";
			echo "<input type='hidden' name='rdestino1' id='rdestino1' value='$destino' readonly>";
			echo "<input type='hidden' name='rnomdestino1' id='rnomdestino1' value='$nom_destino' readonly>";
		}else
		{
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bode=$fcb['BODEGA'];
			$nombode=$fcb['NOMBRE'];
			//echo "<script>alert('entra')</script>";
			echo "<input type='hidden' name='rorigen1' id='rorigen1' value='$bode'>";
			echo "<input type='hidden' name='rnomorigen1' id='rnomorigen1' value='$nombode'>";
			echo "<input type='hidden' name='rdestino1' id='rdestino1' value='$destino' readonly>";
			echo "<input type='hidden' name='rnomdestino1' id='rnomdestino1' value='$nom_destino' readonly>";
		}
	}else if($opcion==3)
	{

		$cb=$conexion1->query("select * from consny.bodega where bodega='$destino'")or die($conexion1->error());
		$ncb=$cb->rowCount();
		if($ncb==0)
		{
			echo "<script>alert('NO SE ENCONTRO LA BODEGA $destino O NO SE ENCUENTRA DISPONIBLE')</script>";
			echo "<input type='hidden' name='rorigen1' id='rorigen1' value='$origen' readonly>";
			echo "<input type='hidden' name='rnomorigen1' id='rnomorigen1' value='$nom_origen' readonly>";
		

		}else

		{
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bode=$fcb['BODEGA'];
			$nombode=$fcb['NOMBRE'];
			//echo "<script>alert('$bode - $nombode')</script>";
			echo "<input type='hidden' name='rorigen1' id='rorigen1' value='$origen' readonly>";
			echo "<input type='hidden' name='rnomorigen1' id='rnomorigen1' value='$nom_origen' readonly>";
			echo "<input type='hidden' name='rdestino1' id='rdestino1' value='$bode' readonly>";
			echo "<input type='hidden' name='rnomdestino1' id='rnomdestino1' value='$nombode' readonly>";
		}
	}else if($opcion==4)
	{
		if($origen==$destino)
		{
			echo "<script>alert('ERROR: BODEGA ORIGEN ES LA MISMA BODEGA DESTINO')</script>";
		}else
		{
		echo "<script>
		$(document).ready(function(){
			
			$('#divlineas').show(800);
			$('#barra').focus();

			
			})
		</script>";

		}
		if($_SESSION['traslado']=='')
		{
		

			//echo "<script>alert('CONFIRMADO')</script>";
			
		}else
		{
			$session=$_SESSION['traslado'];
			$usuario=$_SESSION['usuario'];
			//update de bodegas
			$conexion2->query("update traslado set origen='$origen',destino='$destino' where sessiones='$session' and usuario='$usuario' and estado='0'")or die($conexion2->error());
			echo "<script>location.replace('trasladob.php')</script>";
			//fin update de bodegas

		}
	}else if($opcion==5)
	{
		
		
	}else if($opcion==6)
	{
		$usuario=strtoupper($_SESSION['usuario']);
		$doc=$_SESSION['traslado'];
		//echo "<script>alert('i $idr u $usuario s $doc')</script>";
		$cb=$conexion2->query("select registro.barra from registro inner join traslado on traslado.registro=registro.id_registro where traslado.id='$idr' and traslado.sessiones='$doc' and traslado.usuario='$usuario' and traslado.estado='0'")or die($conexion2->error());
		$ncb=$cb->rowCount();
		if($ncb!=0)
		{
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			//$barras=$fcb['barra'];
		//echo "<script>alert('$barras')</script>";
		if($idr!='' and $doc!='' and $usuario!='')
			{
			$conexion2->query("delete from traslado where id='$idr' and sessiones='$doc' and usuario='$usuario' and estado='0'")or die($conexion2->error());
			echo "<script>
			$(document).ready(function(){
				$('#opcion').val(5);
				$('#opb').val(0);
				$('#form').submit();
				})
			</script>";

			}else
			{
				echo "<script>alert('ERROR AL INTENTAR ELIMINAR EL FARDO DE ESTE TRASLADO')</script>";
			}
			
		}
		echo "<script>$(document).ready(function(){
			$('#opb').val(0);
			$('#opcion').val(5);
			$('#form').submit();
		})</script>";
	}else if($opcion==7)
	{
		$_SESSION['traslado']='';
		echo "<script>alert('TRASLADO SE DEJO PENDIENTE CORRECTAMENTE');
		location.replace('pendiente_trasladob.php')</script>";
	}else if($opcion==8)
	{
		$doc=$_SESSION['traslado'];
		$usuario=strtoupper($_SESSION['usuario']);
		$conexion2->query("delete from traslado where usuario='$usuario' and sessiones='$doc' and estado='0'")or die($conexion2->error());
		$_SESSION['traslado']='';
		echo "<script>alert('TRASLADO CANCELADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('trasladob.php')</script>";

	}else if($opcion==9)
	{
		$doc=$_SESSION['traslado'];
		$usuario=strtoupper($_SESSION['usuario']);
		$cf=$conexion2->query("select origen,destino,sessiones,usuario,paquete,articulo,COUNT(articulo) cantidad from traslado where sessiones='$doc' and usuario='$usuario' and estado='0'
group by origen,destino,sessiones,usuario,paquete,articulo")or die($conexion2->error());
		$ncf=$cf->rowCount();
		if($ncf==0)
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN FARDO EN ESTE TRASLADO SIN FINALIZAR\\N VERIFICA SINO FUE FINALIZADO ANTES O NOTIFICA AL DEPARTAMENTO DE INFORMATICA y mostrales este # $doc\\nMENSAJE SOLO APARECERA UNA VEZ')</script>";
			$_SESSION['traslado']='';
			echo "<script>location.replace('trasladob.php')</script>";
		}else
		{
			//proceso de tranferir a exactus
			$qconsecutivo=$conexion1->query("select SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='traslado'")or die($conexion1->error());
			$fqconsecutivo=$qconsecutivo->FETCH(PDO::FETCH_ASSOC);
			$documento=$fqconsecutivo['SIGUIENTE_CONSEC'];
			$explode=explode('TRA-', $documento);
			//$num=$explode[1]+1;
			$num1=$explode[1]+1;
			//$num=str_pad($num,10,"0",STR_PAD_LEFT);
			$num1=str_pad($num1,10,"0",STR_PAD_LEFT);
			//$documento="TRA-$num";
			$queda="TRA-$num1";

			$k=0;
			while($k==0)
			{
				$qv=$conexion1->query("select * from consny.documento_inv where documento_inv='$documento'")or die($conexion1->error());
				$nqv=$qv->rowCount();
				if($nqv==0)
				{
					$k=1;
				}else
				{
					$explode=explode('TRA-', $documento);
					$num=$explode[1]+1;
					$num1=$explode[1]+2;
					$num=str_pad($num,10,"0",STR_PAD_LEFT);
					$num1=str_pad($num1,10,"0",STR_PAD_LEFT);
					$documento="TRA-$num";
					$queda="TRA-$num1";

					$k=0;
				}
			}
			$linea=0;
			while($fcf=$cf->FETCH(PDO::FETCH_ASSOC))
			{
				$linea++;
				$usuario=$fcf['usuario'];
				$paquete=$fcf['paquete'];
				$sessiones=$fcf['sessiones'];
				$origen=$fcf['origen'];
				$destino=strtoupper($fcf['destino']);
				$articulo=$fcf['articulo'];
				$cantidad=$fcf['cantidad'];
				if($linea==1)
				{
					$cb=$conexion1->query("select * from consny.bodega where bodega='$destino'")or die($conexion1->error());
					$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
					$tienda=$fcb['NOMBRE'];
					$referencia="$tienda(B.$sessiones), $comentario";
					$conexion1->query("insert into consny.documento_inv(paquete_inventario,
						documento_inv,
						consecutivo,
						referencia,
						FECHA_HOR_CREACION,
						FECHA_DOCUMENTO,
						SELECCIONADO,
						USUARIO,
						MENSAJE_SISTEMA,
						APROBADO,
						NoteExistsFlag) values('$paquete',
						'$documento',
						'TRASLADO',
						'$referencia',
						getdate(),
						'$fecha',
						'N',
						'$usuario',
						'',
						'N',
						'0')")or die($conexion1->error());
				}
				//insert linea_doc_inv
				$conexion1->query("insert into consny.linea_doc_inv (PAQUETE_INVENTARIO,
					DOCUMENTO_INV,
					LINEA_DOC_INV,
					AJUSTE_CONFIG,
					ARTICULO,
					BODEGA,
					TIPO,
					SUBTIPO,
					SUBSUBTIPO,
					CANTIDAD,
					COSTO_TOTAL_LOCAL,
					COSTO_TOTAL_DOLAR,
					PRECIO_TOTAL_LOCAL,
					PRECIO_TOTAL_DOLAR,
					BODEGA_DESTINO,
					NoteExistsFlag) values('$paquete',
					'$documento',
					'$linea',
					'~TT~',
					'$articulo',
					'$origen',
					'T',
					'D',
					'',
					'$cantidad',
					'1',
					'0',
					'0',
					'0',
					'$destino',
					'0')")or die($conexion1->error());
				//fin linea_doc_inv

			}

			$usuf=strtoupper($_SESSION['usuario']);
			$docf=$_SESSION['traslado'];
			$conexion2->query("update traslado set fecha='$fecha',observacion='$comentario',estado='1',documento_inv='$documento' where sessiones='$docf' and usuario='$usuf'")or die($conexion2->error());

			$conexion2->query("update registro set fecha_traslado='$fecha',bodega='$destino' where id_registro in(select registro from traslado where sessiones='$docf' and usuario='$usuf')")or die($conexion2->error());

			$conexion1->query("update consny.CONSECUTIVO_CI set SIGUIENTE_CONSEC='$queda' where consecutivo='traslado'")or die($conexion1->error());
			//$_SESSION['traslado']='';
			//INSERT AND TRANSACCION_SYS
			$usuf=strtoupper($_SESSION['usuario']);
			$docf=$_SESSION['traslado'];
			$ctf=$conexion2->query("select traslado.registro,traslado.articulo,(ISNULL(registro.lbs,0)+ISNULL(registro.peso,0)) peso,
				traslado.fecha,traslado.documento_inv,traslado.usuario,traslado.paquete,
				traslado.origen,traslado.destino from traslado inner join registro
				on registro.id_registro=traslado.registro where traslado.sessiones='$docf' and 
				traslado.usuario='$usuf' and traslado.estado='1'")or die($conexion2->error());
			while($fctf=$ctf->FETCH(PDO::FETCH_ASSOC))
			{
				$articulot=$fctf['articulo'];
				$registrot=$fctf['registro'];
				$origent=$fctf['origen'];
				$destinot=$fctf['destino'];
				$documento_invt=$fctf['documento_inv'];
				$registrot=$fctf['registro'];
				$peso=$fctf['peso'];
				$fechat=$fctf['fecha'];
				$usuariot=$fctf['usuario'];
				$paquetet=$fctf['paquete'];
				$peso1=$peso*-1;
				$conexion2->query("insert into transaccion_sys(registro,referencia,tipo_transaccion,articulo,cantidad,peso,fecha_documento,usuario,paquete,bodega,fecha_creacion,tabla,documento_inv) values('$registrot','INGRESO POR TRASLADO DE FARDOS','E','$articulot','1','$peso','$fechat','$usuariot','$paquetet','$destinot',getdate(),'traslado','$documento_invt')")or die($conexion2->error());

					$conexion2->query("insert into transaccion_sys(registro,referencia,tipo_transaccion,articulo,cantidad,peso,fecha_documento,usuario,paquete,bodega,fecha_creacion,tabla,documento_inv) values('$registrot','SALIDA POR TRASLADO DE FARDOS','S','$articulot','-1','$peso1','$fechat','$usuariot','$paquetet','$origent',getdate(),'traslado','$documento_invt')")or die($conexion2->error());
			}

			//FIN INSERT TRANSACCION_SYS
			$usuf=strtoupper($_SESSION['usuario']);
			$docf=$_SESSION['traslado'];
			echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";
			$_SESSION['traslado']='';
			echo "<script>location.replace('imprimir_traslado.php?doc=$docf&&u=$usuf')</script>";
			
			



		}
	}
}
?>
</body>
</html>