<!DOCTYPE html>
<html>
<head>
	<META HTTP-EQUIV="REFRESH" CONTENT="1800;URL=index.php">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			//alert('f');
			$("#lbs").focus();
			
		});
	</script>
	<script type="text/javascript" src="moment.min.js"></script>

	<script>

		function cambiar()
		{
			var fecha1 = moment($("#ac").val());
			var fecha2 = moment($("#fecha").val());
			var dia =fecha2.diff(fecha1, 'days');
		if(dia>0 || dia<=-29)
		{
			alert('FECHA NO VALIDA: '+$("#fecha").val());
			$("#fecha").val($("#ac").val());
		}

		}

		function empacados()
		{
			$("#empacadores").show();
		}
		function cerrarempaca()
		{
			$("#empacadores").hide();
		}
		function finalempaca()
		{
			var n=0;
			var texto='';
			var numero=$("#numero_empaca").val();
			while(n<=numero)
			{
			var t="#r"+n;
			if($(t).is(':checked'))
			{
				if(texto=='')
				{
					texto=$(t).val();
					//alert(texto);
				}else
				{
					texto=texto+"-"+$(t).val();

				}
			}else
			{
				//alert('falso');
			}
			n++;
		}
			$("#empacado").val(texto);
			$("#empacadores").hide();
		}
	</script>
	<?php
	
	error_reporting(0);
	session_start();
	if($_SESSION['usuario']=="")
	{
		echo "<script>location.replace('conexiones.php')</script>";
	}
	include("conexion.php");
	if($_SESSION['tipo']==2)
	{
		echo "<script>alert('NO TIENES AUTORIZACION PARA PRODUCCION')</script>";
		echo "<script>location.replace('desglose.php')</script>";
	}else if($_SESSION['tipo']==3)
	{
		echo "<script>location.replace('consultar.php')</script>";
	}
	
	$barra=$_SESSION['cbarra'];	
	$cambio=$_GET['c'];
	$ff=$_GET['f'];
	$btn=$_GET['ca'];
	$numf=$_GET['nf'];
	$lbs=$_GET['lb'];
	$unid=$_GET['un'];
	$empacado=$_GET['em'];
	$producido=$_GET['pr'];
	$digitado=$_GET['di'];
	$fecha=$_GET['fe'];
	$obs=$_GET['obs'];
	$empaqueg=$_GET['empaqs'];
	$ubicaciong=$_GET['ubik'];

	if($cambio=="")
	{
		if($_SESSION['cbarra']!="")
		{
			echo "<script>location.replace('ingreso.php')</script>";
		}
	}
	if($cambio==1)
	{
		
		$con=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error);
		$ncon=$con->rowCount();
		if($ncon==0)
		{
			$cambio="";
		}else
		{
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$n1=$fcon['numero_fardo'];
			$n2=$fcon['lbs'];
			$n3=$fcon['und'];
			$n4=$fcon['empacado'];
			$n5=$fcon['producido'];
			$n6=$fcon['digitado'];
			$fhoy=$fcon['fecha_documento'];
			$n7=$fcon['categoria'];
			$id=$fcon['id_registro'];
			$bog=$fcon['bodega'];
			$obs1=$fcon['observacion'];
			$n99=$fcon['ubicacion'];
			$empaques=$fcon['empaque'];

		}
		
	}





	if($ff==2 and $cambio==1)
	{
		$bodear=$_GET['bar'];
		$conexion2->query("delete from detalle where registro='$id'")or die($conexion2->error);
		
  $conexion2->query("update registro set
  	categoria='$btn',
  	numero_fardo='$numf',
  	lbs='$lbs',
  	und='$unid',
  	empacado='$empacado',
  	producido='$producido',
  	digitado='$digitado',
  	fecha_documento='$fecha',bodega='$bodear',observacion='$obs',bodega_produccion='$bodear',empaque='$empaqueg',ubicacion='$ubicaciong'
  	where barra='$barra'")or die($conexion2->error);
  echo "<script>location.replace('ingreso.php')</script>";
	}
		
		$paquete=$_SESSION['paquete'];
		if($paquete=="")
		{
			echo "<script>location.replace('conexiones.php')</script>";
		}

		if($fhoy=="")
		{
			$hoy=date("Y-m-d");
		}else
		{
			$hoy=$fhoy;
		}
		

	
	?>
	<title></title>
	<script>

	</script>
</head>
<body>
	<center>

<div id="empacadores" style="position: fixed; width: 105%; height: 105%; background-color: rgb(0,0,0,0.3); margin-top: -5.5%;  margin-left: -5%; display: none;">
<a href="#" onclick="cerrarempaca()" style="float: right; margin-right: 2%; margin-top: 0.5%; text-decoration: none; color: white;">CERRAR X</a><br>

<div class="adentro" style="width: 93%; height: 95%; margin-left: 4%; padding-top: -5%;">

<button style="float: right; margin-right: 4%; position:  sticky; top: 25%; background-color: black; color: white; border-color: white; padding: 1%; cursor:pointer;" onclick="finalempaca()">OK</button>

<?php
//no carga empacado revisar lo ultimo agregado fue button ok

$consulta=$conexion1->query("select * from PRODUCCION_ACCPERSONAL where empaca='1' and activo='1'")or die($conexion1->query());
echo "<table border='0' style='border-collapse:collapse; width:98%; margin-bottom:8%;' cellpadding='10'>";
$numero=0;
while($fila=$consulta->FETCH(PDO::FETCH_ASSOC))
{
	$codigo=$fila['CODIGO'];
	$nom=$fila['NOMBRE'];
	$codigo_barra=$_SESSION['cbarra'];
	$crem=$conexion2->query("select * from registro where barra='$codigo_barra' and empacado like '%$nom%'")or die($conexion2->error());
	$ncrem=$crem->rowCount();
	if($ncrem==0)
	{
		echo "<tr>
	<td>
	<label>
	<input type='checkbox' name='r$numero' id='r$numero' value='$nom'> $nom</label>
	</td>
	</tr>";
}else
{
	echo "<tr>
	<td>
	<label>
	<input type='checkbox' name='r$numero' id='r$numero' value='$nom' checked> $nom</label>
	</td>
	</tr>";
}
	
	$numero++;
	
}
$nom="BODEGA SAN MIGUEL";
$numero++;
$codigo_barra=$_SESSION['cbarra'];
	$crem=$conexion2->query("select * from registro where barra='$codigo_barra' and empacado like '%$nom%'")or die($conexion2->error());
	$ncrem=$crem->rowCount();

	if($ncrem==0)
	{
		echo "<tr>
	<td>
	<label>
	<input type='checkbox' name='r$numero' id='r$numero' value='$nom'> $nom</label>
	</td>
	</tr>";
}else
{
	echo "<tr>
	<td>
	<label>
	<input type='checkbox' name='r$numero' id='r$numero' value='$nom' checked> $nom</label>
	</td>
	</tr>";
}

echo "<input type='hidden' name='numero_empaca' id='numero_empaca' value='$numero'>";
echo "</table>";
?>
<hr>

</div>


</div>



		<input type="hidden" name="ac" id="ac" value='<?php echo "$hoy";?>'>
	<form method="POST">
<table class="tabla" border="0">
	<tr>
		<td><legend>NUMERO FARDO<br>
			<input type="number" name="numf" class='text' style="padding-top: 1%; padding-bottom: 1%; width: 70%;" value='<?php if($numf!=""){ echo "$numf"; }else{ echo "$n1"; } ?>'>
			<br><br>
		</td>
		<td>LBS<br>
			<input type="text" name="lbs" id='lbs' class="text" style="padding-top: 1%; padding-bottom: 1%; width: 70%;" value='<?php if($lbs!=""){echo "$lbs";}else{ echo "$n2";}?>' required><br><br>
		</td>
		<td>UNID<br>
			<input type="text" name="unid" class='text' style="padding-top: 1%; padding-bottom: 1%; width: 68%;" value='<?php if($unid!=""){echo "$unid";}else{echo "$n3";}?>'><br><br>
		</td>
	</tr>

	<tr>
		<td colspan="2">FECHA PRODUCCION<br>
			<input type="date" name="fecha" id="fecha" value='<?php echo "$hoy";?>' class='text' onchange='cambiar()' required><br><br>
		</td>
		<td>UBICACION<br>
			<input type="text" name="ubic" class='text' style="padding-top: 1%; padding-bottom: 1%; width: 68%;" value='<?php if($ubic!=""){echo "$ubic";}else{echo "$n99";}?>'><br><br>
		</td>
	</tr>

	<tr>
		<td colspan="2">EMPACADO POR<br>
			<input required ondblclick="empacados()" type="text" name="empacado" id="empacado" class='text'  value='<?php if($empacado!=""){echo "$empacado";}else if($n4!=''){ echo "$n4";}?>'  readonly ><br><br>
		</td>
		<td>TIPO EMPAQUE<br>
			<select name="empaq" class='text' style="padding-top: 1%; padding-bottom: 1%; width: 68%;"  required>
				<?php
					if($empaques!='')
					{
						if($empaques=='CAJA PEQ')
						{
							echo "<OPTION value='CAJA PEQ'>CAJA PEQUEÑA</OPTION>";
						}else if($empaques=='BOLSA PEQ')
						{
							echo "<OPTION valuel='BOLSA PEQ'>BOLSA PEQUEÑA</OPTION>";
						}else
						{
							echo "<option>$empaques</option>";
						}
					}else
					{
						echo "<option value=''>(SELECCIONE EMPAQUE)</option>";
					}
				?>
				
				<option value="CAJA GRANDE">CAJA GRANDE</option>
				<OPTION value="CAJA PEQ">CAJA PEQUEÑA</OPTION>
				<OPTION value="BOLSA GRANDE">BOLSA GRANDE</OPTION>
				<OPTION valuel="BOLSA PEQ">BOLSA PEQUEÑA</OPTION>
</select>
				<br><br>
		</td>
	</tr>

	<tr>
		<td colspan="3">PRODUCIDO POR<br>
			<input type="text" name="producido" class='text' value='<?php if($producido!=""){echo "$producido";}else{echo "$n5";}?>'><br><br>
		</td>
	</tr>

	<tr>
		<td colspan="3">DIGITADO POR<br>
			<input type="text" name="digitado" class='text' value='<?php if($digitado!=""){echo "$digitado";}else{ echo "$n6";}?>'><br><br>
		</td>
	</tr>

	<tr>
		<td colspan="3">BODEGA:<br>
			<select class="text" name="bodegaart">
	<?php 
	$bode = $_SESSION['bodega'];
	if($cambio==1)
	{
		$bode=$bog;
	}
	if($cambio=='no')
	{
		if($_GET['bar'])
		{
			$bode=$_GET['bar'];
		}
		
	}
	{

	}
	$query=$conexion1->query("select * from consny.bodega where bodega='$bode'")or die($conexion1->error);
	$fquery=$query->FETCH(PDO::FETCH_ASSOC);
	$coda=$fquery['BODEGA'];
	$noma=$fquery['NOMBRE'];
	//echo "<option value='$coda'>$coda: $noma</option>";
	$query2=$conexion1->query("select * from consny.BODEGA where bodega like 'sm%' and nombre not like '%(ruta)%' and bodega='SM00'
")or die($conexion1->error);
	while($fquery2=$query2->FETCH(PDO::FETCH_ASSOC))
	{
		$codbo=$fquery2['BODEGA'];
		$nombo=$fquery2['NOMBRE'];
		echo "<option value='$codbo'>$codbo: $nombo</option>";
	}
	
	?>
			</select><br><br>
		</td>
	</tr>
	<tr>
		<td colspan="3">OBSERVACIONES:<br>
			<input type="text" class="text" name="obs" value='<?php echo "$obs1";?>'>
				
			</textarea></td>
	</tr>
</table>
<input type="submit" name="btn" value="ROPA" class="boton1">

<input type="submit" name="btn" value="CARTERAS" class="boton1">

<input type="submit" name="btn" value="CINCHOS" class="boton1">

<input type="submit" name="btn" value="GORRAS" class="boton1">

<input type="submit" name="btn" value="JUGUETES" class="boton1">

<input type="submit" name="btn" value="ZAPATOS" class="boton1">

<input type="submit" name="btn" value="OTROS" class="boton1">

<input type="submit" name="btn" value="GANCHOS" class="boton1">

<?php
if($_POST)
{
	extract($_REQUEST);
	if($empacado=='')
	{
		$empacado="BODEGA SAN MIGUEL";
	}
	if($fecha=='')
	{
		$fecha=date("Y-m-d");
	}
	$_SESSION['op']=1;

	if($cambio=="")
{
	$letra=chr(rand(ord("A"), ord("Z")));
	$e=substr($fecha,0);
	$a="$e[2]$e[3]";
	$m="$e[5]$e[6]";
	$d="$e[8]$e[9]";
	$c=$conexion2->query("select max(session) as id_registro from dbo.registro where fecha_documento='$fecha' and tipo='P'")or die($conexion2->error);
	$f=$c->fetch( PDO::FETCH_ASSOC);
	$session=$f['id_registro'];
	$session=$session+1;
	//echo "<script>alert('$session')</script>";
	
	$_SESSION['session']=$session;
	$_SESSION['fecha']=$fecha;
	if($session>0 and $session<10)
	{
		$num="00$session";
	}else if($session>9 and $session<100)
	{
		$num="0$session";
	}else
	{
		$num=$session;
	}
	$segundo=date("s");
	$minuto=date("i");
	$o="P";
	$barra="$letra$o$d$m$num$a";
$nums=1;
while($nums!=0)
{
	$k=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error);
	$nk=$k->rowCount();
	if($nk!=0)
	{
		$session=$session+1;
	//echo "<script>alert('$session')</script>";
	
	$_SESSION['session']=$session;
	$_SESSION['fecha']=$fecha;
	if($session>0 and $session<10)
	{
		$num="00$session";
	}else if($session>9 and $session<100)
	{
		$num="0$session";
	}else
	{
		$num=$session;
	}
	$segundo=date("s");
	$minuto=date("i");
	$o="P";
	$barra="$letra$o$d$m$num$a";
	$nums=1;
	}else
	{
		$nums=0;
	}
}


	$_SESSION['cbarra']=$barra;
	$paq=$_SESSION['paquete'];
	$usui=$_SESSION['usuario'];	
	//$emp=$_SESSION['empaq'];	
	if($paq=='' or $usui=='')
	{
		echo "<script>location.replace('conexiones.php')</script>";
	}
	
	$conexion2->query("insert into registro(categoria,
	numero_fardo,
	lbs,
	und,
	empacado,
	producido,
	digitado,
	fecha_documento,
	barra,session,codigo,estado,paquete,usuario,bodega,tipo,observacion,fecha_ingreso,bodega_produccion,ubicacion,empaque) values('$btn',
	'$numf',
	'$lbs',
	'$unid',
	'$empacado',
	'$producido',
	'$digitado',
	'$fecha',
	'$barra','$session','','0','$paq','$usui','$bodegaart','P','$obs',getdate(),'$bodegaart','$ubic','$empaq')")or die($conexion2->error);
	echo "<script>location.replace('ingreso.php')</script>";


}else if($cambio==1)
{
	if($n7 !=$btn)
	{
		echo "<script>
if(confirm('SEGURO DESEA CAMBIAR CATEGORIA DE $n7 por $btn\\n!SE VACIARA EL DETALLE DE LA PRODUCION DE $n7!'))
{
location.replace('index.php?c=1&&ca=$btn&&nf=$numf&&lb=$lbs&&un=$unid&&em=$empacado&&pr=$producido&&di=$digitado&&fe=$fecha&&bar=$bodegaart&&f=2&&obs=$obs&&empaqs=$empaq&&ubik=$ubic');
}else
	{
		location.replace('ingreso.php?i=2&&b= &&bu= ');
		
	}
</script>";
//falta ver k al dar cancelar siempre se cambia de categoria
//ver eso

	}else
	{

	$conexion2->query("update registro set
  	categoria='$btn',
  	numero_fardo='$numf',
  	lbs='$lbs',
  	und='$unid',
  	empacado='$empacado',
  	producido='$producido',
  	digitado='$digitado',
  	fecha_documento='$fecha',
  	registro.bodega='$bodegaart',observacion='$obs',ubicacion='$ubic',empaque='$empaq'
  	where barra='$barra'")or die($conexion2->error);
		
  echo "<script>location.replace('ingreso.php?i=2&&b= &&bu= ')</script>";

	}
//colocar l opcion de finalizar en producion y tomar el documento_inv de producion


}

}

	

?>


</body>
</html>