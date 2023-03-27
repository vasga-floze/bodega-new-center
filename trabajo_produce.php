<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
	<div style="display: none;">
<?php
	include("conexion.php");
	
?>
	</div>

<div class="detalle" style="width: 100%; margin-left: -0.5%;">
	<?php
	$mesa=$_GET['mesa'];
	$obs=$_GET['obs'];
	$fecha=$_GET['fecha'];
	echo "<a href='trabajos.php?fecha=$fecha&&mesa=$mesa&&obs=$obs'>";
	?>
	<button style="float: right; cursor: pointer; margin-right: 0.5%;">Cerrar X</button></a>
	<div class="adentro" style="margin-left: 2.8%; margin-top: 0.5%;">
	<form method="POST">
	
<?php
$c=$conexion1->query("select * from dbo.PRODUCCION_ACCPERSONAL where PRODUCE='1'")or die($conexion1->error());
$n=0;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	echo "<label><input type='checkbox' name='op[$n]' value='".$f['NOMBRE']."'>".$f['NOMBRE']." </label><br><br>";
	$n++;
}
?>
<hr>
	<input type="submit" name="" value="OK" class="boton3" style="float: right; margin-right: 3%; padding: 1%;">
	</form><br><br>
		
	</div>
</div>
<?php

if($_POST)
{
	extract($_REQUEST);
	$n1=0;
	$k=0;
	while($n1<=$n)
	{
		if($op[$n1]!='')
		{
			if($k==0)
			{
				$t="$op[$n1]";
				$k++;
			}else
			{
				$t="$t ; $op[$n1]";
			}

		}
		$n1++;
	}
	echo "<script>location.replace('trabajos.php?fecha=$fecha&&mesa=$mesa&&obs=$obs&&t=$t')</script>";
}
?>
</body>
</html>