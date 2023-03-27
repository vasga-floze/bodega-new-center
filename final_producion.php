<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	$(document).ready(function(){
		$("#img").hide();
		$("#caja").hide();

		window.print();
		$("#img").show();
		$("#caja").show();

	})
	function imprimire()
	{
		$("#img").hide();
		$("#caja").hide();

		window.print();
		$("#img").show();
		$("#caja").show();


	}
</script>
<?php
echo "<div id='caja'>";
include("conexion.php");
echo "</div>";

$fe=$_SESSION['fecha'];
   
      
     
 ?>


<?php
$barra=$_SESSION['cbarra'];

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
$_SESSION['fecha']="";
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
<img src="imprimir.png" id="img" width="5%" height="5%" style="float: right; margin-right: 0.5%;" onclick="imprimire()">
<table border="1" class="tabla" cellpadding="5" style="border-color: black;">
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
				echo "<u style='font-size:180%;'>$obs</u><h1 style='font-size:400%; margin-top:10%; margin-bottom:25%; margin-top:-0.5%;'>$cod</h1>";
				echo "<center><table border='1' style='margin-top:-25%; width:50%; border-collapse:collapse;'>
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
				
				</table></center>";
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
<table border="1" class="tabla" cellpadding="5" style="border-color: black; margin-top: 3.5%;">
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
				echo "<u style='font-size:180%;'>$obs</u><h1 style='font-size:400%; margin-top:10%; margin-bottom:25%; margin-top:-0.5%;'>$cod</h1>";
				echo "<center><table border='1' style='margin-top:-25%; width:50%; border-collapse:collapse;'>
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
				
				</table></center>";
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
	/*$(document).ready(function(){
if($("#error").val()==1)
{
	window.close();
}else
{
	window.print();
	window.close();
}
	});*/
	
</script>