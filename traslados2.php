<!DOCTYPE html>
<html>
<head>

<?php
//ver las autorizaciones en las otras paginaas hechas solo index y trasladoss
error_reporting(0);
include("conexion.php");
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
	$k=$conexion2->query("select top 1 * from traslado where sessiones='$docu' order by id desc
")or die($conexion2->error);
	$knum=$conexion2->query("select count(*) as total from traslado where sessiones='$docu'")or die($conexion2->error);
	$fknum=$knum->FETCH(PDO::FETCH_ASSOC);
	$tf=$fknum['total'];
	$fk=$k->FETCH(PDO::FETCH_ASSOC);
	$bode1=$fk['destino'];
	if($bode1!="")
	{
		$i=2;

	}else
	{
		
	}
	
}else
{
	
}

$con=$conexion1->query("select * from consny.bodega where bodega='$bode'")or die($conexion1->error);
$fcon=$con->FETCH(PDO::FETCH_ASSOC);
$bodt=$fcon['BODEGA'];
$nomt=$fcon['NOMBRE'];
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
	$("#form1").show(500);
	$("#barra").focus();
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
		document.form.submit();
	}

	function enviarbtn()
	{
		$("#codb").prop("required",true);
		$("#nomb").prop("required",true);
		$("#op").val("2");
	}

	function enviarform()
	{
		document.bodega.submit();
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
		document.formorigen.submit();
	}
	</script>

</head>
<body>
<input type="hidden" name="io" id="io" value='<?php echo "$io";?>'>

<button class="boton4" onclick="activar1()">ORIGEN</button>
<form method="POST" name="formorigen" action="origenpost.php">
	<input type="text" name="origen" class="text" style="width: 20%;" placeholder="BODEGA ORIGEN" onkeyup="this.value=this.value.toUpperCase()" onkeypress="cop1()" onchange="enviar3()" value='<?php echo "$ori";?>'>

	<input type="text" name="nom_origen" class="text" style="width: 57%;" placeholder="NOMBRE BODEGA" value='<?php echo "$nori";?>'>

	<input type="hidden" name="op1" id="op1">
	<input type="submit" name="btn" value="SIGUIENTE" class="boton2" onclick="enviarop()">
	
</form>
<br>
<button onclick="activar()" class="boton4" id="btndes" style="margin-left: 4%;">DESTINO</button>
<form method="POST" name="form" id="form" action="buscarbodega.php" style="margin-left: 4%;">

	<input type="text" name="codb" id="codb" onchange="enviar()" onkeypress="cambiar()" class="text" style="width: 20% " placeholder="BODEGA DESTINO" value='<?php echo $bodt?>' onkeyup="this.value=this.value.toUpperCase()">
	<input type="text" name="nomb" id="nommb" class="text" style="width: 60%" placeholder="NOMBRE BODEGA" value='<?php echo $nomt?>'>
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
<?php
	echo "Fardos agregados: <u>$tf</u>";
?>
</form>
<hr>

<?php
$doc=$_SESSION['doc'];
$hoy=date("Y-m-d");
if($doc!="")
{
	$q=$conexion2->query("select * from dbo.traslado where sessiones='$doc'	order by id desc")or die($conexion2->error);
	$nq=$q->rowCount();
	if($nq==0)
	{

	}else
	{
		echo "<form method='POST' action='finalt.php'>
	FECHA TRASLADO:
	<input type='date' name='fecha' class='text' value='$hoy' style='width: 20%;'>
	<input type='hidden' name='codboedega' value='$bode'>
	<input type='submit' name='btn' class='btnfinal' value='FINALIZAR' style='padding-top:0.5%; padding-bottom:0.5%;'>
	
</form>";

		echo "<table border='1' class='tabla' cellpadding='10'>
		<tr style='background-color:rgb(133,133,137,0.7); color:white;'>
			<td>CODIGO DE BARRA</td>
			<td>CODIGO</td>
			<td>NOMBRE</td>
			<td>CANTIDAD</td>
			<td>ORIGEN</td>
			<td>DESTINO <button style='float:right;' onclick='editar()' class='boton3'>EDITAR</button></td>
			<td width='5%' style='text-align:center;'>#</td>
		</tr>";
		$total=0;
		while($fq=$q->FETCH(PDO::FETCH_ASSOC))
		{
			$idre=$fq['registro'];
			$query=$conexion2->query("select * from dbo.registro where id_registro='$idre'")or die($conexion2->error);
			$fquery=$query->FETCH(PDO::FETCH_ASSOC);
			$codi=$fquery['codigo'];
			$cve=$conexion2->query("select count(*) as cant from traslado where sessiones='$doc' and articulo='$codi'")or die($conexion2->error());
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
				$origen=$fq['origen'];
			}
			echo "<tr>
			<td>$cbarra</td>
			<td>$codi</td>
			<td>$nom</td>
			<td>$cantii</td>
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
		<td colspan='3'></td>

		</tr></table>";
			
	}
}

?>


</body>
</html>




