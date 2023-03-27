<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<?php
	error_reporting(0);
	include("conexion.php");
	$hoy=date("Y-m-d");
	$b=$_GET['b'];
	$i=$_GET['a'];
	$art=$_GET['art'];
	$con=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error);
	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
	$codigo=$fcon['ARTICULO'];
	$nombre=$fcon['DESCRIPCION'];
	$fechas=$_SESSION['fecha'];
	if($_SESSION['fecha']!="" and $_SESSION['contenedor']!="")
	{
		
		$contenedors=$_SESSION['contenedor'];
	$con=$conexion2->query("select * from registro where fecha_documento='$fechas' and contenedor='$contenedors' and tipo='C1'")or die($conexion2->error);
		$ncon=$con->rowCount();
		if($ncon!=0)
		{
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$hoy=$fechas;
			$conte=$fcon['contenedor'];
			$bod=$fcon['bodega'];
			$idc=$fcon['id_registro'];
			$a=1;
		}
	}
	if($fechas!="")
	{
		$hoy=$fechas;
	}
	?>

<script>
	$(document).ready(function(){
		$("#filas").hide();
		$(".detalle").hide();
		if($("#a").val()==1)
		{
			$("#filas").show();
		}else if($("#i").val()==2)
		{
			$(".detalle").show();
		}
		
	});
	function activar()
	{
		$(".detalle").show();
	}
	function cerrar()
	{
		$(".detalle").hide();
	}
	function enviar()
	{
		$("#op").val('2');
		document.form.submit();
	}
	function enviar1()
	{
		document.form.submit();
	}
	function cambiar()
	{
		$("#op").val('1');
	}
</script>

</head>
<body>
<div class="detalle" style="margin-top: -5%;">
	<button onclick="cerrar()" style="float: right; margin-right: 0.5; background-color: (130,130,137,0.0);color: white; border:none; cursor: pointer;">Cerrar X</button>
	<div class="adentro" style="height: 93%; margin-left: 0.8%; width: 98%;">
<form method="POST">
<input type="text" name="bu" class="text" style="width: 40%; margin-left: 15%;" placeholder="CODIGO O NOMBRE ARTICULO">
<input type="submit" name="btn" class="boton2" value="BUSCAR">
	<hr>
</form>

<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=="BUSCAR")
	{
		echo "<script>location.replace('contenedor.php?b=$bu&&a=2')</script>";
	}
	
}else
{

	
	$q=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION FROM consny.articulo inner join consny.EXISTENCIA_BODEGA ON consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO AND consny.EXISTENCIA_BODEGA.BODEGA='SM02' AND consny.ARTICULO.ACTIVO='S' AND consny.ARTICULO.CLASIFICACION_1!='DETALLE' WHERE consny.ARTICULO.ARTICULO='$b' OR consny.ARTICULO.DESCRIPCION LIKE '%$b%'
")or die($conexion1->error);
	$nq=$q->rowCount();
	if($nq==0)
	{
		echo "NO SE ENCONTRO NINGUN REGISTRO DISPONIBLE";
	}else
	{
		echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:3.5%;'>
		<tr>
			<td>CODIGO</td>
			<td>NOMBRE</td>
		</tr>";
		while($fq=$q->FETCH(PDO::FETCH_ASSOC))
		{
			$cod=$fq['ARTICULO'];
			$nom=$fq['DESCRIPCION'];
			echo "
			<tr>
			<td><a href='contenedor.php?art=$cod' style='text-decoration:none; color:black;'>$cod</a></td>
			<td><a href='contenedor.php?art=$cod' style='text-decoration:none; color:black;'>
			$nom</a></td>
			
			</tr>";
		}
	}
}
?>
	</table>	
	</div>
</div>

	<input type="hidden" name="a" id="a" value='<?php echo "$a";?>'>
	<input type="hidden" name="i" id="i" value='<?php echo "$i";?>'>
<table class="tabla">
	<tr>
		<form method="POST">
		<td width="25%">
			Fecha:<br>
			<input type="date" name="fecha" class="text" value='<?php echo "$hoy";?>' required>
		</td>
		<td># CONTENEDOR:<br>
			<input type="text" class="text" name="contenedor" class="text" value='<?php echo $_SESSION['contenedor'] ;?>' required></td>
		<td>BODEGA:<br>
			<select name="bodega" class="text" style="padding-top: 1.5%; padding-bottom: 1.5%;">
			<?php
				if($bod!="")
				{
					echo "<option>$bod</option>";
				}else
				{
					echo "<option>SM02</option>";
				}
				
			?>
			
	<?php
		$c=$conexion1->query("Select * from consny.bodega where bodega like'sm%' and nombre not like'(N)'
")or die($conexion1->error);
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$b=$f['BODEGA'];
		echo "<option>$b</option>";
	}
	?>
			</select>
		</td>

		<td><br>
			<input type="submit" name="btn" class="boton2" value="Siguiente" style="padding-top: 5%; padding-bottom: 5%; width:100%; margin-top: 0.5%;">
		</td>
		</form>
	</tr>
	<tr id="filas">
			
				<td>
					<button onclick="activar()" class="boton4">ARTICULOS</button> 
				<form method="POST" action="addart.php" name="form">
					<input type="text" name="cod" onkeypress="cambiar()" onchange="enviar1()" class="text" style="padding-bottom: 1.5%; padding-top: 1.5%;" value='<?php echo "$codigo";?>' >
				</td>
				<td>
					NOMBRE:<br>
					<input type="text" name="nom" class="text" style="width: 80%;" value='<?php echo "$nombre";?>'>
				<td>
					CANTIDAD:<br>
					<input type="number" name="cant" class="text"  placeholder="Cantidad" value="1">
				<input type="text" name="op" id="op" style="width: 5%;">
				</td>
				<td>
					<br>
					<input type="submit" name="btn" value="Add." class="boton3" style="padding-bottom: 5%; padding-top: 5%; " onclick="enviar()"></td>
			</form>
		</td>
	</tr>
</table>
</body>
</html>
<?php
if($_POST)
{
	extract($_REQUEST);
	echo "<script>alert('$btn')</script>";
	if($btn=='Siguiente')
	{
		$paquete=$_SESSION['paquete'];
		$usuario=$_SESSION['usuario'];
		if($_SESSION['contenedor']=="" and $_SESSION['fecha']=="")
		{
			$_SESSION['contenedor']=$contenedor;
			$_SESSION['fecha']=$fecha;
$conexion2->query("insert into registro(fecha_documento,contenedor,bodega,tipo,estado,paquete,usuario) values('$fecha','$contenedor','$bodega','C1','0','$paquete','$usuario')")or die($conexion2->error);

		echo "<script>location.replace('contenedor.php')</script>";
		}else
		{
			$_SESSION['contenedor']=$contenedor;
			$_SESSION['fecha']=$fecha;
			$conexion2->query("update registro set contenedor='$contenedor', fecha_documento='$fecha',bodega='$bodega' where id_registro='$idc'")or die($conexion2->error);
			echo "<script>alert('gurda')</script>";
			echo "<script>location.replace('contenedor.php')</script>";
		}
	
	}
	
}
$contenedors=$_SESSION['contenedor'];
$cons=$conexion2->query("select * from registro where contenedor='$contenedors' and fecha_documento='$fechas' and tipo='CD'")or die($conexion2->error);
$ncons=$cons->rowCount();
if($ncons!=0)
{
	echo "<table class='tabla' border='1' cellpadding='10'>
	<tr>
		<td>CODIGO</td>
		<td width='20%'>NOMBRE</td>
		<td width='10%'>CANTIDAD</td>
		<td width='15%'>CODIGOS BARRAS</td>
		<td width='10%'>QUITAR</td>
	</tr>";
	while($fcons=$cons->FETCH(PDO::FETCH_ASSOC))
	{
		$codd=$fcons['codigo'];
		$cant=$fcons['cantidad'];
		$idd=$fcons['id_registro'];
		$k=$conexion1->query("select * from consny.ARTICULO where consny.ARTICULO.ARTICULO='$codd'")or die($conexion1->error);
		$fk=$k->FETCH(PDO::FETCH_ASSOC);
		$nomd=$fk['DESCRIPCION'];
	echo "<tr>
		<td>$codd</td>
		<td>$nomd</td>
		<td>$cant</td>
		<td><a href='revisar.php?id=$idd'>
		<img src='revisar.png' width='15%' height='5%' style='cursor:pointer;'></a></td>
		<td>
		<a href='eliconte.php?id=$idd'>
		<img src='eliminar.png' width='15%' height='5%' style='cursor:pointer;'></a>
		</td>
	</tr>";
	}
	echo "<tr>
	<td colspan='5'><button class='btnfinal' style='padding-top:1%; float:right; margin-right0.5%;padding-bottom:0.5%; float:right; margin-right0.5%;margin-bottom:-0.5%;'>Finalizar</button></td>
	</tr>
	</table>";
}
?>