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
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select documento_producion,documento_inv_contenedor from registro where codigo='$art' group by documento_producion,documento_inv_contenedor")or die($conexion2->error());
	$n=$c->rowCount();
	if($n!=0)
	{
		echo "<table border='1'>";
		echo "<tr>
		<td>articulo</td>
			<td>documento</td>
			<td>fecha</td>
			<td>cantidad Sys</td>
			<td>Cantidad exactus</td>
			<td>aplicado</td>
			<td>tabla</td>
		</tr>";
		$tabla='N/A';
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$aplicado='';
			$tabla='N/a';
			$cantidadex=0;
			if($f['documento_producion']!='')
			{
				$doc=$f['documento_producion'];
				$campo='documento_producion';
			}else
			{
				$doc=$f['documento_inv_contenedor'];
				$campo='documento_inv_contenedor';
			}
			$q=$conexion2->query("select codigo,count(codigo) as cantidad,fecha_documento from registro where $campo='$doc' and codigo='$art' group by codigo,fecha_documento") or die($conexion2->error());
			$fq=$q->FETCH(PDO::FETCH_ASSOC);
			$cantidadsys=$fq['cantidad'];

			$cd=$conexion1->query("select articulo,count(articulo) as cantidad from consny.linea_doc_inv where documento_inv='$doc' and articulo='$art' group by articulo")or die($conexion1->error());
			$ncd=$cd->rowCount();
			if($ncd!=0)
			{
				$aplicado='NO';
				$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
				$cantidadex=$fcd['Cantidad'];
				$tabla='linea';
				//$cant2= $cantidadex;
			}else
			{
				
			}
			$cau=$conexion1->query("declare @audi varchar(50)=(select audit_trans_inv from consny.audit_trans_inv where aplicacion='$doc');
				select articulo,count(articulo) as cantidad from consny.transaccion_inv where audit_trans_inv=@audi and articulo='$art' group by articulo
					")or die($conexion1->error());
			$ncau=$cau->rowCount();
			if($ncau!=0)
			{
				$aplicado='SI';
				//$cant2=0;
				$fcau=$cau->FETCH(PDO::FETCH_ASSOC);
				$cantidadex=$fcau['cantidad'];
				$tabla='audi';
				$cant1=$fcau['cantidad'];
			}

			if($cantidadsys==$cantidadex)
			{
				echo "<tr>";
			}else
			{
				echo "<tr style='background-color:red;'>";
			}
			echo "
			<td>$art</td>
			<td>$doc</td>
			<td>".$fq['fecha_documento']."</td>
			<td>$cantidadsys</td>
			<td>$cantidadex</td>
			<td>$aplicado</td>
			<td>$tabla | $campo |</td>
		</tr>";
		}
	}
}


?>
</body>
</html>