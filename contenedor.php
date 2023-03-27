<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="moment.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#hora").val(moment().format("YYYY-MM-DD H:mm:ss"));
		})
		function fechahora()
  {
  	 var horas=moment().format("YYYY-MM-DD H:mm:ss");
  	 //alert(horas);
 	 $("#actual").val(horas);
 	 var h=$("#hora").val();
 	 //alert(h);
 	 var minutos=moment(horas).diff(moment(h),'minutes') ;
 	 //alert(minutos);
 	 if(minutos>=10)
 	 {
 	 	alert('CERRADO POR INACTIVIDAD');
 	 	location.replace('salir.php');
 	 }

 	 return horas;
  }
 setInterval(fechahora,1000);
	</script>
	<?php
	echo "<input type='text' name='hora' id='hora' readonly>";
	echo "<input type='text' name='actual' id='actual' readonly>";
	error_reporting(0);
	include("conexion.php");
	/*if($_SESSION['usuario']!='staana3')
	{
		echo "<script>alert('NO DISPONIBLE')</script>";
		echo "<script>location.replace('salir.php')</script>";
	}*/
	if($_SESSION['tipo']==2)
	{
		echo "<script>alert('NO TIENES AUTORIZACION PARA CONTENEDOR')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}
	$hoy=date("Y-m-d");
	$b=$_GET['b'];
	$i=$_GET['a'];
	$art=$_GET['art'];
	$con=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error);
	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
	$codigo=$fcon['ARTICULO'];
	$nombre=$fcon['DESCRIPCION'];
	$fechas=$_SESSION['fecha'];
	if($codigo!='' and $nombre!='')
	{
		$kam=1;
	}else
	{
		$kam=2;
	}
	if($_SESSION['fecha']!="" and $_SESSION['contenedor']!="")
	{
		
		$contenedors=$_SESSION['contenedor'];
	$con=$conexion2->query("select * from registro where fecha_documento='$fechas' and contenedor='$contenedors' and tipo='C1'")or die($conexion2->error);
	$knum=$conexion2->query("select count(*) as total from registro where contenedor='$contenedors' and fecha_documento='$fechas' and tipo='CD' and estado='0'")or die($conexion2->error);
	$fknum=$knum->FETCH(PDO::FETCH_ASSOC);
	$tf=$fknum['total'];


		$ncon=$con->rowCount();
		//echo "<script>alert('$ncon $fechas $contenedors')</script>";
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
		//$(".detalle").hide();
		if($("#a").val()==1)
		{
			$("#filas").show();
		}else if($("#i").val()==2)
		{
			$(".detalle").show();
		}
		if($("#i").val()==2)
		{
			$(".detalle").show();
		}
		if($("#kam").val()==1)
		{
			$("#cant").focus();
		}else if($("#kam").val()==2)
		{
			$("#cod").focus();
		}
	});
	function activar()
	{
		location.replace('addconte.php');
	}
	function cerrar()
	{
		$(".detalle").hide();
	}
	function enviar()
	{
		$("#op").val('2');
		if($("#cod").val()!='')
		{
			document.form.submit();
		}else
		{
			alert('INGRESE UN ARTICULO');
			location.replace('contenedor.php');
		}
		
	}
	function enviar1()
	{
		document.form.submit();
	}
	function cambiar()
	{
		$("#op").val('1');
	}
	function final()
	{
		if(confirm('SEGURO DESEA FINALIZAR EL CONTENEDOR'))
		{
			location.replace('final_contenedor.php');
		}
	}
	function continuar()
	{
		location.replace('continuar.php');
	}
</script>

</head>
<body>

	<input type="hidden" name="kam" id="kam" value='<?php echo "$kam";?>'>
	<input type="hidden" name="a" id="a" value='<?php echo "$a";?>'>
	<input type="hidden" name="i" id="i" value='<?php echo "$i";?>'>
<table class="tabla" cellpadding="20">
	<tr>
		<form method="POST">
		<td width="25%">
			Fecha:<br>
			<input type="date" name="fecha" class="text" value='<?php echo "$hoy";?>' required style=" padding-bottom: 2.5%; padding-top: 2.5%;">
		</td>
		<td># CONTENEDOR:<br>
			<input type="text" class="text" name="contenedor" class="text" value='<?php echo $_SESSION['contenedor'] ;?>' required style=" padding-bottom: 1.5%; padding-top: 1.5%;"></td>
		<td>BODEGA:<br>
			<select name="bodega" class="text" style=" padding-bottom: 3%; padding-top: 3%;">
			<?php
				if($bod!="")
				{
					echo "<option>$bod</option>";
				}else
				{
					echo "<option>BODEGA</option>";
				}
				
			?>
			
	<?php
		$c=$conexion1->query("Select * from consny.bodega where bodega like'sm%' or bodega='CA00' and nombre not like'%(N)%'
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
			<input type="submit" name="btn" class="boton2" value="Siguiente" style="padding: 3.5%;  margin-top: 0.5%;">
			
		</td>
		</form>
	</tr>
	<tr id="filas">
			
				<td>
					<button onclick="activar()" class="boton4" style="padding: 1.5%;">ARTICULOS</button> 
				<form method="POST" action="addart.php" name="form">
					<input type="text" name="cod" id="cod" onkeypress="cambiar()" onchange="enviar1()" class="text" style="padding-bottom: 2.5%; padding-top: 2.5%;" value='<?php echo "$codigo";?>' >
				</td>
				<td>
					NOMBRE:<br>
					<input type="text" name="nom" class="text" style="width: 80%; padding: 2.5%;" value='<?php echo "$nombre";?>'>
				<td width="5%">
					CANTIDAD:<br>
					<input type="number" name="cant" class="text" id="cant"  placeholder="Cantidad" value="1" style="padding:8%;">
				<input type="hidden" name="op" id="op" style="width: 5%;">
				</td>
				<td width="10%">PESO:<br>
				<input type="number" step="any" name="peso" class="text" required style="padding:8%;">
				</td>
				<td><br>
					<input type="submit" name="btn" value="Add." class="boton3" style="padding-bottom: 3%; padding-top: 3%; " onclick="enviar()"> <?php echo "TOTAL: $tf"?></td>
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
	if($btn=='Siguiente')
	{
		$paquete=$_SESSION['paquete'];
		$usuario=$_SESSION['usuario'];
		if($_SESSION['contenedor']=="" and $_SESSION['fecha']=="")
		{
			$_SESSION['contenedor']=$contenedor;
			$_SESSION['fecha']=$fecha;
			
$conexion2->query("insert into registro(fecha_documento,contenedor,bodega,tipo,estado,paquete,usuario,fecha_ingreso,bodega_produccion) values('$fecha','$contenedor','$bodega','C1','0','$paquete','$usuario',getdate(),'$bodega')")or die($conexion2->error);

		echo "<script>location.replace('contenedor.php')</script>";
		}else
		{
			$cont_actual=$_SESSION['contenedor'];
			$fecha_actual=$_SESSION['fecha'];
			$_SESSION['contenedor']=$contenedor;
			$_SESSION['fecha']=$fecha;
			$conexion2->query("update registro set contenedor='$contenedor', fecha_documento='$fecha',bodega='$bodega',bodega_produccion='$bodega' where contenedor='$cont_actual' and fecha_documento='$fecha_actual'")or die($conexion2->error);
			echo "<script>location.replace('contenedor.php')</script>";
		}
	
	}
	
}
$contenedors=$_SESSION['contenedor'];
$fechas=$_SESSION['fecha'];
$cons=$conexion2->query("select sum(cantidad) as cantidad,codigo,peso,fecha_ingreso from registro where contenedor='$contenedors' and fecha_documento='$fechas' and tipo='CD' and estado='0'  group by codigo,peso,cantidad,fecha_ingreso order by fecha_ingreso desc
")or die($conexion2->error);
$ncons=$cons->rowCount();
$artp="n/a";
if($ncons!=0)
{
	echo "<table class='tabla' border='1' cellpadding='10'>
	<tr>
		<td>CODIGO</td>
		<td width='20%'>NOMBRE</td>
		<td width='10%'>CANTIDAD</td>
		<td width='10%'>PESO</td>
		<td width='10%'>TOTAL PESO</td>
		<td width='15%'>CODIGOS BARRAS</td>
		<td width='10%'>QUITAR</td>
	</tr>";
	$f_peso=0;
	while($fcons=$cons->FETCH(PDO::FETCH_ASSOC))
	{
		$codd=$fcons['codigo'];
		$cant=$fcons['cantidad'];
		$idd=$fcons['id_registro'];
		$peso=$fcons['peso'];
		$fecha_hora=$fcons['fecha_ingreso'];
		$k=$conexion1->query("select * from consny.ARTICULO where consny.ARTICULO.ARTICULO='$codd'")or die($conexion1->error);
		$fk=$k->FETCH(PDO::FETCH_ASSOC);
		$nomd=$fk['DESCRIPCION'];
		//peso acumulado
		$qacumulado=$conexion2->query("select sum(peso) as cantidad_peso from registro where codigo='$codd' and contenedor='$contenedors' and fecha_documento='$fechas' and estado='0' ")or die($conexion2->error());
		$fqacumulado=$qacumulado->FETCH(PDO::FETCH_ASSOC);
		$peso_acumulado=$fqacumulado['cantidad_peso'];
		//fin peso acumulado
		$f_peso=$f_peso+($cant*$peso);
	echo "<tr>
		<td><a href='contenedor.php?art=$codd' style='text-decoration:none; color:black;'>$codd</a></td>
		<td><a href='contenedor.php?art=$codd' style='text-decoration:none; color:black;'>$nomd</a></td>
		<td>$cant</td>
		<td>$peso</td>
		<td>$peso_acumulado</td>
		<td><a href='revisar.php?id=$codd&&p=$peso&&fecha_hora=$fecha_hora&&cant=$cant'>
		<img src='revisar.png' width='15%' height='5%' style='cursor:pointer;'></a>
		<a href='nueva_impresion.php?id=$codd&&p=$peso&&fecha_hora=$fecha_hora&&cant=$cant' target='blank'>
		<img src='viñeta.png' width='15%' height='15%'>
		</a>

		</td>
		<td>
		<a href='eliconte.php?id=$codd&&p=$peso&&fecha_hora=$fecha_hora&&cant=$cant'>
		<img src='eliminar.png' width='15%' height='5%' style='cursor:pointer;'></a>
		</td>
	</tr>";
	}
	echo "<tr>
	<td colspan='6'>$f_peso<button class='btnfinal' style='padding-top:1%; float:right; margin-right0.5%;padding-bottom:0.5%; float:right; margin-right0.5%;margin-bottom:-0.5%;' onclick='final()'>Finalizar</button></td>
	</tr>
	</table>";
}
?>