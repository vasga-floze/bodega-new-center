<meta charset="utf-8">
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
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
//echo "<script>alert('$desde - $hasta')</script>";
if($desde=='' or $hasta=='')
{
	echo "<script>alert('IMPOSIBLE EXPORTAR INTENTELO NUEVAMETE')</script>";
	echo "<script>location.replace('reporte_venta.php')</script>";
}else
{

$q=$conexion2->query("select e.fecha, e.cliente, e.documento_inv,e.Articulo, e.TOTAL_VENTA, e.CANTIDAD,e.TOTAL_VENTA* e.CANTIDAD as totalv  from 
(SELECT        venta.fecha, venta.cliente, venta.documento_inv, CASE WHEN registro IS NULL THEN articulo ELSE registro.codigo END AS Articulo,
SUM(cast(venta.precio as decimal(8,4))) TOTAL_VENTA,
case when registro IS NULL then SUM(cast(venta.cantidad as int)) else COUNT(registro) end AS CANTIDAD
FROM            venta LEFT OUTER JOIN
                         registro ON venta.registro = registro.id_registro
WHERE        venta.fecha between '$desde' and '$hasta'
group by venta.registro, venta.fecha, venta.cliente, venta.documento_inv,CASE WHEN registro IS NULL THEN articulo ELSE registro.codigo END) as E
where e.TOTAL_VENTA is not null
")or die($conexion2->error());
            $n=$q->rowCount();
    if($n==0)
    {
        echo "<script>alert('NO SE ENCONTRO NINGUNA VENTA PARA EXPORTAR INTENTELO NUEVAMETE')</script>";
    echo "<script>location.replace('reporte_venta.php')</script>";
    }else
    {
        header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=reporte-ventas.xls');
        echo "<table border='1' style='border-collapse:collapse; width:100%;' cellpadding='10'>";

        echo "
        <tr>
        <td colspan='7'>VENTAS DESDE: $desde HASTA: $hasta
        </td>
        </tr>
        ";
        echo "<tr>
        <td>FECHA</td>
        <td>CLIENTE</td>
        <td>CANTIDAD</td>
        <td>ARTICULO</td>
        <td>DESCRIPCION</td>            
        <td>TOTAL</td>
        <td>DOCUMENTO</td>
        </tr>";
            $totalf=0;
            while($fq=$q->FETCH(PDO::FETCH_ASSOC))
            {
                $articulo=$fq['Articulo'];
                $cantidad=$fq['CANTIDAD'];
                $total=$fq['totalv'];
                $documento=$fq['documento_inv'];
                $cliente=$fq['cliente'];
                $fecha=$fq['fecha'];
                $ca=$conexion1->query("select * from consny.articulo where articulo='$articulo'")or die($conexion1->error());
                $fca=$ca->FETCH(PDO::FETCH_ASSOC);
                echo "<tr>
                    <td>$fecha</td>
                    <td>$cliente</td>
                    <td>$cantidad</td>
                    <td>$articulo</td>
                    <td>".$fca['DESCRIPCION']."</td>                    
                    <td>$total</td>
                    <td>$documento</td>
                    
                    
                </tr>";
                $totalf=$totalf+$total;
            }
            echo "<tr>
            <td colspan='5'>TOTAL</td>
            <td>$totalf</td><td></td>

            </tr>";
        

    }








}




?>