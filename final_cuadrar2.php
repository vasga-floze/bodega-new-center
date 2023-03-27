<?php
include("conexion.php");
$cuadrar=$_SESSION['cuadrar'];
$usuario=$_SESSION['usuario'];


$c=$conexion1->query("select * from cuadro_venta where session='$cuadrar' and usuario='$usuario'")or die($conexion1->error());
$nu=$c->rowCount();
$l=$_SESSION['liquido'];
//echo "<script>alert('$l')</script>";
if($_SESSION['cuadrar']!=''  and $nu!=0)
{

$bodega=$_SESSION['bodega'];

//datos,esquema,centro_costo
$consulta=$conexion1->query("select * from usuariobodega where usuario='$usuario'")or die($conexion1->error());
$fila=$consulta->FETCH(PDO::FETCH_ASSOC);

$esquema=$fila['ESQUEMA'];
$centro=$fila['CENTRO_COSTO'];
//echo "<script>alert('$centro - > $esquema')</script>";
$centro=substr($centro, 0,9);
$query=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());

$fquery=$query->FETCH(PDO::FETCH_ASSOC);

$tienda=$fquery['NOMBRE'];
$liquido=$_SESSION['liquido'];

$conexion1->query("update cuadro_venta set 	monto_liquido='$liquido' where usuario='$usuario' and session='$cuadrar'")or die($conexion1->error());

$con=$conexion1->query("select sum(cuadro_venta_detalle.monto) as salidas from cuadro_venta_detalle inner join cuadro_venta on cuadro_venta_detalle.cuadro_venta=cuadro_venta.id where cuadro_venta.usuario='$usuario' and cuadro_venta.session='$cuadrar' and cuadro_venta_detalle.tipo_transaccion='SALIDA'")or die($conexion1->error());
$fcon=$con->FETCH(PDO::FETCH_ASSOC);


$f=$c->FETCH(PDO::FETCH_ASSOC);//de cuadro_venta



$total=$f['MONTO_USUARIO'] - $fcon['salidas'];
$id=$f['ID'];
$monto_usuario=$f['MONTO_USUARIO'];
$iva=$monto_usuario /1.13;
$remesa=$monto_usuario - $iva;
$remesa=round($remesa,2);
$iva=round($iva,2);
$fecha=$f['FECHA'];
$liquido=$total;
$cp=$conexion1->query("select * from $esquema.paquete where paquete='CG'")or die($conexion1->error());

$fcp=$cp->FETCH(PDO::FETCH_ASSOC);

$asiento=$fcp['ULTIMO_ASIENTO'];
$asiento_estaba=$asiento;
$numero=explode("CG-", $asiento);
if(is_int($numero[1]+0))
{
  $tipo="VERDADERO";
}else
{
  $tipo="FALSO";
}

$t=explode($asiento,"CG-");

$cantidad=strlen($numero[1]);
//echo "<script>alert('$t[0]  $t[1] $asiento $tipo $cantidad')</script>";
//$tu=$tipo - $numero[1];
//echo "<hr> $asiento / $t[0] / $numero[1] -- $cantidad --- ($tipo)";
if($t[0]=='CG-' and $cantidad>=7 and $tipo=='VERDADERO' and is_numeric($numero[1]))
{
  //echo "<hr>valido $asiento<hr>"; //asiento valido
  
}else 
{
  //echo "<hr>error $t[0]<hr>";
  //sCO ESQUEMA
  $usuarios=$_SESSION['usuario'];
  $cesquema=$conexion1->query("select esquema,correotienda from USUARIOBODEGA where USUARIO='$usuarios' and TIPO='TIENDA'
")or die($conexion1->error());
  $fcesquema=$cesquema->FETCH(PDO::FETCH_ASSOC);
  $esquema=$fcesquema['esquema'];
  $cua=$conexion1->query("select max(asiento) asiento from $esquema.asiento_de_diario where tipo_asiento='CG'")or die($conexion1->error());

  $ncua=$cua->rowCount();
  if($ncua==0)
  {
    $cua1=$conexion1-query("select max(asiento) asiento from $esquema.ASIENTO_MAYORIZADO where tipo_asiento='CG'")or die($conexion1->error());
    $fcua1=$cua1->FETCH(PDO::FETCH(PDO::FETCH_ASSOC));
    $asiento=$fcua1['asiento'];
  }else
  {
    $fcua=$cua->FETCH(PDO::FETCH_ASSOC);
    $asiento=$fcua['asiento'];
  }

  $text=explode("CG-", $asiento);
  $nuevo_asiento=$text[1]+1;
  $nuevo_asiento=str_pad($nuevo_asiento,7,'0',STR_PAD_LEFT);
  $asiento="CG-$nuevo_asiento";

 // echo "NUEVO ASIENTO: ($asiento) queda= $queda";
$correo=$fcesquema['correotienda'];
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$usuarioc=$_SESSION['usuario'];
//dirección del remitente
$headers .= "From: $usuarioc <$correo@newyorkcentersadcv.com>\r\n";


//ruta del mensaje desde origen a destino
$headers .= "Return-path: $correo@newyorkcentersadcv.com\r\n";

$asunto="ASIENTO CG NO VALIDO(SISTEMITA)";
$tabla="SE OBTUVO UN NUMERO DE ASIENTO NO VALIDO: <b><u>$asiento_estaba</u></b> -> SE BUSCO EL MAXIMO EN ASIENDO DIARIO Y SE OBTUVO -> $asiento y se aactualizo en el paquete cg con $queda";
mail("jlainez@newyorkcentersadcv.com", $asunto, $tabla,$headers);


} // termina validacion de asiento




$valor=0;
while($valor==0)
{
	$qvalidacion=$conexion1->query("select * from $esquema.asiento_de_diario where asiento='$asiento'")or die($conexion1->error());
	$nqvalidacion=$qvalidacion->rowcount();
	if($nqvalidacion==0)
	{
		$valor=1;
	}else
	{
		$vnum=explode('CG-', $asiento);
		$numero_max=$vnum[1]+1;
		$nuevo_num=str_pad($numero_max,7,"0", STR_PAD_LEFT);
		$asiento="CG-$nuevo_num";
		$valor=0;
	}

}


$num=explode("CG-", $asiento);

$queda=$num[1] + 1;

$queda=str_pad($queda,7,"0", STR_PAD_LEFT);

$queda="CG-$queda";

$k=1;

while($k==1)
{
	$q=$conexion1->query("select * from $esquema.asiento_de_diario where asiento='$asiento'")or die($conexion1->error());
	$nq=$q->rowCount();
	if($nq!=0)
	{
		$asiento=$queda;
		$num=explode("CG-", $asiento);

		$queda=$num[1] + 1;

		$queda=str_pad($queda,7,"0", STR_PAD_LEFT);

		$queda="CG-$queda";
		$k=1;

	}else
	{
		$k=0;
	}


}

//validacion de cuadro_venta existe asiento
$k=0;
while($k==0)
{
	$qc=$conexion1->query("select * from cuadro_venta where asiento='$asiento'")or die($conexion1->error());
	$nqc=$qc->rowCount();
	if($nqc!=0)
	{
		$numero=explode("CG-", $asiento);
		$nume=$numero[1]+1;
		$qnume=$numero[1]+2;
		$nume=str_pad($nume,7,"0", STR_PAD_LEFT);
		$qnume=str_pad($qnume,7,"0", STR_PAD_LEFT);
		$asiento="CG-$nume";
		$queda="CG-$qnume";
		$k=0;
	}else
	{
		$k=1;
	}
}
//fin validacion cuadro_venta asiento		
//echo "<script>alert('$asiento -> $queda')</script>";
$conexion1->query("insert into $esquema.ASIENTO_DE_DIARIO(asiento,paquete,tipo_asiento,fecha,contabilidad,origen,clase_asiento,total_debito_loc,total_debito_dol,total_credito_loc,total_credito_dol,ultimo_usuario,fecha_ult_modif,marcado,notas,total_control_loc,total_control_dol,usuario_creacion,fecha_creacion,noteexistsflag)
	values('$asiento','CG','CG','$fecha','A','CG','N','$monto_usuario','0','$monto_usuario','0','sa',getdate(),'N','','$monto_usuario','0','sa',getdate(),'0')")or die($conexion1->error());
//insert encabezado
$cl=$conexion1->query("select * from cuadro_venta_detalle where cuadro_venta='$id'")or die($conexion1->error());
$n=1;

while($fl=$cl->FETCH(PDO::FETCH_ASSOC))
{
	$monto=$fl['MONTO'];
	$concepto=$fl['CONCEPTO'];
	//echo "<script>alert('$concepto <-')</script>";
	$tipo=$fl['TIPO_TRANSACCION'];
	$tipo_doc=$fl['TIPO_DOCUMENTO'];
	$num_doc=$fl['NUM_DOCUMENTO'];
	$proveedor=$fl['ID_AGENTE'];
	$monto=round($monto,2);
	switch ($tipo_doc) 
	{
		case 'LIQUIDACION':
			$concepto="$concepto $num_doc";
			break;
		case 'CONTROL INTERNO':
			$concepto="$concepto CIE-$num_doc";
			break;
		case 'CREDITO FISCAL':
			$concepto="$concepto CCF-$num_doc";
			break;
		case 'RECIBO':
			$concepto="$concepto REC-$num_doc";
			break;

		case 'SUJETO EXCLUIDO':
			$concepto="$concepto FSE-$num_doc";
			break;
		case 'FACTURA':
			$concepto="$concepto FAC-$num_doc";
			break;
		case 'TICKET':
			$concepto="$concepto TIK-$num_doc";
			break;
		case 'OTROS':
			$concepto="$concepto $num_doc";
			break;
		case 'VENTA CON TARJETA':
			$concepto="$concepto $num_doc";
			break;

			case 'BITCOIN':
			$concepto="BITCOIN $concepto $num_doc";
			break;
		
		default:
			# code...
			break;
	}
	$cproveedor=$conexion1->query("select * from LibrosIVA.dbo.Agentes where idagente='$proveedor'")or die($conexion1->error());
	$fcproveedor=$cproveedor->FETCH(PDO::FETCH_ASSOC);
	$nom_proveedor=$fcproveedor['NombreAgente'];
	$concepto="$concepto $nom_proveedor";
	$concepto=substr($concepto, 0,249);

	if($tipo=='INGRESO')
	{
		//echo "<h1>a: $asiento f:$fecha con: $concepto mo: $monto cC: <u>$centro</u> N: $n</h1> ";
		$cuenta='1-02-01-001-000-000';
		//$centro='01-02-005';
		$conexion1->query("insert into $esquema.diario(asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,debito_local,debito_unidades,credito_unidades,noteexistsflag)
		values
		('$asiento','$n','ND','$centro','$cuenta','$fecha','$concepto','$monto','0','0','0')")or die($conexion1->errorinfo());
		//credito ingreso
		$cuenta='5-02-03-000-000-000';
		$n++;

		$conexion1->query("insert into $esquema.diario(asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,credito_local,debito_unidades,credito_unidades,noteexistsflag)
		values
		('$asiento','$n','ND','$centro','$cuenta','$fecha','$concepto ','$monto','0','0','0')")or die($conexion1->errorinfo());

		/*$conexion1->query("insert into $esquema.diario(asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,debito_local,debito_dolar,credito_local,credito_dolar,debito_unidades,credito_unidades,noteexistsflag)
		values('$asiento','','ND','$centro','$cuenta','$fecha','$concepto','0','0','$monto','0','0','0','0')")or die($conexion1->error()); malo */
	}else if($tipo=='SALIDA')
	{
		if($tipo_doc!='VENTA CON TARJETA' or $tipo_doc!='BITCOIN')
		{
			$cuenta='4-02-01-011-000-000';
			if($tipo_doc=='CREDITO FISCAL')
			{
				$ivas1=$monto/1.13;
				$ivas=$monto-$ivas1;
				$ivas=round($ivas,2);
				$monto=$monto-$ivas;
				$conexion1->query("insert into $esquema.diario(asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,debito_local,debito_unidades,credito_unidades,noteexistsflag)
		values('$asiento','$n','ND','$centro','1-02-03-005-001-000','$fecha','$concepto ','$ivas','0','0','0')")or die($conexion1->error());
				$n++;
		
			}
		}else
		{
			$cuenta='1-02-03-006-001-000';
		}
		if($tipo_doc=='BITCOIN')
		{
			$cuenta='1-02-03-006-008-000';

			//echo "<script>alert('bitcoin')</script>";
		} 

		if($tipo_doc=='VENTA CON TARJETA')
		{
			$cuenta='1-02-03-006-001-000';

			//echo "<script>alert('bitcoin 2')</script>";
		} 


		
		$conexion1->query("insert into $esquema.diario(asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,debito_local,debito_unidades,credito_unidades,noteexistsflag)
		values('$asiento','$n','ND','$centro','$cuenta','$fecha','$concepto ','$monto','0','0','0')")or die($conexion1->error());
		
	}
	$n++;

}
//inset remesa iva
$n++;
$cuenta='3-02-05-001-001-000';
//echo "<script>alert('a: $asiento n:$n ce:$centro cu:$cuenta r: $remesa es: $esquema')</script>";
$conexion1->query("insert into $esquema.diario(asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,credito_local,debito_unidades,credito_unidades,noteexistsflag)
		values('$asiento','$n','ND','$centro','$cuenta','$fecha','REMESA POR VENTA FECHA: $fecha  $tienda','$remesa','0','0','0')")or die($conexion1->error());
//insert inva ------------
$n++;
$cuenta='5-01-01-001-000-000';
$conexion1->query("insert into $esquema.diario(asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,credito_local,debito_unidades,credito_unidades,noteexistsflag)
		values('$asiento','$n','ND','$centro','$cuenta','$fecha','REMESA POR VENTA FECHA: $fecha $tienda','$iva','0','0','0')")or die($conexion1->error());
//venta liquida
$n++;
$cuenta='1-02-01-001-000-000';
		$conexion1->query("insert into $esquema.diario(asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,debito_local,debito_unidades,credito_unidades,noteexistsflag)
		values('$asiento','$n','ND','$centro','$cuenta','$fecha','REMESA VENTA $fecha DE $tienda','$liquido','0','0','0')")or die($conexion1->error());
		$cuadrar=$_SESSION['cuadrar'];
		$usuario=$_SESSION['usuario'];
		$conexion1->query("update cuadro_venta set asiento='$asiento', estado='1' where session='$cuadrar' and usuario='$usuario'")or die($conexion1->error());
		$conexion1->query("update $esquema.paquete set ULTIMO_ASIENTO='$queda' where paquete='CG'")or die($conexion1->error());
		
		$_SESSION['cuadrar']='';
		$_SESSION['liquido']='';
		echo "<script>alert('FINALIZADO CORECTAMENTE')</script>";
		echo "<script>location.replace('cuadrar.php')</script>";

}else
{
	echo "<script>alert('ERROR: 0001-0')</script>";
	echo "<script>location.replace('cuadrar.php')</script>";

}

?>