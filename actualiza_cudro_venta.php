<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


<?php
include("conexion.php");
?>
<form method="POST">
	<input type="text" name="bodega">
</form>

<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion1->query("select * from cuadro_venta where (total_fardo is null or fardos_vendidos is null) and bodega='$bodega' 
")or die($conexion1->error());
	echo "<table border='1'>";
	echo "<tr>
		<td>ID</td>
		<td>FECHA</td>
		<td>CAJA</td>
		<td>BODEGA</td>
		<td>DESCUENTO</td>
		<td>FARDOS VENDIDOS</td>
		<td>TOTAL_FARDO</td>
		<td>MONTO_LIQUIDACIONES</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC)){

	$id=$f['ID'];
	$bodega=$f['BODEGA'];
	$caja=$f['CAJA'];
	$descuento=$f['DESCUENTO'];
	$total_fardo=$f['TOTAL_FARDO'];
	$monto_liquidaciones=$f['MONTO_LIQUIDACIONES'];
	$fardos_vendidos=$f['FARDOS_VENDIDOS'];
	$fecha=$f['FECHA'];

	

	echo "<tr>
		<td>$id</td>
		<td>$fecha</td>
		<td>$caja</td>
		<td>$bodega</td>
		<td>$descuento</td>
		<td>$fardos_vendidos</td>
		<td>$total_fardo</td>
		<td>$monto_liquidaciones</td></tr>";

	$cu=$conexion1->query("select * from usuariobodega where bodega='$bodega'")or die($conexion1->error());
	$fcu=$cu->FETCH(PDO::FETCH_ASSOC);
	$hamachi=$fcu['HAMACHI'];
	$bd=$fcu['BASE'];

	try {
$conexion_tienda=new PDO("sqlsrv:Server=$hamachi;Database=$bd", "sa", "$0ftland");
}
catch(PDOException $e) {
        die("<h1>!!ERROR!! NO SE LOGRO CONECTAR CON LA BASE DE DATOS VERIFICA SI HAMACHI SE ENCUENTRA ENCENDIDO Y ACTUALIZA LA PAGINA</h1> ");
    }

    $con=$conexion_tienda->query("declare @fecha datetime='$fecha'

SELECT isnull(convert(decimal(10,2),SUM(E.TOTAL)),0) as tfardo FROM 
(select case consny.DOC_POS_LINEA.TIPO when 'F' THEN  SUM(PRECIO_VENTA+TOTAL_IMPUESTO1-DESCUENTO_LINEA) ELSE SUM(PRECIO_VENTA+TOTAL_IMPUESTO1-DESCUENTO_LINEA) *-1 END  TOTAL from consny.DOC_POS_LINEA where caja='$caja' and DOCUMENTO IN
(select documento from consny.documento_pos where caja='$caja' and FCH_HORA_COBRO between DATEADD(minute,1,@fecha) and DATEADD(MINUTE,1439,@fecha))
and (ARTICULO like 'P%' or ARTICULO like 'T%' or ARTICULO like 'F%')
GROUP BY consny.DOC_POS_LINEA.TIPO
) AS E
")or die($conexion_tienda->error());
			$fcon=$con->FETCH(PDO::FETCH_ASSOC);
			$total_fardo1=$fcon['tfardo'];

			$cfv=$conexion_tienda->query("declare @fecha datetime='$fecha'
declare @caja nvarchar(4)='$caja'

SELECT isnull(convert(decimal(10,2),SUM(E.TOTAL)),0) as total FROM 
(select case consny.DOC_POS_LINEA.TIPO when 'F' THEN  SUM(CANTIDAD) ELSE SUM(CANTIDAD) *-1 END  TOTAL 
from consny.DOC_POS_LINEA where caja=@caja and DOCUMENTO IN
(select documento from consny.documento_pos where caja=@caja and FCH_HORA_COBRO between DATEADD(minute,1,@fecha) and DATEADD(MINUTE,1439,@fecha))
and (ARTICULO like 'P%' or ARTICULO like 'T%' or ARTICULO like 'F%')
GROUP BY consny.DOC_POS_LINEA.TIPO
) AS E
")or die($conexion_tienda->error());

			$fcfv=$cfv->FETCH(PDO::FETCH_ASSOC);
			$fardos_vendidos1=$fcfv['total'];

	$cl=$conexion2->query("select convert(decimal(10,2),isnull(sum((precio_origen * cantidad) - (precio_destino * cantidad)),0)) as total from liquidaciones where bodega='$bodega' and fecha='$fecha'")or die($conexion1->error());
	$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
	$monto_liquidaciones1=$fcl['total'];

	$consulta=$conexion_tienda->query("declare @fecha datetime='$fecha'
SELECT convert(decimal(10,2),SUM(E.DESCUENTO)) as descuento from 
(select case TIPO when 'F' then   sum(descuento_linea)*1.13 else sum(descuento_linea*-1)*1.13 END DESCUENTO
from consny.DOC_POS_LINEA where caja='$caja' and DOCUMENTO IN 
(select DOCUMENTO from consny.documento_pos where caja='$caja' and FCH_HORA_COBRO between DATEADD(minute,1,@fecha) and DATEADD(MINUTE,1439,@fecha))
group by TIPO) as E
")or die($conexion_tienda->error());
			$fconsulta=$consulta->FETCH(PDO::FETCH_ASSOC);
			$descuento1=$fconsulta['descuento'];
			if($descuento1=='')
			{
				$descuento1="0.00";
			}
			echo "<tr style='background-color:#94A89A;'>
		<td>$id</td>
		<td>$fecha</td>
		<td>$caja</td>
		<td>$bodega</td>
		<td>$descuento1</td>
		<td>$fardos_vendidos1</td>
		<td>$total_fardo1</td>
		<td>$monto_liquidaciones1</td>
	</tr>";
	//$conexion1->query("update cuadro_venta set descuento='$descuento1',total_fardo='$total_fardo1',fardos_vendidos='$fardos_vendidos1',monto_liquidaciones='$monto_liquidaciones1' where id='$id'")or die($conexion1->error());

}
}
?>
</body>
</html>