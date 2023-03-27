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
			}else if($("#i").val()==2)
			{
				$("#form1").show(500);
				$("#peso").focus();
			}else
			{
				$("#form1").hide();
			}
		});

		function produce()
		{
			//alert('r');
			location.replace('trabajo_produce.php?fecha='+$("#fecha").val()+'&&mesa='+$("#mesa").val()+'&&obs='+$("#obs").val());
		}

		function articulo()
		{
			location.replace('trabajo_articulos.php?deposito='+$("#deposito").val()+'&&peso='+$("#peso").val()+'&&obs='+$("#obs").val()+'&&cantidad='+$("#cantidad").val());
		}
		function borrar()
		{
			//alert('dfdf');
			if(confirm('SEGURO DESEA CANCELAR, SE ELIMINARA LO INGRESADO'))
			{
				$("#btn").val('CANCELAR');
				$("#cancelar").submit();
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
$deposito=$_GET['deposito'];
$peso=$_GET['peso'];
$ta=$_GET['ta'];

if($_GET['fecha']!='')
{
	$fecha=$_GET['fecha'];
}else
{
	$fecha=date("Y-m-d");
}
$mesa=$_GET['mesa'];
$obs=$_GET['obs'];
$t=$_GET['t'];
$cantidad=$_GET['cantidad'];
if($_SESSION['trabajo']!='')
{

	$trabajo=$_SESSION['trabajo'];
	$usu=$_SESSION['usuario'];
	$q=$conexion2->query("select * from trabajo where sessiones='$trabajo' and usuario='$usu'")or die($conexion2->error());
	$fq=$q->FETCH(PDO::FETCH_ASSOC);
	$mesa=$fq['mesa'];
	//$obs=$fq['observacion'];
	$fecha=$fq['fecha'];
	$t=$fq['producido'];
	$i=1;
}else
{
	$i=0;
}
if($ta!='')
{
	$i=2;
}
?>
<input type="hidden" name="i" id="i" value='<?php echo "$i";?>'>
<form method="POST" name="form" id="form">
<input type="date" name="fecha" class="text" style="width: 20%;" id="fecha" value='<?php echo "$fecha";?>'>

<input type="text" name="producido" ondblclick="produce()" placeholder="PRODUCIDO POR" class="text" style="width: 35%;" value='<?php echo "$t";?>' title='<?php echo "$t";?>'>

<input type="text" name="mesa" placeholder="MESA" class="text" style="width: 10%;" id="mesa" value='<?php echo "$mesa";?>'>


<input type="submit" name="btn" value="SIGUIENTE" class="boton2">
</form>
<hr>
<form method="POST" name="form1" id="form1">
<select name="deposito" id="deposito" class="text" style="width: 10%;" required>
<?php
if($deposito=='')
{
	echo "<option value=''>DEPOSITO</option>";
}else
{
	echo "<option>$deposito</option>";
}
?>
	
<option>BARILES</option>
<option>BOLSAS</option>
<option>CAJAS</option>
</select>

<input type="text" name="articulos" ondblclick="articulo()" placeholder="ARTICULOS" class="text" style="width: 35%;" value='<?php echo "$ta";?>'>
<input type="number" name="peso" id="peso" placeholder="PESO" class="text" style="width: 10%;" value='<?php echo "$peso";?>' step="any" lang="en" min="0" >
<input type="number" name="cantidad" id="cantidad" placeholder="CANTIDAD" class="text" style="width: 10%;" value='<?php echo "$cantidad";?>' step="any" lang="en" min="0" >
<input type="text" name="obs" class="text" placeholder="OBSERVACION" style="width: 20%;" id="obs" value='<?php echo "$obs";?>'>
<input type="submit" name="btn" value="AGREGAR" class="boton2">
</form>
<hr>
<?php
$c=$conexion2->query("select * from trabajo where sessiones='$trabajo' and usuario='$usu' and articulos is not null order by id desc")or die($conexion2->error());
$n=$c->rowCount();
if($n!=0)
{
	echo "<table border='1' class='tabla' cellpadding='10'>";
	echo "<tr>
	<td colspan='6'>

	<form method='POST'>
		<input type='submit' name='btn' value='FINALIZAR' class='btnfinal' style='padding:0.5%; margin-bottom:0.3%; float:right;'>
	</form>
	<form method='POST' name='cancelar' id='cancelar'>
		<input type='hidden' name='btn' id='btn' value=''>
	</form>
	<button onclick='borrar()' class='btnfinal'  style='padding:0.5%; margin-bottom:0.3%; float:right; background-color:red; margin-right:0.5%;'>CANCELAR</button>

	<form method='POST'>
		<input type='submit' name='btn' value='DEJAR PENDIENTE' class='btnfinal' style='padding:0.5%; margin-bottom:0.3%; float:right; background-color:#18db44; margin-right:0.5%;'>
	</form>
	</td>
	</tr>";
	echo "<tr>
		<td>DEPOSITO</td>
		<td>ARTICULOS</td>
		<td>PESO</td>
		<td>CANTIDAD</td>
		<td>TOTAL</td>
		<td>OBSERVACION</td>
		<td>QUITAR</td>
	</tr>";
	$tpeso=0;
	$multi=0;
	$t=0;
	$tcantidad=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$id=$f['id'];
		$multi=$f['peso'] * $f['cantidad'];
		$t=$t + $multi;
		$tpeso=$tpeso + $f['peso'];
		$tcantidad=$tcantidad + $f['cantidad'];
		echo "<tr>
		<td>".$f['deposito']."</td>
		<td>".$f['articulos']."</td>
		<td>".$f['peso']."</td>
		<td>".$f['cantidad']."</td>
		<td>$multi</td>
		<td>".$f['observacion']."</td>
		<td><a href='elimina_trabajo.php?id=$id' style='text-decoration:none; color:black;'>QUITAR</a></td>
	</tr>";
	}
	echo "<tr>
	<td colspan='2'>TOTALES</td>
	<td>$tpeso</td>
	<td>$tcantidad</td>
	<td>$t</td>
	</tr>";
}


if($_POST)
{
extract($_REQUEST);

if($btn=='SIGUIENTE')
{


if($_SESSION['trabajo']=='')
{
	$c=$conexion2->query("select max(sessiones) as sessiones from trabajo")or die($conexion2->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$n=1;
	$sessiones=1  + $f['sessiones'];
	while($n!=1)
	{
		
		$c=$conexion2->query("select * from trabajo where sessiones='$sessiones'")or die($conexion2->error());
		$nc=$c->rowCount();
		if($nc==0)
		{
			$n=0;
		}else
		{
			$sessiones++;
			$n=1;
		}
	}

	$_SESSION['trabajo']=$sessiones;
	$usu=$_SESSION['usuario'];
	$paque=$_SESSION['paquete'];
	$conexion2->query("insert into trabajo(producido,mesa,sessiones,fecha,fecha_ingreso,usuario,paquete,estado,cantidad)values('$producido','$mesa','$sessiones','$fecha',getdate(),'$usu','$paque','0','$cantidad')")or die($conexion2->error());
	echo "<script>location.replace('trabajos.php')</script>";

}else
{

}
}else if($btn=='AGREGAR')
{

	$sessiones=$_SESSION['trabajo'];
	$c=$conexion2->query("select * from trabajo where sessiones='$sessiones' and usuARIO='$usu'")or die($conexion2->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$producido=$f['producido'];
	$fecha=$f['fecha'];
	$mesa=$f['mesa'];
	$usu=$_SESSION['usuario'];
	$paquete=$_SESSION['paquete'];
	if($sessiones=='')
	{
		echo "<script>alert('INGRESE LA FECHA,PRODUCIDO Y MESA PRIMERO')</script>";
	echo "<script>location.replace('trabajos.php')</script>";
}else
{


	$conexion2->query("insert into trabajo(producido,mesa,observacion,deposito,articulos,peso,sessiones,fecha,fecha_ingreso,usuario,paquete,estado,cantidad)values('$producido','$mesa','$obs','$deposito','$articulos','$peso','$sessiones','$fecha',getdate(),'$usu','$paquete','0','$cantidad')")or die($conexion2->error());
	//echo "<script>alert('hecho')</script>";
	echo "<script>location.replace('trabajos.php')</script>";
	}
}else if($btn=='FINALIZAR')
{
	
	$sessiones=$_SESSION['trabajo'];
	$usu=$_SESSION['usuario'];
	//echo "<script>alert('$sessiones - $usu')</script>";
	$conexion2->query("update trabajo set estado='1' where sessiones='$sessiones' and usuario='$usu'")or die($conexion2->error());
	echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";
	$_SESSION['trabajo']='';
	echo "<script>location.replace('trabajos.php')</script>";

}else if($btn=='DEJAR PENDIENTE')
{
	$sessiones=$_SESSION['trabajo'];
	$usu=$_SESSION['usuario'];
	$_SESSION['trabajo']='';
	$conexion2->query("update trabajo set estado='2' where sessiones='$sessiones' and usuario='$usu'")or die($conexion2->error());
	echo "<script>alert('SE DEJO PENDIENTE CORRECTAMENTE')</script>";
	echo "<script>location.replace('trabajos.php')</script>";
}else if($btn=='CANCELAR')
{
	$sessiones=$_SESSION['trabajo'];
	$usu=$_SESSION['usuario'];
	$conexion2->query("delete from trabajo where sessiones='$sessiones' and usuario='$usu'")or die($conexion2->error());
	echo "<script>alert('CANCELADO CORRECTAMENTE')</script>";
	$_SESSION['trabajo']='';
	echo "<script>location.replace('trabajos.php')</script>";
}

}
?>
</form>
</body>
</html>