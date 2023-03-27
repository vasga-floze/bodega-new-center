<style>

	body{
		font-family: Consolas, monaco, monospace;
	}
</style>
<meta charset="utf-8">
<?php
$bodega=$_GET['bodega'];
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
if($desde=='' or $hasta=='')
{
	echo "<script>alert('ERROR!! NO SE PUDO HACER LA EXPORTACION')</script>";
}else
{


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
if($bodega=='')
	{
		$c=$conexion2->query("select eximp600.consny.articulo.articulo,count(eximp600.consny.articulo.articulo)as cantidad,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino,sum(convert(decimal(10,2),isnull(pruebabd.dbo.registro.lbs,0)+isnull(pruebabd.dbo.registro.peso,0))) as peso 
from pruebabd.dbo.registro inner join eximp600.consny.articulo on 
pruebabd.dbo.registro.codigo=eximp600.consny.articulo.articulo inner join pruebabd.dbo.traslado on 
pruebabd.dbo.registro.id_registro=pruebabd.dbo.traslado.registro where (pruebabd.dbo.traslado.origen like 'SM%' or traslado.origen='CA00')
and pruebabd.dbo.traslado.destino not like 'SM%' and pruebabd.dbo.traslado.fecha between '$desde' and '$hasta' 

group by eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino,eximp600.consny.articulo.clasificacion_2 order by traslado.destino,eximp600.consny.articulo.clasificacion_2")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select eximp600.consny.articulo.articulo,count(eximp600.consny.articulo.articulo)as cantidad,eximp600.consny.articulo.descripcion,eximp600.consny.articulo.clasificacion_2,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino,sum(convert(decimal(10,2),isnull(pruebabd.dbo.registro.lbs,0)+isnull(pruebabd.dbo.registro.peso,0))) as peso 
from pruebabd.dbo.registro inner join eximp600.consny.articulo on 
pruebabd.dbo.registro.codigo=eximp600.consny.articulo.articulo inner join pruebabd.dbo.traslado on 
pruebabd.dbo.registro.id_registro=pruebabd.dbo.traslado.registro where (pruebabd.dbo.traslado.origen like 'SM%' or traslado.origen='CA00')
and pruebabd.dbo.traslado.destino='$bodega' and pruebabd.dbo.traslado.fecha between '$desde' and '$hasta' 

group by eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,pruebabd.dbo.traslado.origen,pruebabd.dbo.traslado.destino,eximp600.consny.articulo.clasificacion_2 order by traslado.destino,eximp600.consny.articulo.clasificacion_2")or die($conexion2->error());
	}

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE OBTUVO NINGUN RESULTADO</h3>";
	}else
	{
		header('Content-type:application/xls');
    
		header('Content-Disposition: attachment; filename=REPORTE-TRASLADOS-'.$desde.'-'.$hasta.'.xls');
		echo "<table border='1' style='border-collapse:collapse; width:130%;'>";
		echo "<tr style='text-align:center;'>
			<td colspan='7'>RESULTADO DE LA BUSQUEDA DE $desde - $hasta $bodega</td>
		</tr>";
		echo "<tr>
			<td>EMPRESA</td>
			<td width='20%'>BODEGA ORIGEN</td>
			<td>BODEGA DESTINO</td>
			<td>ARTICULO</td>
			<td width='25%'>DESCRIPCION</td>
			<td>CATEGORIA</td>
			<td>CANTIDAD</td>
			<td>TOTAL PESO</td>
		</tr>";
		$tc=0; $tp=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$origen=$f['origen'];
			$destino=$f['destino'];
			$art=$f['articulo'];
			$cantidad=$f['cantidad'];
			$peso=$f['peso'];
			$art=$f['articulo'];
			$desc=$f['descripcion'];
			$clasi=$f['clasificacion_2'];
			$destino1=$destino;
			$cbd=$conexion1->query("select concat(consny.bodega.bodega,': ',consny.bodega.nombre) as bodega,usuariobodega.esquema from consny.bodega inner join usuariobodega on usuariobodega.bodega=consny.bodega.bodega where consny.bodega.bodega='$destino'")or die($conexion1->error());
			$fcbd=$cbd->FETCH(PDO::FETCH_ASSOC);
			$destino=$fcbd['bodega'];
			$empresa=$fcbd['esquema'];

			$cbo=$conexion1->query("select concat(bodega,': ',nombre) as bodega from consny.bodega where bodega='$origen'")or die($conexion1->error());
			$fcbo=$cbo->FETCH(PDO::FETCH_ASSOC);
			$origen=$fcbo['bodega'];
			if($destino1=='US01')
			{
				$empresa='USULUTAN';
			}
			if($bodega=='CA00')
			{
				$qb=$conexion1->query("select concat(bodega,': ',nombre) as bodega from consny.bodega where bodega='$bodega'")or die($conexion1->error());
				$fqb=$qb->FETCH(PDO::FETCH_ASSOC);
				$destino=$fqb['bodega'];
			}
			echo "<tr>
			<td>$empresa</td>
			<td>$origen</td>
			<td>$destino</td>
			<td>$art</td>
			<td>$desc</td>
			<td>$clasi</td>
			<td>$cantidad</td>
			<td>$peso</td>
		</tr>";
		$tc=$tc+$cantidad;
		$tp=$tp+$peso;
		}
		echo "<tr>
			<td colspan='6'>TOTAL</td>
			<td>$tc</td>
			<td>$tp</td>
		</tr>";
	}
}
?>
<script>
	window.close();
</script>