<!DOCTYPE html>
<html>
<head>
	<title></title>
<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
	$(document).ready(function(){
		
		$("#formu1").hide();
		$(".detalle").hide();
		if($("#i").val()==1)
		{
			$("#formu1").show();
		}
		if($("#tipo_doc").val()=='CREDITO FISCAL')
		{
			$("#proveedor1").show();
			
		}
	});

	function enviar()
	{
		if($("#tipo_trans").val()=='INGRESO')
		{

			$("#concepto").prop("required",true);
			$("#monto_linea").prop("required",true);
			
		}else
		{
			$("#concepto").prop("required",true);
			$("#monto_linea").prop("required",true);
			$("#tipo_doc").prop("required",true);
			$("#num_doc").prop("required",true);
		}
		

		if($("#tipo_doc").val()=='CREDITO FISCAL')
		{
			if($("#proveedor1").val()=='')
			{
				alert('INGRESE EL NOMBRE DEL PROVEEDOR\nHAZ DOBLE CLIC EN PROVEEDOR,  PARA SELECCIONARLO');
				$("#formulario").submit(false);
			}else
			{
				$("#formulario").submit(true);
			}
		}
		
		
	}
	function final()
	{
		//alert($("#v").val());
		if($("#v").val()<0)
		{
			alert('NO PUEDES FINALIZAR POR QUE EL MONTO LIQUIDO ES NEGATIVO');
		}else
		{
			if(confirm("SEGURO DESEA FINALIZAR"))
			{
				if($("#nqb").val()!='0')
				{
					if($("#asiento_r").val()=='')
					{
					location.replace("final_cuadrar2.php");	
					}else
					{
						location.replace("final_cuadrar1.php");

					}

				}else
				{
					//alert('matrix');
					location.replace("final_cuadrar1.php");
				}
				
				//para que llege a exactus redirecionar a final_cuadrar.php
				//estara el asiento vacio porque no esta cayendo a exactus desde 24-08-2020
			}
		}
	}
	function eliminar()
	{
		if(confirm('SEGURO DESEA QUITAR LA LINEA'))
		{
			$("#elimina").submit();
		}
	}
	function validarproveedor()
	{
		//alert($("#tipo_doc").val());
		if($("#tipo_doc").val()=='CREDITO FISCAL')
		{

			$("#proveedor1").show();
			$("#proveedor1").attr("required",true);
		
		//alert();
		}else
		{
		//$("#proveedor1").prop('required',false);
		$("#proveedor1").hide(); 
		//alert('ssdffd');

		}
	}

	function activarproveedor()
	{
		location.replace('proveedor_cuadrar.php?tipo_trans='+$("#tipo_trans").val()+'&&concepto='+$("#concepto").val()+'&&monto_linea='+$("#monto_linea").val()+'&&tipo_doc='+$("#tipo_doc").val()+'&&num_doc='+$("#num_doc").val());
	}

	function validacion()
	{
		if($("#tipo_trans").val()=='INGRESO')
		{
			$("#tipo_doc").empty();
			$("#proveedor1").val('');
			$("#proveedor1").hide();
			$("#tipo_doc").append('<option value="">TIPO DOCUMENTO</option>');
			$("#tipo_doc").append('<option value="CONTROL INTERNO">CONTROL INTERNO</option>');
			$("#tipo_doc").append('<option value="RECIBO">RECIBO</option>');

		}else if($("#tipo_trans").val()=='SALIDA')
		{
			$("#tipo_doc").empty();
			$("#tipo_doc").append('<option value="">TIPO DOCUMENTO</option>');
			$("#tipo_doc").append('<option value="CREDITO FISCAL">CREDITO FISCAL</option>');
			$("#tipo_doc").append('<option value="FACTURA">FACTURA</option>');
			$("#tipo_doc").append('<option value="RECIBO">RECIBO</option>');
			$("#tipo_doc").append('<option value="CONTROL INTERNO">CONTROL INTERNO</option>');
			$("#tipo_doc").append('<option value="TICKET">TICKET</option>');
			$("#tipo_doc").append('<option value="SUJETO EXCLUIDO">SUJETO EXCLUIDO</option>');
			$("#tipo_doc").append('<option value="VENTA CON TARJETA">VENTA CON TARJETA</option>');
			$("#tipo_doc").append('<option value="LIQUIDACION">LIQUIDACION</option>');
			$("#tipo_doc").append('<option value="BITCOIN">BITCOIN</option>');
		}
			$("#tipo_doc").append('<option value="OTROS">OTROS</option>');

	}
</script>
</head>
<body>
<div class="detalle" style="width: 110%; margin-left: -2%; background-color: white; opacity: 0.3;">
<img src="load1.gif" width="25%" height="25%;" style="position: sticky; top:0; margin-left: 33%; margin-top: 13%;">
</div>
<?php

error_reporting(0);
include("conexion.php");
$id_proveedor=$_GET['id_proveedor'];
$cpro=$conexion1->query("select * from librosiva.dbo.Agentes where esproveedor='1' and IdAgente='$id_proveedor'")or die($conexion1->error());
$fcpro=$cpro->FETCH(PDO::FETCH_ASSOC);
$provedores=$fcpro['NombreAgente'];

$v=$_GET['v'];
$s=$_GET['s'];
if($v==1)
{
	$usuario=$_SESSION['usuario'];
	$bod=$_SESSION['bodega'];

	$rc=$conexion1->query("select * from cuadro_venta where session='$s' and bodega='$bod'")or die($conexion1->error());
	$nrc=$rc->rowCount();
	if($nrc==0)
	{
		echo "<script>alert('ERROR: NO SE PUEDE CONTINUAR')</script>";
		echo "<script>location.replace('cuadrar.php')</script>";
	}else
	{
		$_SESSION['cuadrar']=$s;

		echo "<script>location.replace('cuadrar.php')</script>";
	}
}
//error_reporting(0);
//$_SESSION['cuadrar']=1;

if($_SESSION['cuadrar']!='')
{
	$i=1;
	$sessione=$_SESSION['cuadrar'];
	$usuario=$_SESSION['usuario'];

	$con=$conexion1->query("select * from cuadro_venta where session='$sessione' and usuario='$usuario'")or die($conexion1->error());
	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
	$hoy=$fcon['FECHA'];
	$caja=$fcon['CAJA'];
	$monto_usu=$fcon['MONTO_USUARIO'];
	$r_asiento=$fcon['ASIENTO'];
	$bodega_r=$fcon['BODEGA'];
	echo "<input type='hidden' name='asiento_r' id='asiento_r' value='$r_asiento' readonly>";
	$qb=$conexion1->query("select * from  consny.BODEGA where (nombre like '%san miguel%' or nombre like '%usulu%' or nombre like '%apopa%' 
or nombre like '%san vicente%' or  nombre like '%ahu%' or nombre like '%santa ana%' or nombre like '%sonso%'
or nombre like '%tecla%' or nombre like '%sucia%' or nombre like '%chal%' or nombre like '%agui%')
 and BODEGA not like 'SM%' and nombre not like '%(N)%' and 
BODEGA='$bodega_r' order by nombre
")or die($conexion1->error());
	$nqb=$qb->rowCount();
	echo "<input type='hidden' name='nqb' id='nqb' value='$nqb' readonly>";

	$_SESSION['liquido']=$fcon['MONTO_USUARIO'];
}else
{
	$hoy=date("Y-m-d");
}
$usuario=$_SESSION['usuario'];
$bodega=$_SESSION['bodega'];

$c=$conexion1->query("select * from dbo.usuariobodega where usuario='$usuario'")or die($conexion1->error());
$usere=substr($usuario, 0);
echo "<input type='hidden' name='user' id='user' value='$usere[0]'>";
$f=$c->FETCH(PDO::FETCH_ASSOC);
$bd=$f['BASE'];
$hamachi=$f['HAMACHI'];
ini_set('max_execution_time', 10000000);
date_default_timezone_set('America/El_Salvador');
//$_SESSION['esquema']=$f['ESQUEMA'];
//-------------------------------------------------------
//echo "<script>alert('$bd - $hamachi-$usuario')</script>"; 
//conexion con hamachi para las tiendas
try {
$conexion_tienda=new PDO("sqlsrv:Server=$hamachi;Database=$bd", "sa", "$0ftland");
echo "<h3>CONECTADO</h3>";
}
catch(PDOException $e) {
        die("<h1>!!ERROR!! NO SE LOGRO CONECTAR CON LA BASE DE DATOS VERIFICA SI HAMACHI SE ENCUENTRA ENCENDIDO Y ACTUALIZA LA PAGINA</h1>");
    }

#--------------------------------------------------------
  //  echo "<script>alert('$bodega')</script>";
$q=$conexion_tienda->query("select * from consny.caja_pos where bodega='$bodega' and caja not in('029','020')
")or die($conexion_tienda->error());
$nq=$q->rowCount();
//echo "<script>alert('$nq')</script>";

?>
<h3 style="text-align: center; text-decoration: underline;">INGRESO DE CUADRO DE VENTA</h3>
<input type="hidden" name="i" id="i" value='<?php echo "$i"; ?>'>
<form method="POST" name="form">
	CAJA:
<select class="text" name="caja" style="width: 15%;" required>
	<option><?php echo "$caja";?></option>
<?php
while($f=$q->FETCH(PDO::FETCH_ASSOC))
{
	echo "<option>".$f['CAJA']."</option>";
}
?>
</select>
FECHA: <input type="date" name=" fecha" id="fecha" value="<?php echo "$hoy";?>" class='text' style='width: 30%; margin-left: 3%;'>


MONTO: <input type="number" step="any" lang="en" min="0"  name="monto_usuario" placeholder="MONTO" id="monto" class='text' required style='width: 20%; margin-left: 3%;' step="any" lang="en" min="0" value='<?php echo "$monto_usu";?>'>


<input type="submit" name="btn" value="SIGUIENTE" class="boton2">
</form>
<div id="formu1">
<hr style="background-color: skyblue; height:1px;">



<br>

<form method="POST" name="formulario" id="formulario">
<select name="tipo_trans" id="tipo_trans" required class="text" style="width: 10%;" onchange="validacion()">
	
	<?php
		if($_GET['tipo_trans']!='')
		{
		echo "<option>".$_GET['tipo_trans']."</option>";

		}else
		{
		echo "<option>TIPO DE TRANSACCION</option>";
		}
	
	?>
	<option>INGRESO</option>
	<option>SALIDA</option>
</select>
<select name="tipo_doc" id="tipo_doc"  class="text" style="width: 10%;" onchange="validarproveedor()" required>

	<?php
	if($_GET['tipo_doc']=='')
	{

		echo "<option value=''>TIPO DE DOCUMENTO</option>";
	}else
	{
		echo "<option>".$_GET['tipo_doc']."</option>";

	}
	?>
	

</select>	
<?php //echo "<script>alert('$provedores<-')</script>";
$conceptos=$_GET['concepto'];
?>
<input type="number" name="num_doc" id="num_doc" placeholder="# DOCUMENTO" class="text" style="width: 10%;" value='<?php if($_GET['num_doc']!=''){echo $_GET['num_doc']; }?>'>

<input type="text" name="concepto"  id="concepto" placeholder="CONCEPTO" class="text" style="width: 30%;" value='<?php if($_GET['concepto']!=''){ echo $_GET['concepto'];}?>' maxlength="100">
<input type="text" name="proveedor" id="proveedor1" placeholder="PROVEEDOR" class="text" style="padding: 0.5%; width: 20%; display: none;" ondblclick="activarproveedor()" readonly value='<?php if($provedores!=''){ echo $provedores; }?>'>
<input type="hidden" name="id_proveedor" value='<?php echo $id_proveedor; ?>'>
<input type="number" name="monto_linea" id="monto_linea" placeholder="MONTO"  class="text" style="width:8%;" step="any" lang="en" min="0" value='<?php if($_GET['monto_linea']!=''){ echo $_GET['monto_linea'];}?>'>
<input type="submit" class="boton3" name="btn" value="AGREGAR" readonly style="width: 6%;" id="btn2" onclick="enviar()">

</form>

<br>
<hr style="background-color: skyblue; height:1px;">
</div>
<?php
$sessione=$_SESSION['cuadrar'];
$usuario=$_SESSION['usuario'];
$bode=$_SESSION['bodega'];
//SACO EL ENCABEZADO
$con=$conexion1->query("select id from cuadro_venta where session='$sessione' and bodega='$bode'")or die($conexion1->error());
$fcon=$con->FETCH(PDO::FETCH_ASSOC);
$id_cuadro=$fcon['id'];

$query=$conexion1->query("select * from cuadro_venta_detalle where cuadro_venta='$id_cuadro' order by id desc")or die($conexion1->error());
$nquery=$query->rowCount();
if($_SESSION['cuadrar']!='')
{
echo "<table border='1' class='tabla' cellpadding='10'>";
echo "
<tr style='background-color:#39C2A1; color:black;'>
<td colspan='5'>GASTOS Y OTROS INGRESOS
<button class='btnfinal' style='padding-bottom:0.5%; padding-top:0.5%; margin:0%; float:right;' onclick='final()'>FINALIZAR</button>
</td>
</tr>
<tr>
<td>TIPO TRANSACCION</td>
<td>TIPO DOCUMENTO</td>
<td>CONCEPTO</td>
<td>MONTO</td>
<td>OPCION</td>
</tr>";
}

if($nquery!=0)
{


$cm=$conexion1->query("select monto_usuario from cuadro_venta where session='$sessione' and caja='$caja'")or die($conexion1->error());
$fcm=$cm->FETCH(PDO::FETCH_ASSOC);
$monto_final=$fcm['monto_usuario'];
$salida22=0;
while($fquery=$query->FETCH(PDO::FETCH_ASSOC))
{
	if($fquery['TIPO_TRANSACCION']=='INGRESO')
	{
		if($fquery['TIPO_DOCUMENTO']!='VENTA CON TARJETA')
		{
			$monto_final=$monto_final + $fquery['MONTO'];

		}
		
	}else
	{
		$salida22=$salida22+$fquery['MONTO'];
		//$monto_final=$monto_final - $fquery['MONTO'];
		//echo "<script>alert('$salida22 - $monto_final')</script>";

	}
	$id=$fquery['ID'];
	echo "<tr>
	<td>".$fquery['TIPO_TRANSACCION']."</td>
	<td>".$fquery['TIPO_DOCUMENTO']."</td>
	<td>".$fquery['CONCEPTO']."</td>
	<td>$".$fquery['MONTO']."</td>
	<td>
	<a href='quitar_cuadro.php?id=$id'>

	<input type='text' readonly value='ELIMINAR' class='boton2' style='background-color: red; color:white; padding:1.5%; border-radius:5px; width:30%;'></a>
	</td>
</tr>";
}

$cqf=$conexion2->query("select convert(decimal(10,2),'$salida22') salidas, convert(decimal(10,2),'$monto_final') final")or die($conexion2->error());
$fcqf=$cqf->FETCH(PDO::FETCH_ASSOC);
$salida22=$fcqf['salidas'];
$monto_final=$fcqf['final'];
//echo "<script>alert('$salida22 - $monto_final')</script>";


$monto_final=$monto_final-$salida22;
//echo "<script>alert('$tt')</script>";
echo "<tr>
<td colspan='3'>TOTAL LIQUIDO</td>
<td>$$monto_final</td><td></td>
</tr>";
$_SESSION['liquido']=$monto_final;
echo "<input type='hidden' name='v' id='v' value='$monto_final'>";
}
?>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='SIGUIENTE')
	{

		$cvali=$conexion_tienda->query("select estado from consny.cierre_pos where num_cierre in(select top 1 num_cierre from consny.documento_pos where caja='$caja' and fch_hora_cobro between '$fecha 00:01' and '$fecha 23:59')")or die($conexion_tienda->error());
		$fcvali=$cvali->FETCH(PDO::FETCH_ASSOC);
		$estados=$fcvali['estado'];
		//echo "<script>alert('$estados')</script>";
		if($estados=='A' or $estados=='')
		{
			echo "<script>alert('NO A REALIZADO EL CIERRE DE CAJA $caja DEL DIA $fecha')</script>";
			echo "<script>location.replace('cuadrar.php')</script>";
		}else
		{
			//FARDOS VENDIDOS
			$cfv=$conexion_tienda->query("declare @fecha datetime='$fecha'
declare @caja nvarchar(4)='$caja'

SELECT SUM(E.TOTAL) as total FROM 
(select case consny.DOC_POS_LINEA.TIPO when 'F' THEN  SUM(CANTIDAD) ELSE SUM(CANTIDAD) *-1 END  TOTAL 
from consny.DOC_POS_LINEA where caja=@caja and DOCUMENTO IN
(select documento from consny.documento_pos where caja=@caja and FCH_HORA_COBRO between DATEADD(minute,1,@fecha) and DATEADD(MINUTE,1439,@fecha))
and (ARTICULO like 'P%' or ARTICULO like 'T%' or ARTICULO like 'F%')
GROUP BY consny.DOC_POS_LINEA.TIPO
) AS E
")or die($conexion_tienda->error());

			$fcfv=$cfv->FETCH(PDO::FETCH_ASSOC);
			$fardos_vendidos=$fcfv['total'];
			if($fardos_vendidos=='')
			{
				$fardos_vendidos=0;
			}
			//FIN FARDOS VENDIDOS


//echo "<script>alert('$caja $fecha $monto_usuario')</script>";
		$c=$conexion_tienda->query("DECLARE @FECHA datetime='$fecha'


select min(documento) as doc_inicio, max(documento) as doc_final, 
min(FCH_HORA_COBRO) as fecha_i, max(FCH_HORA_COBRO) as fecha_f,
SUM(case when tipo='F' then TOTAL_PAGAR else TOTAL_PAGAR*-1 END) TOTAL 
from consny.DOCUMENTO_POS where CAJA='$caja' and FCH_HORA_COBRO between DATEADD(mi,1,@FECHA) and DATEADD(mi,1439,@FECHA)

")or die($conexion_tienda->error());

		$f=$c->FETCH(PDO::FETCH_ASSOC);
		/*echo "documento inicio: ".$f['doc_inicio']."<br>
		documento finl: ".$f['doc_final']."<br>
		fecha inicio: ".$f['fecha_i']."<br>
		fecha final: ".$f['fecha_f']."<br>
		fecha final: ".$f['TOTAL']."<br>";*/

		$doc_inicio=$f['doc_inicio'];
		$doc_final=$f['doc_final'];
		$fecha_inicio=$f['fecha_i'];
		$fecha_final=$f['fecha_f'];
		$monto_sis=$f['TOTAL'];
		//TOTAL FARDO

		//MONTO SISTEMA TEMPORAL
			$bod=$_SESSION['bodega'];
		$cms=$conexion_tienda->query("declare @venta decimal(10,2)=(select sum(consny.documento_pos.TOTAL_PAGAR) from CONSNY.DOCUMENTO_POS inner join 
CONSNY.CAJA_POS on CONSNY.DOCUMENTO_POS.CAJA=CONSNY.CAJA_POS.CAJA where CONSNY.CAJA_POS.BODEGA='$bod' and 
CONVERT(date,CONSNY.DOCUMENTO_POS.FCH_HORA_COBRO)='$fecha' and CONSNY.DOCUMENTO_POS.TIPO='F')

declare @descuento decimal(10,2)=(select sum(consny.documento_pos.TOTAL_PAGAR) from CONSNY.DOCUMENTO_POS inner join 
CONSNY.CAJA_POS on CONSNY.DOCUMENTO_POS.CAJA=CONSNY.CAJA_POS.CAJA where CONSNY.CAJA_POS.BODEGA='$bod' and 
CONVERT(date,CONSNY.DOCUMENTO_POS.FCH_HORA_COBRO)='$fecha' and CONSNY.DOCUMENTO_POS.TIPO='D')
declare @total decimal(10,2)=(isnull(@venta,0)-isnull(@descuento,0))
select @total as total") or die($conexion_tienda->error());
	$fcms=$cms->FETCH(PDO::FETCH_ASSOC);
	$monto_sis=$fcms['total'];
		
		

		//FIN MONTO SISTEMA TEMPORAL
		$fini="$fecha 01:00";
		$ffinal="$fecha 23:59";
			$con=$conexion_tienda->query("declare @fecha datetime='$fecha'

SELECT SUM(E.TOTAL) as tfardo FROM 
(select case consny.DOC_POS_LINEA.TIPO when 'F' THEN  SUM(PRECIO_VENTA+TOTAL_IMPUESTO1-DESCUENTO_LINEA) ELSE SUM(PRECIO_VENTA+TOTAL_IMPUESTO1-DESCUENTO_LINEA) *-1 END  TOTAL from consny.DOC_POS_LINEA where caja='$caja' and DOCUMENTO IN
(select documento from consny.documento_pos where caja='$caja' and FCH_HORA_COBRO between DATEADD(minute,1,@fecha) and DATEADD(MINUTE,1439,@fecha))
and (ARTICULO like 'P%' or ARTICULO like 'T%' or ARTICULO like 'F%')
GROUP BY consny.DOC_POS_LINEA.TIPO
) AS E
")or die($conexion_tienda->error());
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$total_fardo=$fcon['tfardo'];
			if($total_fardo=='')
			{
				$total_fardo=0;
			}
			//echo "<script>alert('$total_fardo')</script>";
			//FIN TOTAL FARDO

			
	    //INICIO DE TOTAL POR CATEGORIA (SIN IVA)
	    /*$con=$conexion_tienda->query("declare @fecha datetime='$fecha'
declare @caja nvarchar(4)='$caja'

SELECT E.CLASIFICACION, (E.TOTAL) as total FROM 
(SELECT case when CLASIFICACION_2 in (
'ZAPATOS','CARTERAS','CINCHOS','GORRAS','CARTERA') THEN 'ACCESORIOS' 
WHEN CLASIFICACION_2 IN (
'JUGUETES','OTROS','VARIOS')  THEN 'OTROS'
ELSE CLASIFICACION_2 END CLASIFICACION,
 CASE consny.DOC_POS_LINEA.TIPO WHEN 'F' THEN 
 ROUND(SUM(CONSNY.DOC_POS_LINEA.PRECIO_VENTA+CONSNY.DOC_POS_LINEA.TOTAL_IMPUESTO1-CONSNY.DOC_POS_LINEA.DESCUENTO_LINEA)/1.13,2) ELSE 
 ROUND(SUM(CONSNY.DOC_POS_LINEA.PRECIO_VENTA+CONSNY.DOC_POS_LINEA.TOTAL_IMPUESTO1-CONSNY.DOC_POS_LINEA.DESCUENTO_LINEA)/1.13,2) * - 1 END AS TOTAL
FROM CONSNY.DOC_POS_LINEA INNER JOIN
 CONSNY.ARTICULO ON CONSNY.DOC_POS_LINEA.ARTICULO = CONSNY.ARTICULO.ARTICULO
WHERE        (CONSNY.DOC_POS_LINEA.CAJA = @caja) AND (CONSNY.DOC_POS_LINEA.DOCUMENTO IN
 (SELECT        DOCUMENTO
 FROM            CONSNY.DOCUMENTO_POS
 WHERE        (CAJA = @caja) AND (FCH_HORA_COBRO BETWEEN DATEADD(minute, 1, @fecha) AND DATEADD(MINUTE, 1439, @fecha))))
GROUP BY CONSNY.DOC_POS_LINEA.TIPO, case when CLASIFICACION_2 in (
'ZAPATOS','CARTERAS','CINCHOS','GORRAS','CARTERA') THEN 'ACCESORIOS' 
WHEN CLASIFICACION_2 IN (
'JUGUETES','OTROS','VARIOS')  THEN 'OTROS'
ELSE CLASIFICACION_2 END) AS E")or die($conexion_tienda->error());
	    $mROPA;
	    $mACCESORIOS:
	    $mOTROS;
	    */
			//FIN DE TOTAL POR CATEGORIA (SIN IVA)


			//descuento
			$consulta=$conexion_tienda->query("declare @fecha datetime='$fecha'
SELECT SUM(E.DESCUENTO) as descuento from 
(select case TIPO when 'F' then   sum(descuento_linea)*1.13 else sum(descuento_linea*-1)*1.13 END DESCUENTO
from consny.DOC_POS_LINEA where caja='$caja' and DOCUMENTO IN 
(select DOCUMENTO from consny.documento_pos where caja='$caja' and FCH_HORA_COBRO between DATEADD(minute,1,@fecha) and DATEADD(MINUTE,1439,@fecha))
group by TIPO) as E
")or die($conexion_tienda->error());
			$fconsulta=$consulta->FETCH(PDO::FETCH_ASSOC);
			$descuento=$fconsulta['descuento'];
			if($descuento=='')
			{
				$descuento=0;
			}
			//fin descuento

			//liquidaciones
			$cl=$conexion2->query("select isnull(sum(precio_origen*cantidad) - sum(precio_destino*cantidad),0) as monto from liquidaciones where bodega='$bodega' and fecha='$fecha'")or die($conexion1->error());
			$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
			$liquidacion=$fcl['monto'];
			if($liquidacion=='')
			{
				$liquidacion=0;
			}
			// fin liquidaciones

		if($_SESSION['cuadrar']=='')
		{
			//echo "<script>alert('entra')</script>";
			$cv=$conexion1->query("select max(session) as session from cuadro_venta")or die($conexion1->error());
			$fcv=$cv->FETCH(PDO::FETCH_ASSOC);
			$session=$fcv['session'] + 1;
			$k=1;
			while($k==1)
			{
				$cve=$conexion1->query("select * from cuadro_venta where session='$session'")or die($conexion1->error());
				$n=$cve->rowCount();
				if($n==0)
				{
					$k=0;
				}else
				{
					$k=1;
					$session++;
				}
			}
			
			$bodega=$_SESSION['bodega'];
			$usuario=$_SESSION['usuario'];
			//echo "<script>alert('$monto_usuario - $session')</script>";
			//validacion si ya ingresaron en el dia selecionado
			$q2=$conexion1->query("select * from cuadro_venta where fecha='$fecha' and bodega='$bodega' ")or die($conexion1->error());
			$nq2=$q2->rowCount();

			if($nq2!=0)
			{
				$fq2=$q2->FETCH(PDO::FETCH_ASSOC);
				$s=$fq2['SESSION'];
				//echo "<script>alert('dffd$s')</script>";
				echo "<script>if(confirm('FECHA SELECCIONADA YA FUE UTILIZADA ANTES\\nDESEA CONTINUAR'))
				{
					location.replace('cuadrar.php?v=1&&s=$s')
				}else
				{
					location.replace('cuadrar.php?op=2')
				}
				</script>";

			}else{

			
			//echo "<script>alert('$fardos_vendidos')</script>";
			//echo "<script>alert('insert d $descuento,- tf $total_fardo, l $liquidacion')</script>";
			if($monto_sis==$monto_usuario)
			{
				$_SESSION['cuadrar']=$session;
				$conexion1->query("insert into cuadro_venta(caja,bodega,usuario,fecha,fecha_inicial,fecha_final,fecha_hor_creacion,estado,session,monto_sistema,monto_usuario,documento_inicial,documento_final,descuento,total_fardo,monto_liquidaciones,fardos_vendidos) values('$caja','$bodega','$usuario','$fecha','$fecha_inicio','$fecha_final',getdate(),'0','$session','$monto_sis','$monto_usuario','$doc_inicio','$doc_final','$descuento','$total_fardo','$liquidacion','$fardos_vendidos')")or die($conexion1->error());
			echo "<script>location.replace('cuadrar.php?')</script>";
			}else
			{
				echo "<script>alert('ERROR: NO ES POSIBLE GUARDAR LA INFORMACION DIGITADA')</script>";
				echo "<script>location.replace('cuadrar.php')</script>";
			}
			
			}
		}else
		{
			//echo "<script>alert('entra update')</script>";
			//update 
			$bodega=$_SESSION['bodega'];
			$session=$_SESSION['cuadrar'];
			//echo "<script>alert('$bodega - $session')</script>";
			if($monto_usuario==$monto_sis)
			{
			$conexion1->query("update cuadro_venta set
			caja='$caja',
			bodega='$bodega',
			usuario='$usuario',
			fecha='$fecha',
			fecha_inicial='$fecha_inicio',
			fecha_final='$fecha_final',
			monto_sistema='$monto_sis',
			monto_usuario='$monto_usuario',
			documento_inicial='$doc_inicio',
			documento_final='$doc_final',
			descuento='$descuento',
			total_fardo='$total_fardo',
			monto_liquidaciones='$liquidacion',
			fardos_vendidos='$fardos_vendidos'
			 where session='$session' and bodega='$bodega'")or die($conexion1->error());
			echo "<script>location.replace('cuadrar.php?o=1')</script>";

			
		}else
		{
			echo "<script>alert('INFORMACION NO VALIDAD...')</script>";
			echo "<script>location.replace('cuadrar.php?o')</script>";
		}
		}
	}
	}else if($btn=='AGREGAR')
	{
		$usuario=$_SESSION['usuario'];
		$sessione=$_SESSION['cuadrar'];
		$c=$conexion1->query("select * from cuadro_venta where session='$sessione' and usuario='$usuario'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('SE PRODUJO UN ERROR: 3730')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$id=$f['ID'];

			$conexion1->query("insert into cuadro_venta_detalle(cuadro_venta,tipo_transaccion,tipo_documento,num_documento,concepto,monto,fecha_hor_creacion,usuario,id_agente) values('$id','$tipo_trans','$tipo_doc','$num_doc','$concepto','$monto_linea',getdate(),'$usuario','$id_proveedor')")or die($conexion1->error());
			echo "<script>location.replace('cuadrar.php')</script>";
		}
		

	}
}//FIN POST
?>
</body>
</html>