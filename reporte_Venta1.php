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
if($_SESSION['usuario']=='staana3')
{

}else
{
	echo "<script>alert('POR EL MOMENTO NO DISPONIBLE')</script>";
}

?>
<form method="POST">
Desde: <input type="date" name="desde" class="text" style="width: 30%;" required>

Hasta: <input type="date" name="hasta" class="text" style="width: 30%;" required>

<input type="submit" name="btn" value="GENERAR" class="btnfinal" style="padding: 0.5%; background-color:skyblue; color: black; border-color: black;">
	
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select sessiones,usuario from venta where fecha between '$desde' and '$hasta' group by sessiones,usuario")or die($conexion2->error());

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUNA VENTA</h3>";
	}else
	{
		echo "<table border='1'>";
			echo "<TR>
					<td>ARTICULO</td>
					<td>DESCRIPCION</td>
					<td>CANTIDAD</td>
					<td>TOTAL</td>
					<td>DOCUMENTO</td>
					<td>CLIENTE</td></tr>";
					$totalf=0;
					$n=1;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$n++;
			$sessiones=$f['sessiones'];
			$usuario=$f['usuario'];


			$q=$conexion2->query("select venta.cliente,venta.documento_inv,convert(decimal(10,2),isnull(venta.precio,0)) * isnull(venta.cantidad,1) as total,isnull(articulo,registro.codigo) as articulo from venta left join registro on venta.registro=registro.id_registro  where fecha between'$desde' and '$hasta' order by venta.documento_inv
")or die($conexion2->error());

			$nq=$q->rowCount();
			if($nq==0)
			{

				$q=$conexion2->query("select articulo,sum(convert(int,cantidad))as cantidad,sum(convert(int,cantidad)* convert(decimal,precio))as total,documento_inv,cliente from venta where sessiones='$sessiones' and usuario='$usuario' and articulo is not null group by articulo,documento_inv,cliente")or die($conexion2->error());
			
			}
			
			
			$k=0;
			while($fq=$q->FETCH(PDO::FETCH_ASSOC))
			{
				$k++;
				$art=$fq['articulo'];
				$ca=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
				$fca=$ca->FETCH(PDO::FETCH_ASSOC);
				echo "<TR>
					<td>".$fca['ARTICULO']."</td>
					<td>".$fca['DESCRIPCION']."</td>
					<td>".$fq['cantidad']."</td>
					<td>".$fq['total']."</td>
					<td>".$fq['documento_inv']."</td>
					<td>".$fq['cliente']."</td>

					</tr>";

					$totalf=$totalf+$fq['total'];
			}

		}
		echo "<tr>
			<td colspan='3'>TOTAL</td>
			<td>$totalf</td>
			<td colspan='2'></td>
			</tr>";
	}
}
?>
</body>
</html>