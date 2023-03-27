<!DOCTYPE html>
<html>
<head>
	
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<script>
		$(document).ready(function(){
			$("#form1").hide();
			if($("#i").val()==1)
			{
				$("#form1").show(500);
				$("#art").focus();
			}else if($("#i").val()==2)
			{
				$("#form1").show();
				$("#cant").focus();
			}
		});
		function cambio()
		{
			$("#op").val('1');
		}

		function envio()
		{
			$("#form2").submit();
		}
		function enviar()
		{
			$("#op").val('2');
			$("#form2").submit();
		}
		function envia()
		{
			if(confirm('SEGURO DESEA FINALIZAR'))
			{
				//$("#formfinal").submit();
				document.formfinal.submit();
			}else
			{
				
			}
			
		}
	</script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
$session=$_SESSION['p_averia'];
	$usu=$_SESSION['usuario'];
	//echo "<script>alert('$session')</script>";
	if($usu=='HARIAS' or $usu=='harias')
	{
		$tipo=1;
		//echo "<script>alert('$tipo')</script>";
	}else
	{
		$tipo=$_SESSION['tipo'];
	}
	//echo "<script>alert('$tipo')</script>";
	if($tipo==2)
	{
		echo "<script>alert('NO TIENES AUTORIZACION')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}

if($_SESSION['p_averia']!='')
{
	$session=$_SESSION['p_averia'];
	$usu=$_SESSION['usuario'];
	$c=$conexion2->query("select top 1 * from averia where tipo='P' and usuario='$usu' and sessiones='$session'")or die($conexion2->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$core=$f['corelativo'];
	$hoy=$f['fecha_documento'];
	$i=1;
}else
{
	$hoy=date("Y-m-d");
}
if($_GET['art']!='')
{
	
	$art=$_GET['art'];
	$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
	$nca=$ca->rowCount();
	if($nca==0)
	{
		$i=1;
	}else
	{
		$i=2;
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$articulo=$fca['ARTICULO'];
		$desc=$fca['DESCRIPCION'];
	}
}
?>
<form method="POST">
	<input type="hidden" name="i" id='i' value='<?php echo "$i";?>'>
<input type="text" name="corelativo" class="text" style="width: 40%;" placeholder="CORELATIVO" value='<?php if($core!=''){echo $core;}else {echo "0000000";}?>'>

<input type="date" name="fecha" class="text" style="width: 40%;" value='<?php echo "$hoy";?>' ><br><br>

<input type="text" name="und" class="text" style="width: 40%; margin-left: 4%;" placeholder="UNIDADES" value='<?php echo "".$f['unidades']."";?>'>

<input type="text" name="auditor" class="text" style="width: 40%;" placeholder="AUDITOR" value='<?php echo "".$f['auditor']."";?>'><br><br>

<input type="text" name="encargado" class="text" style="width: 40%; margin-left: 4%;" placeholder="ENCARGADO" value='<?php echo "".$f['encargado']."";?>'>

<select name="bodega" class="text" style="width: 40%;" required>

<?php
if($f['tienda']!='')
{
	$bodes=$f['tienda'];
	
	$cb=$conexion1->query("select * from consny.bodega where bodega='$bodes'")or die($conexion1->error());
	$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
	$bo=$fcb['BODEGA'];
	$nomb=$fcb['NOMBRE'];
	echo "<option value='$bo'>$nomb ($bo)</option>";
}else
{
	echo "<option value=''>TIENDA</option>";
}

$c=$conexion1->query("select * from consny.bodega where bodega not like 'SM%' and nombre not like '%%(N)%' order by nombre")or die($conexion1->error());
while($fb=$c->FETCH(PDO::FETCH_ASSOC))
{
	$nom=$fb['NOMBRE'];
	$bod=$fb['BODEGA'];
	echo "<option value='$bod'>$nom ($bod)</option>";
}

?>
</select><br><br>
<input type="text" name="obs" class="text" placeholder="OBSERVACIONES" style="margin-left: 4%; width: 60%;" value='<?php echo "".$f['observacion']."";?>'>



<input type="submit" name="btn" value="SIGUIENTE" class="boton2" style="margin-right: 13%;"> 

</form>
<hr>
<div id="form1">
	<a href="add_averia.php">
<button class="boton4" style="margin-left: 4%;">ARTICULOS</button>
</a>
<?php

?>
<form method="POST" name="form2" id="form2">
<input type="text" name="arti" id="art" class="text" style="width: 13%; margin-left: 4%;" placeholder="ARTICULO" value='<?php echo "$articulo";?>' onkeypress='cambio()' onchange ='envio()'>	
<input type="text" name="des" class="text" style="width: 42%;" placeholder="DESCRIPCION" value='<?php echo "$desc";?>'>
<input type="number" name="cant" id="cant" placeholder="CANTIDAD" class="text" style="width: 12%;">
<input type="submit" name="btn" value="AGREGAR" class="boton2" onclick="enviar()">
<input type="hidden" name="op" id="op" >
</form>
</div>

<?php
$session=$_SESSION['p_averia'];
$usuario=$_SESSION['usuario'];
$query=$conexion2->query("select articulo,cantidad,corelativo,id from averia where usuario='$usuario' and tipo='p' and sessiones='$session' and articulo is not null order by id desc")or die($conexion2->error());
$nquery=$query->rowCount();
if($nquery!=0)
{
	echo "<hr><table border='1' cellpadding='10' class='tabla' style='margin-left:3%;'>";
	echo '<tr>
		<td colspan="3">
		<form method="POST" name="formfinal"  action="averia_fin.php" id="formfinal">
		<input type="hidden" name="boton" >
		</form>
		<input type="button" value="FINALIZAR" class="btnfinal" style="float:right; margin-right:0.5%; padding-bottom:0.5%; padding-top:0.5%; margin-bottom:-0.5%;" onclick="envia()">
		</td>
	</tr>';
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>QUITAR</td>
	</tr>";
	$t=0;
	while($fquery=$query->FETCH(PDO::FETCH_ASSOC))
	{
		$artt=$fquery['articulo'];
		$cant=$fquery['cantidad'];
		$id=$fquery['id'];
		$ca=$conexion1->query("select * from consny.articulo where articulo='$artt'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		echo "<tr>
		<td>".$fca['ARTICULO']."</td>
		<td>".$fca['DESCRIPCION']."</td>
		<td>$cant</td>
		<td><a href='eliminare_averia.php?id=$id'>quitar</a></td>
	</tr>";
	$t=$t + $cant;
	}
	echo "<tr>
	<td colspan='2'>TOTAL</td>
	<td>$t</td>
	<td></td>

	</tr>";
}	
if($_POST)
{
	extract($_REQUEST);
	if($btn=='SIGUIENTE')
	{
		$usuario=$_SESSION['usuario'];
		$c=$conexion2->query("select * from averia where corelativo='$corelativo' and asignado='$usuario' and estado='0'")or die($conexion2->error());
		$num=$c->rowCount();
		if($num==0)
		{
			echo "<script>alert('error: CORELATIVO NO SE TE HA ASIGNADO')</script>";
			echo "<script>location.replace('averia.php')</script>";
		}else
		{
		$cv=$conexion2->query("select * from averia where corelativo='$corelativo' and usuario='$usuario' and estado is null and tipo='p'");
		$ncv=$cv->rowCount();
		if($ncv==0)
		{
			//echo "<script>alert('00')</script>";
			$_SESSION['p_averia']='';
		}else
		{
			$fcv=$cv->FETCH(PDO::FETCH_ASSOC);
			$s=$fcv['sessiones'];
			echo "<script>location.replace('averia.php')</script>";
			$_SESSION['p_averia']=$fcv['sessiones'];
			echo "<script>location.replace('averia.php')</script>";
		}




	if($_SESSION['p_averia']=='')
	{


	$c=$conexion2->query("select max(sessiones) as sessiones  from averia where tipo='P'")or die($conexion1->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$sessiones=$f['sessiones'] + 1;
	$n=1;
	while($n!=0)
	{
		$c=$conexion2->query("select * from averia where tipo='P' and sessiones='$sessiones'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n!=0)
		{
			$sessiones++;
			$n=1;
		}else
		{
			$n=0;
		}
	}
		$_SESSION['p_averia']=$sessiones;
		$usu=$_SESSION['usuario'];
		$paque=$_SESSION['paquete'];
		$conexion2->query("insert into averia(corelativo,unidades,fecha_documento,auditor,encargado,tienda,observacion,fecha_ingreso,usuario,paquete,tipo,sessiones) values('$corelativo','$und','$fecha','$auditor','$encargado','$bodega','$obs',getdate(),'$usu','$paque','P','$sessiones')")or die($conexion2->error());
		echo "<script>location.replace('averia.php?o=1')</script>";
	}else
	{
		//para update
	}
}

}

if($op==1)
{ 
	$c=$conexion1->query("select * from consny.articulo where articulo='$arti'")or die($conexion1->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO ARTICULO: $arti')</script>";
		echo "<script>location.replace('averia.php')</script>";
	}else
	{
		echo "<script>location.replace('averia.php?art=$arti')</script>";
	}
}else if($op==2)
{
	
	$usuario=$_SESSION['usuario'];
	$paquete=$_SESSION['paquete'];
	$session=$_SESSION['p_averia'];
	$q=$conexion2->query("select * from averia where sessiones='$session' and usuario='$usuario'")or die($conexion2->error());
	$nq=$q->rowCount();
	if($nq==0)
	{
		echo "<script>alert('SE PRODUJO UN FATAL ERROR COMUNICARLO AL DEPARTAMENTO DE INFORMATICA')</script>";
		echo "<script>location.replace('averia.php')</script>";
	}else
	{
		$fq=$q->FETCH(PDO::FETCH_ASSOC);
		$corelativo=$fq['corelativo'];

	$conexion2->query("insert into averia(corelativo,tipo,usuario,paquete,fecha_ingreso,articulo,cantidad,sessiones) values('$corelativo','P','$usuario','$paquete',getdate(),'$arti','$cant','$session')")or die($conexion2->error());
	echo "<script>location.replace('averia.php')</script>";
	}
}

}
?>
</body>
</html>