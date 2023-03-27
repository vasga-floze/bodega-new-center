<?php
error_reporting(0);
extract($_REQUEST);
include("conexion.php");
if($consulta!='')
{
	$c=$conexion1->query("select * from CONSNY.ARTICULO where ARTICULO='$consulta' or descripcion like (SELECT '%'+REPLACE('$consulta',' ','%')+'%')")or die($conexion1->error());
}else
{
	$c=$conexion1->query("select * from consny.ARTICULO")or die($conexion1->error());
}


$salida ='';

$salida.= "<table border='1' style='border-collapse:collapse; width:98%;' cellpadding='10'>";
$salida.="<tr>
			<td>nombre</td>
			<td>telefono</td>
			</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$nom=$f['ARTICULO'];
		$tel=$f['DESCRIPCION'];
		$salida.="<tr>
			<td>$nom</td>
			<td>$tel</td>
			</tr>";

	}
	$salida.="</table>";

	echo $salida;

?>