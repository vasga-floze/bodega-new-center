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
<input type="text" name="art">
<input type="submit" name="btn" value="BUSCAR">	
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	echo "<table border='1' cellapadding='10'>";
	echo "<tr>
			<td>CANTIDAD SISTEMA</td>
			<td>DOCUMENTO</td>
			<td>ARTICULO</td>
			<td>BODEGA SISTEMA</td>
			<td>CANTIDAD EXACTUS</td>
			<td>BODEGA EXACTUS</td>
			<td>TABLA EXACTUS</td>";
		$cp=$conexion2->query("select documento_producion,count(documento_producion) as cantidad,bodega_produccion from registro where codigo='$art' and estado='1' and tipo='p' group by documento_producion,bodega_produccion")or die($conexion2->error());
		while($fcp=$cp->FETCH(PDO::FETCH_ASSOC))
		{
			$doc=$fcp['documento_producion'];
			$cantidad=$fcp['cantidad'];
			$bodega=$fcp['bodega_produccion'];

			$ca=$conexion1->query("select concat(articulo,':',descripcion) as articulo from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$articulo=$fca['articulo'];
			echo "<tr>
			<td>$cantidad</td>
			<td>$doc</td>
			<td>$articulo</td>
			<td>$bodega</td>";
		

		$cl=$conexion1->query("select bodega,count(bodega) as cantidad from consny.linea_doc_inv where articulo='$art' and documento_inv='$doc' group by bodega")or die($conexion1->error());
		$ncl=$cl->rowCount();
		$fcl=$cl->FETCH(PDO::FETCH_ASSOC);

		$ct=$conexion1->query("declare @doc varchar(20)='$doc';
			declare @audi int=(select audit_trans_inv from consny.audit_trans_inv where aplicacion=@doc);
			select bodega,count(bodega) as cantidad from consny.transaccion_inv where articulo='$art' and audit_trans_inv=@audi group by bodega")or die($conexion1->error());
		$nct=$ct->rowCount();
		$fct=$ct->FETCH(PDO::FETCH_ASSOC);

		if($ncl!=0 and $nct==0)
		{
			$tabla="LINEA_DOC";
			$cantidad_ex=$fcl['cantidad'];
			$bodega_ex=$fcl['bodega'];
			echo "we";
		}else if($ncl==0 and $nct!=0)
		{
			$tabla="TRANSACCION_INV";
			$cantidad_ex=$fct['cantidad'];
			$bodega_ex=$fct['bodega'];


		}else
		{
			$tabla="error";
			$bodega_ex="----";
			$cantidad_ex="---";
		}
		if($cantidad!=$cantidad_ex)
		{
			echo "<td style='background-color:red; color:white;'>";
		}else
		{
			echo "<td>";
		}
		echo "$cantidad_ex</td>
			<td>$bodega_ex</td>
			<td>$tabla</td>

		</tr>";
	}
	//finproduciion
	$cp=$conexion2->query("select documento_inv_contenedor,count(documento_inv_contenedor) as cantidad,bodega_produccion from registro where codigo='$art' and estado='1' and tipo='cd' group by documento_inv_contenedor,bodega_produccion")or die($conexion2->error());
		while($fcp=$cp->FETCH(PDO::FETCH_ASSOC))
		{
			$doc=$fcp['documento_inv_contenedor'];
			$cantidad=$fcp['cantidad'];
			$bodega=$fcp['bodega_produccion'];

			$ca=$conexion1->query("select concat(articulo,':',descripcion) as articulo from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$articulo=$fca['articulo'];
			echo "<tr>
			<td>$cantidad</td>
			<td>$doc</td>
			<td>$articulo</td>
			<td>$bodega</td>";
		

		$cl=$conexion1->query("select bodega,count(bodega) as cantidad from consny.linea_doc_inv where articulo='$art' and documento_inv='$doc' group by bodega")or die($conexion1->error());
		$ncl=$cl->rowCount();
		$fcl=$cl->FETCH(PDO::FETCH_ASSOC);

		$ct=$conexion1->query("declare @doc varchar(20)='$doc';
			declare @audi int=(select audit_trans_inv from consny.audit_trans_inv where aplicacion=@doc);
			select bodega,count(bodega) as cantidad from consny.transaccion_inv where articulo='$art' and audit_trans_inv=@audi group by bodega")or die($conexion1->error());
		$nct=$ct->rowCount();
		$fct=$ct->FETCH(PDO::FETCH_ASSOC);

		if($ncl!=0 and $nct==0) 
		{
			$tabla="LINEA_DOC";
			$cantidad_ex=$fcl['cantidad'];
			$bodega_ex=$fcl['bodega'];
			echo "we";
		}else if($ncl==0 and $nct!=0)
		{
			$tabla="TRANSACCION_INV";
			$cantidad_ex=$fct['cantidad'];
			$bodega_ex=$fct['bodega'];


		}else
		{
			$tabla="error";
			$bodega_ex="----";
			$cantidad_ex="---";
		}
		if($cantidad!=$cantidad_ex)
		{
			echo "<td style='background-color:red; color:white;'>";
		}else
		{
			echo "<td>";
		}
		echo "$cantidad_ex</td>
			<td>$bodega_ex</td>
			<td>$tabla</td>

		</tr>";
	}
}
?>
</body>
</html>