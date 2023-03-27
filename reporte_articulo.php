<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
	$(document).ready(function(){

		$("#detalle").hide();
		
		});
	</script>
	<title></title>

	<style>
  .preloader {
  width: 70px;
  height: 70px;
  border: 10px solid #eee;
  border-top: 10px solid skyblue;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 3s;
  animation-iteration-count: infinite;

}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
</head>
<body>
	<div class="detalle" id="detalle" style="background-color: white; display: none;">
	<div class="preloader" id="preloader" style="margin-top: 15%;">
	</div>
	</div>
	<?php
	//error_reporting(0);
	include("conexion.php");
	?>
<form method="POST">
<input type="date" name="desde" class="text" style="width: 20%;" required>

<input type="date" name="hasta" class="text" style="width: 20%;">
<input type="text" name="bodega" class="text" style="width: 20%;" required>
<input type="submit" name="btn">
</form>
<?php
if($_POST)
{
extract($_REQUEST);
$mysql=new mysqli("localhost","root","","respaldo")or die(mysqli_error());


$c=$mysql->query("select codigo from registro where fecharespaldo like '%$desde%' and bodega='$bodega' group by codigo")or die($mysql->error());
while($f=mysqli_fetch_array($c))
{
	echo "<script>alert('$f[0]')</script>";
	$cr=$mysql->query("select id_registro from registro where fecharespaldo like '%$desde%' and codigo='$f[0]' group by id_registro")or die($mysql->error());
	$cantidad=mysqli_num_rows();
	while($fcr=mysqli_fetch_array($cr))
	{
		$q=$mysql->query("select lbs,peso,tipo,activo from registro where id_registro='$fcr[0]' and fecharespaldo like '%$desde%'")or die($mysql->error());
		$peso=0;
		$fq=mysqli_fetch_array($q);
		if($fq[2]=='P')
		{
			$peso=$peso + $fq[0];
		}else
		{
			$peso=$peso + $fq[1];
		}

	}
	echo "<script>alert('$f[0]')</script>";
	echo "<tr>
	<td>$f[0]</td>
	<td>$cantidad</td>
	<td>$peso</td>
	</tr>";
}




}
?>
</body>
</html>