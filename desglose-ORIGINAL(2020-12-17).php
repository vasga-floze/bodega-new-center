<!DOCTYPE html>
<html>
<head>
	<?php
		include("conexion.php");
		error_reporting(0);
		if($_SESSION['tipo']==3)
	{
		echo "<script>location.replace('consultar.php')</script>";
	}
		$hoy=date("Y-m-d");
		//session_start();
		$paquete=$_SESSION['paquete'];
		$usu=$_SESSION['usuario'];
		$registro=$_SESSION['registro'];
		//echo "<script>alert('$registro <-')</script>";
		$cr=$conexion2->query("select * from dbo.registro where id_registro='$registro'")or die($conexion2->error);
		$fr=$cr->FETCH(PDO:: FETCH_ASSOC);
		if($registro !="")
		{
			$i=1;
		}
		$ii=$_GET['ii'];
		$b=$_GET['b'];	
		$cod=$fr['codigo'];
		$nom=$fr['subcategoria'];
		$num=$fr['numero_fardo'];
		$lbs=$fr['lbs'];
		$und=$fr['und'];
		$emp=$fr['empacado'];
		$pro=$fr['producido'];
		$dig=$fr['digitado'];
		$fechap=$fr['fecha_documento'];
		$categoria=$fr['categoria'];
		$bodegas=$fr['bodega'];
		$art=$_GET['art'];
		$arti=$_GET['arte'];
		$canti=$_GET['canti'];
		$consulta=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fila=$consulta->FETCH(PDO::FETCH_ASSOC);
		$categoria_barra=$fila['CLASIFICACION_2'];
		$cve=$conexion2->query("select * from desglose where registro='$registro' and articulo='$arti'")or die($conexion2->error);
		$ncve=$cve->rowCOUNT();
		if($ncve==0)
		{

		}else
		{
			$fcve=$cve->FETCH(PDO::FETCH_ASSOC);
			$cantidad=$fcve['cantidad'];
			$tcan=$cantidad + $canti;
			$conexion2->query("update desglose set cantidad='$tcan' where registro='$registro' and articulo='$arti'")or die($conexion2->error);
			echo "<script>location.replace('desglose.php?b= ')</script>";

		}
		

		$q=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error);
		$fq=$q->FETCH(PDO::FETCH_ASSOC);
		$artd=$fq['ARTICULO'];
		$descd=$fq['DESCRIPCION'];
	?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="moment.min.js"></script>
	<script>
		$(document).ready(function(){

			$(".detalle").hide();
			$("#info").hide();
			$("#muestra").hide();
			if($("#i").val()==1)
			{
				
				$("#info").show();
				$("#muestra").show();
			}

			if($("#ii").val()==2)
			{
				
				$(".detalle").show();
			}
		});
		function activar()
		{
			location.replace("artdesglose.php");
		}
		function cerrar()
		{
			$(".detalle").hide();
		}
		function enviar()
		{
			document.form.submit();
		}
		function cambiar()
		{
			$("#cod").prop("required",true);
			$("#nom").prop("required",true);
			$("#cant").prop("required",true);
			$("#op").val("2");
		}
		function valor()
		{
			
			$("#op").val("1");
		}
		function ver()
	{
		var fecha1 = moment($("#ac").val());
		var fecha2 = moment($("#hc").val());
		var dia =fecha2.diff(fecha1, 'days');
		

		if(dia<=-7 || dia>0)
		{
			alert('FECHA NO VALIDA');
			$("#hc").val('');
			return false;
		}else
		{
			
			
		}
	}

	function ver1()
	{
		if(confirm('SEGURO DESEA FINALIZAR EL DESGLOSE'))
		{
			$("#kp").val('1');
		}else
		{
			$("#kp").val('2');
			location.replace('desglose.php');
		}
	}
	</script>
</head>
<center>
<body>
	<h3 style="text-decoration: underline;">DESGLOSE CODIGOS DE BARRA</h3>
	<input type="hidden" name="ac" id="ac" value='<?php echo "$hoy";?>' readonly>
	<input type="hidden" name="i" id="i" value='<?php echo $i;?>'>
	<input type="hidden" name="ii" id="ii" value='<?php echo $ii;?>'>
<form method="POST">
	
	<input type="text" name="barra" class="text" style="width: 30%;" placeholder="CODIGO DE BARRA">
	<input type="submit" name="btn" value="SIGUIENTE" class="boton2" style="width: 18%;">
	
</form>
<table class="tabla" border="0" cellpadding="10" id='info'>

	<td colspan="3">
		<button onclick="activar()" class="boton4">DESGLOSE</button>
		<form method="POST" name="form">
			<input type="text" name="cod" id="cod" class="text" placeholder="CODIGO" style="width: 15%;" onchange="enviar()" onkeypress="valor()" value='<?php echo $artd;?>'>
			<input type="text" name="nom" id="nom" class="text" placeholder="NOMBRE" style="width: 50%;" value='<?php echo $descd;?>'>
			<input type="number" name="cant" id="cant" class="text" placeholder="cantidad" style="width: 10%;">
			<input type="hidden" name="op" id="op" style="width: 2%;">
			<input type="submit" name="btn" value="Add." onclick="cambiar()" class="boton2">
			
		</form>
	</td>
	<tr>
	
</table>

</body>
</html>
<?php
extract($_REQUEST);
if($_POST)
{
	
	if($btn=="SIGUIENTE")
	{
		$idreg=$_SESSION['registro'];
			$kk=$conexion2->query("select mesa.mesa,detalle_mesa.registro,mesa.estado from mesa inner join detalle_mesa on mesa.id=detalle_mesa.mesa  and mesa.estado='1' or estado='T' where detalle_mesa.registro='$idreg'")or die($conexion2->error());
			$nkk=$kk->rowCOUNT();
			//echo "<script>alert('$nkk')</script>";

		if($nkk !=0)
		{
			echo "<script>alert('FARDO NO DISPONIBLE')</script>";
			$_SESSION['registro']='';
			echo "<script>location.replace('desglose.php?o=9')</script>";
		}else
		
		//echo "<script>alert('aki')</script>";
	$c=$conexion2->query("select * from dbo.registro where barra='$barra'")or die($conexion2->error);
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO DE: $barra')</script>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$_SESSION['registro']=$f['id_registro'];
		$vfecha=$f['fecha_desglose'];
		$vdigita=$f['digita_desglose'];
		$vcodigo=$f['codigo'];
		$idre=$f['id_registro'];
		$bodegav=$f['bodega'];
		$bodega1v=$_SESSION['bodega'];
		$activo=$f['activo'];
		if($activo=='0')
		{
			echo "<script>alert('FARDO NO SE ENCUENTRA DISPONIBLE')</script>";
			$_SESSION['registro']='';
			echo "<script>location.replace('desglose.php')</script>";
		}

		if($bodegav !=$bodega1v)
		{
			echo "<script>alert('NO PUEDES DESGLOSAR ESTE FARDO NO HA SIDO ASIGNADO A TU BODEGA')</script>";
			$_SESSION['registro']='';
			echo "<script>location.replace('desglose.php')</script>";
		}else
		{
		if($vfecha!='' and $vdigita!="")
		{
			echo "<script>alert('FARDO: $vcodigo YA FUE DESGLOSADO EN LA FECHA: $vfecha\\nDIGITADO POR: $vdigita')</script>";
			echo "<script>location.replace('desglose.php')</script>";
			$_SESSION['registro']="";
		}
		echo "<script>location.replace('desglose.php?i=1')</script>";


	}
}
}


if($op==1)
{
	if($categoria=="")
{
	$ck=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error());
	$fck=$ck->FETCH(PDO::FETCH_ASSOC);
	$categoria=$fck['CLASIFICACION_2'];
}
	//echo "<script>alert('$categoria - $cod')</script>";
	$con=$conexion1->query("select * from consny.ARTICULO where clasificacion_2='$categoria_barra' and consny.ARTICULO.clasificacion_1='DETALLE' AND consny.ARTICULO.ARTICULO='$cod'")or die($conexion1->error);
	$ncon=$con->rowCOUNT();
	if($ncon==0)
	{
		if($cod=='0621')
		{
			echo "<script>location.replace('desglose.php?i=1&&b= &&art=$cod')</script>";
		}else
		{
			echo "<script>alert('NO SE ENCONTRO REGISTRO DE $cod CON CLASIFICACION DE $categoria_barra')</script>";
		echo "<script>location.replace('desglose.php?i=1&&b= ')</script>";
		}
		
	}else
	{
		$fcon=$con->FETCH(PDO::FETCH_ASSOC);
		$arti=$fcon['ARTICULO'];
		echo "<script>location.replace('desglose.php?i=1&&b= &&art=$arti')</script>";
	}

}else if($op==2)
{
	if($categoria=="")
{
	$ck=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error());
	$fck=$ck->FETCH(PDO::FETCH_ASSOC);
	$categoria=$fck['CLASIFICACION_2'];
}
//if($_SESSION['usuario']=='sonsonate4')
//{
	//echo "<script>alert('$bodegas - $categoria - $cod - $categoria_barra ')</script>";
//}
	

	//verificar si esta activo bodega sm02 y clsificacion_2
//echo "<script>alert('$categoria - $cod - $bodegas')</script>";
	$query=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO and consny.ARTICULO.CLASIFICACION_1='DETALLE' AND consny.ARTICULO.CLASIFICACION_2='$categoria_barra' AND consny.ARTICULO.ACTIVO='S' AND consny.EXISTENCIA_BODEGA.BODEGA='$bodegas' WHERE consny.ARTICULO.ARTICULO='$cod'")or die($conexion1->error);
	$nquery=$query->rowCOUNT();
	if($nquery==0 and $cod!='0621')
	{
		echo "<script>alert('ARTICULO $cod NO SE ENCUENTRA ACTIVO O NO ESTA EN LA CLASIFICACION DE $categoria_barra')</script>";
	}else
	{
		$k=$conexion2->query("select * from dbo.desglose where registro='$registro' and articulo='$cod'")or die($conexion2->error);
		$nk=$k->rowCOUNT();
		//echo "<script>alert('$nkk - $nk')</script>";
		if($nk==0)
		{
			
			
			if($nkk!=0)
			{
				echo "<script>alert('REGISTRO YA NO ESTA DISPONIBLE')</script>";
				echo "<script>location.replace('desglose.php')</script>";
			}else
			{
				$cona=$conexion1->query("select * from consny.ARTICULO where consny.ARTICULO.ARTICULO='$cod'")or die($conexion1->error());
				$fcona=$cona->FETCH(PDO::FETCH_ASSOC);
				$precio=$fcona['PRECIO_REGULAR'];
				if($precio=='')
				{
					$precio='0';
				}
				//echo "<script>alert('$precio f')</script>";
			$conexion2->query("insert into dbo.desglose(registro,articulo,cantidad,paquete,usuario,precio,fecha) values('$registro','$cod','$cant','$paquete','$usu','$precio',getdate())")or die($conexion2->error);
			}
		}else
		{
			/*echo"<script>
				if(confirm('EL ARTICULO $cod YA FUE AGREGADO ANTES DESEA SUMARLE $cant UNIDADES MAS'))
				{
				 location.replace('desglose.php?b &&arte=$cod&&canti=$cant');
				}
			</script>";*/
			$cona=$conexion1->query("select * from consny.ARTICULO where consny.ARTICULO.ARTICULO='$cod'")or die($conexion1->error());
				$fcona=$cona->FETCH(PDO::FETCH_ASSOC);
				$precio=$fcona['PRECIO_REGULAR'];
				if($precio=='')
				{
					$precio='0';
				}
			$conexion2->query("insert into dbo.desglose(registro,articulo,cantidad,paquete,usuario,precio,fecha) values('$registro','$cod','$cant','$paquete','$usu','$precio',getdate())")or die($conexion2->error);

		}
		
		echo "<script>location.replace('desglose.php?b= ')</script>";
	}
}

}
?>
<hr>
<div id="muestra">
<div class="desglose">
<?php
$idre=$_SESSION['registro'];
$co=$conexion2->query("select * from registro where id_registro='$idre'")or die($conexion2->error());
$fco=$co->FETCH(PDO::FETCH_ASSOC);
$subcategoria1=$fco['subcategoria'];
$arti1=$fco['codigo'];	
$barra=$fco['barra'];
$que=$conexion1->query("select * from consny.ARTICULO WHERE ARTICULO='$arti1'")or die($conexion1->error());
$fque=$que->FETCH(PDO::FETCH_ASSOC);
$de=$fque['DESCRIPCION'];

echo "<h3>$arti1: $de, CODIGO BARRA: $barra</h3>";



?>

	<table border="1" class="tabla" cellpadding="10" style="text-align: center;">
		<tr style='color:white; background-color: rgb(133,133,133,0.8)'>
			<td colspan="4">ARTICULOS AGREGADOS</td>
		</tr>
		<tr>
			<td>CODIGO</td>
			<td>NOMBRE</td>
			<td>CANTIDAD</td>
			<td>QUITAR</td>
		</tr>
	<?php
	$cd=$conexion2->query("select * from dbo.desglose where registro='$registro'")or die($conexion2->error);
	$ncd=$cd->rowCOUNT();
	if($ncd==0)
	{
		echo "<tr>
		<td colspan='4'>NO SE HAN AGREGADOS ARTICULOS AL DESGLOSE AUN</td>
		</tr>";
	}else
	{
		$total=0;
		while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
		{
			$a=$fcd['articulo'];
			$c=$fcd['cantidad'];
			$id=$fcd['id'];
			$cp=$conexion1->query("select * from consny.articulo where ARTICULO='$a'")or die($conexion1->error);
			$fcp=$cp->FETCH(PDO::FETCH_ASSOC);
			$ar=$fcp['ARTICULO'];
			$de=$fcp['DESCRIPCION'];
			echo "<tr>
					<td>$ar</td>
					<td>$de</td>
					<td>$c</td>
					<td><a href='quitad.php?cod=$id' style='text-decoration:none; color:black;' '>quitar</a></td>
				 </tr>";
				 $total=$total + $c;
		}

		echo "<tr style='color:white; background-color: rgb(133,133,133,0.8)'>
			<td colspan='2'>TOTAL ARTICULOS</td>
			<td>$total</td><td></td>
		</tr>";
	}
	$hoy=date("Y-m-d");
	if($ncd!=0)
	{
	?>
<tr>
	<td colspan="4">
		<form method="POST" name="formf">
		DESGLOSADO POR: <input type="text" name="desglosado" required>
	DESGLOSE DIGITADO POR: <input type="text" name="dig" required id="dig">
	FECHA DE DESGLOSE: <input type="date" onchange="ver()" name="fecha" value='<?php echo $hoy; ?>' id='hc' required>
	<input type="hidden" name="kp" id="kp">
	<input type="submit" name="btn" class="btnfinal" value="Finalizar" style="padding-top: 0.5%; padding-bottom: 0.5%; margin-bottom: 0%;" onclick="ver1()">
	</form>
	<?php
}
	?>
	
</td>
</tr>
	</table>

</div>



<?php
//si acasso quitar del if lo de session
if($_POST)
{
	extract($_REQUEST);
	if($btn=="Finalizar" and $kp==1 and $_SESSION['registro']!='')
	{
	



		$idr=$_SESSION['registro'];
		$cc=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error);
		$ncc=$cc->rowCount();
		
		if($ncc==0 or $idr=="")
		{
			echo "<script>alert('SE PRODUJO UN ERROR INTENTELO NUEVAMENTE')</script>";
			echo "<script>location.replace('desglose.php')</script>";
		}
		

$kconsumo=$conexion1->query("SELECT CONSECUTIVO,SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='CONSUMO'")or die($conexion1->error);
$fconsumo=$kconsumo->FETCH(PDO::FETCH_ASSOC);
$ec=$fconsumo['SIGUIENTE_CONSEC'];
$exx=explode("-", $ec);
$quec=$exx[1]+1;
$quec=str_pad($quec,10,"0",STR_PAD_LEFT);

$docconsumo="$exx[0]-$quec";

$kingreso=$conexion1->query("SELECT CONSECUTIVO,SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='ING'")or die($conexion1->error);
$fingreso=$kingreso->FETCH(PDO::FETCH_ASSOC);
$ei=$fingreso['SIGUIENTE_CONSEC'];
$eing=explode("-", $ei);
$qi=$eing[1]+1;
$qi=str_pad($qi,10,"0",STR_PAD_LEFT);

$docingreso="$eing[0]-$qi";

$conexion1->query("update consny.CONSECUTIVO_CI set SIGUIENTE_CONSEC='$docingreso' where CONSECUTIVO='ING'")or die($conexion1->error);
$conexion1->query("update consny.CONSECUTIVO_CI set SIGUIENTE_CONSEC='$docconsumo' where CONSECUTIVO='CONSUMO'")or die($conexion1->error);


		//para registro de consumo 
		$fcc=$cc->FETCH(PDO::FETCH_ASSOC);
$kon=$conexion1->query("select COUNT(*) as ns from consny.LINEA_DOC_INV where DOCUMENTO_INV='$docconsumo'")or die($conexion1->error);
$fkon=$kon->FETCH(PDO::FETCH_ASSOC);
$nsc=$fkon['ns']+ 1;
$koni=$conexion1->query("select COUNT(*) as ns from consny.LINEA_DOC_INV where DOCUMENTO_INV='$docingreso'")or die($conexion1->error);
$fkoni=$koni->FETCH(PDO::FETCH_ASSOC);
$nsi=$fkoni['ns']+ 1;
		$articulo=$fcc['codigo'];
		$des=$fcc['subcategoria'];
		$barra=$fcc['barra'];
		$paquete=$_SESSION['paquete'];
		$usuario=$_SESSION['usuario'];
		$bode=$_SESSION['bodega'];
		$cantidad=$fcc['cantidad'];
//echo "<script>alert('a:$articulo d:$des b:$barra p:$paquete u:$usuario b:$bode docc:$docconsumo f:$fecha')</script>";
		$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$ec','CONSUMO','CONSUMO POR DESGLOSE DE FARDO: $articulo: $des CODIGO DE BARRA: $barra',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error);

$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$ei','ING','INGRESO POR DESGLOSE DE FARDO: $articulo: $des CODIGO DE BARRA: $barra',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error);




$conexion1->query("insert into consny.LINEA_DOC_INV(PAQUETE_INVENTARIO,
DOCUMENTO_INV,
LINEA_DOC_INV,
AJUSTE_CONFIG,
ARTICULO,
BODEGA,
TIPO,SUBTIPO,
SUBSUBTIPO,
CANTIDAD,
COSTO_TOTAL_LOCAL,COSTO_TOTAL_DOLAR,PRECIO_TOTAL_LOCAL,PRECIO_TOTAL_DOLAR,NoteExistsFlag) 
values('$paquete',
'$ec',
'$nsc','~CC~',
'$articulo',
'$bode',
'C',
'D',
'N',
'1',
'1',
'1',
'0',
'0',
'0')")or die($conexion1->error);
//echo "<script>alert('$idr <-id')</script>";
$cd=$conexion2->query("select * from desglose where registro='$idr'")or die($conexion2->error);
$ncd=$cd->rowCOUNT();
if($ncd==0)
{
	//echo "<script>alert('NO SE GREGO NINGUN ARTICULO A DESGLOSE')</script>";
	echo "<script>location.replace('desglose.php')</script>";
}

while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
{
$paquete=$fcd['paquete'];
$usuario=$fcd['usuario'];
$bode=$_SESSION['bodega'];//reistro o session
$articulo=$fcd['articulo'];
$des==$fcd['subcategoria'];
$cantidad=$fcd['cantidad'];

//echo "<script>alert('p: $paquete u: $usuario b: $bode des: $des a:$articulo')</script>";
$conexion1->query("insert into consny.LINEA_DOC_INV(PAQUETE_INVENTARIO,
DOCUMENTO_INV,
LINEA_DOC_INV,
AJUSTE_CONFIG,
ARTICULO,
BODEGA,
TIPO,SUBTIPO,
SUBSUBTIPO,
CANTIDAD,
COSTO_TOTAL_LOCAL,COSTO_TOTAL_DOLAR,PRECIO_TOTAL_LOCAL,PRECIO_TOTAL_DOLAR,NoteExistsFlag) 
values('$paquete',
'$ei',
'$nsi','~OO~',
'$articulo',
'$bode',
'O',
'D',
'L',
'$cantidad',
'1',
'1',
'0',
'0',
'0')")or die($conexion1->error);
	$nsi++;
	//echo "<script>alert('$nsi l')</script>";
}
$conexion2->query("update dbo.registro set digita_desglose='$dig',fecha_desglose='$fecha',documento_inv_consumo='$ec',documento_inv_ing='$ei',desglosado_por='$desglosado' where id_registro='$idr'")or die($conexion2->error);

 // agregar precio_regular a los desgglose

		






		echo "<script>alert('HECHO!...')</script>";
		$_SESSION['registro']="";
		echo "<script>location.replace('desglose.php')</script>";
	
}
}


?>
</div>
