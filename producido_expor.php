<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
 header('Content-type:application/xls');
 header('Content-Disposition: attachment; filename=producido.xls');

$fecha=$_GET['dia'];
$usua=$_GET['usu'];
if($usua=='TODOS')
{
	$c=$conexion2->query("select * from registro where tipo='P' and fecha_documento='$fecha' and estado='0' order by 1 desc")or die($conexion2->error());
}else
{
	$c=$conexion2->query("select * from registro where tipo='P' and fecha_documento='$fecha' and estado='0' and usuario='$usua' order by 1 desc")or die($conexion2->error());

}
echo "<table border='1' cellpadding='10'>";

echo "<tr>
	<td>ARTICULO</td>
	<td>DESCRIPCION</td>
	<td>PESO</td>
	<td>FECHA PRODUCCION</td>
	<td>EMPACADO</td>
	<td>PRODUCIDO POR</td>
	<td>CODIGO BARRA</td>
	<td>BODEGA PRODUCCION</td>
	<td>USUARIO</td>
</tr>";

while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$cod=$f['codigo'];
	$fecha=$f['fecha_documento'];
	$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
	$fca=$ca->FETCH(PDO::FETCH_ASSOC);

	echo "<tr>
	<td>".$fca['ARTICULO']."</td>
	<td>".$fca['DESCRIPCION']."</td>
	<td>".$f['lbs']."</td>
	<td>$fecha</td>
	<td>".$f['empacado']."</td>
	<td>".$f['producido']."</td>
	<td>".$f['barra']."</td>
	<td>".$f['usuario']."</td>
</tr>";
}
echo "</table>";
?>