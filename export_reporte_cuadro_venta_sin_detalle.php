<!DOCTYPE html>
<html>
<head>
	<title></title>
    <meta charset="utf-8">
</head>
<body>
<?php
try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("!!ERROR!! SE PERDIO CONEXION CON EL SERVIDOR: " );
    }

    try {
        $conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }
ini_set('max_execution_time', 9000);
$bodega=$_GET['bodega'];
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
if($bodega=='' and $desde=='' and $hasta=='')
{
    echo "<script>alert('IMPOSIBLE EXPORTAR A EXCEL')</script>";
}else
{
    header('Content-type:application/xls');
    header('Content-Disposition: attachment; filename=reporte_cuadro_venta_sin_detalle.xls');
    
}
if($bodega!='')
    {
        $c=$conexion1->query("select BODEGA,sum(monto_usuario) as MONTO_TIENDA,sum(monto_sistema) as MONTO_SISTEMA,SUM(CONVERT(DECIMAL(10,2),DESCUENTO)) AS MONTO_DESCUENTO,SUM(CONVERT(DECIMAL(10,2),TOTAL_FARDO)) AS MONTO_FARDO,SUM(CONVERT(DECIMAL(10,2),FARDOS_VENDIDOS)) AS CANTIDAD_FARDOS,convert(decimal(10,2),sum(monto_liquido)) as MONTO_LIQUIDO  FROM CUADRO_VENTA WHERE FECHA BETWEEN '$desde' AND '$hasta' and bodega='$bodega'  GROUP BY BODEGA ORDER BY BODEGA
")or die($conexion1->error());
    }else
    {
        
        $c=$conexion1->query("select BODEGA,sum(monto_usuario) as MONTO_TIENDA,sum(monto_sistema) as MONTO_SISTEMA,isnull(SUM(CONVERT(DECIMAL(10,2),DESCUENTO)),0) AS MONTO_DESCUENTO,isnull(SUM(CONVERT(DECIMAL(10,2),TOTAL_FARDO)),0) AS MONTO_FARDO,isnull(SUM(CONVERT(DECIMAL(10,2),FARDOS_VENDIDOS)),0) AS CANTIDAD_FARDOS,isnull(convert(decimal(10,2),sum(monto_liquido)),0) as MONTO_LIQUIDO  FROM CUADRO_VENTA WHERE FECHA BETWEEN '$desde' AND '$hasta'  GROUP BY BODEGA ORDER BY BODEGA
")or die($conexion1->error());
    }
    $n=$c->rowCount();
    if($n==0)
    {
        echo "NO SE OBTUBO NINGUN RESULTADO";
    }else
    {
        echo "<table border='1' style='border-collapse:collapse; width:110%;'>";
        echo "<tr>
            <td width='20%'>BODEGA</td>
            <td>MONTO TIENDA</td>
            <td>MONTO SISTEMA</td>
            <td>TOTAL INGRESO</td>
            <td>TOTAL SALIDA</td>
            <td>TOTAL DESCUENTOS</td>
            <td>MONTO LIQUIDO</td>
            <td>FARDOS VENDIDOS</td>
            <td>MONTO FARDOS</td>
            <td>TOTAL LIQUIDACIONES</td>            
            <td>FARDOS ABIERTOS</td>
            <td>TOTAL DESGLOSE</td>
            <td>TOTAL AVERIA</td>
            <td>TOTAL MERCA.NO VENDIBLE</td>


        </tr>";
        while($f=$c->FETCH(PDO::FETCH_ASSOC))
        {
            $bodega=$f['BODEGA'];
            $monto_tienda=$f['MONTO_TIENDA'];
            $monto_sistema=$f['MONTO_SISTEMA'];
            $descuento=$f['MONTO_DESCUENTO'];
            $monto_fardos=$f['MONTO_FARDO'];
            $cant_fardo=$f['CANTIDAD_FARDOS'];
            $monto_liquido=$f['MONTO_LIQUIDO'];
            $q=$conexion1->query("select tipo_tranSaccion as TRANSACCION,SUM(CONVERT(DECIMAL(10,2),MONTO)) AS TOTAL,CUADRO_VENTA.BODEGA FROM CUADRO_VENTA_DETALLE INNER JOIN CUADRO_VENTA ON CUADRO_VENTA.ID=CUADRO_VENTA_DETALLE.CUADRO_VENTA where cuadro_venta.fecha between '$desde' and '$hasta' and bodega='$bodega' GROUP BY TIPO_TRANSACCION,CUADRO_VENTA.BODEGA ORDER BY CUADRO_VENTA.BODEGA
")or die($conexion1->error());
            $ingresos=0;
            $salidas=0;
            while($fq=$q->FETCH(PDO::FETCH_ASSOC))
            {
                $transaccion=$fq['TRANSACCION'];
                $monto=$fq['TOTAL'];
                if($transaccion=='INGRESO')
                {
                    $ingresos=$monto;
                }else
                {
                    $salidas=$monto;
                }

            }
            $eingreso=explode(".", $ingresos);
            if($eingreso[0]=='')
            {
                $ingresos="0.$eingreso[1]";
            }

            $esalida=explode(".", $salidas);
            if($esalida[0]=='')
            {
                $salidas="0.$esalida[1]";
            }
        $cd=$conexion2->query("select(select count(*) as cantidad from registro where bodega='$bodega' and fecha_desglose between '$desde' and '$hasta')as cantidad, 
(select sum(cantidad*precio)as total from desglose where registro in(select id_registro from registro where bodega='$bodega' and fecha_desglose between '$desde' and '$hasta'))as total")or die($conexion2->error());
        $fcd=$cd->FETCH(PDO::FETCH_ASSOC);
        $cant_desglose=$fcd['cantidad'];
        $total_desglose=$fcd['total'];
        $cl=$conexion2->query("select isnull(convert(decimal(10,2),sum(precio_origen * cantidad-precio_destino * cantidad)),0) as total from liquidaciones where bodega='$bodega' and fecha between '$desde' and '$hasta'")or die($conexion2->error());

        $fcl=$cl->FETCH(PDO::FETCH_ASSOC);
        $total_liquidacion=$fcl['total'];

        $ca=$conexion2->query("select sum(convert(decimal(10,2),(precio * cantidad))) as total,TIPO from averias where fecha between '$desde' and '$hasta' and bodega='$bodega' GROUP BY TIPO")or die($conexion2->error());
        $total_averia="0.00";
            $total_merca="0.00";
        while($fca=$ca->FETCH(PDO::FETCH_ASSOC))
        {

            if($fca['TIPO']=='AVERIA')
            {
                $total_averia=$fca['total'];
            }else
            {
                $total_merca=$fca['total'];
            }
        }
            $etotal_averia=explode(".", $total_averia);
            if($etotal_averia[0]=='')
            {
                $total_averia="0.$etotal_averia[1]";
            }
            $emonto_fardos=explode(".", $monto_fardos);
            if($emonto_fardos[0]=='')
            {
                if($emonto_fardos[1]=='')
                {
                    $monto_fardos="0.00";
                }else
                {
                    $monto_fardos="0.$emonto_fardos[1]";
                }
                
            }
            $monto_liquido=$monto_tienda + $ingresos - $salidas;

            $ecant_fardo=explode(".", $cant_fardo);
            if($ecant_fardo[0]=='')
            {
                $cant_fardo="0.$ecant_fardo[1]";
            }

            $eliquidacion=explode(".", $total_liquidacion);
            if($eliquidacion[0]=='')
            {
                $total_liquidacion="0.$eliquidacion[1]";
            }
            $cb=$conexion1->query("select * from consny.bodega where bodega='$bodega'")or die($conexion1->error());
            $fcb=$cb->FETCH(PDO::FETCH_ASSOC);
            $bode="".$fcb['BODEGA'].": ".$fcb['NOMBRE']."";
            if($bodega!='CA16')
            {
                echo "<tr>
            <td>$bode</td>
            <td>$monto_tienda</td>
            <td>$monto_sistema</td>
            <td>$ingresos</td>
            <td>$salidas</td>
            <td>$descuento</td>
            <td>$monto_liquido</td>
            <td>$cant_fardo</td>
            <td>$monto_fardos</td>
            <td>$total_liquidacion</td>     
            <td>$cant_desglose</td>
            <td>$total_desglose</td>
            <td>$total_averia</td>
            <td>$total_merca</td>


        </tr>";
            }
            
        }

    }
?>
<script>
    window.close();
</script>
</body>
</html>