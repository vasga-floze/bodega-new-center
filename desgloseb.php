<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="moment.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#load").hide();
		})

		function buscar()
		{
			$("#op").val(1);
			$("#form1").submit();
		}

		function cerrar()
		{
			$("#conteart").hide();
		}
		function seleciona(e)
		{
			$("#articulo").val($("#arti"+e).val());
			$("#descripcion").val($("#desci"+e).val());
			$("#conteart").hide();
			$("#cantidad").focus();
		}

		function buscar1()
		{
			$("#op").val(2);
			$("#form1").submit();
		}

		function final()
		{
			$("#op").val('4');
		}
	</script>
</head>
<body>
<div style="width: 100%; height: 100%; position: fixed; background-color: white;" id="load">
	<img src="loadf.gif" style="margin-top: 10%; margin-left: 40%;" >
</div>
<?php
include("conexion.php");
error_reporting(0);
if($_SESSION['desglose']!='')
{
	$barras=$_SESSION['desglose'];
	$cb=$conexion2->query("select concat(eximp600.consny.articulo.articulo,': ',eximp600.consny.articulo.descripcion) as articulo from registro inner join eximp600.consny.articulo on registro.codigo=eximp600.consny.articulo.articulo where registro.barra='$barras'")or die($conexion2->error());
	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
	$articulos=$fcb['articulo'];
	echo "<script>$(document).ready(function(){
		$('#desglose').show();
		$('#articulo').focus();

	})</script>";
}else
{
	$barras='';
	$articulos='';
	echo "<script>$(document).ready(function(){
		$('#desglose').hide();

	})</script>";
}
extract($_REQUEST);
if($_POST and $op==1)
	{
		//mostrar 
		$barras=$_SESSION['desglose'];
		$ca=$conexion2->query("select eximp600.consny.articulo.clasificacion_2 from eximp600.consny.articulo inner join registro on registro.codigo=eximp600.consny.articulo.articulo where registro.barra='$barras'")or die($conexion2->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$clasificacion=$fca['clasificacion_2'];
		//echo $clasificacion;

		$cart=$conexion1->query("select articulo,descripcion from consny.articulo where clasificacion_1='DETALLE' AND activo='S' and clasificacion_2='$clasificacion' order by descripcion")or die($conexion1-error());

		echo "<div style='background-color:white; border:double; border-color:blue; position:fixed; overflow:auto; width: 90%; height:95%; margin-top:-3.5%; margin-left:1%;' id='conteart'>

		<button style='background-color:red; color:white; position:sticky; top:0; right: 0; float:right; cursor:pointer;' onclick='cerrar()'>X</button> <br>
		<br>

		<table border='1' cellspadding='10' style='border-collapse:collapse; border-color: black; width: 98%;'>
		<tr>
		<td width='20%'>ARTICULO</td>
		<td>DESCRIPCION</td>
		</tr>";
		$n=0;
		while($fcart=$cart->FETCH(PDO::FETCH_ASSOC))
		{
			$art1=$fcart['articulo'];
			$desc1=$fcart['descripcion'];
			echo "<input type='hidden' name='arti$n' id='arti$n' value='$art1'>";
			echo "<input type='hidden' name='desci$n' id='desci$n' value='$desc1'>";
			echo "<tr class='tre' onclick='seleciona($n)' style='cursor:pointer;'>
					<td width='20%'>$art1</td>
					<td>$desc1</td>
				</tr>";
				$n++;
		}
		echo "</table></div>";


	}
?>
<form name="from" id="form" method="POST" autocomplete="off">
	<input type="text" name="barra" class="text" style="width: 30%;" placeholder="CODIGO DE BARRA">
	<input type="submit" name="btn" id="btn" value="SIGUIENTE" class="boton3"></form><br><br>
	<div id='desglose' style="margin-left: 2.3%;">
	<hr>
	<center>
	<h2 style="text-decoration: underline; margin-left:">DESGLOSE DE ARTICULO:<?php echo $articulos?> || CODIGO DE BARRA: <?php echo $barras;?></h2></center>
	<hr><br>
	<form method="POST" id="form1" name="form1" autocomplete="off" style="width: 100%;">
		<label class="boton2" onclick="buscar()">DESGLOSE</label><br>
		<input type="text" name="art" class="text" style="width: 15%;" placeholder="ARTICULO" id="articulo" onchange="buscar1()">
		<input type="text" name="desc" id="descripcion" class="text" style="width: 50%;" placeholder="DESCRIPCION">
	

	
		<input type="number" name="cantidad" id="cantidad" min="1" class="text" style="width: 10%;" placeholder="CANTIDAD" required>

		<input type="hidden" name="op" id="op" readonly>
		<input type="hidden" name="id" id="id" readonly>
		<input type="submit" name="btn" value="AGREGAR" onclick="enviar()" class="boton3">
	</div>
</form>
<?php
$barras=$_SESSION['desglose'];
$cd=$conexion2->query("select eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion ,desglose.cantidad,desglose.id from desglose inner join eximp600.consny.articulo on desglose.articulo=eximp600.consny.articulo.articulo where desglose.registro in(select id_registro from registro where barra='$barras') ORDER BY desglose.id desc")or die($conexion2->error());
$ncd=$cd->rowCount();
if($ncd!=0)
{
	$barras=$_SESSION['desglose'];
	$hoy=date("Y-m-d");
	$cf=$conexion2->query("select convert(date,DATEADD(DAY,-5,'$hoy')) menos8,fecha_traslado from registro where barra='$barras'")or die($conexion2->error());
	$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
	if($fcf['menos8']>$fcf['fecha_traslado'])
	{
		$fecha_menor=$fcf['menos8'];
	}else
	{
		$fecha_menor=$fcf['fecha_traslado'];
	}
	//echo "<script>alert('$fecha_menor')</script>";
	echo "<table border='1' cellspadding='8' style='border-collapse: collapse; width: 90%; margin-top:2%;'>";

	echo "<tr>
		<td colspan='4'>
		<form name='form2' id='form2' method='POST'>
		<input type='text' name='desglosado_por' placeholder='DESGLOSADO_POR' class='text' style='width:30%;' required>
		<input type='text' name='digitado' placeholder='DIGITADO POR' class='text' style='width:30%;' required>
		<input type='date' name='fecha' id='fecha' min='$fecha_menor' max='$hoy' value='$hoy' class='text' style='width:15%;' required>
		<input type='submit' name='btn' value='FINALIZAR' class='btnfinal' style='padding:0.5%; margin-bottom:1%;'>
		</form>
		</td>
	</tr>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>OPCION</td>
	</tr>";
	$total=0;
	while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
	{
		$idg=$fcd['id'];
		echo "<tr>
		<td>".$fcd['articulo']."</td>
		<td>".$fcd['descripcion']."</td>
		<td>".$fcd['cantidad']."</td>
		<td><button class='boton3' style='background-color:red; color: white;'onclick=\"javascript:
	 						if (confirm('SEGURO DESEA ELIMINAR ESTE ARTICULO DEL DESGLOSE'))
	 						{
								$('#id').val('$idg');
								$('#op').val('3');
								$('#form1').submit();
	 						}else
	 						{
	 							//alert('nel'); 
	 						}
				 \">ELIMINAR</button></td>
	</tr>";
	$total=$total + $fcd['cantidad'];
	}
	echo "<tr><td colspan='2'>TOTAL</td>
	<td>$total</td><td></td>
	</tr></table>";
}
?>

<?php
if($_POST)
{
	extract($_REQUEST);
	//echo "<script>alert('$op - $btn')</script>";
	if($btn=='SIGUIENTE' and $barra!='')
	{
		$bodega=$_SESSION['bodega'];
		$c=$conexion2->query("select * from registro where (fecha_desglose is null or fecha_desglose='') and bodega='$bodega' and barra='$barra' and activo is null AND id_registro not in(select registro from traslado where estado=0)")or die($conexion2->error());
		$n=$c->rowCount();
		//echo "<script>alert('$barra $n')</script>";
		if($n==0)
		{
			echo "<script>alert('FARDO NO PUEDE SER DESGLOSADO POR:\\n - CODIGO DE BARRA MAL DIGITADO\\n - NO SE ENCUENTRA EN TU BODEGA\\n - NO ESTA DISPONIBLE\\n - YA FUE DESGLOSADO\\n - ESTA SIENDO UTILIZADO EN UN TRASLADO\\n NOTA: INFORMA AL ENCARGADO DE BODEGA')</script>";
			echo "<script>location.replace('desgloseb.php?p=1')</script>";

		}else
		{
			
			$_SESSION['desglose']=$barra;
			echo "<script>location.replace('desgloseb.php?u=1')</script>";
		}
	}else if($btn=='AGREGAR')
	{
		
		$cp=$conexion1->query("select convert(decimal(10,2),precio_regular) as total from consny.articulo where articulo='$art' and clasificacion_1='detalle'")or die($conexion1->error());
		$ncp=$cp->rowCount();
		if($ncp==0)
		{
			echo "<script>alert('SE PRODUJO UN ERROR Y NO SE PUDO AGREGAR AL DESGLOSE EL ARTICULO: $art INFORMA AL ENCARGADO DE BODEGA')</script>";
			echo "<script>location.replace('desgloseb.php?t=1')</script>";
		}else
		{
			$fcp=$cp->FETCH(PDO::FETCH_ASSOC);
			$precio=$fcp['total'];
			$user=$_SESSION['usuario'];
			$paq=$_SESSION['paquete'];
			$barras=$_SESSION['desglose'];
			$cr=$conexion2->query("select * from registro where barra='$barras'")or die($conexion2->error());
			$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
			$idr=$fcr['id_registro'];
		$conexion2->query("insert into desglose(registro,articulo,cantidad,paquete,usuario,fecha,precio) values('$idr','$art','$cantidad','$paq','$user',getdate(),'$precio')")or die($conexion2->error());
		//echo "<script>alert('$art - $desc -$cantidad - $precio')</script>";
		echo "<script>location.replace('desgloseb.php?o=0')</script>";

		}
	}else if($btn=='FINALIZAR')
	{
		$barras=$_SESSION['desglose'];
		$bodega=$_SESSION['bodega'];
		//echo "<script>alert('$barrasv <--')</script>";
		
		$cv=$conexion2->query("select codigo,bodega,barra from registro where (fecha_desglose is null or fecha_desglose='') and bodega='$bodega' and activo is null  and id_registro not in(select registro from traslado where estado='0') and barra='$barras'")or die($conexion2->error());
		$ncv=$cv->rowCount();
		//echo "<script>alert('$ncv')</script>";
		if($ncv==0)
		{
	echo "<script>alert('Error: PUEDE SER:\\n -- YA ESTA FINALIZADO\\n -- NO SE ENCUENTRA EN TU BODEGA \\n -- ESTA SIENDO UTILIZADO EN UN TRASLADO SIN FINALIZAR\\n -- NO SE HA AGREGADO NINGUN ARTICULO EN EL DESGLOSE\\n NOTA: INFORMA AL ENCARGADO DE BODEGA')</script>";

		}else
		{	
		$fcv=$cv->FETCH(PDO::FETCH_ASSOC);
		$cod=$fcv['codigo'];
		$barra=$fcv['barra'];
		$bod=$fcv['bodega'];
		//Echo "<script>alert('$cod -- $barra')</script>";
		$carti=$conexion1->query("select articulo,descripcion from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fcaeti=$carti->FETCH(PDO::FETCH_ASSOC);
		$articulo=$fcaeti['articulo'];
		$descripcion=$fcaeti['descripcion'];
		$tc="CONSUMO POR DESGLOSE FARDO: $articulo: $descripcion CODIGO DE BARRA: $barra";
		$ti="INGRESO POR DESGLOSE FARDO: $articulo: $descripcion CODIGO DE BARRA: $barra";
		//Echo "<script>alert('$tc -- $ti')</script>";
		$paquete=$_SESSION['paquete'];
		$usuario=$_SESSION['usuario'];

		$cvl=$conexion1->query("select * from consny.documento_inv where referencia like '%$barra%'")or die($conexion1->error());
		$nvl=$cvl->rowCount();
		if($nvl==0)
		{
			//consecutivo consumo
			$qc=$conexion1->query("select SIGUIENTE_CONSEC as consecutivo from consny.CONSECUTIVO_CI where consecutivo='consumo'")or die($conexion1->error());
			$fqc=$qc->FETCH(PDO::FETCH_ASSOC);
			$consumo=$fqc['consecutivo'];
			$conse=explode("CON-", $consumo);
			$num_consumo=$conse[1]+1;
			$num_consumo=str_pad($num_consumo,10,"0",STR_PAD_LEFT);
			$queda_consumo="CON-$num_consumo";
			$k=0;
			while($k==0)
			{
				$qvl=$conexion1->query("select * from consny.documento_inv where documento_inv='$consumo'")or die($conexion1->error());
				$nqvl=$qvl->rowcount();
				if($nqvl==0)
				{
					$k=1;
				}else
				{
					$consumo=$queda_consumo;
					$conse=explode("CON-", $queda_consumo);
					$num_consumo=$conse[1]+1;
					$queda_consumo=str_pad($num_consumo,10,"0",STR_PAD_LEFT);
					$queda_consumo="CON-$queda_consumo";
					$k=0;

				}
			}
			//echo "<script>alert('$consumo -- $queda_consumo')</script>";
			//fin consecutivo consumo

			//consecutivo ing
			$cing=$conexion1->query("select siguiente_consec consecutivo from consny.CONSECUTIVO_CI where consecutivo='ing' ")or die($conexion1->error());
			$fcing=$cing->FETCH(PDO::FETCH_ASSOC);
			$ing=$fcing['consecutivo'];
			$conse_ing=explode('ING-',$ing);
			$queda_ing=$conse_ing[1]+1;
			$queda_ing=str_pad($queda_ing,10,"0",STR_PAD_LEFT);
			$queda_ing="ING-$queda_ing";
			$i=0;
			while($i==0)
			{
				$cvi=$conexion1->query("select * from consny.documento_inv where documento_inv='$ing'")or die($conexion1->error());
				$ncvi=$cvi->rowCount();
				if($ncvi==0)
				{
					$i=1;
				}else
				{
					$ing=$queda_ing;
					$conse_ing=explode('ING-',$ing);
					$queda_ing=$conse_ing[1]+1;
					$queda_ing=str_pad($queda_ing,10,"0",STR_PAD_LEFT);
					$queda_ing="ING-$queda_ing";
					$i=0;
				}
			}
			//echo "<script>alert('$ing -- $queda_ing')</script>";
			//fin consecutivo ing
			$paquete=$_SESSION['paquete'];
			$usuario=$_SESSION['usuario'];

			$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag) values('$paquete','$consumo','CONSUMO','$tc',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error());

			$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag) values('$paquete','$ing','ING','$ti',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error());
			$barras=$_SESSION['desglose'];
			$cf=$conexion2->query("select * from registro where barra='$barras'")or die($conexion2->error());
			$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
			$articulo=$fcf['codigo'];
			$bod=$fcf['bodega'];

			//linea_doc consumo
			$conexion1->query("insert into consny.linea_doc_inv(PAQUETE_INVENTARIO,documento_inv,linea_doc_inv,ajuste_config,articulo,bodega,tipo,subtipo,subsubtipo,cantidad,costo_total_local,costo_total_dolar,precio_total_local,precio_total_dolar,NoteExistsFlag) values('$paquete','$consumo','1','~CC~','$articulo','$bod','C','D','N','1','1','1','0','0','0')")or die($conexion1->error());

			//fin linea_doc consumo

			//linea_doc ing
			$desglose=$conexion2->query("select articulo,cantidad from desglose where registro in(select id_registro from registro where barra='$barras')")or die($conexion2->error());
			$num=0;
			while($fdesglose=$desglose->FETCH(PDO::FETCH_ASSOC))
			{
				$num++;
				$articulo=$fdesglose['articulo'];
				$cantidad=$fdesglose['cantidad'];
				$conexion1->query("insert into consny.linea_doc_inv(PAQUETE_INVENTARIO,documento_inv,linea_doc_inv,ajuste_config,articulo,bodega,tipo,subtipo,subsubtipo,cantidad,costo_total_local,costo_total_dolar,precio_total_local,precio_total_dolar,NoteExistsFlag) values('$paquete','$ing','$num','~OO~','$articulo','$bod','O','D','L','$cantidad','1','1','0','0','0')")or die($conexion1->error());
			}
			$barras=$_SESSION['desglose'];
			$conexion2->query("update registro set fecha_desglose='$fecha',desglosado_por='$desglosado_por',digita_desglose='$digitado',documento_inv_consumo='$consumo',documento_inv_ing='$ing' where barra='$barras'")or die($conexion2->error());

			$conexion1->query("update consny.CONSECUTIVO_CI set siguiente_consec='$queda_consumo' where consecutivo='consumo';
				update consny.CONSECUTIVO_CI set siguiente_consec='$queda_ing' where consecutivo='ing'")or die($conexion1->error());
			//mail
			$crm=$conexion2->query("select concat(EXIMP600.consny.ARTICULO.ARTICULO,':',EXIMP600.consny.ARTICULO.DESCRIPCION) as articulo,
			registro.bodega,(isnull(registro.lbs,0)+isnull(registro.peso,0)) as peso,registro.barra,registro.fecha_desglose from registro inner join
			EXIMP600.consny.ARTICULO on registro.codigo=EXIMP600.consny.ARTICULO.ARTICULO where registro.barra='$barras'
")or die($conexion2->error());
			$fcrm=$crm->FETCH(PDO::FETCH_ASSOC);
			$tabla="<table border='1' cellspadding='8' style='border-collapse:collapse;'>";
			$tabla.="<tr>
			<td colspan='4'>
			CODIGO DE BARRA: ".$fcrm['barra']."<br>
			ARTICULO: ".$fcrm['articulo']."<br>
			PESO: ".$fcrm['peso']."<br>
			FECHA DESGLOSE: ".$fcrm['fecha_desglose']."
			</td>
			</tr>";

			$tabla.="<tr>
				<td colspan='4'>DETALLE DEL DESGLOSE</td>
			</tr>";

			$tabla.="<tr>
			<td>ARTICULO</td>
			<td>CANTIDAD</td>
			<td>PRECIO</td>
			<TD>TOTAL</TD>
			</tr>";

			$cdm=$conexion2->query("select CONCAT(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION) as articulo,desglose.cantidad,
desglose.precio,(desglose.cantidad*desglose.precio) as total from desglose inner join EXIMP600.consny.ARTICULO on desglose.articulo=EXIMP600.consny.ARTICULO.ARTICULO
 where desglose.registro in(select id_registro from registro where barra='$barras')")or die("")or die($conexio2->error());
			$t=0;
			$tcant=0;
			while($fcdm=$cdm->FETCH(PDO::FETCH_ASSOC))
			{
				$tabla.="<tr>
				<td>".$fcdm['articulo']."</td>
				<td>".$fcdm['cantidad']."</td>
				<td>".$fcdm['precio']."</td>
				<td>".$fcdm['total']."</td>
				</tr>";
				$t=$t + $fcdm['total'];
				$tcant=$tcant+$fcdm['cantidad'];
				$bodegac=$fcdm['bodega'];
			}
			$tabla.="<tr>
			<td>TOTAL</td>
			<td>$tcant</td>
			<td></td>
			<td>$t</td>
			</tr></table>";
			$usuarioc=$_SESSION['usuario'];
$qcorreo=$conexion1->query("select * from usuariobodega where usuario='$usuarioc'")or die($conexion1->error());
$fqcorreo=$qcorreo->FETCH(PDO::FETCH_ASSOC);
$correo=$fqcorreo['CORREOTIENDA'];
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

//dirección del remitente
$headers .= "From: $usuarioc <$correo@newyorkcentersadcv.com>\r\n";


//ruta del mensaje desde origen a destino
$headers .= "Return-path: $correo@newyorkcentersadcv.com\r\n";
	$bodegac=$_SESSION['bodega'];
$asunto="*SISTEMA* DESGLOSE $bodegac BARRA: $barras";
if(mail("jlainez@newyorkcentersadcv.com,ocampos@newyorkcentersadcv.com,$correo@newyorkcentersadcv.com,gjurado@newyorkcentersadcv.com", $asunto, $tabla,$headers))
{
	echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
}else
{
	echo "<script>alert('GUARDADO CORRECTAMENTE, PERO NO SE PUDO ENVIAR EL CORREO INFORMA A OSMIN CAMPOS')</script>";
}


			//fin mail

			$_SESSION['desglose']='';
			//echo "<script>alert('FINALIZADO CORRECTAMENTE..')</script>";

			echo "<script>location.replace('desgloseb.php?i=0')</script>";
			//fin linea_doc ing










		}else
		{
			echo "<script>alert('AL PARECER ESTE FARDO YA FUE DESGLOSADO INFORMA A OSMIN PARA QUE VERIFIQUE LA INFORMACION')</script>";
			$_SESSION['desglose']='';
			echo "<script>location.replace('desgloseb.php?t=0')</script>";
		}

		

		

		}
	}

	if($op==2)
	{
			$barras=$_SESSION['desglose'];
		$ca=$conexion2->query("select eximp600.consny.articulo.clasificacion_2 from eximp600.consny.articulo inner join registro on registro.codigo=eximp600.consny.articulo.articulo where registro.barra='$barras'")or die($conexion2->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$clasificacion=$fca['clasificacion_2'];

		$cart=$conexion1->query("select articulo,descripcion from consny.articulo where articulo='$art' and clasificacion_2='$clasificacion' and clasificacion_1='detalle'")or die($conexion1->error());
		$ncart=$cart->rowCount();
		if($ncart==0 and $art!='0621')
		{
			echo "<script>alert('$art NO DISPONIBLE O NO SE ENCUENTRA EN LA CLASIFICACION: $clasificacion')</script>";
		}else
			{
				if($art=='0621')
				{
					$cart=$conexion1->query("select articulo,descripcion from consny.articulo where articulo='$art'")or die($conexion1->error());
				}
		$fcart=$cart->FETCH(PDO::FETCH_ASSOC);
		$arti1=$fcart['articulo'];
		$desc1=$fcart['descripcion'];
		echo "<script>
			$(document).ready(function(){
				$('#articulo').val('$arti1');
				$('#descripcion').val('$desc1');
				$('#cantidad').focus();
				})
		</script>";
		}

	}else if($op==3)
	{
		$barras=$_SESSION['desglose'];
		$conexion2->query("delete from desglose where id='$id' and registro in(select id_registro from registro where barra='$barras')")or die($conexion2->error());
		echo "<script>location.replace('desgloseb.php?u=1')</script>";
	}
}
?>
</body>
</html>