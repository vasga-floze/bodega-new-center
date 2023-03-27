<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST">
<input type="text" name="barra" class="text" style="width: 20%;" placeholder="CODIGO DE BARRA">
<input type="submit" name="btn" class="boton3" value="GENERAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("declare @barra varchar(20)='$barra'
select CONCAT(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION)
as articulo,registro.barra,registro.bodega_produccion bodega, case registro.tipo when 'p' then
registro.documento_producion when 'CD' then registro.documento_inv_contenedor end 
as documento, case registro.tipo when 'P' then registro.lbs when 'CD' then registro.peso
end as peso, case registro.tipo when 'P' then 'INGRESO POR PRODUCCION' WHEN 'CD' THEN
CONCAT('INGRESO DE CONTENEDOR: ',registro.contenedor) end referencia,registro.fecha_documento,
registro.observacion
from registro inner join EXIMP600.consny.ARTICULO on registro.codigo=
EXIMP600.consny.ARTICULO.ARTICULO where registro.barra=@barra
union all
select '' articulo,'' barra,registro.bodega,mesa.documento_inv,0 peso,'BULTOS TRABAJADOS' referencia,mesa.fecha,'' observacion
from mesa inner join detalle_mesa on mesa.id=detalle_mesa.mesa inner join registro
on registro.id_registro=detalle_mesa.registro where registro.barra=@barra

union all
select '' articulo,'' barra,registro.bodega,venta.documento_inv, 0 peso,'VENTA DE FARDO BODEGAS',
venta.fecha,'' observacion from venta inner join registro on venta.registro=registro.id_registro
where registro.barra=@barra and venta.registro is not null
union all
select '' articulo,'' barra,registro.bodega,documento_inv_consumo,0 peso,'CONSUMO DE FARDO' referencia,
fecha_desglose,'' observacion from registro where (fecha_desglose!='' or fecha_desglose is not null) and barra=@barra
union all
select '' articulo,'' barra,registro.bodega,registro.documento_inv_ing,0 peso,'ING DE FARDO' referencia,
registro.fecha_desglose,'' observacion from registro where (fecha_desglose
!='' or fecha_desglose is not null) and barra=@barra

--select * from registro order by id_registro desc")or die($conexion2->error());

	$n=$c->rowCount();
	if($n!=0)
	{
		echo "<table border='1' style='border-collapse:collapse;'>";
		echo "<tr>
		<td>CODIGO BARRA</td>
		<td>ARTICULO</td>
		<td>PESO</td>
		<td>BODEGA</td>
		<td>TRANSACCION</td>
		<td>FECHA TRANSACCION</td>
		<td>DOCUMENTOS</td>
		<td>OBSERVACION</td>
		<td>ESTADO EXACTUS</td>
		</tr>";

		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			if($f['peso']=='.00')
			{
				$f['peso']='';
			}
			$doc=$f['documento'];

			$ce=$conexion1->query("declare @doc varchar(20)='$doc'
declare @na varchar(20)=(select 'SIN APLICAR' from consny.DOCUMENTO_INV where DOCUMENTO_INV=@doc)
declare @aplicado varchar(20)=(select 'APLICADO' from consny.AUDIT_TRANS_INV where APLICACION=@doc)

select isnull(isnull(@na,@aplicado),'BORRADO DE EXACTUS') as estado")or die($conexion1->error());

			$fce=$ce->FETCH(PDO::FETCH_ASSOC);
			$estado=$fce['estado'];
			echo "<tr>
		<td>".$f['barra']."</td>
		<td>".$f['articulo']."</td>
		<td>".$f['peso']."</td>
		<td>".$f['bodega']."</td>
		<td>".$f['referencia']."</td>
		<td>".$f['fecha_documento']."</td>
		<td>".$f['documento']."</td>
		<td>".$f['observacion']."</td>
		<td>$estado</td>
		</tr>";
		}

	}else
	{
		echo "<h3>NO SE ENCONTRO NINGUN RESULTADO VERIFICA SI EL CODIGO DE BARRA ESTA BIEN DIGITADO: $barra</h3>";
	}
	

}
?>
</body>
</html>