<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<?php
error_reporting(0);
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
  


$fe=$_SESSION['fecha'];
   
       
     
 ?>


<?php
$barra="ZP180901319";
$_SESSION['cbarra']="";
$_SESSION['session']="";
$_SESSION['fecha']="";
$valor=$barra;	

$q=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error);
$nq=$q->rowCount();
if($nq==0)
{
	echo "<script>widow.close();</script>";
}
$fq=$q->FETCH(PDO::FETCH_ASSOC);
$cod=$fq['codigo'];
$fecha=$fq['fecha_documento'];
$obs=$fq['observacion'];

//sacar articulo
$k=$conexion1->query("select DESCRIPCION FROM consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error);
$fk=$k->FETCH(PDO::FETCH_ASSOC);
$nom=$fk['DESCRIPCION'];

if($barra=="")
{
	echo "<script>location.replace('ingreso.php')</script>";

}



?>
<table border="1" class="tabla" cellpadding="10" style="border-color: black;">
	<tr>
	<td width="10%;"><h2><?php echo $barra; ?></h2></td>
	<td width="80%" style="text-align: right;"><h2><?php echo $fecha; ?></h2></td>
	<tr>
		<td>
			<?php
				echo "<img src='barcode/barcode.php?text=$valor\n&size=80&codetype=Code39&print=true'/>";
			?>
		</td>
		<td style="text-align: center;">
			<?php
				echo "<h1 style='font-size:500%; margin-top:12%; margin-bottom:25%;'>$cod</h1><u>$obs</u>";
			?>
		</td>

	</tr>
	<tr>
		<td style="text-align: center;" colspan="2" cellpadding='40'>
			<?php
				echo "<h1>$nom</h1>";
			?>
		</td>
	</tr>

	<tr>
	
</table>
<table border="1" class="tabla" cellpadding="10" style="border-color: black; margin-top: 5%;">
	<tr>
	<td width="10%;"><h2><?php echo $barra; ?></h2></td>
	<td width="80%" style="text-align: right;"><h2><?php echo $fecha; ?></h2></td>
	<tr>
		<td>
			<?php
				echo "<img src='barcode/barcode.php?text=$valor&size=80&codetype=Code39&print=true'/>";
			?>
		</td>
		<td style="text-align: center;">
			<?php
				echo "<h1 style='font-size:500%; margin-top:12%; margin-bottom:25%;'>$cod</h1><u>$obs</u>";
			?>
		</td>

	</tr>
	<tr>
		<td style="text-align: center;" colspan="2" cellpadding='40'>
			<?php
				echo "<h1>$nom</h1>";
			?>
		</td>
	</tr>

	<tr>
	
</table>



<script>
	window.print();
	window.close();
</script>