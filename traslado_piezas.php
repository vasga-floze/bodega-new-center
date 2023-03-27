<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			//alert('dfvdfv');
			$("#imgn").hide();
			if($("#s_empresa").val()!='')
			{
				$("#btnorigen").show();
				$("#formorigen").show();
				$("#origen").focus();
			}
			
			if($("#origi").val()=='1')
			{
				$("#formdestino").show();
				$("#btndestino").show();
				$("#destino").focus();
			}else
			{
				$("#formdestino").hide();
				$("#btndestino").hide();
				
			}

			if($("#origi1").val()==1)
			{
				$("#formart").show();
				$("#btnarticulo").show();
				$("#art").focus();
			}else
			{
				$("#formart").hide();
				$("#btnarticulo").hide();	
			}

			if($("#des").val()!='')
			{
				$("#cantidad").focus();
			}

		})

		function activaorigen()
		{
			location.replace('bode_origen_traslado_piezas.php?origen='+$("#origen").val()+'&&destino='+$("#destino").val());
		}

		function activardestino()
		{
			location.replace('bode_destino_traslado_piezas.php?origen='+$("#origen").val()+'&&destino='+$("#destino").val());
		}

		function enviar()
		{
			$("#op").val('1');
			$("#formorigen").submit();
		}

		function enviar1()
		{
			if($("#origen_session").val()==$("#origen").val() || $("#origen_session").val()=='')
			{
				$("#op").val('2');
					$("#formorigen").submit(true);
			}else
			{
				if(confirm('SEGURO DESEA CAMBIAR LA BODEGA DE ORIGEN SE CAMBIARA PARA TODO EL TRASLADO'))
				{
					$("#op").val('2');
					$("#formorigen").submit(true);
				}else
				{
					$("#formorigen").submit(false);
				}
			}
			
		}

		function enviardestino()
		{

			$("#op1").val('3');
			$("#formdestino").submit();
		}
		function enviardestino1()
		{
			if($("#origen").val()==$("#destino").val())
			{
				alert('ERROR: TRASLADO NO VALIDO LA BODEGA ORIGEN ES IGUAL A LA BODEGA DESTINO');
				location.replace('traslado_piezas.php?origen='+$("#origen").val());
			}else
			{
				//alert($("#destino_session").val()+''+$("#destino").val());
				if($("#destino_session").val()==$("#destino").val() && $("#destino_session").val()=='')
				{
					$("#op1").val('4');
					$("#formdestino").submit(true);
				}else
				{
					if(confirm('SEGURO DESEA CAMBIAR LA BODEGA DE DESTINO SE CAMBIARA PARA TODO EL TRASLADO'))
					{
						$("#op1").val('4');
					$("#formdestino").submit(true);
					}else
					{
					$("#formdestino").submit(false);

					}
					
				}
				
			}
		}
		function activaarticulo()
		{
			
		location.replace('articulos_tpiezas.php?origen='+$("#origen").val()+'&&destino='+$("#destino").val()+'&&art='+$("#art").val());
		}

		function cambiarempresa()
		{
			$("#formempresa").submit();
		}

		function enviarart()
		{
			$("#opart").val('1');
			$("#formart").submit();
		}

		function enviarart1()
		{
			$("#opart").val('2');
			$("#formart").submit();
		}
		
		function cancelar()
		{
			if(confirm('SEGURO DESEA CANCELAR EL TRASLADO'))
			{
				$("#formfinal").submit(true);
			}else
			{
				$("#formfinal").submit(false);

			}
		}

		function eliminare(e)
		{
			var ori=$("#origen").val();
			var des=$("#destino").val();
			var art=$("#art").val();
			if(confirm('SEGURO DESEA ELIMINAR LA LINEA'))
			{
				location.replace('eliminar_tpiezas.php?origen='+ori+'&&destino='+des+'&&articulo='+art+'&&id='+e);
			}
		}
	</script>
</head>
<body>
<img src="load2.gif" width="3.5%" height="5%" id='imgn' style="float: center; margin-left: 40%; margin-top: 25%; position: fixed;">
<?php
include("conexion.php");
?>
<center>
	<input type="hidden" name="s_empresa" id="s_empresa" value='<?php echo "".$_SESSION['empresa_tpieza']."";?>'>
<form id="formempresa" method="POST" action="empresatpiezas.php" style="float: center;">
	<label>EMPRESA: <select name="empresa" id="empresa" onchange="cambiarempresa()" class="text" style="width: 15%;">
	<?php
	if($_SESSION['tpiezas']!='')
	{
		echo "<option>".$_SESSION['empresa_tpieza']."</option>";
	}else
	{
		
		if($_SESSION['empresa_tpieza']!='')
		{
			echo "<option>".$_SESSION['empresa_tpieza']."</option>";
		}else
		{
			echo "<option value=''>EMPRESA</option>";
		}
		$qconjunto=$conexion1->query("select * from conjunto")or die($conexion1->error());
		while($fqconjunto=$qconjunto->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<option>".$fqconjunto['CONJUNTO']."</option>";
		}
	}

	
	?>
	
</select>
</label>
</form>
</center>
<?php
error_reporting(0);
$origen=$_GET['origen'];
if($_GET['origen']!='')
{
	$origen=$_GET['origen'];
}else if($_SESSION['origen_tpiezas']!='')
{
	$origen=$_SESSION['origen_tpiezas'];

}
if($_GET['origen']==$_SESSION['origen_tpiezas'] and$_SESSION['origen_tpiezas']!='')
{
	$valor=1;
	if($_GET['destino']==$_SESSION['destino_tpiezas'] and $_SESSION['destino_tpiezas']!='')
	{
		$valor1=1;
	}else
	{
		$valor1=0;
	}
}else
{
	$valor1=0;
}
$empresa=$_SESSION['empresa_tpieza'];
$co=$conexion1->query("select * from $empresa.bodega where bodega='$origen'")or die($conexion1->error());

$fco=$co->FETCH(PDO::FETCH_ASSOC);

$destino=$_GET['destino'];

$cd=$conexion1->query("select * from $empresa.bodega where bodega='$destino'")or die($conexion1->error());
$fcd=$cd->FETCH(PDO::FETCH_ASSOC);

$art=$_GET['art'];
echo $art;
$ca=$conexion1->query("select * from $empresa.articulo where articulo='$art'")or die($conexion1->error());
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
?>
<BR><BR><BR>


<button class="boton4" onclick="activaorigen()" id="btnorigen" style="display: none;">ORIGEN</button>
<form method="POST" id="formorigen" style="display: none;">
	<input type="hidden" name="origen_session" id="origen_session" value='<?php echo $_SESSION['origen_tpiezas'];?>'>
	<input type="hidden" name="origi" id="origi" value='<?php echo "$valor";?>'>
	<input type="hidden" name="origi1" id="origi1" value='<?php echo "$valor1";?>'>

	<input type="text" name="origen" id="origen" class="text" style="width: 20%;" placeholder="BODEGA ORIGEN" onchange="enviar()" onkeyup="this.value=this.value.toUpperCase()" value='<?php echo "".$fco['BODEGA']."";?>'>
	<input type="text" name="nom_origen" id="nom_origen" class="text" style="width: 60%;" placeholder="NOMBRE BODEGA" value='<?php echo "".$fco['NOMBRE']."";?>'>
	<input type="hidden" name="op" id="op">
	<input type="submit" name="btn" value="SIGUIENTE" class="boton3" onclick="enviar1()">
</form><br>

<button class="boton4" id='btndestino' style="display: none;" onclick="activardestino()">DESTINO</button>
<form method="POST" id="formdestino" style="display: none;">

	<input type="hidden" name="op1" id="op1">
	<input type="hidden" name="destino_session" id="destino_session" value='<?php echo $_SESSION['destino_tpiezas'];?>'>

	<input type="text" name="destino" id="destino" class="text" style="width: 20%;" placeholder="BODEGA DESTINO" onkeyup="this.value=this.value.toUpperCase()" value='<?php echo "".$fcd['BODEGA']."";?>' onchange="enviardestino()">

	<input type="text" name="nom_destino" id="nom_destino" class="text" style="width: 60%;" placeholder="NOMBRE BODEGA" value='<?php echo "".$fcd['NOMBRE']."";?>'>

	<input type="submit" name="btn" value="SIGUIENTE." class="boton3" onclick="enviardestino1()">
</form>

<BR>
<button class="boton4" id='btnarticulo' onclick="activaarticulo()">ARTICULO</button>
<form method="POST" id="formart">

<input type="hidden" name="opart" id="opart">
<input type="text" name="art" id="art" placeholder="ARTICULO" class="text" style="width: 15%;" value='<?php echo "".$fca['ARTICULO']."";?>' onchange='enviarart()' required>

<input type="text" name="des" id="des" placeholder="DESCRIPCION" class="text" style="width: 49%;" value='<?php echo "".$fca['DESCRIPCION']."";?>'>

<input type="number" name="cantidad" id="cantidad" placeholder="CANTIDAD" class="text" style="width: 15%;">

<input type="submit" name="btn" value="ADD." class="boton3" onclick="enviarart1()">

</form>
<?php
$hoy=date("Y-m-d");
if($_SESSION['tpiezas']!='')
{
	$doc=$_SESSION['tpiezas'];
	$user=$_SESSION['usuario'];
	$ct=$conexion2->query("select * from traslado_piezas where session='$doc' and usuario='$user' order by id desc")or die($conexion2->error());
	$nct=$ct->rowCount();
	echo "<input type='hidden' name='nct' id='nct' value='$nct'>";
	if($nct!=0)
	{

		echo "<table border='1' style='border-collapse:collapse; width:100%; margin-top:3%;' cellpadding='10'>";
		echo "
		<tr>
		<td colspan='7'><form method='POST' name='formfinal' id='formfinal'>";
		echo "<input type='submit' name='btn' value='CANCELAR TRASLADO' class='boton3' style='background-color:red; float:right;' onclick='cancelar()'>
		<label style=''>FECHA TRASLADO:<input type='date' name='fecha' id='fecha' class='text' style='width:15%;' value='$hoy'></label>
		<input type='submit' name='btn' value='FINALIZAR' class='boton3' style='background-color:BLACK;'>
		
		</form></td><tr>
		<tr>
			<td>EMPRESA</td>
			<td>BODEGA ORIGEN</td>
			<td>BODEGA DESTINO</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CANTIDAD</td>
			<td width='5%'>ELIMINAR</td>
		</tr>";
		$cantidade=0;
		while($fct=$ct->FETCH(PDO::FETCH_ASSOC))
		{
			$empresa=$fct['empresa'];
			$art=$fct['articulo'];
			$cantidad=$fct['cantidad'];
			$borigen=$fct['origen'];
			$bdestino=$fct['destino'];
			$id=$fct['id'];

			$cbo=$conexion1->query("select * from $empresa.bodega where bodega='$borigen'")or die($conexion1->error());
			$fcbo=$cbo->FETCH(PDO::FETCH_ASSOC);
			$borigen="".$fcbo['BODEGA'].": ".$fcbo['NOMBRE']."";
			//-------------------------------------------------------
			$cbd=$conexion1->query("select * from $empresa.bodega where bodega='$bdestino'")or die($conexion1->error());
			$fcbd=$cbd->FETCH(PDO::FETCH_ASSOC);
			$bdestino="".$fcbd['BODEGA'].": ".$fcbd['NOMBRE']."";
			$arti=$fct['articulo'];
			$cart=$conexion1->query("select * from $empresa.articulo where articulo='$arti'")or die($conexion1->error());
			$num=1;
			$fcart=$cart->FETCH(PDO::FETCH_ASSOC);
			echo "<tr>
			<td>$empresa</td>
			<td>$borigen</td>
			<td>$bdestino</td>
			<td>".$fcart['ARTICULO']."</td>
			<td>".$fcart['DESCRIPCION']."$num</td>
			<td>$cantidad</td>
			<td><img src='eliminar.png' width='45%' height='35%' onclick='eliminare($id)' style='cursor:pointer;'></td>
		</tr>";	
		$cantidade=$cantidade + $cantidad;
		$num++;
		}
		echo "<tr>
			<td colspan='5'>TOTAL:</td>
			<td>$cantidade</td>
			
		</tr>";
	}
}
?>

<?php
if($_POST)
{
	extract($_REQUEST);
		//echo "<script>alert('entrass $op d')</script>";
	$empresa=$_SESSION['empresa_tpieza'];
	if($op==1)
	{
		$qo=$conexion1->query("select * from $empresa.bodega where bodega='$origen'")or die($conexion1->error());
	$nqo=$qo->rowCount();
		if($nqo==0)
		{
			echo "<script>alert('NO SE ENCONTRO LA BODEGA O NO ESTA DISPONIBLE EN LA EMPRESA SELECIONADA: $origen')</script>";
			echo "<script>location.replace('traslado_piezas.php')</script>";
		}else
		{
			echo "<script>location.replace('traslado_piezas.php?origen=$origen')</script>";
		}
	}else if($op==2)
	{

		$cvo=$conexion1->query("select * from $empresa.bodega where bodega='$origen'")or die($conexion1->error());
		$ncvo=$cvo->rowCount();
		if($ncvo==0)
		{
			echo "<script>location.replace('NO SE PUDO AGREGAR LA BODEGA DE ORIGEN, INTENTELO NUEVAMENTE')</script>";

			echo "<script>location.replace('traslado_piezas.php?destino=$destino')</script>";
		}else
		{
			if($_SESSION['tpiezas']=='')
			{
				$_SESSION['origen_tpiezas']=$origen;
			echo "<script>location.replace('traslado_piezas.php?origen=$origen&&destino=$destino')</script>";
		}else
		{
			$doc=$_SESSION['tpiezas'];
			$usu=$_SESSION['usuario'];
			$_SESSION['origen_tpiezas']=$origen;
			$conexion2->query("update traslado_piezas set origen='$origen' where usuario='$usu' and session='$doc'")or die($conexion2->error());
			echo "<script>location.replace('traslado_piezas.php?origen=$origen&&destino=$destino&&h=3')</script>";



		}
		}
	}
	//echo "<script>alert('$op1 $destino')</script>";
	if($op1==3)
	{
		$cd=$conexion1->query("select * from $empresa.bodega where bodega='$destino'")or die($conexion1->error());
		$ncd=$cd->rowCount();
		//echo "<script>alert('$ncd')</script>";
		if($ncd==0)
		{
			//echo "<script>alert('$destino 5')</script>";

			echo "<script>alert('NO SE ENCONTRO LA BODEGA $destino EN LA EMPRESA SELECIONADA')</script>";
		}else
		{
			//echo "<script>alert('$destino 2')</script>";

			echo "<script>location.replace('traslado_piezas.php?origen=$origen&&destino=$destino&y=0')</script>";
		}
	}else if($op1==4)
	{
		if($_SESSION['tpiezas']=='')
		{
			$_SESSION['destino_tpiezas']=$destino;
			echo "<script>location.replace('traslado_piezas.php?origen=$origen&&destino=$destino&&y=5')</script>";

		}else
		{
			$doc=$_SESSION['tpiezas'];
			$usu=$_SESSION['usuario'];
			$conexion2->query("update traslado_piezas set destino='$destino' where usuario='$usu' and session='$doc'")or die($conexion2->error());
			$_SESSION['destino_tpiezas']=$destino;
			echo "<script>location.replace('traslado_piezas.php?origen=$origen&&destino=$destino&&y=3')</script>";
		}
	}
			
	
	if($opart==1)
	{
		$empresa=$_SESSION['empresa_tpieza'];
		//echo "<script>alert('entras')</script>";
		$ca=$conexion1->query("select * from $empresa.articulo where articulo='$art'")or die($conexion1->error());
		$nca=$ca->rowCount();
			
		if($nca==0)
		{
			echo "<script>alert('NO SE ENCONTRO ARTICULO O NO ESTA DISPONIBLE')</script>";

			echo "<script>location.replace('traslado_piezas.php?art=$art&&origen=$origen&&destino=$destino')</script>";
		}else
		{
			//echo "<script>alert('$opart $art $empresa $nca $art')</script>";
			echo "<script>location.replace('traslado_piezas.php?art=$art&&origen=$origen&&destino=$destino')</script>";

		}
	}else if($opart==2)
	{
		//echo "<script>alert('entra')</script>";
		if($_SESSION['tpiezas']=='')
		{
			$c=$conexion2->query("select max(session) as session from traslado_piezas")or die($conexion2->error());
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$_SESSION['tpiezas']=$f['session']+1;
		}
		$doc=$_SESSION['tpiezas'];
		$usu=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		$empresa=$_SESSION['empresa_tpieza'];
		$origenes=$_SESSION['origen_tpiezas'];
		$destinos=$_SESSION['destino_tpiezas'];
		$conexion2->query("insert into traslado_piezas(fecha_ingreso,origen,destino,usuario,paquete,articulo,cantidad,documento,session,empresa) values(getdate(),'$origenes','$destinos','$usu','$paquete','$art','$cantidad','- -','$doc','$empresa')")or die($conexion2->error());
		echo "<script>location.replace('traslado_piezas.php?origen=$origen&&destino=$destino')</script>";
	}

	if($btn=='CANCELAR TRASLADO')
	{
		$usu=$_SESSION['usuario'];
		$doc=$_SESSION['tpiezas'];
		$conexion2->query("delete from traslado_piezas where session='$doc' and usuario='$usu'")or die($conexion2->error());
		$_SESSION['tpiezas']='';
		$_SESSION['origen_tpiezas']='';
		$_SESSION['destino_tpiezas']='';
		$_SESSION['empresa_tpieza']='';
		echo "<script>alert('CANCELADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('traslado_piezas.php')</script>";
	}else if($btn=='FINALIZAR')
	{
		$cf=$conexion2->query("select max(correlativo) as correlativo,year('$fecha') as anio from traslado_piezas where year(fecha)=year('$fecha')")or die($conexion2->error());

		$fcf=$cf->FETCH(PDO::FETCH_ASSOC);
		$correlativo=$fcf['correlativo']+1;
		if($correlativo>=999999)
		{

		}else
		{
			$correlativo1=str_pad($correlativo,7,"0",STR_PAD_LEFT);
			
		}
		$anio=substr($fcf['anio'], 2,4);
		$documento="TRA-$anio$correlativo1";

		$usuario=$_SESSION['usuario'];
		$doc=$_SESSION['tpiezas'];
		$conexion2->query("update traslado_piezas set documento='$documento',fecha='$fecha',correlativo='$correlativo' where usuario='$usuario' and session='$doc'")or die($conexion2->error());
		echo "<script>alert('TRASLADO FINALIZADO CORRECTAMENTE')</script>";
		$_SESSION['empresa_tpieza']='';
		$_SESSION['origen_tpiezas']='';
		$_SESSION['destino_tpiezas']='';
		$_SESSION['tpiezas']='';
		echo "<script>location.replace('imprimir_traslado_piezas.php?doc=$doc&&usu=$usuario')</script>";
	}

	
}
?>
</body>
</html>