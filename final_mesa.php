<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<?php
	include("conexion.php");
	$hoy=date("Y-m-d");
	//echo "<script>alert('NO DISPONIBLE')</script>";
	//echo "<script>location.replace('CONEXIONES.php')</script>";
	?>
</head>
<body>
<form method="POST">
	<input type="date" name="fecha" value='<?php echo "$hoy";?>' class='text' style='width: 20%; padding: 0.4%;'>
	<input type="submit" name="btn" value="MOSTRAR" class="boton2">
</form>
<?php

if($_POST)
{
	extract($_REQUEST);
	if($btn=="MOSTRAR")
	{
		$c=$conexion2->query("select * from mesa where fecha='$fecha' and estado='1'")or die($conexion2->error);
		

	
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
	}else
	{
		echo "<br><table border='1' class='tabla' cellpadding='10'>";
		echo "<tr>
			<td>MESA</td>
			<td>PRODUCIDO</td>
			<td>FECHA</td>
			<td>CANTIDAD</td>
		</tr>";
		$t=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$pro=$f['producido'];
			$mesa=$f['mesa'];
			$fechar=$f['fecha'];
			$mesaid=$f['id'];
			$ctm=$conexion2->query("select COUNT(*) as total from detalle_mesa where mesa='$mesaid'")or die($conexion2->error());
			$fctm=$ctm->FETCH(PDO::FETCH_ASSOC);
			$cant=$fctm['total'];

			$mesaid=base64_encode($mesaid);
			echo "<tr>
			<td><a href='detalle_mesa.php?id=$mesaid' target='_blank;'>$mesa</a></td>
			<td><a href='detalle_mesa.php?id=$mesaid' target='_blank;'>$pro</a></td>
			<td><a href='detalle_mesa.php?id=$mesaid' target='_blank;'>$fecha</a></td>
			<td><a href='detalle_mesa.php?id=$mesaid' target='_blank;'>$cant</a></td>
		</tr>";
		$t=$t +$cant;
		}
		echo "<tr>
		<td colspan='3'>
		<form method='POST' >
		<input type='hidden' value='$fecha' name='fe' style='float:right; margin-right:0.5%;'>
		<input type='submit' name='btn' value='FINALIZAR' class='btnfinal' style='padding:0.6%; float:right; margin-right:1%; margin-bottom:0%;'>
		</form>
		</td><td>$t</td>
		</tr></table>";
	}
}else if($btn=="FINALIZAR")
{
  $q=$conexion1->query("select CONSECUTIVO,SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='AJN'")or die($conexion1->error);
  $fq=$q->FETCH(PDO::FETCH_ASSOC);
  $esta=$fq['SIGUIENTE_CONSEC'];
  $e=explode("-", $esta);
  $queda=$e[1] + 1;
  $queda=str_pad($queda,10,"0",STR_PAD_LEFT);
  $queda="$e[0]-$queda";
  $conexion1->query("update consny.CONSECUTIVO_CI set SIGUIENTE_CONSEC='$queda' where CONSECUTIVO='AJN'")or die($conexion1->error);
  $con=$conexion2->query("select * from mesa where fecha='$fe' and estado='1'")or die($conexion2->error);
  $ncon=$con->rowCount();
  if($ncon==0)
  {
  	echo "<script>alert('NO SE ENCONTRO NINGUNA MESA FINALIZADA O YA FUERON FINALIZADAS')</script>";
  	echo "<script>location.replace('salir.php')</script>";

  }else
  {
  	$paquete=$_SESSION['paquete'];
  	$usuarios=$_SESSION['usuario'];

  	$conexion1->query("insert into consny.DOCUMENTO_INV(PAQUETE_INVENTARIO,DOCUMENTO_INV,CONSECUTIVO,REFERENCIA,FECHA_HOR_CREACION,FECHA_DOCUMENTO,SELECCIONADO,USUARIO,MENSAJE_SISTEMA,APROBADO,NoteExistsFlag)
values('$paquete',
'$esta','AJN','BULTOS TRABAJADOS DE LA FECHA: $fe',getdate(),'$fe','N','$usuarios','','N','0')")or die($conexion1->error);
  	$ns=1;
  	while($fcon=$con->FETCH(PDO::FETCH_ASSOC))
  	{
  		$mesa=$fcon['id'];
  		$conexion2->query("update mesa set estado='T',documento_inv='$esta' where id='$mesa'")or die($conexion2->error);
  		$k=$conexion2->query("select * from detalle_mesa where mesa='$mesa'")or die($conexion2->error);
  		$nk=$k->rowCount();
  		if($nk!=0)
  		{
  			while($fk=$k->FETCH(PDO::FETCH_ASSOC))
  			{
  				$id=$fk['registro'];
  				$cr=$conexion2->query("select * from registro where id_registro='$id'")or die($conexion2->error);
  				$ncr=$cr->rowCount();
  				if($ncr!=0)
  				{
  					$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
  					$art=$fcr['codigo'];
  					$bode=$fcr['bodega'];
  					//echo "<script>alert('$art - $bode')</script>";
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
'$esta',
'$ns','AJN',
'$art',
'$bode',
'C',
'D',
'N',
'1',
'1',
'1',
'0',
'0',
'0')")or die($conexion1->error);
 $ns++;
 $conexion2->query("update registro set activo='0' where id_registro='$id'")or die($conexion2->error());
 	//transaccion_sys
$ct=$conexion2->query("select detalle_mesa.registro,'SALIDA POR BULTOS TRABAJADOS' ref,'S' tipo,registro.codigo ,-1 cANTIDAD, (ISNULL(registro.lbs,0)
+ ISNULL(registro.peso,0)*-1) PESO, mesa.fecha,mesa.usuario,mesa.paquete,
registro.bodega,GETDATE(),'mesa' tabla,mesa.documento_inv from mesa inner join detalle_mesa
on mesa.id=detalle_mesa.mesa inner join registro on registro.id_registro=
detalle_mesa.registro where registro.id_registro='$id'")or die($conexion2->error());
$fct=$ct->FETCH(PDO::FETCH_ASSOC);
$idr=$fct['registro'];
$ref=$fct['ref'];
$tipo=$fct['tipo'];
$art=$fct['codigo'];
$cant=$fct['cANTIDAD'];
$peso=$fct['PESO'];
$fecha=$fct['fecha'];
$usu=$fct['usuario'];
$paq=$fct['paquete'];
$bod=$fct['bodega'];
$tabla=$fct['tabla'];
$doc=$fct['documento_inv'];
$conexion2->query("insert into transaccion_sys(registro,REFERENCIA,tipo_transaccion,articulo,cantidad,peso,fecha_documento,usuario,paquete,bodega,fecha_creacion,tabla,documento_inv) values('$idr','$ref','$tipo','$art','$cant','$peso','$fecha','$usu','$paq','$bod',getdate(),'$tabla','$doc')")or die($conexion1->error());
 //fin trnasaccion_sys
  				}
  			}

  		}
  	}
  	echo "<script>alert('FINALIZADO CORRECTAMENTE')</script>";
echo "<script>location.replace('final_mesa.php')</script>";

  }

}

}//FIN POST
?>





</body>
</html>