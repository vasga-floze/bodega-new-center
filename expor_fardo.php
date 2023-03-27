<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
 $art=$_GET['art'];
 $bodega=$_GET['bodega'];
 $fardo=$_GET['fardo'];
  header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=fardos.xls');
 if($art!='' and $bodega!='')
 {
 	$c=$conexion2->query("select * from registro where bodega='$bodega' and codigo='$art' and activo is null")or die($conexion2->error());
 }else if($art!='' and $bodega=='')
 {
 	$c=$conexion2->query("select * from registro where codigo='$art' and activo is null")or die($conexion2->error());
 }else if($art=='' and $bodega!='')
 {
 	$c=$conexion2->query("select * from registro where bodega='$bodega' and activo is null")or die($conexion2->error());
 }
 echo "<table border='1' cellpadding='10' style='text-align:center;'>";
 echo "<tr>
 	<td>ARTICULO</td>
 	<td>DESCRIPCION</td>
 	<td>BODEGA</td>
 	<td>CODIGO BARRA</td>
    <td>PESO</td>
 	<td>FECHA</td>
 	<td>FARDO ABIERTO</td>
 </tr>";
 while($f=$c->FETCH(PDO::FETCH_ASSOC))
 {
 	if($f['fecha_traslado']!='')
 	{
 		$fecha=$f['fecha_traslado'];
 	}else
 	{
 		$fecha=$f['fecha_documento'];
 	}
 	$art=$f['codigo'];
 	$idr=$f['id_registro'];
    $peso=$f['lbs'] + $f['peso'];
 	$cd=$conexion2->query("select * from desglose where registro='$idr'")or die ($conexion2->error());
 	$ncd=$cd->rowCount();
 	if($ncd==0)
 	{
 		$o='NO';
 	}else
 	{
 		$o='SI';
 	}
 	if($fardo==$o)
 	{
 		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
 		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
 		echo "<tr>
 	<td>".$fca['ARTICULO']."</td>
 	<td>".$fca['DESCRIPCION']."</td>
 	<td>".$f['bodega']."</td>
 	<td>".$f['barra']."</td>
    <td>$peso</td>
 	<td>$fecha</td>
 	<td>$o</td>
 </tr>";
 	}
 	
 }
?>