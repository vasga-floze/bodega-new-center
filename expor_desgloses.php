<meta charset="utf-8">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

<?php
//error_reporting(0);
session_start();
ini_set('max_execution_time', 9000);

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
        die("ERROR SE PERDIO CONEXION CON EL SERVIDOR: " . $e->getMessage());
    }


   
   if($_SESSION['paquete']=="" or $_SESSION['usuario']=="")
   {
    echo "<script>alert('INICIE SESION')</script>";
    echo "<script>location.replace('conexiones.php')</script>";
   }
   if($_GET['d']!='' and $_GET['h']!='')
   {
   	$d=$_GET['d']; $h=$_GET['h'];
   	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=reporte-desglose.xls');

	$c=$conexion2->query("select registro.id_registro,desglose.usuario,registro.fecha_desglose from registro inner join desglose on registro.id_registro=desglose.registro where registro.fecha_desglose between '$d' and '$h'group by registro.id_registro,desglose.usuario,registro.fecha_desglose order by registro.fecha_desglose,desglose.usuario
")or die($conexion2->error());
	echo "<hr>";
	echo "<table border='1' class='tabla' cellpadding='10'>";
	
	echo "<tr>
	<td>USUARIO</td>
	<td>FECHA DESGLOSE</td>
	<td>CODIGO</td>
	<td>SUBCATEGORIA</td>
	<td>CANTIDAD</td>
	<td>TOTAL</td>
	<td>DOCUMENTO CONSUMO</td>
	<td>DOCUMENTO ING</td>
	</tr>";
	$n=$c->rowCount();
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$idr=$f['id_registro'];
		$cr=$conexion2->query("select * from registro where id_registro='$idr'")or die($conexion2->error());
		$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
		$fecha=$fcr['fecha_desglose'];
	$doc_consumo=$fcr['documento_inv_consumo'];
		$doc_ing=$fcr['documento_inv_ing'];
		$cod=$fcr['codigo'];
		$cd=$conexion2->query("select * from desglose where registro='$idr'") or die($conexion2->error());
	$cantidad=0;
	$total=0;
	$subtotal=0;
	while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
	{
		$dart=$fcd['articulo'];
		$cantidad=$cantidad + $fcd['cantidad'];
		$subtotal=$fcd['cantidad'] * $fcd['precio'];
		$total=$total+$subtotal;
		$usuario=$fcd['usuario'];
	}
	



		$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$arti=$fca['ARTICULO'];
		$des=$fca['DESCRIPCION'];
		echo "<tr>
	<td>$usuario</td>
	<td>$fecha</td>
	<td>$arti</td>
	<td>$des</td>
	<td>$cantidad</td>
	<td>$total</td>
	<td>$doc_consumo</td>
	<td>$doc_ing</td>
	</tr>";
	}
	echo "</table>";
}
?>