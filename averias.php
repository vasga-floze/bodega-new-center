<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="moment.min.js"></script>


	<script>
		$(document).ready(function(){

			if($("#art").val()!='')
			{
				$("#cant").focus();
			}else
			{
				$("#art").focus();
			}

		})

		function enviar()
		{
			$("#op").val('1');
			$("#form").submit();
		}
		function enviar1()
		{
			$("#op").val('2');
			$("#cant").attr('required',true);
		}
		function ver()
	{
		var fecha1 = moment($("#ac").val());
		var fecha2 = moment($("#hc").val());
		var dia =fecha2.diff(fecha1, 'days');
		

		if(dia<=-7 || dia>0)
		{
			//if($("#usuario1").val()=='sanmiguel2')
						//{
							//$("#formF").submit();
						//}
			alert('FECHA NO VALIDA');
			$("#hc").val('');
			
		}else
		{
			if($("#tipo").val()=='')
			{
				alert('SELECCIONA EL TIPO DE TRANSACCION');
			}else
			{
				if(confirm('SEGURO DESEA FINALIZAR LA TRANSACCION DE: '+ $("#tipo").val()))
				{
					if($("#hc").val()=='')
					{

						alert('SELECCIONA UNA FECHA VALIDA');
					}else
					{
						if($("#numero").val()==$("#t").val())
						{
							$("#formF").submit();
						}else
						{
							alert("LA CANTIDAD TOTAL DE PIEZAS NO CUADRA CON LA CANTIDAD DE PIEZAS INGRESADAS");
						}
						
					}
				}else
				{
					//alert('sdf');
				}
				
			}
			
		}
	}

	function art()
	{
		//alert($("#numero").val());
		location.replace("art_averias.php?num="+$("#numero").val());
	}


	</script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
$art=$_GET['art'];
$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$articulo=$fca['ARTICULO'];
$desc=$fca['DESCRIPCION'];
$hoy=date("Y-m-d");
$usuario1=$_SESSION['usuario'];
?>


<h3 style="text-align: center; text-decoration: underline;">AVERIA Y MERCADERIA NO VENDIBLE</h3>

<input type="hidden" name="ac" id="ac" value='<?php echo "$hoy";?>'>
<input type="hidden" name="ac" id="usuario1" value='<?php echo "$usuario1";?>'>
<form method="POST" id="form">
	<label>TOTAL PIEZAS: <input type="number" name="numero" id="numero" class="text" style="width: 15%;" placeholder="TOTAL PIEZAS" required value="<?php echo $_GET['num']; ?>">

	</label><br><br>
<a href="#" style="background-color: #B5D7C3; color: white; padding: 0.5%; text-decoration: none; color: black;" onclick="art()">ARTICULOS</a><br>
<input type="text" name="art" id="art" class="text" style="width: 15%;" placeholder="ARTICULO" onchange="enviar()" value='<?php echo "$articulo"?>'>
<input type="text" name="desc" id="desc" class="text" style="width: 50%;" placeholder="DESCRIPCION" value='<?php echo "$desc"?>'>

<input type="number" name="cant" id="cant" class="text" style="width: 15%;" placeholder="CANTIDAD" min="1">
<input type="hidden" name="op" id="op">
<input type="submit" name="btn" value="AGREGAR" class="btnfinal" style="padding: 0.5%; background-color: #B5D7C3;" onclick="enviar1()">
	
</form>
</body>
</html>
<?php
$session=$_SESSION['averias'];
$user=$_SESSION['usuario'];
$c=$conexion2->query("select * from averias where session='$session' and usuario='$user' and estado='0' order by id desc")or die($conexion2->error());

$n=$c->rowCount();
if($n!=0)
{
	echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width:98%;'>";

	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>OPCION</td>
	</tr>";
	$t=0;
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$arti=$f['articulo'];
		$cantidad=$f['cantidad'];
		$id=base64_encode($f['id']);
		$ca=$conexion1->query("select * from consny.articulo where articulo='$arti'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$articulos=$fca['ARTICULO'];
		$descripcion=$fca['DESCRIPCION'];
		$num=$_GET['num'];
		echo "<tr>
		<td>$articulos</td>
		<td>$descripcion</td>
		<td>$cantidad</td>
		<td><a href='#' style='padding:1.3%; background-color: red; color:white; text-decoration:none;' onclick=\"javascript:
	 			location.replace('eli_averias.php?id=$id&&num='+$('#numero').val());
	 							
				 \">ELIMINAR</a></td>
	</tr>";
	$t=$t+$cantidad;
	}
$hoy=date("Y-m-d");
echo "<input type='hidden' name='t' id='t' value='$t'>";
echo "<tr><td colspan='2'>TOTAL PIEZAS INGRESADAS:</td><td>$t</td></tr>";

	echo "<tr>
		<td colspan='4'>
		<form method='POST' id='formF' action='final_averias.php'>
		<select name='tipo' id='tipo' class='text' style='width: 15%;' required>
	<option value=''>TIPO TRANSACCION</option>
	<option>AVERIA</option>
	<OPTION>MERCADERIA NO VENDIBLE</OPTION>
</select>
<input type='text' name='obs' class='text' placeholder='OBSERVACION' style='width:20%;' maxlength='250'>

<input type='text' name='marchamo' class='text' placeholder='MARCHAMO' style='width:15%;'>

<input type='text' name='digita' class='text' placeholder='DIGITADO POR' style='width:15%;'>


<input type='date' name='fecha' class='text' id='hc'  style='width:12%;' value='$hoy'>


</form>";



echo "
<input type='button' value='FINALIZAR' onclick='ver()' class='btnfinal' style='padding:0.5%; margin-bottom:0.5%; width:8%; float:right;'>
		</td>
	</tr>";
	}



if($_POST)
{
	extract($_REQUEST);

	if($op==1)
	{
		$c=$conexion1->query("select * from consny.articulo where articulo='$art' and clasificacion_1='DETALLE' and activo='S'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO EL ARTICULO $art')</script>";
			echo "<script>location.replace('averias.php?num=$numero')</script>";
		}else
		{
			echo "<script>location.replace('averias.php?art=$art&&num=$numero')</script>";


		}
	}else if($op==2)
	{
		if($_SESSION['averias']=='')
		{
			$c=$conexion2->query("select max(session) as session from averias")or die($conexion2->error());
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$session=$f['session']+1;
			$k=1;
			while ($k==1) 
			{
				$cv=$conexion2->query("select * from averias where session='$session'")or die($conexion2->error());

				$ncv=$cv->rowCount();
				if($ncv==0)
				{
					$k=0;
					$_SESSION['averias']=$session;
				}else
				{
					$session++;
					$k=1;
				}
			}



		}

		$averias=$_SESSION['averias'];
		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		$bodega=$_SESSION['bodega'];

		$ca=$conexion1->query("select *,precio_regular from consny.articulo where articulo='$art' and clasificacion_1='DETALLE' AND ACTIVO='S'")or die($conexion1->error());
		$nca=$ca->rowCount();

		if($nca==0)
		{
			echo "<script>alert('ARTICULO $art NO VALIDO')</script>";
			echo "<script>location.replace('averias.php?num=$numero.php')</script>";
		}else
		{
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$precio=$fca['precio_regular'];
			if($precio=='')
			{
				$precio=0;
			}
			//echo"$usuario,$paquete,$bodega,getdate(),0,$art,$precio,cant,$averias";
			$conexion2->query("insert into averias(usuario,paquete,bodega,fecha_hora_crea,estado,articulo,precio,cantidad,session)values('$usuario','$paquete','$bodega',getdate(),'0','$art','$precio','$cant','$averias')")or die($conexion2->error());

			echo "<script>location.replace('averias.php?num=$numero')</script>";
		}

	}
}
?>