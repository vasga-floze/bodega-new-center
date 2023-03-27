<?php
if($_POST)
{
	error_reporting(0);
	include("conexion.php");
	extract($_REQUEST);
	$c=0;
	$totalp=0; $totala=0;
	echo "<a href='peso_traslado.php?d=$desde&&h=$hasta'><img src='atras.png' width='2%' height='4%' title='VOLVER'></a>";
	echo "<table border='1' class='tabla' cellpadding='10'>";
		echo "<tr>
				<td>DOCUMENTO</td>
				<td>FECHA</td>
				<td>ORIGEN</td>
				<td>DESTINO</td>
				<td>TOTAL ARTICULOS</td>
				<td>TOTAL PESO</td>
		</tr>";
		$k--;
	while($c<=$k)
	{
		if($op[$c]!='')
		{
			$doc=$op[$c];
			$q=$conexion2->query("select * from traslado where documento_inv='$doc'")or die($conexion2->error());
			$ta=0;
			$tp=0;
		
		while($fq=$q->FETCH(PDO::FETCH_ASSOC))
		{
			$origen=$fq['origen'];
			$destino=$fq['destino'];
			$idr=$fq['registro'];
			$fecha=$fq['fecha'];

			$cr=$conexion2->query("select lbs,peso from registro where id_registro='$idr'")or die($conexion2->error());
			$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
			$lbs=$fcr['lbs'];
			$peso=$fcr['peso'];
			$ta++;
			$tp=$tp + $lbs + $peso;
			
		}
		echo "<tr>
				<td>$doc</td>
				<td>$fecha</td>
				<td>$origen</td>
				<td>$destino</td>
				<td>$ta</td>
				<td>$tp</td>
		</tr>";
		$totala=$totala+ $ta;
		$totalp=$totalp+$tp;
		}
		
		$c++;
	}
	echo "<tr>
		<td colspan='4'>TOTAL</td>
		<td>$totala</td>
		<td>$totalp</td>
	</tr>";
	
	echo "</table>";
}else
{
	echo "<script>location.replace('peso_traslado.php')</script>";
}
?>