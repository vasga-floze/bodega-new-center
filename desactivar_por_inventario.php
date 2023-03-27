<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	function agrega()
	{
		$("#op").val('1');
	}

	function elimina(e)
	{
		if(confirm('SEGURO DESEA ELIMINAR LA LINEA'))
		{
			$("#op").val('2');
			$("#identificar").val(e);
			$("#form").submit();
		}

		}

	function final()
	{
		$("#opc").val('1');
		$("#formf").submit();
	}
</script>
</head>

<body>
<?php
include("conexion.php");
?>
<form method="POST" id="form">
<label  id="label">CODIGO DE BARRA:<BR>
<input type="text" name="barra" id="barra" class="text" style="width: 25%;">
</label>
<input type="hidden" name="op" id="op">
<input type="hidden" name="identificar" id="identificar" readonly>
<input type="submit" name="btn1" id="btn1" value="AGREGAR" onclick="agrega()" class="boton2">
</form>
<?php
$inv=$_SESSION['audit'];
$usu=strtoupper($_SESSION['usuario']);
$cr=$conexion2->query("select registro.barra,concat(eximp600.consny.articulo.articulo,': ',eximp600.consny.articulo.descripcion) articulo, case registro.tipo when 'P' then isnull(registro.lbs,0) when 'cd' then isnull(peso,0) end peso,concat(eximp600.consny.BODEGA.BODEGA,': ',eximp600.consny.BODEGA.nombre) bodega,audit_inventario.id, case registro.activo when '0' then 'NO' ELSE 'SI' end activo from audit_inventario inner join registro on registro.id_registro=audit_inventario.registro inner join eximp600.consny.articulo on registro.codigo=eximp600.consny.articulo.articulo inner join eximp600.consny.bodega on eximp600.consny.bodega.bodega=registro.bodega where audit_inventario.correlativo='$inv' and audit_inventario.usuario='$usu'")or die($conexion2->error());
$ncr=$cr->rowCount();
if($ncr!=0)
{
	echo "<table border='1' style='border-collapse:collapse; width:100%;' cellpadding='10'>";

	echo "<tr>
	<td colspan='7'>
	<form method='POST' name='formf' id='formf'>
	<input type='text' name='comentario' id='comentario' placeholder='COMENTARIO' class='text' required style='width:40%;'>
	<input type='date' name='fecha' id='fecha' class='text' style='width:30%;'>
	<input type='hidden' name='opc' id='opc' readonly>
	<input type='submit' name='btn' name='btn' value='FINALIZAR' class='btnfinal' style='padding:0.5%; margin-bottom:-5%;' onclick='final()'>
	</form>
	</td>
	</tr>";

	echo "<tr>
	<td>#</td>
	<td>CODIGO DE BARRA</td>
	<td>ARTICULO</td>
	<td>PESO</td>
	<td>BODEGA</td>
	<td>DISPONIBLE</td>
	<td>ELIMINAR</td>
	";
	$numero=0;

	while($fcr=$cr->FETCH(PDO::FETCH_ASSOC))
	{
		$numero++;
		$ide=$fcr['id'];
		echo "<tr class='tre'>
		<td>$numero</td>
	<td>".$fcr['barra']."</td>
	<td>".$fcr['articulo']."</td>
	<td>".$fcr['peso']."</td>
	<td>".$fcr['bodega']."</td>
	<td>".$fcr['activo']."</td>
	<td><button class='boton3' onclick='elimina($ide)' style='padding:5%; background-color:red; color:white border-color:white;'>ELIMINAR</button></td>
	";
	}
}

if($_POST)
{
	extract($_REQUEST);
	if($op==1)
	{
//echo "<script>alert('644886-')</script>";
	if($_SESSION['audit']=='')
	{
		//echo "<script>alert('644886-1)</script>";
		$c=$conexion2->query("select max(correlativo) correlativo from audit_inventario ")or die($conexion2->error());
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$inv=$f['correlativo']+1;
		$k=0;
		//echo "<script>alert('$inv')</script>";
		while($k==0)
		{
			$cv=$conexion2->query("select * from audit_inventario where correlativo='$inv'")or die($conexion2->error());
			$ncv=$cv->rowCount();
			if($ncv==0)
			{
				$k=1;
			}else
			{
			$inv++;
			$k=0;

			}
		}
		$_SESSION['audit']=$inv;
	}

	$vali=validacion_disponible($barra);
	if($vali=='FARDO NO SE PUEDE USAR POR:')
	{
		$q=$conexion2->query("select id_registro idr,codigo from registro where barra='$barra'")or die($conexion2->error());
		$nq=$q->RowCount();
		if($nq!=0)
		{
		$fq=$q->FETCH(PDO::FETCH_ASSOC);
		$idr=$fq['idr'];
		$art=$fq['codigo'];
		$inv=$_SESSION['audit'];
		$usu=strtoupper($_SESSION['usuario']);
		$paq=$_SESSION['paquete'];
		$qv=$conexion2->query("select * from audit_inventario where correlativo='$inv' and usuario='$usu' and registro in(select id_registro from registro where barra='$barra')")or die($conexion2->error());
		$nqv=$qv->rowCount();
		if($nqv==0)
		{
		$conexion2->query("insert into audit_inventario(REGISTRO,ARTICULO,FECHA_INGRESO,CORRELATIVO,ESTADO,usuario,paquete) VALUES('$idr','$art',getdate(),'$inv','0','$usu','$paq')")or die($conexion2->error());

		echo "<script>location.replace('desactivar_por_inventario.php')</script>";

	}else
	{
		echo "<script>alert('YA FUE AGREGADO ANTES ')</script>";
		echo "<script>location.replace('desactivar_por_inventario.php')</script>";
	}

	}else
	{
		echo "<script>alert('NO SE ENCONTRO NINGUN CODIGO DE BARRA: $barra')</script>";
	}

	}else
	{
		echo "<script>alert('$vali')</script>";
	}
	
	}else if($op==2)
	{
		//echo "<script>alert('$identificar')</script>";
		$inv=$_SESSION['audit'];
		$usu=strtoupper($_SESSION['usuario']);
		$conexion2->query("delete from audit_inventario where id='$identificar' and usuario='$usu' and correlativo='$inv'")or die($conexion2->error());
		echo "<script>location.replace('desactivar_por_inventario.php')</script>";
	}
	if($opc==1)
	{
		//echo "<script>alert('final  $fecha ->$comentario')</script>";
		$inv=$_SESSION['audit'];
		$usu=strtoupper($_SESSION['usuario']);
	$conexion2->query("update registro set activo=0 where id_registro in(select registro from audit_inventario where usuario='$usu' and correlativo='$inv' and estado='0') and activo is null")or die($conexion2->error());

	$conexion2->query("update audit_inventario set estado='1',documento='INVD-$usu-$inv',fecha='$fecha',observacion='$comentario',TIPO_TRANSACCION='DESACTIVACION' where usuario='$usu' and correlativo='$inv'")or die($conexion2->error());
		//falta que guarde en transaccion_sys
		//transaccion_sys
		$ct=$conexion2->query("select audit_inventario.registro,CONCAT('SALIDA POR AJUSTE DE INVENTARIO',
audit_inventario.FECHA) referencia,'S' tipo,registro.codigo articulo,-1 cantidad,
case registro.tipo
when 'P' then isnull(registro.lbs,0) when 'CD' then isnull(registro.peso,0) end*-1 
peso,audit_inventario.fecha,audit_inventario.usuario,audit_inventario.paquete,registro.bodega,getdate() fechai,'audit_inventario' tabla,
documento from  audit_inventario inner join registro on audit_inventario.registro=
registro.id_registro where audit_inventario.CORRELATIVO='$inv' and audit_inventario.usuario='$usu' and audit_inventario.estado='1'")or die($conexion2->error());
		while($fct=$ct->FETCH(PDO::FETCH_ASSOC))
		{
			$registro=$fct['registro'];
			$referencia=$fct['referencia'];
			$tipo=$fct['tipo'];
			$articulo=$fct['articulo'];
			$cantidad=$fct['cantidad'];
			$peso=$fct['peso'];
			$fecha=$fct['fecha'];
			$usuario=$fct['usuario'];
			$paquete=$fct['paquete'];
			$bodega=$fct['bodega'];
			$fechai=$fct['fechai'];
			$tabla=$fct['tabla'];
			$documento=$fct['documento'];
			//echo "<script>alert('$registro -> $referencia -> $tipo -> $articulo -> $cantidad -> $peso -> $fecha -> $usuario -> $paquete -> $bodega -> -> $fechai -> $tabla ->$documento')</script>";


			$conexion2->query("insert into transaccion_sys(REGISTRO,REFERENCIA,TIPO_TRANSACCION,ARTICULO,CANTIDAD,PESO,FECHA_DOCUMENTO,USUARIO,PAQUETE,BODEGA,FECHA_CREACION,TABLA,documento_inv) values('$registro','$referencia','$tipo','$articulo','$cantidad','$peso','$fecha','$usuario','$paquete','$bodega','$fechai','$tabla','$documento')")or die($conexion2->error());
			echo "<script>alert('GUARDO CORRECTAMENTE')</script>";
			$_SESSION['audit']='';
		}
		echo "<script>location.replace('desactivar_por_inventario.php')</script>";
	}
}
?>
</form>
</body>
</html>