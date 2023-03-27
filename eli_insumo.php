<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body style="background-color: #B7C7BC;">
<div style="display: none;">
	<?php
	include("conexion.php");
	$digitado=$_GET['digitado'];
	$fecha=$_GET['fecha'];
	$ide=$_GET['id'];
	$id=base64_decode($ide);
	echo "$id";
	?>
</div>
<center>
<div class="adentro" style="width: 30%; margin-top: 15%;  text-align: center; padding-bottom: 3%; border-radius: 10px; border-color: black;">
<h3>¿SEGURO DESEA ELIMINAR EL INSUMO?</h3>
<form method="POST">
	<input type="hidden" name="id" value='<?php echo "$id";?>'>
<input type="submit" name="btn" value="CONFIRMAR" style="background-color: red; border-color:black; padding: 1.5%; color: white; cursor: pointer;">
<input type="submit" name="btn" value="CANCELAR" style="background-color: white; border-color:black; padding: 1.5%; cursor: pointer;">

</form>
</div>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='CANCELAR')
	{
		echo "<script>location.replace('insumo.php?digitado=$digitado&&fecha=$fecha')</script>";
	}else if($btn=='CONFIRMAR')
	{
		//echo "<script>alert('$btn')</script>";
		$conexion2->query("delete from insumo where insumo='$id'")or die($conexion2->error());
		echo "<script>location.replace('insumo.php?digitado=$digitado&&fecha=$fecha')</script>";
	}
}
?>
</body>
</html>