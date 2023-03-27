<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

<script>
	$(document).ready(function(){
		//alert($("#opc").val());
		if($("#opc").val()==1)
		{
			$("#form1").show();
			$("#barra").focus();
			$("#bodega").attr('readonly',true);
			$("#nombre").attr('readonly',true);
			$("#btn1").hide();
			$("#bod1").hide();
		}else
		{
			$("#form1").hide();
		}
	});
	function enviar()
	{
		document.form.submit();
	}
	function cambiar()
	{
		$("#op").val('1');
	}
	function enviar1()
	{
		$("#op").val('2');
		//document.form.submit();
	}

	function mostrar()
	{
		window.open('mostrar_inventario.php');
	}
</script>
</head>
<ceter>
<body style="">
	<?php
	error_reporting(0);
		include("conexion.php");
		if($_SESSION['tipo']==3)
		{
			$tipo=1;
		}else
		{
			$tipo=$_SESSION['tipo'];
		}
		if($tipo!=1)
		{
			echo "<script>location.replace('desglose.php')</script>";
		}
		if($_SESSION['inv']!='')
		{
			$i=1;
			$inv=$_SESSION['inv'];
			//echo "<script>alert('$inv')</script>";
			$u=$_SESSION['usuario'];
			$q=$conexion2->query("select top 1 * from inventario where sessiones='$inv' and usuario='$u' ")or die($conexion2->error());
			$fq=$q->FETCH(PDO::FETCH_ASSOC);
			$bod=$fq['bodega'];

		}else
		{
			$bod=$_GET['bod'];
		}
		if($bod!='')
		{
			$cb=$conexion1->query("select * from consny.bodega where bodega='$bod'")or die($conexion1->error());
			$ncb=$cb->rowCount();
			if($ncb!=0)
			{
				$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
				$bod=$fcb['BODEGA'];
				$nomb=$fcb['NOMBRE'];
			}
		}

	?>
	<input type="hidden" name="opc" id="opc" value='<?php echo "$i";?>'>
	<a href="bodega_inventario.php">
<button class="boton4" id="bod1">BODEGA</button></a><br>
<form method="POST" name="form" id="form" style="margin-left: 4%;">

	<input type="text" name="bodega" class="text" style="width: 15%;" placeholder="BODEGA" onchange="enviar()" onkeypress="cambiar()" value='<?php echo "$bod";?>' id="bodega" required>

	<input type="text" name="nombre" class="text" style="width: 50%;" placeholder="NOMBRE" value='<?php echo "$nomb";?>' id="nombre">
	<input type="hidden" name="op" id="op">

	<input type="submit" name="btn" id='btn1' value="SIGUIENTE" class="boton2" onclick="enviar1()">
</form>
<br>
<form method="POST" style="margin-left: 4%;" id="form1" name="form1" action="add_inventario.php">
<input type="text" name="barra" class="text" style="width: 30%;" id="barra" required>
<input type="submit" name="btn" value="AGREGAR" class="boton2">
<img src="expandir.png" width="2.5%" height="2.5%" onclick="mostrar()" style="cursor: pointer;">
</form><br>
<?php
$u=$_SESSION['usuario'];
$inv=$_SESSION['inv'];
$cinvt=$conexion2->query("select count(*) as total from inventario where sessiones='$inv' and usuario='$u' and registro!='0'")or die($conexion2->error());
$fcinvt=$cinvt->FETCH(PDO::FETCH_ASSOC);
$t=$fcinvt['total'];
$u=$_SESSION['usuario'];
$cvr=$conexion2->query("select top 10 * from inventario where registro!='0' and sessiones='$inv' and usuario='$u' order by id desc
")or die($conexion2->error());
$ncvr=$cvr->rowCount();
if($ncvr!=0)
{
	echo "<h3>TOTAL FARDOS: $t</h3>";
	echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:4%;'>";
	echo "<tr>
	<td>#</td>
	<td>CODIGO DE BARRA</td>
	<td>ARTIULO</td>
	<td>DESCRIPCION</td>
	<td>CANTIDAD</td>
	<td>BODEGA</td>
	<td>OBSERVACION</td>
	<td>FECHA TRASLADO</td>
	<td>QUITAR</td>
	</tr>";
	$number=1;
	while($fcvr=$cvr->FETCH(PDO::FETCH_ASSOC))
	{
		$bodegainv=$fcvr['bodega'];
		$idr=$fcvr['registro'];
		$id=$fcvr['id'];
		//echo "<script>alert('$idr')</script>";
		$cr=$conexion2->query("select barra,codigo,fecha_traslado,bodega,observacion,activo from registro where id_registro='$idr'");
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$barra=$fcr['barra'];
		$fechat=$fcr['fecha_traslado'];
		$cod=$fcr['codigo'];
		$bodega_a=$fcr['bodega'];
		$obser=$fcr['observacion'];
		$activo=$fcr['activo'];
		$ca=$conexion1->query("select * from consny.ARTICULO where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$art=$fca['ARTICULO'];
		$de=$fca['DESCRIPCION'];
		if($bodegainv!=$bodega_a)
		{
			echo "<tr style='background-color:red; color:white;'>";
			
		}else
		{
			echo "<tr>";
			
		}
		//echo "<script>alert('$activo')</script>";
		if($activo=='0')
		{
			echo "<tr style='background-color:#F4ED02; color:white;'>";
		}
		$u=$_SESSION['usuario'];
		$qv=$conexion2->query("select count(*) as total from inventario where registro='$idr' and sessiones='$inv' and usuario='$u'")or die($conexion2->error());
		$fqv=$qv->FETCH(PDO::FETCH_ASSOC);
if($fqv['total'] >1)
{
	echo "<tr style='background-color:yellow; color:black;'>";
}

$cta=$conexion2->query("select count(registro.codigo) as cantidad from registro inner join inventario on registro.id_registro=inventario.registro where inventario.sessiones='$inv' and inventario.usuario='$u' and registro.codigo='$cod' group by codigo")or die($conexion2->error());

$fcta=$cta->FETCH(PDO::FETCH_ASSOC);

		echo "	<td>$number</td>
				<td>$barra</td>
				<td>$art</td>
				<td>$de</td>
				<td>".$fcta['cantidad']."</td>
				<td>$bodega_a</td>
				<td>$obser($activo)</td>
				<td>$fechat</td>
				<td>
				<a href='eli_inventario.php?id=$id' style='text-decoration:none;'>


				QUITAR
				</a>
				</td>
			</tr>";
			$number++;

	}
	$hoy=date("Y-m-d");
	echo "<tr>
	<td colspan='9'>
	<form method='POST' action='final_inventario.php'>
	<input type='date' value='$hoy' required class='text' style='width:20%;' name='fecha'>
	<input type='text' class='text' name='digita' placeholder='DIGITADO POR' style='width:50%;' required>
	<input type='submit' value='FINALIZAR' class='btnfinal' style='padding-top:0.8%; padding-bottom:0.8%; margin-bottom:-3%;'>
	</td>
	</tr>
	</table>";
}

?>

<?php
if($_POST)
{
	extract($_REQUEST);
	if($op==1)
	{
		$c=$conexion1->query("select * from consny.bodega where bodega='$bodega' and nombre not like '%(N)%'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO BODEGA: $bodega O NO ESTA DISPONIBLE')</script>";
			echo "<script>location.replace('inventario.php')</script>";
		}else
		{
			echo "<script>location.replace('inventario.php?bod=$bodega')</script>";
		}
	}else if($op==2)
	{
		if($_SESSION['inv']=='')
		{

			$c=$conexion2->query("select max(sessiones) as num from inventario")or die($conexion2->error());
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$num=$f['num'] + 1;
			//echo "<script>alert('entra $num <')</script>";
			
			$vali=1;
			while($vali!=1)
			{
				$cv=$conexion2->query("select * from inventario where sessiones='$num'")or die($conexion2->error());
				$ncv=$cv->rowCount();
				if($ncv==0)
				{
					$vali=1;
				}else
				{
					$num=$num + 1;
					$vali=0;
				}
			}
			$_SESSION['inv']=$num;
			$usuario=$_SESSION['usuario'];
			$paquete=$_SESSION['paquete'];
			$conexion2->query("insert into inventario(bodega,digita,registro,sessiones,fecha_ingreso,estado,usuario,paquete) values('$bodega','','','$num',getdate(),'0','$usuario','$paquete')")or die($conexion2->error());
			echo "<script>location.replace('inventario.php')</script>";

		}else
		{

		}

	}
}
?>
</body>
</html>