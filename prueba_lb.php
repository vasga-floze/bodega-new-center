<?php
include("conexion.php");

$c=$conexion2->query("select top 10 eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,(isnull(pruebabd.dbo.registro.lbs,0)+isnull(pruebabd.dbo.registro.peso,0)) as peso, pruebabd.dbo.registro.barra from eximp600.consny.articulo inner join pruebabd.dbo.registro on eximp600.consny.articulo.articulo=pruebabd.dbo.registro.codigo where pruebabd.dbo.registro.tipo!='C1'")or die($conexion2->error());

echo "<table border='1'>";
echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>lb articulo</td>
		<td>PESO</td>
</tr>";

while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	//$f['descripcion']="ZAPATO PREMIUM MIX #1  100LB";
	$desc=explode('#',$f['descripcion']);

	$text=explode("lb", $desc[1]);
	$text1=explode(' ', $text[0]);
	$desc=preg_replace("/[^0-9]/", "", $f['descripcion']);
	$lbs=str_replace($text1[1], '', $desc);
	echo "<tr>
		<td>".$f['articulo']."</td>
		<td>".$f['descripcion']."</td>
		<td>$lbs</td>
		<td>".$f['peso']."</td>
</tr>";

}

?>