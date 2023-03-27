<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function imprimir()
		{
			$("#conte").hide();
			$("#img").hide();
			window.print();
			$("#conte").show();
			$("#img").show();
		}
	</script>
</head>
<body>
	<img src="imprimir.png" style="float: right; margin-right: 0.5%; position: fixed; margin-left: 50%; cursor: pointer;" width="5%" height="5%" onclick="imprimir()" id="img">
<?php
echo "<div id='conte'>";
include("conexion.php");
echo "</div>";
$doc=$_GET['doc'];
$usuario=strtoupper($_GET['u']);
$c1=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$usuario'")or die($conexion2->error());
$numero=$c1->FETCHALL();
$numero=count($numero);
$mitad=$numero/2;
$mitad=ceil($mitad);
$c=$conexion2->query("select top $mitad registro.barra,concat(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION) as articulo,traslado.origen,traslado.destino,traslado.fecha,
CONCAT(EXIMP600.consny.BODEGA.BODEGA,': ',EXIMP600.consny.BODEGA.NOMBRE) as bodega,traslado.paquete,traslado.usuario,traslado.documento_inv	  from traslado 
inner join registro on traslado.registro=registro.id_registro inner join EXIMP600.consny.ARTICULO on
traslado.articulo=EXIMP600.consny.ARTICULO.ARTICULO inner join EXIMP600.consny.BODEGA on traslado.destino=
EXIMP600.consny.BODEGA.BODEGA where
traslado.sessiones='$doc' and traslado.usuario='$usuario'")or die($conexion2->error());
$c1=$conexion2->query("select * from traslado where sessiones='$doc' and usuario='$usuario'")or die($conexion2->error());
$n=$c1->FETCHALL();
$n=count($n);
$k=0;
$k1=0;
$num=0;
echo "<table border='1' style='border-collapse:collapse; width:47%; float:left; margin-left:0.5%; margin-top:1%;'>";
		echo "<tr>
		<td width='90%'>ARTICULO</td>
		<td width='10%'>BARRA</td>
		</tr>";
		$k=0;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	if($k==0)
	{
		echo "<div style='background-color: white; float:left; width:30%; height:auto; margin-bottom:1%;'>
		<img src='logo.png' width='30%' height='95%'><br>
		PAQUETE: ".$f['paquete']."<br>
		ORIGEN: ".$f['origen']."<br>
		DESTINO: ".$f['bodega']."
		</div>";

		echo "<div style='background-color: white; float:right; width:30%; height:auto; margin-bottom:15%;'>
		DOCUMENTO: ".$f['documento_inv']."<br>
		CANTIDAD FARDOS: $numero<br>
		FECHA: ".$f['fecha']."
		</div>";
	}
		
		$k++;
		echo "<tr>
		<td>".$f['articulo']."</td>
		<td>".$f['barra']."</td>
		</tr>";
}
echo "</table>";




$c=$conexion2->query("select top $mitad registro.barra,concat(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION) as articulo,traslado.origen,traslado.destino,traslado.fecha,
CONCAT(EXIMP600.consny.BODEGA.BODEGA,': ',EXIMP600.consny.BODEGA.NOMBRE) as bodega,traslado.paquete,traslado.usuario,traslado.documento_inv	  from traslado 
inner join registro on traslado.registro=registro.id_registro inner join EXIMP600.consny.ARTICULO on
traslado.articulo=EXIMP600.consny.ARTICULO.ARTICULO inner join EXIMP600.consny.BODEGA on traslado.destino=
EXIMP600.consny.BODEGA.BODEGA where
traslado.sessiones='$doc' and traslado.usuario='$usuario' order by traslado.id desc")or die($conexion2->error());

$k=0;
$k1=0;
$num=0;
echo "<table border='1' style='border-collapse:collapse; width:47%; float:right; margin-right:0.5%; margin-top:-4%;'>";
		echo "<tr>
		<td>ARTICULO </td>
		<td>BARRA</td>
		</tr>";
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
		
		$k++;
		echo "<tr>
		<td style='text-align:150%;'>".$f['articulo']."</td>
		<td>".$f['barra']."</td>
		</tr>";
}
echo "</table>";

?>
</body>
</html>