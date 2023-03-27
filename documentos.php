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
<input type="date" name="desde">
<input type="date" name="hasta">

<select name="op">
	<option value="1">PRODUCCION</option>
	<option value="2">CONTENEDOR</option>
	<option value="3">VENTA</option>
	<option value="4">TRASLADOS</option>
</select>
<input type="submit" name="">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($op==1)
	{
		$c=$conexion2->query("select documento_producion as doc from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and activo is null group by documento_producion")or die($conexion2->error());
		echo "<table border='1'>";
		echo "<tr>
		<td>FECHA</td>
		<td>BODEGA</td>
		<td>documento</td>
		<td>articulo</td>
		<td>cantidad sys</td>
		<td>cantidad exactus</td>
		<td>tabla</td>
		</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$tabla='N/A';
			$cantidadex=0;
			$doc=$f['doc'];
			$q=$conexion2->query("select codigo,count(codigo) as cantidad from registro where documento_producion='$doc'  group by codigo")or die($conexion2->error());

			$q1=$conexion2->query("select fecha_documento as fecha,bodega_produccion as bodega from registro where documento_producion='$doc' ")or die($conexion1->error());
			$fq1=$q1->FETCH(PDO::FETCH_ASSOC);
			$bodega=$fq1['bodega'];
			$fecha=$fq1['fecha'];


			while($fq=$q->FETCH(PDO::FETCH_ASSOC))
			{
				$art=$fq['codigo'];
				$ce=$conexion1->query("select articulo,count(articulo) as cantidad from consny.linea_doc_inv where documento_inv='$doc' and articulo='$art' group by articulo")or die($conexion1->error());
				$nce=$ce->rowCount();
				if($nce==0)
				{
					
					$ca=$conexion1->query("select sum(convert(int,cantidad)) as cantidad from consny.TRANSACCION_INV where AUDIT_TRANS_INV in(select AUDIT_TRANS_INV from consny.AUDIT_TRANS_INV where aplicacion='$doc') and ARTICULO='$art'")or die($conexion1->error());
					
				$nca=$ca->rowCount();
				if($nca!=0)
				{
					$tabla='TRANS_INV';
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
				$cantidadex=$fca['cantidad'];
				}


				}else
				{
					$tabla='LINEA_DOC ';
					$fce=$ce->FETCH(PDO::FETCH_ASSOC);
					$cantidadex=$fce['cantidad'];
					

				}
				if($cantidadex!=$fq['cantidad'])
				{
					echo "<tr style='background-color:red;'>";
				}else
				{
					echo "<tr>";
				}
				echo "
				<td>$fecha</td>
		<td>$bodega</td>
		<td>$doc</td>
		<td>".$fq['codigo']."</td>
		<td>".$fq['cantidad']."</td>
		<td>$cantidadex</td>
		<td>$tabla</td>
		</tr>";
			}
		}

	}else if($op==2)
	{
		$c=$conexion2->query("select documento_inv_contenedor as doc from registro where tipo='cd' and fecha_documento between '$desde' and '$hasta' group by documento_inv_contenedor")or die($conexion2->error());
		echo "<table border='1'>";
		echo "<tr>
		<td>FECHA</td>
		<td>BODEGA</td>
		<td>documento</td>
		<td>articulo</td>
		<td>cantidad sys</td>
		<td>cantidad exactus</td>
		<td>tabla</td>
		</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$doc=$f['doc'];
			$cantidadex=0;
				$tabla='N/A';
			$q1=$conexion2->query("select fecha_documento as fecha,bodega_produccion as bodega from registro where documento_inv_contenedor='$doc' and tipo='CD' group by fecha_documento,bodega_produccion")or die($conexion2->error());
			$fq1=$q1->FETCH(PDO::FETCH_ASSOC);
			$fecha=$fq1['fecha'];
			$bodega=$fq1['bodega'];

			$q=$conexion2->query("select codigo,count(codigo) as cantidad from registro where documento_inv_contenedor='$doc' and tipo='cd' group by codigo")or die($conexion2->error());

			while($fq=$q->FETCH(PDO::FETCH_ASSOC))
			{
				$art=$fq['codigo'];
				$cantidad=$fq['cantidad'];
				

				$cl=$conexion1->query("select articulo,count(articulo) as cantidad from consny.linea_doc_inv where documento_inv='$doc' and articulo='$art' group by articulo")or die($conexion1->error());
				$ncl=$cl->rowCount();
				if($ncl!=0)
				{
					$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
					$cantidadex=$fcl['cantidad'];
					$tabla='LINEA_DOC <-----';
				}
				$ca=$conexion1->query("declare @audi varchar(20)=(select audit_trans_inv from consny.audit_trans_inv where aplicacion='$doc');
					select articulo,count(articulo) as cantidad from consny.transaccion_inv where audit_trans_inv=@audi and articulo='$art' group by articulo")or die($conexion1->error());
				$nca=$ca->rowCount();
				if($nca!=0)
				{
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$cantidadex=$fca['cantidad'];
					$tabla='TRANS_INV';


				}
				if($cantidad==$cantidadex)
				{
					echo "<tr>";
				}else
				{
					echo "<tr style='background-color:red;'>";
				}
				echo "<tr>
		<td>$fecha</td>
		<td>$bodega</td>
		<td>$doc</td>
		<td>$art</td>
		<td>$cantidad</td>
		<td>$cantidadex</td>
		<td>$tabla</td>
		</tr>";
			}

		}
	}else if($op==3)
	{
		$c=$conexion2->query("select documento_inv from venta where documento_inv!='' group by documento_inv")or die($conexion2->error());
		echo "<table border='1'>";
			echo "<tr>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
				<td>TOTAL</td>
				<td>TOTAL EXACTUS</td>
				<td>TABLA</td>
				<td>DOCUMENTO</td>
			</tr>";
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$tabla='N/A';
			$precio=0;
			$precioex=0;
			$doc=$f['documento_inv'];
			$cv=$conexion2->query("select isnull(articulo,registro.codigo) as articulo,SUM(convert(decimal(10,2),precio)) as precio,bodega_venta as bodega from venta left join registro on venta.registro=registro.id_registro where documento_inv='$doc' group by isnull(articulo,registro.codigo),bodega_venta
")or die($conexion2->error());
			
		while($fcv=$cv->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fcv['articulo'];
			$precio=$fcv['precio'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$articulos=$fca['ARTICULO'];
			$descrdescripcion=$fca['DESCRIPCION'];
			if($art!='' and $precio!='')
			{
				
				$cl=$conexion1->query("select articulo,sum(precio_total_local) as precio from consny.linea_doc_inv where documento_inv='$doc' and articulo='$art' group by articulo")or die($conexion1->error());
				$ncl=$cl->rowCount();
				if($ncl!=0)
				{
					$tabla='LINEA_DOC';
					$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
					$precioex=$fcl['precio'];
				}else
				{
					$ca=$conexion1->query("declare @doc varchar(50)='$doc';
						declare @audi int=(select audit_trans_inv from consny.audit_trans_inv where aplicacion=@doc);
						select articulo,sum(precio_total_local) as precio from consny.transaccion_inv where audit_trans_inv=@audi and articulo='$art' group by articulo")or die($conexion1->error());
					$nca=$ca->rowCount();
					if($nca==0)
					{
						//$tabla='N/A';
					}else
					{
						$tabla='TRANS_INV';
						$fca=$ca->FETCH(PDO::FETCH_ASSOC);
						$precioex=$fca['precio'];
					}
				}
				if($precioex==$precio)
				{
					echo "<tr>";
				}else
				{
					echo "<tr style='background-color: red;'>";
				}
				echo "
				<td>$articulos</td>
				<td>$descrdescripcion</td>
				<td>$precio</td>
				";

				echo "
				<td>$precioex</td>
				<td>$tabla</td>
				<td>$doc</td>
				</tr>";
			}
			

		}


		}
	}else if($op==4)
	{
		$c=$conexion2->query("select documento_inv from traslado where fecha between '$desde' and '$hasta' group by documento_inv")or die($conexion2->error());
		echo "<table border='1'>";
		echo "<tr>
		<td>DOCUMENTO</td>
		<td>ARTICULO</td>
		<td>CANTIDAD</td>		
		<td>CANTIDAD EXACTUS</td>
		<td>TABLA</td>
		</tr>";

		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$tabla="N/A";
			$cantidadex=0;
			$cantidad=0;

			$cambio=0;
			$doc=$f['documento_inv'];
			$q=$conexion2->query("select registro.codigo,count(registro.codigo) as cantidad from registro inner join traslado on registro.id_registro=traslado.registro where traslado.documento_inv='$doc' group by registro.codigo")or die($conexion2->error());
			while($fq=$q->FETCH(PDO::FETCH_ASSOC))
			{
				
				$art=$fq['codigo'];
				$cantidad=$fq['cantidad'];

				$ce=$conexion1->query("select articulo,convert(int,sum(cantidad)) as cantidad from consny.linea_doc_inv where documento_inv='$doc' and articulo='$art' group by articulo")or die($conexion1->error());
				$nce=$ce->rowCount();
				if($nce!=0)
				{
					$fce=$ce->FETCH(PDO::FETCH_ASSOC);
						$cantidadex=$fce['cantidad'];
					$tabla="LINEA_DOC $nce";
				}else
				{
					$cau=$conexion1->query("select SUM(convert(int,CANTIDAD)) as cantidad from consny.TRANSACCION_INV where AUDIT_TRANS_INV in(select AUDIT_TRANS_INV from consny.AUDIT_TRANS_INV where aplicacion='$doc') and ARTICULO='$art' and NATURALEZA='E'
")or die($conexion1->error());
					$num=$cau->rowCount();
					if($num!=0)
					{
						$fcau=$cau->FETCH(PDO::FETCH_ASSOC);
					$cantidadex=$fcau['cantidad'];
					$tabla='TRANS_INV';
					}
					
				}
				
				if($cantidad==$cantidadex)
				{
					echo "<tr>";
				}else
				{
					echo "<tr style='background-color: red;'>";
				}
				
				echo "
				<td>$doc</td>
				<td>$art</td>
				<td>$cantidad</td>
				<td>$cantidadex</td>
				<td>$tabla</td>";
			}
		}
	}
}
?>
</body>
</html>