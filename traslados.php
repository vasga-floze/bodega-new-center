<!DOCTYPE html>
<html>
<head>
<?php
//ver las autorizaciones en las otras paginaas hechas solo index y trasladoss
error_reporting(0);
include("conexion.php");
echo "<script>location.replace('trasladob.php')</script>";
if($_SESSION['tipo']==3)
	{
		echo "<script>location.replace('consultar.php')</script>";
	}

if($_SESSION['usuario']=='SALVARADO' OR $_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='staana3')
{
	if($_SESSION['origen']=='')
	{
	  //$_SESSION['origen']='SM00';
	}
	
}
$tipo=$_SESSION['tipo'];
$bodega=$_SESSION['bodega'];
		$tipousu=substr($bodega,0);
		if($tipousu[0]=='U')
		{
			$tipo=1;

		}else
		{
			$tipo=$_SESSION['tipo'];
		}



if($tipo==2)
	{
		echo "<script>alert('NO TIENES AUTORIZACION PARA TRASLADO')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}
if($tipo==2)
{
	echo "<script>location.replace('desglose.php')</script>";
}
$ori=$_SESSION['origen'];
$ori=$_GET['ori'];

if($_SESSION['origen']!='')
{
	$io=1;
	$ori=$_SESSION['origen'];
}
if($_GET['ori']!='')
{
	$ori=$_GET['ori'];
}
$cori=$conexion1->query("select * from consny.BODEGA where BODEGA='$ori'")or die($conexion1->error());
$fcori=$cori->FETCH(PDO::FETCH_ASSOC);
$ori=$fcori['BODEGA'];
$nori=$fcori['NOMBRE'];

$busca=$_GET['b'];
$i=$_GET['i'];
$bode=$_GET['bcod'];
if($_SESSION['doc']!="")
{
	$docu=$_SESSION['doc'];
	$us=$_SESSION['usuario'];
	$k=$conexion2->query("select top 1 * from traslado where sessiones='$docu' and usuario='$us' order by id desc
")or die($conexion2->error);
	$knum=$conexion2->query("select count(*) as total from traslado where sessiones='$docu' and usuario='$us'")or die($conexion2->error);
	$fknum=$knum->FETCH(PDO::FETCH_ASSOC);
	$tf=$fknum['total'];
	$fk=$k->FETCH(PDO::FETCH_ASSOC);
	$bode1=$fk['destino'];
	$bodega_destino=$bode1;//validar cambio de bodega
	if($bode=='')
	{
		$bode=$bode1;
	}
	if($bode1!="" and $bode==$bodega_destino)
	{
		$i=2;

	}else
	{
		
	}
	
}else
{
	
}
//echo "<script>alert('$bode $bode1')</script>";

$con=$conexion1->query("select * from consny.bodega where bodega='$bode'")or die($conexion1->error);
$fcon=$con->FETCH(PDO::FETCH_ASSOC);
$bodt=$fcon['BODEGA'];
$nomt=$fcon['NOMBRE'];

if($bodt=='E006' or $bodt=='N005')
{
	echo "<script>alert('BODEGA HA SIDO ACTUALIZADA $bodt YA NO ESTA DISPONIBLE')</script>";
	echo "<script>location.replace('traslados.php?ori=$ori')</script>";
}
?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<input type="hidden" name="i" id="i" value='<?php echo $i;?>'>
	<script>
	$(document).ready(function(){
$("#form").hide();
$("#btndes").hide();
$(".detalle").hide();
$("#form1").hide();
	$("#formf").hide();
if($("#io").val()==1)
{
	$("#form").show();
	$("#btndes").show();
}
if($("#i").val()==1)
{
	
	$(".detalle").show();
}else if($("#i").val()==2)
{
	$("#form1").show();
	$("#barra").focus();
	$("#formf").show();
}

	
	});

	function activar()
	{
		location.replace('addestino.php');
	}
	function activar1()
	{
		location.replace('addorigen.php');
	}

	function cerrar()
	{
		$(".detalle").hide(500);
	}

	
	function cambiar()
	{
		$("#op").val("1");
	}
	function enviar()
	{
		document.form.submit(true);
	}

	function enviarbtn()
	{
		$("#codb").prop("required",true);
		$("#nomb").prop("required",true);
		$("#op").val("2");
		if($("#bodega_destino").val()!='' && $("#codb").val()!=$("#bodega_destino").val())
		{
			if(confirm('SEGURO DESEAS CAMBIAR LA BODEGA DE DESTINO, SE CAMBIARA PARA TODO EL TRASLADO'))
			{

			}else
			{
				$("#form").submit(false);
				location.replace('traslados.php?bcod='+$("#bodega_destino").val());
				
			}
		}else
		{
			//dfgdfgdfg cancelar
		}
	}

	function enviarform()
	{
		document.bodega.submit(true);
	}
	function enviareg()
	{
		
		
	}
	function editar()
	{
		location.replace('cambiar.php');
	}
	function cop1()
	{
		$("#op1").val('1');

	}
	function enviarop()
	{
		$("#op1").val('2');
	}
	function enviar3()
	{
		document.formorigen.submit(true);
	}
	function cancel()
	{
		if(confirm("SEGURO DESEA CANCELAR EL TRASLADO SE ELIMINARA LO QUE SE HA DIGITADO"))
		{
			$("#formcancel").submit(true);
		}else
		{
			

		}
	}

	function finaltras()
	{
		//alert($("#numeros").val());
		if(confirm("SEGURO DESEA FINALIZAR EL TRASLADO"))
		{
			if($("#vb").val()==1)
			{
				if($("#numeros").val()>1)
				{
					alert("ERROR: EXISTE MAS DE UNA BODEGA DE ORIGEN");
			location.replace('traslados.php');
					$("#formf").submit(false);

				}else
				{
					$("#formf").submit();
				}

				
			}else
			{
				if($("#comen").val()=='')
				{
					alert("INGRESA EL COMENTARIO");
					$("#formf").submit(false);

					$("#comen").focus();
				}else
				{
					if($("#numeros").val()>1)
				{
					alert("ERROR: EXISTE MAS DE UNA BODEGA DE ORIGEN");
			location.replace('traslados.php');
				}else
				{
					//alert('MINCHO PROGRAMADOR');
					$("#formf").submit();
				}
					//$("#formf").submit(true);

				} 
			}
			
		}else
		{
			location.replace('traslados.php');
		}
	}
	
	
	</script>

</head>
<body>
<input type="hidden" name="io" id="io" value='<?php echo "$io";?>'>
<input type="hidden" name="bodega_destino" id="bodega_destino" value='<?php echo "$bodega_destino";?>'>


<button class="boton4" onclick="activar1()" style="background-color: blue; color: white;">BODEGA ORIGEN</button>
<form method="POST" name="formorigen" action="origenpost.php" autocomplete="off">
	<input type="text" name="origen" id='origen' class="text" style="width: 20%; margin-left: 0.1%;" placeholder="BODEGA ORIGEN" onkeyup="this.value=this.value.toUpperCase()" onkeypress="cop1()" onchange="enviar3()" value='<?php echo "$ori";?>'>

	<input type="text" name="nom_origen" class="text" style="width: 50%;" placeholder="NOMBRE BODEGA" value='<?php echo "$nori";?>'>

	<input type="hidden" name="op1" id="op1">
	<input type="submit" name="btn" value="SIGUIENTE" class="boton2" onclick="enviarop()">
	
</form>
<br>
<button onclick="activar()" class="boton4" id="btndes" style="margin-left: 4%;">DESTINO</button>
<form method="POST" name="form" id="form" action="buscarbodega.php" style="margin-left: 4%;">

	<input type="text" name="codb" id="codb" onchange="enviar()" onkeypress="cambiar()" class="text" style="width: 20% " placeholder="BODEGA DESTINO" value='<?php echo $bodt?>' onkeyup="this.value=this.value.toUpperCase()">
	<input type="text" name="nomb" id="nommb" class="text" style="width: 50%" placeholder="NOMBRE BODEGA" value='<?php echo $nomt?>'>
	<input type="hidden" name="op" id="op">
	<input type="submit" name="btn" class="boton2" value="SIGUIENTE." onclick="enviarbtn()">
	
</form>

<hr><br>

<form method="POST" name="form1" id="form1" action="agregarfardo.php">
	
		CODIGO DE BARRA:<br>
	<input type="text" name="barra" id="barra" class="text" style="width: 40%;">
	<input type="hidden" name="bodeg" value='<?php echo $bode;?>'>
	<input type="hidden" name="bodori" value='<?php echo $ori;?>'>
	<input type="submit" name="btn" class="boton2" value="AGREGAR">
</form>
<?php
	echo "";
	if($tf>0)
	{
?>

<form method="POST" style="float: right; margin-right: 0.5%;" name="formcancel" action="cancelart.php" id="formcancel">
	<input type="text" name="catext" value="CANCELAR TRASLADO" class="boton3" onclick="cancel()" style="padding-top: 2%; padding-bottom: 5%; margin-right: 15%; padding-top: 5%; background-color: red;" readonly>
</form>

<form method="POST" style="float: right; margin-right: 0.7%;" name="formpendiente" action="pendientet.php" id='formpendiente'>
	<input type="submit" name="btne" value="DEJAR PENDIENTE" class="boton3"  style="padding-top: 7%; padding-bottom: 7%; background-color: green; padding-left: 3.5%;" >
</form>




<?php
}
$doc=$_SESSION['doc'];
$hoy=date("Y-m-d");
if($doc!="")
{
	$q=$conexion2->query("select * from dbo.traslado where sessiones='$doc' and usuario='$us'	order by id desc")or die($conexion2->error);
	$nq=$q->rowCount();
		$cva=$conexion2->query("select count(origen) as origen from traslado where sessiones='$doc' and usuario='$us' group by origen")or die($conexion1->error());
		$numero=0;
		while($fcva=$cva->FETCH(PDO::FETCH_ASSOC))
		{
			$numero++;
		}
		$fcva=$cva->FETCH(PDO::FETCH_ASSOC);
		echo "<input type='hidden' name='numeros' id='numeros' value='$numero'>";
	

	if($nq==0)
	{

	}else
	{


		echo "<table border='1' class='tabla' cellpadding='10'>
<tr>
<td colspan='8' style='padding-bottom:-25%;'>
";
echo "


<form method='POST' name='formf' id='formf' action='finalt.php' style='float: left; margin-right:3%;'>";
$ori=$_SESSION['origen'];
$oriv=substr($ori, 0);
$validacion="$oriv[0]$oriv[1]";
$origenes=$ori;
	if($validacion=="SM" and $ori!='CA00')
	{
		echo "<input type='text' class='text' style='width:35%; padding-top:0.5%; padding-bottom:2.5%;' name='comentario' id='comen' placeholder='COMENTARIO'>";
		echo "<input type='hidden' name='vb' id='vb' value='1'>";
	}else
	{
		echo "<input type='text' class='text' style='width:35%; padding-top:0.5%; padding-bottom:2.5%;' name='comentario' id='comen' placeholder='COMENTARIO'  required>";
		echo "<input type='hidden' name='vb' id='vb' value='2'>";
	}
	
	 echo "FECHA
	<input type='date' name='fecha' class='text' value='$hoy' min='$hoy' style='width: 35%;' onkeypress='finaltras1()'>
	<input type='hidden' name='codboedega' value='$bode'>
	<input type='txt' name='btn' class='btnfinal' onclick='finaltras()' value='FINALIZAR' style='padding-top:1.5%; padding-bottom:1.5%; float:right; margin-bottom:-2%; margin-right:-19%; cursor:pointer;' readonly></form>
	
	
";

echo "</td>
</tr>


		<tr><td colspan='8'>FARDOS AGREGADOS: <u>$tf</u></td></tr>
		<tr style='background-color:rgb(133,133,137,0.7); color:white;'>
			<td>CODIGO DE BARRA</td>
			<td>CODIGO</td>
			<td>NOMBRE</td>
			<td>CANTIDAD</td>
			<td>OBSERVACION</td>
			<td>ORIGEN</td>
			<td>DESTINO <button style='float:right;' onclick='editar()' class='boton3'>EDITAR</button></td>
			<td width='5%' style='text-align:center;'>#</td>
		</tr>";
		$total=0;
		while($fq=$q->FETCH(PDO::FETCH_ASSOC))
		{
			$idre=$fq['registro'];
			$bodega_registro=$fq['origen'];
			$query=$conexion2->query("select * from dbo.registro where id_registro='$idre'")or die($conexion2->error);
			$fquery=$query->FETCH(PDO::FETCH_ASSOC);
			$codi=$fquery['codigo'];
			$obs=$fquery['observacion'];
			$cve=$conexion2->query("select count(*) as cant from traslado where sessiones='$doc' and articulo='$codi' and usuario='$us'")or die($conexion2->error());
			$fcve=$cve->FETCH(PDO::FETCH_ASSOC);
			$cantii=$fcve['cant'];
			$k=$conexion1->query("select * from consny.ARTICULO WHERE consny.ARTICULO.ARTICULO='$codi'")or die($conexion1->error());
			$fk=$k->FETCH(PDO::FETCH_ASSOC);
$nom=$fk['DESCRIPCION'];
			//$nom=$fquery['subcategoria'];
			$origen=$fquery['bodega'];
			$destino=$fq['destino'];
			$cbarra=$fquery['barra'];
			$idt=$fq['id'];
			if($fq['origen'] !=$origen)
			{
				$origen=$_SESSION['origen'];
			}
			if($origenes!=$bodega_registro)
			{
				echo "<tr style='background-color:red; color:white;'>";
			}else
			{
				echo "<tr>";
			}
			echo "
			<td>$cbarra</td>
			<td>$codi</td>
			<td>$nom</td>
			<td>$cantii</td>
			<td>$obs</td>
			<td><form method='POST' name='registro' id='registro' action='bodegareg.php'>
			<input type='hidden' name='idt' value='$idt'>
			<select  class='text'  name='nuevab' style='width:90%;' onchange='enviareg()'>
			<option>$origen</option>";
$qu=$conexion1->query("select * from consny.BODEGA WHERE BODEGA !='$origen' and BODEGA LIKE 'S%' and nombre not like'%(N)%'")or die($conexion1->error);
while($fqu=$qu->FETCH(PDO::FETCH_ASSOC))
{
	$bo=$fqu['BODEGA'];
	//echo "<option>$bo</option>";-->para poder cambiar el origen

}
echo "</select>
<input type='submit' value='Aplicar' class='boton2'>
</form>
			</td>
			<td>$destino</td>
			<td>
			<a href='eliminar.php?id=$idt'>
			<img src='eliminar.png' width='90%' title='QUITAR'>
			</a>
			</td>
		</tr>";
		$total++;
		}
		echo "<tr style='background-color:rgb(133,133,137,0.7); color:white;'>
		<td colspan='3'>TOTAL</td>
		<td>$total</td>
		<td colspan='4'></td>

		</tr></table>";
			
	}
}

?>


</body>
</html>




