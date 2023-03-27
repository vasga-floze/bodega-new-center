<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body style="background-color: black;">
<div style="display: none;">
<?php
include("conexion.php");
$id=base64_decode($_GET['id']);
$c=$conexion2->query("select * from averias where id='$id'")or die($conexion2->error());

$n=$c->rowCount();
if($n==0)
{
	echo "<script>location.replace('averias.php')</script>";
}else
{
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$cod=$f['articulo'];
?>	


	
</div>

	<div class="adentro" style="width: 30%; height: 50%; text-align: center; padding: 4%; margin-left: 25%; margin-top: 8%;">
	<h3>¿SEGURO DE ELIMINAR <?php echo "$cod"; ?> ?</h3>
	<form method="POST">
	<input type="hidden" name="id" value='<?php echo "$id";?>'>

	<input type="submit" name="btn" value="CONFIRMAR" style="background-color: red; padding: 1.5%; border-color: black; color: white; cursor: pointer;">
	<input type="submit" name="btn" value="CANCELAR" style="background-color: white; padding: 1.5%; border-color: black; margin-left: 1%; cursor: pointer;">

		
	</form>
</div>

<?php	
}
?>
</body>
</html>
<?php
$num=$_GET['num'];
//echo "<script>alert('$num')</script>";
if($_POST)
{
	extract($_REQUEST);
	
	if($btn=='CONFIRMAR')
	{
		$session=$_SESSION['averias'];
		$user=$_SESSION['usuario'];
		$conexion2->query("delete from averias where id='$id' and session='$session' and usuario='$user'")or die($conexion2->error());
		echo "<script>location.replace('averias.php?num=$num')</script>";


	}else
	{
		//echo "<script>alert('$num')</script>";
		echo "<script>location.replace('averias.php?num=$num')</script>";
	}
	
}

?>