<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#barra").focus();
			//alert();
		})
		{
			
		}

</script>
</head>
<body>
<?php
include("conexion.php");
?>
<h3 style="text-decoration: underline; text-align: center;">DESPACHO DE RIPIO</h3>
<form method="POST" name="form" id="form">
<label>CODIGO DE BARRA:<br>
<input type="text" name="barra" id="barra" class="text" style="width: 35%;">
</label>
<input type="hidden" name="idr" id="idr">
<input type="hidden" name="op" id="op">

<input type="submit" name="btn" class="boton2" value="AGREGAR">
</form>

<?php
if($_SESSION['ripiob']!='')
{
	$usuario=strtoupper( $_SESSION['usuario']);
	$doc=$_SESSION['ripiob'];
	$ctr=$conexion2->query("select ripio.barra,ripio.clasificacion,ripio.peso, traslado_ripio.id from ripio inner join traslado_ripio on ripio.id=traslado_ripio.ripio where traslado_ripio.usuario='$usuario' and traslado_ripio.session='$doc' order by traslado_ripio.id desc")or die($conexion2->error());
	$nctr=$ctr->rowCount();
	if($nctr!=0)
	{
		$ahora=date("Y-m-d");
		$doc=$_SESSION['ripiob'];
		$usu=strtoupper($_SESSION['usuario']);
		$qf=$conexion2->query("select MAX(ripio.fecha_documento) as fecha,COUNT(ripio.id) as cantidad from ripio inner join traslado_ripio
on ripio.id=traslado_ripio.ripio where traslado_ripio.session='$doc' and traslado_ripio.usuario='$usu'
")or die($conexion2->error());
		$fqf=$qf->FETCH(PDO::FETCH_ASSOC);
		$minima=$fqf['fecha'];
		$cantidad=$fqf['cantidad'];
		echo "<br><table border='1' cellspadding='10' style='border-collapse:collapse; width:100%;'>";
		echo "<tr>
		<td colspan='4'>
		<u>CANTIDAD: $cantidad</u><br>
		<form method='POST' name='form1' id='form1'>
		<input type='date' name='fecha' id='fecha' class='text' style='width:15%;' value='$ahora' min='$minima'>


		<input type='text' name='obs' id='obs' class='text' style='width:35%;' placeholder='OBSERVACION' required>
		<input type='submit' name='btn' id='btn' value='FINALIZAR' class='btnfinal' style='padding:0.5%; margin-bottom:0.5%;'></form>

		</td>
		</tr>";
		echo "<tr>
		<td>#</td>
		<td>CODIGO DE BARRA</td>
		<td>CLASIFICACION</td>
		<td>PESO</td>
		<td width='5%'></td>
		</tr>";
		$n=0;
		while($fctr=$ctr->FETCH(PDO::FETCH_ASSOC))
		{
			$n++;
			$id=$fctr['id'];
			echo "<tr>
			<td>$n</td>
				<td>".$fctr['barra']."</td>
				<td>".$fctr['clasificacion']."</td>
				<td>".$fctr['peso']."</td>
				<td><img src='eliminar.png' width='35%' height='35%' style='cursor:pointer;' onclick=\"javascript:
	 						if (confirm('SEFURO DESEA ELIMINAR LA LINEA'))
	 							{
									$('#idr').val('$id');
									$('#op').val('1');
									$('#form').submit();
	 							}
				 \"></td>
				</tr>";
		}
	}
}
?>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='AGREGAR')
	{
		if($_SESSION['ripiob']=='')
		{
			$c=$conexion2->query("select max(session) as session from traslado_ripio")or die($conexion2->error());
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$_SESSION['ripiob']=$f['session']+1;
		}
		
		$cr=$conexion2->query("select * from ripio where barra='$barra' and despacho='N' and id not in(select ripio from traslado_ripio)")or die($conexion2->error());
		$ncr=$cr->rowCount();
		if($ncr==0)
		{
			echo "<script>alert('ERROR: PUEDER SER:\\n- $barra NO EXISTE\\n- YA FUE DESPACHADO\\n- ESTA SIENDO DESPACHADO POR OTRO USUARIO\\n- YA LO AGREGASTES ANTES')</script>";
			echo "<script>location.replace('despacho_ripio.php')</script>";
		}else
		{
			$fcr=$cr->FETCH(pdo::FETCH_ASSOC);
			$clasificacion=$fcr['clasificacion'];
			$idr=$fcr['id'];
			$doc=$_SESSION['ripiob'];
			$usuario=strtoupper( $_SESSION['usuario']);
			$paquete=$_SESSION['paquete'];
			$conexion2->query("insert into traslado_ripio(ripio,clasificacion,fecha_hora_crea,usuario,paquete,session,estado) values('$idr','$clasificacion',getdate(),'$usuario','$paquete','$doc','0')")or die($conexion2->error());
			echo "<script>location.replace('despacho_ripio.php')</script>";

		}

	}else if($btn=='FINALIZAR')
	{
		$doc=$_SESSION['ripiob'];
		$usu=strtoupper($_SESSION['usuario']);
		$conexion2->query("update traslado_ripio set estado='1',fecha='$fecha',observacion='$obs' where session='$doc' and usuario='$usu'")or die($conexion2->error());
		$conexion2->query("update ripio set despacho='S',fecha_despacho='$fecha',fecha_hora_despacho=getdate(),usuario_despacho='$usu' where id in(select ripio from traslado_ripio where usuario='$usu' and session='$doc')")or die($conexion2->error());
		echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";
		$_SESSION['ripiob']='';
		echo "<script>location.replace('despacho_ripio.php')</script>";
	}
	if($op==1)
	{
		$conexion2->query("delete from traslado_ripio where id='$idr'")or die($conexion2->error());
		echo "<script>location.replace('despacho_ripio.php')</script>";
	}
}
?>
</body>
</html>