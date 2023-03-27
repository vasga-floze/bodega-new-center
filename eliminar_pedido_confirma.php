<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<?php
include("conexion.php");
$fecha=$_GET['fecha'];
$session=$_GET['session'];
$bodega=$_GET['bodega'];
if($fecha=='' or $session=='' or $bodega=='')
{
	echo "<script>alert('NO SE PUDO ELIMINAR')</script>";
	echo "<script>location.replace('bodegas_pedidos_confirmar.php')</script>";
}else
{
?>
<div class="detalle" style="width: 110%; height: 110%; margin-top: -5%; margin-left: -1%;">
<div class="adentro" style="margin-top: 8%; width: 30%; height: 30%; margin-left: 25%;">
	
	<form method="POST" style="text-align: center;">
		<h3>¿DESEA ELIMINAR EL PEDIDO?</h3><br>
		<input type="hidden" name="fecha" value='<?php echo "$fecha";?>'>
		<input type="hidden" name="session" value='<?php echo "$session";?>'>
		<input type="hidden" name="bodega" value='<?php echo "$bodega";?>'>
	<input type="submit" name="btn" value="CONFIRMAR" style="background-color: red; color: white; border-color: black; padding: 1.5%; margin-top: 3%; cursor: pointer;">
	<input type="submit" name="btn" value="CANCELAR" style="background-color: white; color: black; border-color: black; padding: 1.5%; margin-top: 3%; cursor: pointer;">

		
	</form>
</div>	
</div>
<?php
}

?>


<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='CANCELAR')
	{
		echo "<script>location.replace('bodegas_pedidos_confirmar.php')</script>";
	}else if($btn=='CONFIRMAR')
	{
		$conexion2->query("delete from pedidos where tienda='$bodega' and convert(date,fecha)='$fecha' and sessiones='$session'")or die($conexion2->error());
		echo "<script>location.replace('bodegas_pedidos_confirmar.php')</script>";


	}
}
?>