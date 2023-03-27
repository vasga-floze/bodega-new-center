<!DOCTYPE html>
<html>
<head>
	<title></title>
<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".detalle").hide();
		})
	</script>
</head>
<body>
<div class="detalle" style="width: 110%; margin-left: -2%; background-color: white; opacity: 0.3;">
<img src="load1.gif" width="25%" height="25%;" style="position: sticky; top:0; margin-left: 33%; margin-top: 13%;">
</div>
<?php
//include("conexion.php");
 try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("!!ERROR!! SE PERDIO CONEXION CON EL SERVIDOR: " );
    }
    session_start();
    ?>
<?php
//include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	//eliminacion
	echo "$idempresa->$idperiodo->$mes->$anio->$validacion";
	$conjunto=$idempresa;
	if($validacion!=0)
	{
		//echo "<script>alert('$validacion <-')</script>";
	$conexion1->query("delete from  LibrosIVA.dbo.Ventas where IdPeriodo='$idperiodo' and IdEmpresa='$conjunto' and idagente is null")or die($conexion1->error());
	}
		//echo "<script>alert('$validacion')</script>";
		switch ($conjunto) {
			case '1':
				$empresa='C';
				break;
			case '5':
				$empresa='N';
				break;
			case '4':
				$empresa='E';
				break;
			
			default:
				# code...
				break;
		}

	$c=$conexion1->query("SELECT        FECHA, PERIODO, BODEGA, NUMCAJA, 
SERIE, INICIAL, FINAL, NETO, IVA,
(SELECT IdResolucion from LibrosIVA.dbo.Resoluciones where Activo=1 and IdCaja=NUMCAJA and IdTipoDocumento=2) IdResolucion,
(SELECT Resolucion from LibrosIVA.dbo.Resoluciones where Activo=1 and IdCaja=NUMCAJA and IdTipoDocumento=2) Resolucion,
(SELECT Serie from LibrosIVA.dbo.Resoluciones where Activo=1 and IdCaja=NUMCAJA and IdTipoDocumento=2)NumeroSerie
FROM            (SELECT        FECHA, EOMONTH(FECHA) AS PERIODO, BODEGA, 
                                        CASE WHEN e.caja = '018' THEN 30 WHEN e.caja = '019' THEN 31 WHEN e.caja = '020' THEN 32 WHEN e.caja = '021' THEN 33 WHEN e.caja = '022' THEN 34 WHEN e.caja = '023' THEN 37 WHEN e.caja = '024' THEN
                                            38 WHEN e.caja = '025' THEN 39 WHEN e.caja = '026' THEN 40 WHEN e.caja = '027' THEN 41 WHEN e.caja = '028' THEN 42 WHEN e.caja = '029' THEN 43 WHEN e.caja = '030' THEN 44 WHEN e.caja = '031' THEN
                                            45 WHEN e.caja = '032' THEN 49 WHEN e.caja = '033' THEN 46 WHEN e.caja = '034' THEN 47 WHEN e.caja = '035' THEN 50 WHEN e.caja = '036' THEN 51 WHEN e.caja = '037' THEN 52 WHEN e.caja = '038' THEN
                                            53 WHEN e.caja = '039' THEN 54 WHEN e.caja = '040' THEN 57 WHEN e.caja = '041' THEN 55 WHEN e.caja = '042' THEN 56 WHEN e.caja = '043' THEN 58 WHEN e.caja = '044' THEN 59 WHEN e.caja = '045' THEN
                                            60 WHEN e.caja = '046' THEN 61 WHEN e.caja = '047' THEN 62 WHEN e.caja = '048' THEN 63 WHEN e.caja = '049' THEN 64
                                            WHEN e.caja = '050' THEN 65
                                            WHEN e.caja = '051' THEN 66
                                            WHEN e.caja = '052' THEN 67
                                            WHEN e.caja = '053' THEN 68  
                                            WHEN e.caja = '006' THEN 2 WHEN e.caja = '005' THEN 16 WHEN e.caja = '009' THEN 26
                                            WHEN e.caja = '008' THEN 27 WHEN e.caja = '010' THEN 28 WHEN e.caja = '007' THEN 29 WHEN e.caja = '012' THEN 19 WHEN e.caja = '011' THEN 20 WHEN e.caja = '013' THEN 21 WHEN e.caja = '014' THEN 22
                                            WHEN e.caja = '015' THEN 23 WHEN e.caja = '016' THEN 24 WHEN e.caja = '017' THEN 25 ELSE '0' END AS NUMCAJA, CASE WHEN e.BODEGA = 'CA25' THEN 'SM4' ELSE LEFT(e.DOCUMENTO, 3) END AS SERIE, 
                                        ABS(CONVERT(int, MIN(NUMERO))) AS INICIAL, ABS(CONVERT(int, MAX(NUMERO)) + 2) AS FINAL, Round(SUM(TOTAL_PAGAR) / 1.13,2) AS NETO, ROUND(SUM(TOTAL_PAGAR) - SUM(TOTAL_PAGAR) / 1.13,2) AS IVA
                          FROM            (SELECT        consny.DOCUMENTO_POS.DOCUMENTO, consny.DOCUMENTO_POS.TIPO, consny.DOCUMENTO_POS.CAJA, consny.DOCUMENTO_POS.CORRELATIVO, RIGHT(consny.DOCUMENTO_POS.DOCUMENTO, 
                                                                              6) AS NUMERO, CASE CONSNY.DOCUMENTO_POS.TIPO WHEN 'D' THEN TOTAL_PAGAR * - 1 ELSE TOTAL_PAGAR END AS TOTAL_PAGAR, 
                                                                              CASE WHEN CONSNY.DOCUMENTO_POS.documento LIKE '%R1%' THEN 'R1' WHEN CONSNY.DOCUMENTO_POS.documento LIKE '%R2%' THEN 'R2' WHEN CONSNY.DOCUMENTO_POS.documento LIKE
                                                                               '%R3%' THEN 'R3' WHEN CONSNY.DOCUMENTO_POS.documento LIKE '%R4%' THEN 'R4' WHEN CONSNY.DOCUMENTO_POS.documento LIKE '%R5%' THEN 'R5' ELSE 'FAC' END AS RESOLUCION, 
                                                                              CONVERT(date, consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS FECHA, consny.DOCUMENTO_POS.CAJERO AS TIENDA, consny.CAJA_POS.BODEGA, consny.DOCUMENTO_POS.NUM_CIERRE, 
                                                                              LEFT(consny.CAJA_POS.BODEGA, 1) AS EMPRESA, MONTH(consny.DOCUMENTO_POS.FCH_HORA_COBRO) AS MES
                                                    FROM            consny.DOCUMENTO_POS INNER JOIN
                                                                              consny.CAJA_POS ON consny.DOCUMENTO_POS.CAJA = consny.CAJA_POS.CAJA AND consny.DOCUMENTO_POS.CAJA_COBRO = consny.CAJA_POS.CAJA
                                                    WHERE        (MONTH(consny.DOCUMENTO_POS.FCH_HORA_COBRO) = '$mes') AND (YEAR(consny.DOCUMENTO_POS.FCH_HORA_COBRO) = '$anio') AND (consny.DOCUMENTO_POS.ESTADO_COBRO = 'C') AND consny.DOCUMENTO_POS.CORRELATIVO IN ('GENERAL','TICKET') and
                                                                              (consny.DOCUMENTO_POS.CORRELATIVO <> 'CREDITO')) AS E
                          WHERE        (BODEGA LIKE '$empresa%')
                          GROUP BY FECHA, BODEGA, LEFT(DOCUMENTO, 3), CAJA) AS E
						  order by 3,1")or die($conexion1->error());


$num=$c->rowCount();
//echo "<script>alert('$n')</script>";
if($num==0)
{
  echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
}else
{
	echo "<table border='1' cellpadding='10' style='border-collapse:collapse; margin-top:1%;'>";
	echo "<tr>
		<td>FECHA</td>
		<td>PERIODO</td>
		<td>BODEGA</td>
		<td>NUMERO CAJA</td>
		<td>SERIE</td>
		<td>INICIAL</td>
		<td>FINAL</td>
		<td>NETO</td>
		<td>IVA</td>
		<td>TOTAL VENTA</td>
		<td>IdResolucion</td>
		<td>Resolucion</td>
		<td>NumeroSerie</td>
	</tr>";
	$total=0;
	$total_neto=0;
	$total_iva=0;
	$total_final=0;

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$total=$f['NETO'] + $f['IVA'];
		$total_neto=$total_neto + $f['NETO'];
		$total_iva=$total_iva + $f['IVA'];
		$total_final=$total_final + $total;
		$bo=$f['BODEGA'];
		$cb=$conexion1->query("select * from consny.bodega where bodega='$bo'")or die($conexion1->error());
		$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
		$bodegas="".$fcb['BODEGA'].":".$fcb['NOMBRE']."";
		$fecha=$f['PERIODO'];
		echo "<tr>
		<td>".$f['FECHA']."</td>
		<td>".$f['PERIODO']."</td>
		<td>$bodegas</td>
		<td>".$f['NUMCAJA']."</td>
		<td>".$f['SERIE']."</td>
		<td>".$f['INICIAL']."</td>
		<td>".$f['FINAL']."</td>
		<td>".$f['NETO']."</td>
		<td>".$f['IVA']."</td>
		<td>$total</td>
		<td>".$f['IdResolucion']."</td>
		<td>".$f['Resolucion']."</td>
		<td>".$f['NumeroSerie']."</td>
	</tr>";
	$fecha_venta=$f['FECHA'];
	$fecha_digitacion=$f['PERIODO'];
	$caja=$f['NUMCAJA'];
	$serie=$f['SERIE'];
	$doc_inicial=$f['INICIAL'];
	$doc_final=$f['FINAL'];
	$venta_gravada=$f['NETO'];
	$iva=$f['IVA'];
	$IdResolucion=$f['IdResolucion'];
	$Resolucion=$f['Resolucion'];
	$NumeroSerie=$f['NumeroSerie'];
	$tipo_doc=2;
	$conexion1->query("insert into LibrosIVA.dbo.Ventas(IdTipoDocumento,FechaVenta,FechaDigitacion,IdCaja,Serie,DocumentoInicial,DocumentoFinal,VentaGravada,IVA,IdEmpresa,IdPeriodo,CodigoUsuario,IdResolucion,Resolucion,NumeroSerie)values('$tipo_doc','$fecha_venta','$fecha_digitacion','$caja','$serie','$doc_inicial','$doc_final','$venta_gravada','$iva','$idempresa','$idperiodo','ADMIN','$IdResolucion','$Resolucion','$NumeroSerie')
")or die($conexion1->error());


	}
	echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
	echo "<script>location.replace('contabilidad_ventas.php')</script>";
}
}else
{
	echo "<script>location.replace('contabilidad_ventas.php')</script>";
}





?>
</body>
</html>
