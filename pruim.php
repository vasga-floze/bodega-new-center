<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#me").hide();
			window.print();
			$("#me").show(300);
		});
		function imprima()
		{
			$("#me").hide();
			window.print();
			$("#me").show(300);
		}
	</script>
</head>
<body>
<?php
$fecha=date("Y-m-d");
$e=substr($fecha,0);
	$a="$e[2]$e[3]";
	$m="$e[5]$e[6]";
	$d="$e[8]$e[9]";

	ECHO "$a";
session_start();

    try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }

    try {
        $conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }


   
   if($_SESSION['paquete']=="" or $_SESSION['usuario']=="")
   {
    echo "<script>alert('INICIE SESION')</script>";
    echo "<script>location.replace('conexiones.php')</script>";
   }
       
     
 ?>
<?php
	$doc=4;
	//$_SESSION['doc']='';
	if($doc=="")
	{
		echo "<script>alert('ERROR AL IMPRIMIR')</script>";
		echo "<script>location.replace('traslados.php')</script>";
	}
	$k=$conexion2->query("select * from traslado where sessiones='$doc'")or die($conexion2->error);
	$nk=$k->rowCount();

	$knum=$conexion2->query("select count(*) as total from traslado where sessiones='$doc' ")or die($conexion2->error);
	$fknum=$knum->FETCH(PDO::FETCH_ASSOC);
	$tf=$fknum['total'];
	if($nk==0)
	{
		echo "<script>alert('ERROR:NO SE ENCONTRO REGISTRO')</script>";
		echo "<script>loction.replace('traslados.php')</script>";
	}
	$fk=$k->FETCH(PDO::FETCH_ASSOC);
	$documento=$fk['documento_inv'];
	$paquete=$fk['paquete'];
	$fecha=$fk['fecha'];
	$origen=$fk['origen'];
	$destino=$fk['destino'];
?>
<img src="logo.png" width="10%" height="10%" style="margin-left: 1.5%; "><br>
<div id="me">
<?php

include("menu.php"); 
?>
<img src="imprimir.png" style="float: right; margin-right: 0.5%; cursor: pointer; margin-top: -5%;" width="5%" height="5%" onclick="imprima()">
	
</div>

<?php

$cdes=$conexion1->query("select * from consny.BODEGA where BODEGA='$destino'")or die($conexion1->error);
$fcdes=$cdes->FETCH(PDO::FETCH_ASSOC);
$destino=$fcdes['NOMBRE'];
 
	echo "<p style='float:left; margin-left:3%;'>PAQUETE: $paquete<br>
	ORIGEN: $origen<BR>
	DESTINO:$destino
	</p>
	<p style='float:right;'>DOCUMENTO: $documento<br>
	FECHA: $fecha</p><br><br><br><br><br>";

	
	$c=$conexion2->query("select count(*) as total from traslado where sessiones='$doc' and estado='1'")or die($conexion2->error);
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$n=$f['total'];
	if($n<=45 and $n>0)//cambiar el 10  a 20
	{
		echo "<table border='1' class='tabla' style='border-color:black; width:98%; margin-left:3%; float:left; font-size:12px;' cellpadding='1'>
		<tr style='text-align:center;'>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>CODIGO</td>
		</tr>";
		$q=$conexion2->query("select * from traslado where  sessiones='$doc' and estado='1'")or die($conexion2->error);
		while($fq=$q->FETCH(PDO::FETCH_ASSOC))
		{
			$art=$fq['articulo'];
			$idr=$fq['registro'];
			$qart=$conexion1->query("select * from consny.ARTICULO where articulo='$art'")or die($conexion1->error);
			$qreg=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error);
			$fqart=$qart->FETCH(PDO::FETCH_ASSOC);
			//INFO ARTICULO
			$cod=$fqart['ARTICULO'];
			$des=$fqart['DESCRIPCION'];
			$des=str_replace("+"," ", $des);
			//INFO REGISTRO DE BARRA

			$fqreg=$qreg->FETCH(PDO::FETCH_ASSOC);
			$barra=$fqreg['barra'];
			echo "<tr>
				<td>$cod</td>
				<td>$des</td>
				<td>$barra</td>
			</tr>";
			
			
		}
		echo "<tr>
			<td colspan='3'>TOTAL FARDOS: <u>$tf</u></td>
		</tr>";
		echo "</table>";


	}else
	{
	$kf=$conexion2->query("select * from traslado where sessiones='$doc' and estado='1'")or die($conexion2->error);
echo "<p class='pa'>ARTICULO</p><p class='pa'>DESCRIPCION</p><p class='pa'>CODIGO</p>";
	while($fkf=$kf->FETCH(PDO::FETCH_ASSOC))
	{
		$registro=$fkf['registro'];
		$cr=$conexion2->query("select * from registro where id_registro='$registro'")or die($conexion2->error);
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$art=$fcr['codigo'];
		$barra=$fcr['barra'];
		$nom=$fcr['subcategoria'];


	}

	}


$_SESSION['doc']="";
?>
<div style="width: 100%; margin-top: 25%; background-color: red;">

<table border="1" width="40%" style=" border-style: hidden; margin-left: 3%; font-size: 12px; float: left;">
	<tr>
		<td width="70%">HORA SALIDA BODEGA:</td>
		<td width="30%"></td>
	</tr>
	<tr>
		<td width="70%">HORA LLEGADA TIENDA:</td>
		<td width="30%"></td>
	</tr>
	<tr>
		<td width="70%">HORA SALIDA TIENDA:</td>
		<td width="30%"></td>
	</tr>
	<tr>
		<td width="70%">HORA LLEGADA BODEGA:</td>
		<td width="30%"></td>
	</tr>
	
</table>
<p style="float: right; margin-right: 3%; text-align: center;">____________<br>RECIBE</p>
<p style="float: right; margin-right: 3%; text-align: center;">____________<br>DESPACHO</p>
<p style="float: right; margin-right: 3%; text-align: center;">____________<br>ENTREGA</p>
</div>
</body>
</html>
