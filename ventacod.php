<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<style type="text/css">
		.btn
		{
			background-color: #4F927E;
			color: white;
			padding: 0.5%;

		}
	</style>
	<script>
		$(document).ready(function(){
			
			if($("#confirmacion").val()==1)
			{
				$("#form1").show();
				
				$("#btna").show();
				if($("#articulo").val()!='')
			{
				$("#cantidad").focus();
			}else
			{
				$("#articulo").focus();
			}

			}else
			{
				$("#form1").hide();
				$("#btna").hide();
			}

		})

		function buscar()
		{
			$("#op").val('1');
			$("#form1").submit();
		}
		function enviar()
		{
			$("#op").val('2');
		}
	</script>
</head>
<body>
<?php
error_reporting(0);
include("conexion.php");
$hoy=date("Y-m-d");
if($_SESSION['tipo']!=1)
{
	echo "<script>alert('NO DISPONIBLE')</script>";
	echo "<script>location.replace('desglose.php')</script>";
}
$c=$conexion1->query("select * from consny.consecutivo_CI where consecutivo='venta'")or die($conexion1->error());
$f=$c->FETCH(PDO::FETCH_ASSOC);
$conse=$f['SIGUIENTE_CONSEC'];
echo "CONSECUTIVO: $conse";
$art=$_GET['art'];

$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());

$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$arti=$fca['ARTICULO'];
$descri=$fca['DESCRIPCION'];
$precio=$fca['PRECIO_REGULAR'];

$ventacod=$_SESSION['ventacod'];

if($ventacod!='')
{
	$confirmacion=1;
	$user=$_SESSION['usuario'];
	$cp=$conexion2->query("select * from venta where sessiones='$ventacod' and usuario='$user'")or die($conexion2->error());

	$fcp=$cp->FETCH(PDO::FETCH_ASSOC);
	$hoy=$fcp['fecha'];
}
?><br>
<input type="hidden" name="confirmacion" id="confirmacion" value='<?php echo "$confirmacion";?>'>
<form method="POST">
	<input type="text" name="cliente" class="text" style="width: 30%;" placeholder="Cliente" required value='<?php echo "".$fcp['cliente']."";?>'>

	<input type="date" name="fecha" class="text" style="width: 30%;" value='<?php echo "$hoy";?>' required>
	<select class="text" name="bodega" style="width: 20%;" required>
		<?php
		if($fcp['bodega_venta']!='')
		{
			echo "<option>".$fcp['bodega_venta']."</option>";
		}else
		{
			echo "<option value=''>Bodega Venta</option>";
		}
		?>

		
		<?php
			$c=$conexion1->query("select * from consny.bodega where bodega like 'SM%' and nombre not like'%(N)%'")or die($conexion1->error());
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				echo "<option>".$f['BODEGA']."</option>";
			}
			
		?>
	</select>
	<input type="submit" name="btn" id="btn" value="Siguiente" class="btn">

</form><br>

<a href="add_ventacod.php">
	<button class="btn" id="btna" style="background-color: #BDD8D2; margin-left: 4%; cursor: pointer;">Articulos</button></a><br>
<form style="margin-left: 4%;" method="POST" id="form1">	
	<input type="text" name="articulo" id="articulo" placeholder="ARTICULO" class="text" style="width: 20%;" onchange="buscar();" value='<?php echo "$arti";?>'>
	<input type="text" name="desc" id="desc" placeholder="DESCRIPCION" class="text" style="width: 40%;" value='<?php echo "$descri";?>'>

	<input type="number" name="cantidad" id="cantidad" placeholder="CANTIDAD" class="text" style="width: 10%;" required>
	<input type="number" name="precio" step="any" lang="en" min="0" placeholder="PRECIO" class="text" style="width: 20%;" value='<?php echo "$precio";?>' >
	<input type="hidden" name="op" id="op">
	<input type="submit" name="btn" value="Add." class="btn" onclick="enviar()">
</form>

<?php
$ventacod=$_SESSION['ventacod'];
$usuario=$_SESSION['usuario'];

	$q=$conexion2->query("select * from venta where sessiones='$ventacod' and usuario='$usuario' and articulo is not null order by id desc")or die($conexion2->error());

	$nq=$q->rowCount();
	if($nq==0)
	{

	}else
	{
		echo "<br><table border='1' width='100%' cellpadding='10' style='border-collapse:collapse;'>
			<tr>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
				<td>PRECIO</td>
				<td>CANTIDAD</td>
				<td>TOTAL</td>
				<td>OPCION</td>
			</tr>
		";
		$totalp=0;
		$tc=0;
		while($fq=$q->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fq['articulo'];
			$cantidad=$fq['cantidad'];
			$precio=$fq['precio'];
			$id=$fq['id'];
			if($precio=='')
			{
				$precio=0;
			}
			$total=$cantidad * $precio;
			$totalp=$totalp+$total;
			$ca=$conexion1->query("select * from consny.articulo where ARTICULO='$art'")or die($conexion1->error());
			
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);

			echo "<tr>
				<td>$art</td>
				<td>".$fca['DESCRIPCION']."</td>
				<td>$precio</td>
				<td>$cantidad</td>
				<td>$total</td>
				<td><a href='eli_ventacod.php?id=$id' style='text-decoration:none;'>ELIMINAR</a></td>

			</tr>";
			$tc=$tc+$cantidad;
		}

		echo "<tr>
			<td colspan='3'>Total:</td>
			<td>$tc</td>
			<td>$totalp</td>
			<td></td>
		</tr>";

		echo "<tr>
		<td colspan='6'>
		<form method='POST'>
		Consecutivo: <input type='text' name='conse' value='$conse' class='text' style='width:20%;'> Observaciones: <input type='text' name='observacion' class='text' style='width:40%;'>
		<input type='submit' name='btn' value='FINALIZAR' class='btnfinal' style='padding:0.6%; margin-bottom:-3%;'>
		</form>
		</td>
		</tr></table>";
	}

?>



<?php
if($_POST)
{
	extract($_REQUEST);
	//echo "<script>alert('$op')</script>";
	if ($btn=='Siguiente') 
	{
		//echo "<script>alert('df')</script>";
		if($_SESSION['ventacod']=='')
		{
		//saco ultima session
		$cs=$conexion2->query("select max(sessiones) as sessiones from venta")or die($conexion2->error());

		$fcs=$cs->FETCH(PDO::FETCH_ASSOC);

		$secion=$fcs['sessiones'] + 1;

		$v=1;
		while($v==1)
		{
			$usuario=$_SESSION['usuario'];
			$cv=$conexion2->query("select * from venta where sessiones='$secion'")or die($conexion2->eror());
			$ncv=$cv->rowCount();
			if($n==0)
			{
				$v=0;
			}else
			{
				$secion++;
				$v=1;
			}
		}
		// fin validacion si existe la session
		$paquete=$_SESSION['paquete'];
		$usuario=$_SESSION['usuario'];

		$conexion2->query("insert into venta(cliente,fecha,paquete,usuario,sessiones,bodega_venta,fecha_ingreso)values('$cliente','$fecha','$paquete','$usuario','$secion','$bodega',getdate())")or die($conexion2->error());
		$_SESSION['ventacod']=$secion;
		//echo "<script>alert('guarda')</script>";
		echo "<script>location.replace('ventacod.php')</script>";
	}else
	{
		//update
		$usuario=$_SESSION['usuario'];
		$ventacod=$_SESSION['ventacod'];
		$conexion2->query("update venta set cliente='$cliente',fecha='$fecha',bodega_venta='$bodega' where sessiones='$ventacod' and usuario='$usuario'")or die($conexion2->error());
	}

	}// fin boton Siguiente

	if($op==1)
	{
		//busqueda articulo
		$c=$conexion1->query("select * from consny.articulo where ARTICULO='$articulo'")or die($conexion1->error());

		$n=$c->rowcount();

		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN ARTICULO $articulo')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);

			echo "<script>location.replace('ventacod.php?art=$articulo')</script>";
		}



	}else if($op==2)
	{
		//agregar articulo click en boton add.

		$paquete=$_SESSION['paquete'];
		$usuario=$_SESSION['usuario'];
		$ventacod=$_SESSION['ventacod'];

		$cr=$conexion2->query("select top 1 * from venta where sessiones='$ventacod' and usuario='$usuario'")or die($conexion2->error());
		$ncr=$cr->rowCount();
		if($ncr==0)
		{
			echo "<script>alert('!Error:! INICIE NUEVAMENTE LA VENTA')</script>";
			$_SESSION['ventacod']='';
			echo "<script>location.replace('ventacod.php')</script>";
		}else
		{
			$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
			$cliente=$fcr['cliente'];
			$fecha=$fcr['fecha'];
			$bodega=$fcr['bodega_venta'];
			//datos articulo
			/*$cart=$conexion1->query("select precio_regular from consny.articulo where articulo='$articulo'")or die($conexion2->error());
			$fcart=$cart->FETCH(PDO::FETCH_ASSOC);
			$precio=$fcart['precio_regular'];*/
			if($articulo!='')
			{

				//validacion si articulo esta disponible
				$consu=$conexion1->query("select * from consny.articulo where activo='S' and articulo='$articulo'")or die($conexion1->error());
				$nconsu=$consu->rowCount();
				if($nconsu==0)
				{
					echo "<script>alert('ARTICULO: $articulo NO SE ENCUENTRA DISPONIBLE')</script>";
				}else
				{
				$conexion2->query("insert into venta(cliente,paquete,usuario,precio,sessiones,fecha_ingreso,bodega_venta,articulo,cantidad,fecha)values('$cliente','$paquete','$usuario','$precio','$ventacod',getdate(),'$bodega','$articulo','$cantidad','$fecha')")or die($conexion2->error());
			echo "<script>location.replace('ventacod.php')</script>";
				}
		}else
		{
			echo "<script>location.replace('ventacod.php?op')</script>";
		}

			

		}

	}

	if($btn=='FINALIZAR')
	{

		//echo "<script>alert('sdfsfsf')</script>";
		$error=0;
		//finalizar venta
		//valido que el consecutivo este valido
			$e=explode("ENV-", $conse);
			
			$cantnum=strlen($e[1]);
			//echo "$cantnum";

			if($cantnum==7)
			{

			}else
			{
				//echo "<script>alert('numero')</script>";
				$msjf='- FORMATO DEL CONSECUTIVO  NO VALIDO';
				$error++;
			}

			$c=$conexion1->query("select * from consny.documento_inv where documento_inv='$conse'")or die($conexion1->eror());
			$n=$c->rowCount();
			if($n!=0)
			{
				//echo "<script>alert('existe $conse')</script>";
				$msje="- CONSECUTIVO YA FUE UTILIZADO";
				$error++;
			}
			$letra=substr($conse,0);

			$letras="$letra[0]$letra[1]$letra[2]$letra[3]";

			if($letras!='ENV-')
			{
				$error++;
				$msjf='- FORMATO DEL CONSECUTIVO NO VALIDO';
			}

			$cventacod=$_SESSION['ventacod'];
			$usuario=$_SESSION['usuario'];
			$consulta=$conexion2->query("select top 1* from venta where usuario='$usuario' and sessiones='$ventacod'")or die($conexion2->error());

			$num_consulta=$consulta->rowcount();

			if($num_consulta==0)
			{
				$error=1;
				//echo "<script>alert('consulta')</script>";
				$msjc="- NO SE ENCONTRO NINGUNA VENTA POR FINALIZAR";

			}

			if($error>=1)
			{
				echo "<script>alert('ERROR: NO SE PUEDE FINALIZAR LA VENTA \\n ERRORES: \\n $msjc \\n $msje \\n $msjf')</script>";
				echo "<script>loction.replace('ventacod.php')</script>";
				//fin validacion consecutivo
			}else
			{
				//insert
				$num=$e[1]+ 1;
				$nume=str_pad($num,7,"0",STR_PAD_LEFT);
				$nuevo_conse="ENV-$nume";
				$fconsulta=$consulta->FETCH(PDO::FETCH_ASSOC);
				$cliente=$fconsulta['cliente'];
				$bodega=$fconsulta['bodega_venta'];
				$fecha=$fconsulta['fecha'];
				$usuario=$fconsulta['usuario'];
				$paquete=$fconsulta['paquete'];

				//insert documento_inv descomentar pr que caiga al paquete
				/*$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
				values('$paquete',
				'$conse','VENTA','VENTA A $cliente',getdate(),'$fecha','N','$usuario','','N','0')")or die($conexion1->error());
				$user=$_SESSION['usuario'];
				$ventacod=$_SESSION['ventacod'];

				$con=$conexion2->query("select * from venta where usuario='$user' and sessiones='$ventacod' and articulo is not null")or die($conexion2-error());
				$ns=1;
				while($fcon=$con->FETCH(PDO::FETCH_ASSOC))
				{
					$articulo=$fcon['articulo'];
					$cantidad=$fcon['cantidad'];
					$paquete=$fcon['paquete'];
					$precio=$fcon['precio'];
					$bode=$fcon['bodega_venta'];
					//insert linea_doc_inv
					$conexion1->query("insert into consny.LINEA_DOC_INV(PAQUETE_INVENTARIO,
DOCUMENTO_INV,
LINEA_DOC_INV,
AJUSTE_CONFIG,
ARTICULO,
BODEGA,
TIPO,SUBTIPO,
SUBSUBTIPO,
CANTIDAD,
COSTO_TOTAL_LOCAL,COSTO_TOTAL_DOLAR,PRECIO_TOTAL_LOCAL,PRECIO_TOTAL_DOLAR,NoteExistsFlag) 
values('$paquete',
'$conse',
'$ns','~VV~',
'$articulo',
'$bode',
'V',
'D',
'L',
'$cantidad',
'1',
'1',
'$precio',
'$precio',
'0')")or die($conexion1->error());
$ns++;
				}*/
		$conexion1->query("update consny.consecutivo_ci set SIGUIENTE_CONSEC='$nuevo_conse' where consecutivo='venta'")or die($conexion1->error());
		$ventacod=$_SESSION['ventacod'];
		$usuario=$_SESSION['usuario'];
		$conexion2->query("update venta set observacion='$observacion',documento_inv='$conse' where sessiones='$ventacod' and usuario='$usuario'")or die($conexion2->error());
		echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";
		$_SESSION['ventacod']='';
		echo "<script>location.replace('imprimir_ventacod.php?ventacod=$ventacod')</script>";



			}



		

	}

}
?>
</body>
</html>