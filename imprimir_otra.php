<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//alert('prueba');
		$("#me").hide();
		//window.print();
		$("#me").show();
	});
	function imprimir()
	{
		$("#me").hide();
		window.print();
		$("#me").show();
	}
</script>
<?php
echo "<div id='me' style='display:none;'>";
include("conexion.php");
?>
<img src="imprimir.png" style="float: right; margin-right: 0.5%; width: 5%; height: 9%; cursor: pointer;" title="IMPRIMIR" onclick="imprimir()">
</div>


<?php

error_reporting(0);
   
   if($_SESSION['paquete']=="" or $_SESSION['usuario']=="")
   {
    echo "<script>alert('INICIE SESION')</script>";
    echo "<script>location.replace('conexiones.php')</script>";
   }
  


   
       
     
 ?>


<?php
$barra=$_GET['b'];
$valor=$barra;	

$q=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error);
$nq=$q->rowCount();
if($nq==0)
{
	echo "<script>alert('SE PRODUJO UN ERROR INTENTELO NUEVAMENTE')</script>";
	echo "<script>window.close()</script>";
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
	echo "<script>window.close()</script>";

}



?>
<table border="1" class="tabla" cellpadding="10" style="border-color: black; width: 90%;">
	<tr>
	<td width="10%;"><h2><?php echo $barra; ?></h2></td>
	<td width="80%" style="text-align: right;"><h3><?php echo "$fecha<br>$nom"; ?></h3></td>
	<tr>
		<td>
			<?php
				echo "<img src='barcode/barcode.php?text=$valor\n&size=80&codetype=Code39&print=true'/>";
			?>
		</td>
		<td style="text-align: center;">
			<?php
				echo "<u style='font-size:60%;'>$obs</u><h1 style='font-size:400%;  margin-bottom:10%;'>$cod</h1>";
			?>
		</td>

	</tr>
	<tr>
		<td style="text-align: center;" colspan="2" cellpadding='40'>
			<?php
			$ahora=date("Y-m-d h:i:s A");
				echo "<h1 style='font-size:150%;'>REIMPRESION: $ahora</h1>";
			?>
		</td>
	</tr>

	<tr>
	
</table>
<table border="1" class="tabla" cellpadding="10" style="border-color: black; margin-top: 5%; width: 90%;">
	<tr>
	<td width="10%;"><h2><?php echo $barra; ?></h2></td>
	<td width="80%" style="text-align: right;"><h3><?php echo "$fecha<br>$nom"; ?></h3></td>
	<tr>
		<td>
			<?php
				echo "<img src='barcode/barcode.php?text=$valor&size=80&codetype=Code39&print=true'/>";
			?>
		</td>
		<td style="text-align: center;">
			<?php
				echo "<u style='font-size:60%;'>$obs</u><h1 style='font-size:400%;  margin-bottom:10%;'>$cod</h1>";
			?>
		</td>

	</tr>
	<tr>
		<td style="text-align: center;" colspan="2" cellpadding='40'>
			<?php

				echo "<h1 style='font-size:150%;'> REIMPRESION: $ahora</h1>";
			?>
		</td>
	</tr>

	<tr>
	
</table>



