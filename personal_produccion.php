<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
   <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
   <script>
   	function  dilete (e)
   	{
   		if(confirm('SEGURO DESEA ELIMINAR DEL PERSONAL'))
   		{
   			location.replace('desactiva_personal.php?cod='+e);
   		}else 
   		{

   		}

   		
   	}
   </script>
</head>
<body>
<?php
include("conexion.php");
echo "<h3 style='text-align:center; text-decoration:underline;'>PERSONAL DE PRODUCCION DISPONIBLE</h3>";
$c=$conexion1->query("select * from produccion_accpersonal where activo='1'")or die($conexion1->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<H3>NO SE ENCONTRO PERSONAL DISPONIBLE</H3>";
}else
{
	echo "<table border='1' cellpadding='7' style='border-collapse:collapse; width:90%;'>";
	echo "<tr>
		<td>NOMBRE</td>
		<td>DIGITA</td>
		<td>EMPACA</td>
		<td>PRODUCE</td>
		<td width='7%'>OPCION</td>
	</tr>";
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$nom=$f['NOMBRE'];
		$dig=$f['DIGITA'];
		$emp=$f['EMPACA'];
		$pro=$f['PRODUCE'];
		$cod=$f['CODIGO'];
		if($dig==1)
		{
			$dig="SI";
		}else
		{
			$dig="NO";
		}
		if($emp==1)
		{
			$emp="SI";
		}else
		{
			$emp="NO";
		}
		if($pro==1)
		{
			$pro="SI";
		}else
		{
			$pro="NO";
		}
		$text="sdfsfsdf";
		echo "<tr>
		<td>$nom</td>
		<td>$dig</td>
		<td>$emp</td>
		<td>$pro</td>
		<td><img src='eliminar.png' width='30%' height='10%' style='cursor:pointer;' title='ELIMINAR A: $nom' onclick='dilete($cod)'></td>
	</tr>";
	}
}
?>
</body>
</html>