<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
		$("#div").hide();
		if($("#i").val()==1)
		{
			$("#div").show(500);
			$("#art").focus();
		}else if($("#i").val()==2)
		{
			$("#div").show();
			$("#cant").focus();
		}
		});
		function cambia()
		{
			$("#op").val('1');
		}
		function enviar()
		{	
			$("#form1").submit();
		}
		function enviar1()
		{
			$("#op").val('2');	
			$("#form1").submit();
		}
		function finalizar()
		{
			$("#final").submit();
		}
	</script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
/*if($_SESSION['usuario']!='staana3')
{
	echo "<script>alert('NO DISPONIBLE POR EL MOMENTO YA ESTAMOS TRABAJANDO PARA SOLUCIONAR EL PROBLEMA SERA REDIRECIONADO AL AREA DE DESGLOSE DE CODIGOS DE BARRA')</script>";
echo "<script>location.replace('desglose.php')</script>";
}*/
if($_SESSION['d_averia']!='')
{
	$i=1;

}
if($_GET['arti']!='')
{
	$i=2;
	$arti=$_GET['arti'];
	$ca=$conexion1->query("select * from consny.articulo where articulo='$arti'") or die($conexion2->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
	$articu=$fca['ARTICULO'];
	$desc=$fca['DESCRIPCION'];

}
$core=$_SESSION['d_averia'];
?>
<h3 style="text-align: center; text-decoration: underline;">DESGLOSE DE AVERIA</h3>
<input type="hidden" name="i" id="i" value='<?php echo "$i";?> '>
<form method="POST">
<input type="text" name="corelativo" placeholder="CORELATIVO" class="text" style="width: 20%;" value='<?php echo "$core";?>'>
<input type="submit" name="btn" value="SIGUIENTE" class="boton2">
</form>
<div id="div">
<hr>
<a href="art_desglose_averia.php">
<button class="boton4" style="margin-left: 4%;">ARTICULOS</button></a>
<form method="POST" id="form1">
	<input type="text" name="art" id="art" placeholder="ARTICULO" class="text" style="width: 16%; margin-left: 4%;" onkeypress="cambia()" onchange="enviar()" value='<?php echo "$articu";?>'>

	<input type="text" name="des" placeholder="DESCRIPCION" class="text" style="width: 35%;" value='<?php echo "$desc";?>'>
	<input type="number" name="cant" id="cant" placeholder="CANTIDAD" class="text" style="width: 10%;" required>
	<input type="hidden" name="op" id="op">
	<input type="submit" name="btn" value="AGREGAR" class="boton2" onclick="enviar1()">
</form>
</div>
<?php
$session=$_SESSION['d_averia'];
$usuario=$_SESSION['usuario'];
$hoy=date("Y-m-d");


	
	$t=0;
	$corelativo=$_SESSION['d_averia'];
	//echo "<script>alert('$corelativo - $usuario')</script>";
	$co=$conexion2->query("select * from averia where corelativo='$corelativo' and tipo='D' and usuario='$usuario' order by id desc")or die($conexion2->error());
	//no muestra lo ingresado del desglose------<----<
	$n=$co->rowCount();

if($n!=0)
{
	echo "<table border='1' class='tabla' cellpadding='10' style='margin-left:4%; margin-top:1%;'>";
	echo "<tr>
	<td colspan='4'>
	<form method='POST' name='final' id='final'>
	<input type='text' name='desglosado' placeholder='DESGLOSADO POR' class='text' style='width:22.5%;  margin-right:0.3%;' required>
	<input type='text' name='digitado' placeholder='DIGITADO POR' class='text' style='width:22.5%;  margin-right:0.3%;' required>
	<input type='text' name='obs' placeholder='OBSERVACION' class='text' style='width:22.5%;  margin-right:0.3%;' required>
	<input type='date' name='fecha'  class='text' style='width:17%;' value='$hoy'>
	<input type='submit' name='btn' value='FINALIZAR' class='btnfinal' style='padding-bottom:0.5%; padding-top:0.5%; margin-bottom:0.05%; float:right; margin-right:0.5%; margin-top:0.5%;' >
	</form>
	
	</td>
	</tr>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>QUITAR</td>
	</tr>";
	while($f=$co->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$f['articulo'];
		$can=$f['cantidad'];
		$id=$f['id'];
		$t=$t + $can;
		$ca=$conexion1->query("select * from consny.articulo  where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
		<td>".$fca['ARTICULO']."</td>
		<td>".$fca['DESCRIPCION']."</td>
		<td>$can</td>
		<td><a href='quita_averia_d.php?id=$id' style='text-decoration:none;'>Quitar</a></td>
	</tr>";
	}
	echo "<tr>
		<td colspan='2'>TOTAL</td>
		<td>$t</td>
		<td>- -</td>
	</tr>";
	echo "</table>";
}
?>

<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='SIGUIENTE')
	{


	
	$bode=$_SESSION['bodega'];
	$c=$conexion2->query("select top 1 * from averia where corelativo='$corelativo' and tienda='$bode' and tipo='P' and estado='1'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('ERROR: POSIBLE CAUSAS\\n- EL NUMERO DE CORRELATIVO NO EXISTE\\n- CORRELATIVO NO ESTA ASIGNADO A TU BODEGA\\n - YA FUE DESGLOSADO')</script>";
		echo "<script>location.replace('desglose_averia.php')</script>";
	}else
	{
		$_SESSION['d_averia']=$corelativo;
		echo "<script>location.replace('desglose_averia.php')</script>";
	}
 }
 	if($op==1)
 	{
 		$c=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
 		$n=$c->rowCount();
 		if($n==0)
 		{
 			echo "<script>alert('NO SE ENCONTRO ARTICULO: $art')</script>";
 		}else
 		{
 			$f=$c->FETCH(PDO::FETCH_ASSOC);
 			$arti=$f['ARTICULO'];
 			echo "<script>location.replace('desglose_averia.php?arti=$arti')</script>";
 		}
 	}else if($op==2)
 	{
 		if($_SESSION['s_d_averia']=='')
 		{


 		$corelativo=$_SESSION['d_averia'];
 		$c=$conexion2->query("select max(sessiones) as sessiones from averia where tipo='D' and corelativo='$corelativo'")or die($conexion2->error());
 		$f=$c->FETCH(PDO::FETCH_ASSOC);
 		$session=$f['sessiones'] + 1;
 		$k=1;
 		while($k!=0)
 		{
 			$c=$conexion2->query("select * from averia where corelativo='$session' and tipo='D'")or die($conexion2->error());
 			$k=$c->rowCount();
 			if($k!=0)
 			{
 				$session++;
 				$k=1;
 			}else
 			{
 				$k=0;
 			}
 		}

 		
 		$_SESSION['s_d_averia']=$session;
 		$tienda=$_SESSION['bodega'];
 		$usuario=$_SESSION['usuario'];
 		$paquete=$_SESSION['paquete'];
 		$corelativo=$_SESSION['d_averia'];
 		//echo "<script>alert('$corelativo')</script>";
 		$conexion2->query("insert into averia(corelativo,tienda,fecha_ingreso,usuario,paquete,articulo,cantidad,sessiones,tipo) values('$corelativo','$tienda',getdate(),'$usuario','$paquete','$art','$cant','$session','D')")or die($conexion2->error());
 		echo "<script>location.replace('desglose_averia.php')</script>";
 		}else
 		{
 			$tienda=$_SESSION['bodega'];
 		$usuario=$_SESSION['usuario'];
 		$paquete=$_SESSION['paquete'];
 		$session=$_SESSION['s_d_averia'];
 		$cd=$conexion2->query("select * from averia where sessiones='$session' and tipo='D' and usuario='$usuario'")or die($conexion2->error());
 		$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
 		$corelativo=$_SESSION['d_averia'];
 		$conexion2->query("insert into averia(corelativo,tienda,fecha_ingreso,usuario,paquete,articulo,cantidad,sessiones,tipo) values('$corelativo','$tienda',getdate(),'$usuario','$paquete','$art','$cant','$session','D')")or die($conexion2->error());
 		echo "<script>location.replace('desglose_averia.php')</script>";

 		
 		}	
 	}
 	if($btn=='FINALIZAR')
 	{

 		$corelativo=$_SESSION['d_averia'];
 		$usuario=$_SESSION['usuario'];
 		echo "<script>alert('$session')</script>";
 		$c=$conexion2->query("select corelativo from averia where tipo='D' and corelativo='$corelativo' and usuario='$usuario'")or die($conexion2->error());
 		$f=$c->FETCH(PDO::FETCH_ASSOC);
 		$corelativo=$f['corelativo'];
 		$usuario=$_SESSION['usuario'];
		$c=$conexion2->query("select * from averia where corelativo='$corelativo' and tipo='D' and usuario='$usuario'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('ERROR: NO SE HA AGREGADO NINGUN ARTICULO AL DESGLOSE')</script>";
	}else
	{

	
		//-------------documento
 		$c=$conexion1->query("select siguiente_consec from consny.consecutivo_ci where consecutivo='CON2'")or die($conexion1->error());
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$consecutivo=$f['siguiente_consec'];
		$cortar=explode( "CON2-",$consecutivo);
		$suma=$cortar[1] + 1;
		$suma=str_pad($suma,10,"0",STR_PAD_LEFT);
		$queda="CON2-$suma";

		$conexion1->query("update consny.consecutivo_ci set siguiente_consec='$queda' where consecutivo='CON2'")or die($conexion1->error());
		$usuario=$_SESSION['usuario'];
		$c=$conexion2->query("select * from averia where corelativo='$corelativo' and tipo='D' and usuario='$usuario'")or die($conexion2->error());
		$k=1;
		$num=1;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$f['articulo'];
			$cant=$f['cantidad'];
			$corelativo=$f['corelativo'];
			$cantidad=$f['cantidad'];
			$usuario=$f['usuario'];
			$paquete=$f['paquete'];
			$bodega=$f['tienda'];
			$bodega=$_SESSION['bodega'];
			if($k==1)
			{
				$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$consecutivo','CON2','CONSUMO POR DESGLOSE DE AVERIA CORRELATIVO: $corelativo FECHA: $fecha',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error());

$conexion1->query("insert into consny.linea_doc_inv(paquete_inventario,documento_inv,linea_doc_inv,ajuste_config,articulo,bodega,tipo,subtipo,subsubtipo,cantidad,costo_total_local,costo_total_dolar,precio_total_local,precio_total_dolar,noteexistsflag) values('$paquete',
	'$consecutivo','$num','AJN','$art','$bodega','C','D','N','$cant','0','0','0','0','0')")or die($conexion1->error());	
$k++;
			}else
			{
				$conexion1->query("insert into consny.linea_doc_inv(paquete_inventario,documento_inv,linea_doc_inv,ajuste_config,articulo,bodega,tipo,subtipo,subsubtipo,cantidad,costo_total_local,costo_total_dolar,precio_total_local,precio_total_dolar,noteexistsflag) values('$paquete',
	'$consecutivo','$num','AJN','$art','$bodega','C','D','N','$cant','0','0','0','0','0')")or die($conexion1->error());	
			}
			$num++;
		}


 		$conexion2->query("update averia set estado='T',documento_inv='$consecutivo',desglosado_por='$desglosado',digita_desglose='$digitado',fecha_desglose='$fecha' where corelativo='$corelativo'")or die($conexion2->error());
 		$conexion2->query("update averia set  observacion='$obs' where corelativo='$corelativo' and tipo='D'")or die($conexion2->error());
 		echo "<script>alert('FINALIZADO CORECTAMENTE')</script>";
 		$_SESSION['d_averia']='';
 		echo "<script>location.replace('desglose_averia.php')</script>";
 	}
 	}
}
?>
</body>
</html>