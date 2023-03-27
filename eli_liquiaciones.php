<!DOCTYPE html>
<html>
<head>
	<title></title>
		<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body style="background-color: #A3B7BC;">
<div style="display: none;">
<?php
include("conexion.php");
echo "</div>";
$id=$_GET['id'];
//echo "$id |	";

$id=base64_decode($id);
//echo "$id";
$session=$_SESSION['liquidacion'];
$usuario=$_SESSION['usuario'];
		//echo "<script>alert('$id | $session | $usuario')</script>";

$c=$conexion2->query("select * from liquidaciones where id_liquidacion='$id' and sessiones='$session' and usuario='$usuario'")or die($conexion2->error());
$n=$c->rowCount();
if($n==0)
{
	echo "<script>ALERT('NO SE PUDO ELIMINAR INTENTALO NUEVAMENTE')</script>";
}else
{
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$art1=$f['art_origen'];
	$art2=$f['art_destino']; 
	$autoriza=$f['autoriza'];
	$fecha=$f['fecha'];
	$msj="<h3>¿ SEGURO DESEA ELIMINAR LIQUIDACION DE $art1 a $art2 ?</h3>";
	?>
<form method="POST" style="width: 30%; height: 30%; padding-bottom: 10%; background-color: white; border-radius: 10px; border-collapse: collapse; border-color: red; padding-top: 2%; text-align: center; margin-left: 30%; margin-top: 8%;">
<?php echo "$msj";?>	
<input type="hidden" name="id" value='<?php echo "$id";?>'>
<input type="submit" name="btn" value="CONFIRMAR" style="border-color: black; padding: 1.5%; background-color: red; color: white; cursor: pointer;">
<input type="submit" name="btn" value="CANCELAR" style="border-color: black; padding: 1.5%; background-color: white; color: black; cursor: pointer;">

</form>

<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='CANCELAR')
	{
		echo "<script>location.replace('liquidaciones.php')</script>";
	}else if($btn=='CONFIRMAR')
	{
		$session=$_SESSION['liquidacion'];
		//echo "<script>alert('$id | $session | $usuario')</script>";
		$conexion2->query("delete from liquidaciones where id_liquidacion='$id' and sessiones='$session' and usuario='$usuario'")or die($conexion2->error());
		echo "<script>location.replace('liquidaciones.php?autoriza=$autoriza&&fecha=$fecha')</script>";

	}
}

}
?>

	

</body>
</html>