<?php
	error_reporting(0);
	include("conexion.php");
	session_start();
	$paquete=$_SESSION['paquete'];
	$usu=$_SESSION['usuario'];

	$i=$_GET['i'];
	$b=$_GET['b'];
	$codg=$_GET['cod'];
	$nomg=$_GET['nom'];
	$arti=$_GET['art'];
	$bu=$_GET['bu'];
	$codd=$_GET['condd'];
	if($arti!="")
	{
		$gg=1;
	}
 $fe=$_SESSION['fecha'];
	$vc=$conexion2->query("select top 1 * from registro where fecha_documento='$fe' and fecha_traslado!='' and tipo='P' and usuario='$usu' ")or die($conexion2->error);
   $fvc=$vc->FETCH(PDO::FETCH_ASSOC);
   $est=$fvc['estado'];
   if($est==1 and $_SESSION['op']==1)
   {
   	$_SESSION['op']=0;
   	echo "<script>if(confirm('LA FECHA DE PRODUCCION $fe YA FUE FINALIZADA LA PRODUCCION¿DESEA CONTINUAR CON ESTA FECHA?\\nSI ACEPTA RECUERDE FINALIZAR NUEVAMENTE LA PRODUCCION DE LA FECHA: $fe'))
   	{

   	}else
   	{
   		 location.replace('index.php?c=1'); 
   	}

   		</script>";
   }




//echo "<script>alert('$bu')</script>";
	//--->--->--->falta ver cambiar dato de i para uando inicie y ya este asignada subcategoria de registro---<--------<---<


	if($_SESSION['session'] =="")
	{
		//echo "<script>alert('$session')</script>";
		echo "<script>location.replace('index.php')</script>";
	}
	$session=$_SESSION['session'];
	$fecha=$_SESSION['fecha'];
	$barras=$_SESSION['cbarra'];

	$con=$conexion2->query("select * from dbo.registro where barra='$barras'")or die($conexion2->error());
	$fr=$con->fetch(PDO::FETCH_ASSOC);
	//echo "<script>alert('$fr[codigo]')</script>";
	$id_registro=$fr['id_registro'];
	//echo "<script>alert('$id_registro - $barras')</script>";
	$articulo=$fr['ARTICULO'];
	$descripcion=$fr['DESCRIPCION'];

	$codigoi=$fr['subcategoria'];
	$op=$_GET['op'];
	$codgg=$_GET['codgg'];
	$catt1=$_GET['cantt1'];
	$boart=$fr['bodega'];
	//echo "<script>alert('$codigoi')</script>";
	//if($codigoi!="")
	//{
		//echo "<script>alert('$id_registro')</script>";
	//	$i=2;
	//}
	//echo "<script>alert('$id_registro')</script>";
	if($op==1)
	{
	  $que=$conexion2->query("select * from dbo.detalle where registro='$id_registro' and articulo='$codgg' ")or die($conexion2->error);
	  $fque=$que->fetch(PDO::FETCH_ASSOC);
	  $cantiad=$fque['cantidad'] + $catt1;
	  //echo "<script>alert('$cantiad - $catt1')</script>";
	  $id_detalle=$fque['id'];
	  $conexion2->query("update dbo.detalle set cantidad='$cantiad' where id='$id_detalle'")or die($conexion2->error);
	  //echo "<script>alert('$cantiad')</script>";
	  echo "<script>location.replace('ingreso.php?b= &&bu= &&i=2')</script>";

	}
	//echo "<script>alert('$id_registro -s-$session -f-$fecha')</script>";
	$boton=$fr['categoria'];//para saber categoria selecionada

	if($codg!="")
	{
		$fr['codigo']=$codg;
		
	}
	if($nomg)
	{
		$fr['subcategoria']=$nomg;
		
	}

	$queryart=$conexion1->query("select * from consny.articulo where ARTICULO='$arti'")or die($conexion1->error);

	$fart=$queryart->fetch(PDO::FETCH_ASSOC);
	$clasificacion=$fart['CLASIFICACION_1'];
	if($clasificacion=='DETALLE')
	{
		echo "<script>alert('ARTICULO $arti NO VALIDO PARA PRODUCCION')</script>";
		echo "<script>location.replace('ingreso.php')</script>";
	}
	if($fart['ARTICULO']!="")
	{
		$fr['codigo']=$fart['ARTICULO'];	
	}
	if($fart['DESCRIPCION']!="")
	{
		$fr['subcategoria']=$fart['DESCRIPCION'];
	}

	if($codg!="")
	{
		$articulo=$codg;
	}

	if($nomg!="")
	{
		$descripcion=$nomg;
	}



?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<input type="hidden" name="i" id="i" value='<?php echo "$i";?>'>
	<input type="hidden" name="gg" id="gg"  value='<?php echo "$gg";?>'>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			
			$("#fila").hide();
			$(".detalle").hide();
			$(".nodetalle").hide();
			$("#cod").focus();
			if($("#gg").val()==1)
			{
				$("#nom").focus();
			}
			if($("#i").val()==1)
			{
				
				$("#sub").show();
			}else if($("#i").val()==2)
			{
				
				if($("#cod").val()!="" || $("#nom").val()!="")
				{
				  $("#fila").show();
				  $(".detalle").hide();
				  $(".nodetalle").hide();
				  $("#cod1").focus();

				}
			}else if($("#i").val()==3)
			{
				//alert("nose");
				$("#fila").show();
				$(".detalle").show();
				$(".nodetalle").hide();
			}else if(("#i").val()==4)
			{
				$("#fila").hide();
				$(".detalle").hide();
				$(".nodetalle").hide();
			}else if(("#i").val()==5)
			{
				$("#fila").show();
				$(".detalle").hide();
				$(".nodetalle").hide();
			}

			if($("#nom1").val()!="")
			{
				$("#cant").focus();
			}
		});
		function activar()
		{
			location.replace('addp.php');
			$("#i").val("1");
			$("#sub").show();
		}
			


		function cerrar()
		{
			$("#sub").hide();
		}

		function activar1()
		{
			location.replace('adddp.php');
		}

		function cerrar1()
		{
			$(".detalle").hide();
		}

		function valor()
		{
			document.form.submit();
		}

		function cambiar()
		{
			$("#p").val("1");
		}
		function enviar()
		{

			
			$("#cod").prop('required', true);
			$("#nom").prop('required', true);
			$("#p").val("2");
			//document.form.submit();
		}
		function enviar1()
		{
			//alert('entra')
			$("#cant").prop('required', true);
			//document.form1.submit();
		}
		function enviarform1()
		{
			document.form1.submit();
		}
		function cambiarform1()
		{
			$("#opc").val("1");
		}
		function finaliza()
		{
			
				window.open('final_producion.php');
				location.reload('ingreso.php');
			
			
		}

		function finaliza1()
		{
			
			if($("#cod").val()!="" || $("#nom").val()!="")
			{
				window.open('final_producion.php');
				location.reload('ingreso.php');
			}else
			{
				alert("NO SE PUEDE FINALIZAR LA PRODUCCION NO SE HA AGREGADO CODIGO DE PRODUCCION");
			}
			
				
			
			
		}

		function cancelar()
		{
			if(confirm('SEGURO DESEA CANCELAR ESTA PRODUCCION'))
			{
				location.replace('cancelarp.php');
			}
		}
	</script>

</head>
<center>
<body>



<?php
$dd=$fr['subcategoria'];
?>

	<table class="tabla" border="1" cellpadding="10">
		<tr>
			<td>
				<BUTTON onclick='activar()' class='boton4'>NOMBRE FARDO</BUTTON>
				<?php echo "$boton"; ?>
				<a href="index.php?c=1">
				<button class="boton3">CAMBIAR</button></a>

			<button style="float: right; background-color: red; color: white;" class="boton3"  onclick= "cancelar()">CANCELAR</button>
			<?php
			if($_SESSION['usuario']=='GJURADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='OCAMPOS' or $_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='mfuentes' or $_SESSION['usuario']=='MFUENTES' or $_SESSION['usuario']=='Mfuentes' or $_SESSION['usuario']=='mcampos' or$_SESSION['usuario']=='MCAMPOS')
			{
				echo "<button onclick='finaliza1()' class='btnfinal' style='float: right; margin-right: 0.5%; padding: 0.5%;'>FINALIZAR</button>";
			}
			?>
			<!--<button onclick='finaliza()' class='btnfinal' style='float: right; margin-right: 0.5%; padding: 0.5%;'>FINALIZAR</button>-->
				<form method="POST" name="form">
				
				<input type="text" name="cod" id='cod' placeholder="CODIGO" onchange="valor()" onkeypress="cambiar();" class="text" style="width: 20%;" value="<?php echo $fr['codigo']; ?>" >
				<input type="text" name="nom" id='nom' placeholder="NOMBRE" class="text" style="width: 50%;" value='<?php echo "$dd"; ?>'>
				<input type="hidden" name="p" id="p">
				<input type="submit" name="btn"  value="Siguiente" onclick="enviar()" class="boton1-1">
				</form>
			</td>
		</tr>
		
		<tr>
			<td id="fila">
<?php
$artd=$_GET['artd'];

if($codd!="")
	{
		$cd=$conexion1->query("select * from consny.articulo where consny.articulo.articulo='$codd'")or die($conexion1->error);
		
	}else
	{
		$cd=$conexion1->query("select * from consny.articulo where consny.articulo.articulo='$artd'")or die($conexion1->error);
	}
	
	



$fd=$cd->fetch(PDO::FETCH_ASSOC);



?>
				<button onclick="activar1()" class="boton4">DETALLE</button>
				<form method="POST" name="form1">
					<input type="text" name="cod1" id="cod1" placeholder="CODIGO" class="text" style="width: 20%;"   value="<?php echo $fd['ARTICULO'];?>" onchange="enviarform1()" onkeypress="cambiarform1()">
					<input type="text" name="nom1" id='nom1' placeholder="NOMBRE" class="text" style="width: 50%;" value="<?php echo $fd['DESCRIPCION'];?>">
					<input type="number" name="cant" id="cant" placeholder="Cantidad" class="text" style="width: 10%;">
					<input type="hidden" name="opc" id='opc'>
					<input type="submit" name="btn" value="Add." onclick="enviar1()" class="boton2">
				</form>
			</td>
		</tr>
		</div>
	</table>
	<div style="border-collapse: collapse; width: 95%; height: 100%; overflow: auto; border-color: blue; background-color: white;">
		<?php
		//echo "<script>alert('$id_registro - $barras')</script>";
		//echo "<script>alert('$id_registro')</script>";
		$k=$conexion2->query("select * from dbo.detalle where registro='$id_registro'")or die($conexion2->error);
		$nk=$k->rowCount();
		//echo "<script>alert('$nk')</script>";
		if($nk==0)
		{
			//echo "<script>alert('$id_registro - $barras 00')</script>";
		}else
		{
			//echo "<script>alert('$id_registro - $barras')</script>";
			echo "<br><table border='1' cellpadding='10' class='tabla' width='100%'>
			<tr style='background-color: rgb(133,133,137,0.6); color:black;'>
				<td colspan='5'>ARICULOS AGREGADOS</td>
			</tr>
			<tr>
				<td>CODIGO</td>
				<td>NOMBRE</td>
				<td>CANTIDAD</td>
				<td>PRECIO</td>
				<td>QUITAR</td>
			</tr>";
			$total=0;
			$tp=0;
			$tf=0;
			while($fk=$k->FETCH(PDO:: FETCH_ASSOC))
			{
				$ide=$fk['id'];
				$kcod=$fk['articulo'];
				$kcant=$fk['cantidad'];
			$cp=$conexion1->query("select * from consny.articulo where articulo='$kcod'")or die($conexion1->error);
			$fcp=$cp->FETCH(PDO:: FETCH_ASSOC);
			$nombre=$fcp['DESCRIPCION'];
			$precio=$fcp['PRECIO_REGULAR'];
			if($precio=='')
			{
				$precio=0;
				
			}
			$tp=$kcant * $precio;
				$tf=$tf +$tp;
			//-----------
				echo "<tr>
				<td>$kcod</td>
				<td>$nombre</td>
				<td>$kcant </td>
				<td>$$tp </td>
				<td><a href='quitarp.php?ide=$ide'>QUITAR</a></td>
			</tr>";

			$total=$total+ $kcant;
			}
			echo "<tr style='background-color: rgb(133,133,137,0.6); color:black;'>
			<td colspan='2'>TOTAL :</td>
			<td>$total</td>
			<td>$$tf</td>
			<td><button onclick='finaliza()' class='btnfinal' style='padding-bottom:1.5%; padding-top:1.5%;'>FINALIZAR</button></td>
				</tr>";
		}

		?>




		<?php
			if($_POST)
			{
				extract($_REQUEST);
				//echo "<script>alert('$btn')</script>";
				if($btn=="Add.")
				{
					
					$consul=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION,consny.EXISTENCIA_BODEGA.BODEGA,consny.ARTICULO.PRECIO_REGULAR FROM consny.ARTICULO INNER JOIN consny.EXISTENCIA_BODEGA ON consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO WHERE CONSNY.ARTICULO.ARTICULO='$cod1' AND consny.ARTICULO.ACTIVO='S' AND consny.ARTICULO.CLASIFICACION_2='$boton' AND consny.EXISTENCIA_BODEGA.BODEGA='$boart' AND
						consny.ARTICULO.CLASIFICACION_1='DETALLE'
")or die($conexion1->error);
					$nconsul=$consul->rowCount();
					//echo "<script>alert('$nconsul')</script>";
					if($nconsul==0)
					{
						echo "<script>alert('ARTICULO FUERA DE CATEGORIA DE: $boton O NO SE ENCUENTRA ACTIVO O SU CLASIFICACION NO ES AL DETALLE')</script>";
						echo "<script>location.replace('ingreso.php?i=2&&b= &&bu= ')</script>";
					}else
					{

$cq=$conexion2->query("select * from dbo.detalle where registro='$id_registro' and articulo='$cod1'")or die($conexion2->error);
						$ncq=$cq->rowCount();
						//echo "<script>alert('$ncq nc')</script>";
						$fconsul=$consul->FETCH(PDO::FETCH_ASSOC);
						$precios=$fconsul['PRECIO_REGULAR'];
						if($ncq==0)
						{
							$conexion2->query("insert into dbo.detalle(registro,articulo,cantidad,precio) values('$id_registro','$cod1','$cant','$precios')")or die($conexion2->error);
							echo"<script>location.replace('ingreso.php?i=2')</script>";
						}else
						{
							echo "<script>if(confirm('ARTICULO $cod1 YA FUE AGREGADO ANTES DESEA SUMARLE $cant UNIDADES MAS'))
							{ 
								location.replace('ingreso.php?op=1&&codgg=$cod1&&cantt1=$cant&&b= &&i=2&&bu= ')
							}else
							{
							location.replace('ingreso.php?i=2&&b= &bu= ')
							}</script>";
						}
						
					}
				}
			}
		?>
		
	</div>



<?php
	if($_POST)
	{
		extract($_REQUEST);
		//echo "<script>alert('$p - $btn')</script>";
	
	if($p==2 and $btn=='Siguiente')
	{
		//echo "<script>alert('$p - $btn - $boton -$cod')</script>";
		$cva=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO and consny.EXISTENCIA_BODEGA.BODEGA='$boart'  WHERE consny.ARTICULO.ARTICULO='$cod' AND consny.ARTICULO.DESCRIPCION='$nom' AND consny.ARTICULO.ACTIVO='S' and consny.ARTICULO.CLASIFICACION_2='$boton'")or die($conexion1->error);//validar bodega si esta activo
		$ncva=$cva->rowCount();
		//echo "<script>alert('$ncva')</script>";
		if($ncva==0)
		{
			echo "<script>alert('ARTICULO $cod NO SE ENCUENTRA DISPONIBLE O NO ESTA EN  LA CLASIFICACION DE $boton')</script>";
		}else
		{

			$conexion2->query("update dbo.registro set subcategoria='$nom',codigo='$cod' where id_registro='$id_registro'")or die($conexion2->error);
			
			echo "<script>location.replace('ingreso.php?i=2&&art=$cod&&b= &&bu ')</script>";
			
		}

	}else if($p==1)
	{ 
		//ECHO "<script>alert('$boton')</script>";
		$cv=$conexion1->query("select * from consny.articulo where consny.articulo.CLASIFICACION_2='$boton' AND consny.articulo.CLASIFICACION_1 !='DETALLE' and consny.articulo.articulo='$cod'")or die($conexion1->error);
		$ncv=$cv->rowCount();
		//echo "<script>alert('$ncv')</script>";
		if($ncv!=0)
		{
			
			$fcv=$cv->fetch(PDO::FETCH_ASSOC);
			$cod=$fcv['ARTICULO'];
			$nom=$fcv['DESCRIPCION'];
			echo "<script>location.replace('ingreso.php?i=4&&art=$cod&&nom=$nom&&cod=$cod&&b= &&bu ')</script>";
		}else
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO DE: $cod, CON CLASIFICACION DE: $boton O NO SE ENCUENTRA ACTIVO')</script>";
			echo "<script>location.replace('ingreso.php?i=4&&b= &&bu= ')</script>";
		}
	}
}

if($_POST)
{
	extract($_REQUEST);
	if($opc==1)
	{
		$cd=$conexion1->query("select * from consny.articulo where consny.articulo.articulo='$cod1'")or die($conexion1->error);
		$ncd=$cd->rowCount();
		if($ncd==0)
		{
			echo "<script>alert('NO SE ENCONTRO NIGUN REGISTRO DE: $cod1')</script>";
			echo "<script>location.replace('ingreso.php?i=2&&b= &&bu ')</script>"; 
			
		}else
		{
			echo "<script>location.replace('ingreso.php?i=2&&b= &&bu= &&condd=$cod1')</script>";
		}
	}
}
?>

</body>
</html>