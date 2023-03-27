<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			//$("#form1").hide();
			$("#form2").hide();
			$("#form3").hide();
			$("#btnart").hide();
			if($("#a").val()==1)
			{
				$("#form2").show();
				$("#btnart").show();
			}
		});
		function arti()
		{
			location.replace('articulobarrav.php');
		}
		function cambiar()
		{
			$("#op").val('1');
		}
		function enviar()
		{
			
			document.form2.submit();
		}

	</script>
</head>
<body>
<?php
include("conexion.php");
$barra=$_GET['b'];
if($_SESSION['barranuevo']!="")
{
	$a=1;
	$barra=$_SESSION['barranuevo'];
}
$c=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->eror());
$n=$c->rowCount();
$fc=$c->FETCH(PDO::FETCH_ASSOC);
$bode=$fc['bodega'];
//echo "<script>alert('$bode $barra')</script>";
$y=$_GET['y'];
if($n==0)
{

}else
{
	if($y=="")
	{
		echo "<script>alert('verificar codigo de barra')</script>";
	echo "<script>location.replace('cambiarbarra.php')</script>";
	}
	
}




$art=$_GET['art'];
$k=$conexion1->query("select * from consny.ARTICULO where consny.ARTICULO.ARTICULO='$art'")or die($conexion1->error());
$fk=$k->FETCH(PDO::FETCH_ASSOC);
$cod=$fk['ARTICULO'];
$nom=$fk['DESCRIPCION'];
?>
<input type="hidden" name="a" id="a" value='<?php echo "$a";?>'>

<form method="POST" name="form1" id="form1">
	<select class="text" style="width: 50%;" name="bodega" required>
		
		<?php

		if($bode!="")
		{
			$cb=$conexion1->query("select * from consny.bodega where bodega='$bode' and bodega like 'S%' and nombre not like '%(N)%'")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bo=$fcb['BODEGA'];
			$no=$fcb['NOMBRE'];
			$bode="$bo: $no";
			echo "<option value='$bo'>$bode</option>";
		}else
		{
			echo "<option value=''>BODEGA</option>";
		}

		$c=$conexion1->query("select * from consny.bodega where bodega like 'S%' and nombre not like '%(N)%'")or die($conexion1->error());
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$bodega=$f['BODEGA'];
			$nombre=$f['NOMBRE'];
			echo "<option value='$bodega'>$bodega: $nombre</option>";
		}
		$hoy=date("Y-m-d");
		?>
	</select>
	FECHA: <input type="date" name="fecha" value='<?php echo "$hoy";?>' class='text' style='width: 10%;'>
	<input type="text" name="barra" value='<?php echo "$barra";?>' style='width: 10%;'>
	<input type="submit" name="btn" value="SIGUIENTE" class="boton2">
</form>


<button id="btnart" onclick="arti()">ARTICULO</button>
<form method="POST" name="form2" id="form2">

<input type="text" name="cod" class="text" style="width: 20%; padding-top: 0.5%; padding-bottom: 0.5%;" value='<?php echo "$cod";?>' onkeypress="cambiar() " onchange="enviar()">

<input type="text" name="nom" class="text" style="width: 44.5%; padding-top: 0.5%; padding-bottom: 0.5%;" value='<?php echo "$nom";?>'>

<input type="hidden" name="op" id="op">
<input type="number" name="peso" placeholder="PESO" class="text" style="width: 10%;">
<input type="submit" name="btn" value="FINALIZAR" class="btnfinal" style="padding-top: 0.6%; padding-bottom: 0.6%;">
</form>

<br><br>
<form method="POST" id="form3" name="form3">
	<input type="text" name="peso" placeholder="peso" style="width: 30%;" class="text">

	<input type="text" name="cantidad" placeholder="cantidad" style="width: 30%;" class="text">
	<input type="submit" name="btn" value="FINALIZAR" class="btnfinal" style="padding-bottom: 0.5%; padding-top: 0.5%;">
</form>

</body>
</html>


<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='SIGUIENTE')
	{
		if($_SESSION['barranuevo']=='')

		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		if($_SESSION['barranuevo']=='')
		{
		$quey=$conexion2->query("select max(session) as n from registro where fecha_documento='$fecha' and tipo='CD'")or die($conexion2->error());
		$fquey=$quey->FETCH(PDO::FETCH_ASSOC);
		$n=$fquey['n'] + 1;
		$conexion2->query("insert into registro(fecha_documento,barra,session,estado,paquete,usuario,bodega,tipo,contenedor) values('$fecha','$barra','$n','0','$paquete','$usuario','$bodega','CD','RESTAU-BARRA')")or die($conexion2->error());
		}else
		{
		$conexion2->query("update registro set bodega='$bodega',codigo='$cod' where barra='$barra'")or die($conexion2->error());
		}
	$_SESSION['barranuevo']=$barra;
	echo "<script>location.replace('nuevobarravacio.php?y=1')</script>";
	}
	if($op==1)
	{
		//echo "<script>alert('$bo - $barra')</script>";
		$b=str_replace(" ", "%", $cod);
	$c=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.ARTICULO.ACTIVO,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO and consny.ARTICULO.ACTIVO='S' and consny.EXISTENCIA_BODEGA.BODEGA='$bo' WHERE consny.ARTICULO.ARTICULO='$b' ")or die($conexion1->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO NINGUN ARTICULO DISPONIBLE DE: $cod')</script>";
	}else
	{
		echo "<script>location.replace('nuevobarravacio.php?art=$cod&&y=1')</script>";

	}


	}
	if($btn=="FINALIZAR")
	{
		$barra=$_SESSION['barranuevo'];
		$conexion2->query("update registro set cantidad='1',peso='$peso',codigo='$cod' where barra='$barra'")or die($conexion2->error());
		echo "<script>alert('GUARDADO CORRECTAMENTE PUDE SEGUIR USANDO EL CODIGO DE BARRA: $barra')</script>";
		
	$_SESSION['barranuevo']='';
		echo "<script>location.replace('cambiarbarra.php')</script>";
	}


	
}



//codigo nuevo barra existe

/*
$hoy=date("Y-m-d");
	$con=$conexion2->query("select max(session) as num from registro where fecha_documento='$hoy' and tipo='CD'")or die($conexion2->error());
	$o="C";
	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
	$numse=$fcon['num'] + 1;
	$numses=str_pad($numse,3,"0",STR_PAD_LEFT);
	$letra=chr(rand(ord("A"), ord("Z")));
	$e=substr($hoy,0);
	$a="$e[2]$e[3]";
	$m="$e[5]$e[6]";
	$d="$e[8]$e[9]";
	$barra="$letra$a$o$d$numses$m";
	$veri=1;
	while($veri!=0)
	{
		$q=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
		$nq=$q->rowCount();
		if($nq!=0)
		{
			$numse=$numse+1;
			$numses=str_pad($numse,3,"0",STR_PAD_LEFT);
			$letra=chr(rand(ord("A"), ord("Z")));
			$barra="$letra$a$o$d$numses$m";
			$veri=1;
		}else
		{
			$veri=0;
		}
		
	}*/
?>