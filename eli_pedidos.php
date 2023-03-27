<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body style="background-color: #000;">
<div style="display: none;">
	<?php
	include("conexion.php");
	$pedido=base64_decode($_GET['pedido']);


	?>
</div>
	<div style="background-color: white; width: 30%; height: 60%; margin-left: 30%; margin-top: 10%; text-align: center; border-radius: 6px;">
		<form method="POST">
			<h3>¿ SEGURO DESEA ELIMINAR EL REGISTRO ?</h3>
			<input type="submit" name="btn" value="CONFIRMAR" style="margin-bottom: 15%; margin-top: 6%; border-color: white; background-color: red; padding: 1.5%; color: white; cursor: pointer; margin-left: 5%;">
			<input type="hidden" name="pedido" value='<?php echo "$pedido";?>'>
			<input type="submit" name="btn" value="CANCELAR" style="margin-bottom: 15%; margin-top: 6%; border-color: black; background-color: white; padding: 1.5%; color: black; cursor: pointer;">

		</form>
	</div>
	<?php
	if($_POST)
	{
		extract($_REQUEST);
		if($btn=='CONFIRMAR')
		{
			$user=$_SESSION['usuario'];
			$session=$_SESSION['pedidos'];
			//echo "<script>alert('$pedido| $user | $session')</script>";
			$conexion2->query("delete from pedidos where pedido='$pedido' and sessiones='$session' and usuario='$user'")or die($conexion2->error());
			echo "<script>location.replace('pedidos.php')</script>";
		}else if($btn=='CANCELAR')
		{
			echo "<script>location.replace('pedidos.php')</script>";

		}
	}
	?>

</body>
</html>