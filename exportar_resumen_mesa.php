<meta charset="utf-8">
<?php
ini_set('max_execution_time', 9000);
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
   
    $desde=$_GET['desde'];
    $hasta=$_GET['hasta'];
    $clasificacion=$_GET['clasificacion'];
    $titulo=$_GET['titulo'];
    $bodega=$_GET['bodega'];
        /*$c=$conexion2->query("SELECT        registro.codigo, COUNT(registro.codigo) as cantidad, SUM(ISNULL(convert(int,registro.peso),0) + ISNULL(CONVERT(int,registro.lbs),0)) AS peso
FROM            detalle_mesa INNER JOIN
                         mesa ON detalle_mesa.mesa = mesa.id INNER JOIN
                         registro ON detalle_mesa.registro = registro.id_registro
                         where mesa.fecha between '$desde' and '$hasta' and registro.bodega='$bodega' and registro.activo is not null
GROUP BY registro.codigo order by registro.codigo
")or die($conexion2->error());/*/
$c=$conexion2->query("select registro.codigo,count(registro.codigo) as cantidad,sum(isnull(registro.lbs,0)+isnull(registro.peso,0)) as peso,mesa.producido from registro  inner join detalle_mesa on registro.id_registro=detalle_mesa.registro inner join mesa on detalle_mesa.mesa=mesa.id where mesa.fecha between '$desde' and '$hasta' and mesa.bodega='$bodega' group by mesa.producido,registro.codigo order by registro.codigo
")or die($conexion2->error());
    if($clasificacion=='')
    {
    	$msj='TODAS';
    }else
    {
    	$msj=$clasificacion;
    }
    $n=$c->rowCount();
    if($n==0)
    {
    	echo "<script>alert('FUE IMPOSIBLE EXPORTAR A EXCEL INTENTELO NUEVAMENTE')";
    }else
    {
    	 header('Content-type:application/xls');
    
		header('Content-Disposition: attachment; filename=reporte-bultos.xls');

		echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
		echo "<tr>
<td colspan='7'>$titulo </td>
</tr>";
echo "<tr>
<td>PRODUCIDO POR</td>
<td>ARTICULO</td>
<td>DESCRIPCION</td>
<td>CLASIFICACION</td>
<td>CANTIDAD</td>
<td>PESO</td>
<td>BODEGA</td>
</tr>";
$t=0;
$tp=0;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$art=$f['codigo'];
	$cant=$f['cantidad'];
	$peso=$f['peso'];
    $producido=$f['producido'];
	$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);
if($clasificacion=='' or $clasificacion==$fca['CLASIFICACION_2'])
{
	$t=$t + $cant;
	echo "<tr>
    <td>$producido</td>
<td>".$fca['ARTICULO']."</td>
<td>".$fca['DESCRIPCION']."</td>
<td>".$fca['CLASIFICACION_2']."</td>
<td>$cant</td>
<td>$peso</peso>
<td>$bodega</td>
</tr>";
$tp=$tp+$peso;





}
}
echo "<tr>
	<td colspan='4'>TOTAL</td>
	<td>$t</td>
	<td>$tp</td>
	<td></td>
</tr>";
echo "</table>";//


    }

?>
<script type="text/javascript">
	window.close();
</script>