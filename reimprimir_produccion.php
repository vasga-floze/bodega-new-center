<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<?php
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
$barra=$_GET['b'];

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
$ubic=$fq['ubicacion'];
if($cod=="")
{
	echo "<script>alert('NO SE HA AREGADO CODIGO DE PRODUCCION')</script>";
	echo "<script>window.close();</script>";
}else
{
	$_SESSION['cbarra']="";
$_SESSION['session']="";
}
	

//sacar articulo
$k=$conexion1->query("select DESCRIPCION FROM consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error);
$fk=$k->FETCH(PDO::FETCH_ASSOC);
$nom=$fk['DESCRIPCION'];

if($barra=="")
{
	echo "<script>location.replace('ingreso.php')</script>";

}



?>
<table border="1" class="tabla" cellpadding="5" style="border-color: black; width: 100%; height: 40%;">
	<tr>
	<td width="10%;"><h2><?php echo $barra; ?></h2></td>
	<td width="80%" style="text-align: right;"><h3><?php echo "$fecha <br> $nom <br> Ubicación: $ubic"; ?></h3></td>
	<tr>
		<td>
			<?php
				echo "<img src='barcode/barcode.php?text=$valor\n&size=80&codetype=Code39&print=true'/>";
			?>
		</td>
		<td style="text-align: center;">
			<?php
				echo "<u style='font-size:80%;'>$obs</u><h1 style='font-size:400%; margin-top:10%; margin-bottom:25%; margin-top:-0.5%;'>$cod</h1>";
				/*echo "<center><table border='1' style='margin-top:-25%; width:45%; border-collapse:collapse; margin-right:0.5%;'>
				<tr>
				<td>ARTICULO</td><td>CANTIDAD</td>
				<td>ARTICULO</td><td>CANTIDAD</td>
				<td>ARTICULO</td><td>CANTIDAD</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				
				</table></center>";*/
			?>
		</td>

	</tr>
	<tr>
		<td style="text-align: center;" colspan="2" cellpadding='35'>
			<?php
				echo "<h3>$nom</h3>";
			?>
		</td>
	</tr>

	<tr>
	
</table>
<table border="1" class="tabla" cellpadding="5" style="border-color: black; margin-top: 3.5%; width: 100%; height: 40%;">
	<tr>
	<td width="10%;"><h2><?php echo $barra; ?></h2></td>
	<td width="80%" style="text-align: right;"><h3><?php echo "$fecha <br> $nom <br> Ubicación: $ubic"; ?></h3></td>
	<tr>
		<td>
			<?php
				echo "<img src='barcode/barcode.php?text=$valor&size=80&codetype=Code39&print=true'/>";
			?>
		</td>
		<td style="text-align: center;">
			<?php
				echo "<u style='font-size:80%;'>$obs</u><h1 style='font-size:400%; margin-top:10%; margin-bottom:25%; margin-top:-0.5%;'>$cod</h1>";
				/*echo "<center><table border='1' style='margin-top:-25%; width:45%; border-collapse:collapse; margin-right:0.5%;'>
				<tr>
				<td>ARTICULO</td><td>CANTIDAD</td>
				<td>ARTICULO</td><td>CANTIDAD</td>
				<td>ARTICULO</td><td>CANTIDAD</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td></tr>
				
				</table></center>";*/
			?>
		</td>

	</tr>
	<tr>
		<td style="text-align: center;" colspan="2" cellpadding='35'>
			<?php
				echo "<h3>$nom</h3>";
			?>
		</td>
	</tr>

	<tr>
	
</table>

<input type="hidden" name="error" id="error" value='<?php echo "$error;"?>'>

<script>
	$(document).ready(function(){
if($("#error").val()==1)
{
	window.close();
}else
{
	window.print();
	window.close();
}
	});
	
</script>