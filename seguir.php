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
<input type="text" name="busca">
<label><input type="radio" name="op" value="1" required>BARRA</label>
<label><input type="radio" name="op" value="2" required>ARTICULO</label>	
<input type="submit" name="" value="BUSCAR">
</form>
<hr>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($op==1)
	{

	$c=$conexion2->query("select * from registro where barra='$busca'")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<H3>CODIGO DE BARRA NO ENCONTRADO</H3>";
	}else
	{
		echo "<table border='1' class='tabla' cellpadding='10'>";
		echo "<tr>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>TIPO</td>
			<td>DOCUMENTOS</td>
			<td>FECHA</td>
		</tr>";
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$tipo='PRODUCCION';
		$art=$f['codigo'];
		$fecha=$f['fecha_documento'];
		$idr=$f['id_registro'];
		$documentos="".$f['documento_inv_consumo']." y ".$f['documento_inv_ing'].""; 
		$fecha_desglose=$f['fecha_desglose'];
		if($f['tipo']=='P')
		{
			$documento=$f['documento_producion'];
		}else
		{
			$documento=$f['documento_inv_contenedor'];
		}
		$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$articulo=$fca['ARTICULO'];
		$descripcion=$fca['DESCRIPCION'];
		echo "<tr>
			<td>$articulo</td>
			<td>$descripcion</td>
			<td>$tipo</td>
			<td>$documento</td>
			<td>$fecha</td>
		</tr>";
		$c=$conexion2->query("select * from traslado where registro='$idr'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n!=0)
		{
			$tipo='TRASLADO';
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$documento=$f['documento_inv'];
				$fecha=$f['fecha'];
				echo "<tr>
			<td>$articulo</td>
			<td>$descripcion</td>
			<td>$tipo</td>
			<td>$documento</td>
			<td>$fecha</td>
		</tr>";
			}
		}
		$c=$conexion2->query("select * from desglose where registro='$idr'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n!=0)
		{
			$tipo='DESGLOSE';
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$fecha=$f['fecha'];
			echo "<tr>
			<td>$articulo</td>
			<td>$descripcion</td>
			<td>$tipo</td>
			<td>$documentos</td>
			<td>$fecha_desglose</td>
		</tr>";
		}

		$c=$conexion2->query("select * from detalle_mesa where registro='$idr'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n!=0)
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$mesa=$f['mesa'];
			$c=$conexion2->query("select * from mesa where id='$mesa' and estado='T'")or die($conexion2->error());
			$n=$c->rowCount();
			if($n!=0)
			{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
		$documento=$f['documento_inv'];
		//echo "<script>alert('$documento - $mesa')</script>";
		$fecha=$f['fecha'];
		$tipo='BULTO';
		echo "<tr>
			<td>$articulo</td>
			<td>$descripcion</td>
			<td>$tipo</td>
			<td>$documento</td>
			<td>$fecha</td>
		</tr>";
	}

		}
		$c=$conexion2->query("select * from venta where registro='$idr'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n!=0)
		{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$documento=$f['documento_inv'];
		$fecha=$f['fecha'];
		$tipo='VENTA';
		echo "<tr>
			<td>$articulo</td>
			<td>$descripcion</td>
			<td>$tipo</td>
			<td>$documento</td>
			<td>$fecha</td>
		</tr>";
	}
	
	}
}else
{
	$cp=$conexion2->query("select documento_producion,codigo,count(codigo) as cantidad from registro where codigo='$busca' and tipo='P' and year(fecha_documento)='2020'  group by documento_producion,codigo")or die($conexion2->error());
	$cc=$conexion2->query("select documento_inv_contenedor,codigo,count(codigo) as cantidad from registro where codigo='$busca' and tipo='CD' and year(fecha_documento)='2020' group by documento_inv_contenedor,codigo")or die($conexion2->error());
	echo "<table border='1' class='tabla' cellpadding='10'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD INGRESADA</td>
		<td>CANTIDAD EXACTUS</td>
		<td>APLICADO</td>
		<td>DOCUMENTO</td>
	</tr>";
	$cant1=0;
	while($fp=$cp->FETCH(PDO::FETCH_ASSOC))
	{
		$art=$fp['codigo'];
		$cant=$fp['cantidad'];
		$documento=$fp['documento_producion'];
		//echo "<table border='1' class='tabla' cellpadding='10'>";
		if($documento!='')
		{
			$cd=$conexion1->query("select * from consny.documento_inv where documento_inv='$documento'")or die($conexion1->error());
			$ncd=$cd->rowCount();
			if($ncd!=0)
			{
				$cl=$conexion1->query("select * from consny.linea_doc_inv where documento_inv='$documento' and articulo='$art' group by articulo")or die($conexion1->error());
				$ncl=$cl->rowCount();
				if($ncl!=0)
				{
					$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
					$cant1=$fcl['cantidad'];
				}
			}else
			{
				
				$ca=$conexion1->query("select * from consny.audit_trans_inv where aplicacion='$documento'")or die($conexion1->error());
				$nca=$ca->rowCount();
				if($nca!=0)
				{
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$audi=$fca['AUDIT_TRANS_INV'];
					
					$ct=$conexion1->query("select articulo,count(articulo) as cantidad from consny.transaccion_inv where audit_trans_inv='$audi' and articulo='$art' group by articulo")or die($conexion1->error());
					$nct=$ct->rowCount();
					if($nct!=0)
					{
						$fct=$ct->FETCH(PDO::FETCH_ASSOC);
						$cant1=$fct['cantidad'];
					}else
					{
						$cant1=0;
					}

				}else
				{
					$cant1=0;
				}
			}
		}
		if($cant!=$cant1)
		{
			echo "<tr style=' background-color:red; color:blue;'>";
		}else
		{
			echo "<tr>";
		}
		echo "
		<td>$art</td>
		<td>DESCRIPCION</td>
		<td>$cant</td>
		<td>$cant1</td>
		<td>APLICADO</td>
		<td>$documento</td>
	</tr>";
	}
}

while($fc=$cc->FETCH(PDO::FETCH_ASSOC))
{
	$codigo=$fc['codigo'];
	$cant=$fc['cantidad'];
	$documento=$fc['documento_inv_contenedor'];
	if($documento!='')
		{
			$cd=$conexion1->query("select * from consny.documento_inv where documento_inv='$documento'")or die($conexion1->error());
			$ncd=$cd->rowCount();
			if($ncd!=0)
			{
				$cl=$conexion1->query("select * from consny.linea_doc_inv where documento_inv='$documento' and articulo='$codigo' group by articulo")or die($conexion1->error());
				$ncl=$cl->rowCount();
				if($ncl!=0)
				{
					$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
					$cant1=$fcl['cantidad'];
				}
			}else
			{
			
				$ca=$conexion1->query("select * from consny.audit_trans_inv where aplicacion='$documento'")or die($conexion1->error());
				$nca=$ca->rowCount();
				if($nca!=0)
				{
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$audi=$fca['AUDIT_TRANS_INV'];
					
					$ct=$conexion1->query("select articulo,count(articulo) as cantidad from consny.transaccion_inv where audit_trans_inv='$audi' and articulo='$codigo' group by articulo")or die($conexion1->error());
					$nct=$ct->rowCount();
					if($nct!=0)
					{
						$fct=$ct->FETCH(PDO::FETCH_ASSOC);
						$cant1=$fct['cantidad'];
					}else
					{
						$cant1=0;
					}

				}else
				{
					$cant1=0;
				}
			}
		}
echo "<tr>
		<td>$codigo</td>
		<td>DESRIPCION</td>
		<td>$cant</td>
		<td>$cant1</td>
		<td>APLICADO</td>
		<td>$documento</td>
	</tr>";
}
echo "</table>";

}//FIN POST
?>
</body>
</html>