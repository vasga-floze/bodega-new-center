<!DOCTYPE html>
<html>
<head>
	<title></title>
		<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body style="background-color: rgb(133,133,137,0.8);">
<div style="display: none;">
	<?php
		include("conexion.php");
		$id=$_GET['id'];
	?>
</div>
	<div  style="background-color: white; padding-top: 10%; padding-bottom: 10%; width: 30%; margin-left: 35%; margin-top: 3.5%; text-align: center; border-radius: 5px; border-color: black;">
		
	
		<p style="margin-top: -15%;">¿SEGURO DESEA QUITAR EL ARTICULO?</p>
		<form method="POST">
			<input type="hidden" name="id" value='<?php echo "$id";?>'>
			<input type="submit" name="btn" value="ELIMINAR" style="padding: 1.5%; background-color: red; border-color: black; cursor: pointer; color: white;">
			<input type="submit" name="btn" value="CANCELAR" style="padding: 1.5%; background-color: white;  border-color: black; cursor: pointer; color: black;">
			
		</form>
		<?php
			if($_POST)
			{
				extract($_REQUEST);
				if($btn=='CANCELAR')
				{
					echo "<script>location.replace('ventacod.php')</script>";
				}else if($btn=='ELIMINAR')
				{
					$ventacod=$_SESSION['ventacod'];
					$usuario=$_SESSION['usuario'];
					$conexion2->query("delete from venta where id='$id' and sessiones='$ventacod' and usuario='$usuario'")or die($conexion2->error());
					echo "<script>location.replace('ventacod.php')</script>";
				}
			}
		?>
	</div>
</body>
</html>