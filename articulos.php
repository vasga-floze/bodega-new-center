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
<input type="text" name="b">
<input type="submit" name="" value="BUSCAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c1=$conexion2->query("select tipo from registro where codigo='$b' group by tipo")or die($conexion2->error());
	echo "<table class='tabla' border='1'>
	<tr>
	<td>#</td><td>articulo</td><td>DOCUMENTO</td><td>SYSTEMA</td><td>EXACTUS</td><td>ELIMINADO</td>
	</tr>";
	$k=1;
	$totalr=0; $totale=0;
	while($f=$c1->FETCH(PDO::FETCH_ASSOC))
	{
		$tipo=$f['tipo'];
		echo "$tipo";
		if($tipo=='P')
		{
			$c=$conexion2->query("select documento_producion from registro where codigo='$b' and tipo='P' group by documento_producion")or die($conexion2->error());
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$docu=$f['documento_producion'];
				$cr=$conexion2->query("select count(codigo) as cantidadr from registro where documento_producion='$docu' group by codigo")or die($conexion2->error());
				$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
			$ce=$conexion1->query("select audit_trans_inv from consny.audit_trans_inv where aplicacion='$docu'")or die($conexion1->error());
			$fce=$ce->FETCH(PDO::FETCH_ASSOC);
			$audi=$fce['audit_trans_inv'];

			$ce=$conexion1->query("select count(articulo) as cantidade from consny.transaccion_inv where audit_trans_inv='$audi' group by articulo")or die($conexion1->error());

			$fce=$ce->FETCH(PDO::FETCH_ASSOC);
			$cantidadr=$fcr['cantidadr'];
	$cantidade=$fce['cantidade'];
	$totalr=$totalr +$cantidadr;
	$totale=$totale + $cantidade;
			echo "<tr>
	<td>$k</td><td>$b</td><td>$docu</td><td>".$fcr['cantidadr']."</td><td>".$fce['cantidade']."</td><td>--</td>
	</tr>";
				

			}

	$k++;
		}else
		{
			$c=$conexion2->query("select contenedor from registro where codigo='$b' and tipo='CD' group by contenedor")or die($conexion2->error());
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$conte=$f['contenedor'];
				$cr=$conexion2->query("select count(codigo) as cantidadr from registro where contenedor='$conte' and codigo='$b' group by codigo")or die($conexion2->error());
				$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
				$cantidadr=$fcr['cantidadr'];

				$ce=$conexion1->query("select audit_trans_inv,aplicacion from consny.audit_trans_inv where referencia like '%contenedor $conte%'")or die($conexion1->error());
				$fce=$ce->FETCH(PDO::FETCH_ASSOC);
				$audi=$fce['audit_trans_inv'];
				$docu=$fce['aplicacion'];
				$ce=$conexion1->query("select count(articulo) as cantidade from consny.transaccion_inv where articulo='$b' and audit_trans_inv='$audi' group by articulo")or die($conexion1->error());
				$fce=$ce->FETCH(PDO::FETCH_ASSOC);
				$cantidade=$fce['cantidade'];
				$totalr=$totalr +$cantidadr;
				$totale=$totale + $cantidade;

				echo "<tr>
	<td>$k</td><td>$b</td><td>$docu</td><td>".$fcr['cantidadr']."</td><td>".$fce['cantidade']."</td><td>-- ($conte)</td>
	</tr>";
			$k++;
			}
		}

	
}
echo "<tr>
	<td colspan='3'>Total</td>
	<td>$totalr</td>
	<td>$totale</td>
	<td></td>
	</tr>";
}
?>
</body>
</html>