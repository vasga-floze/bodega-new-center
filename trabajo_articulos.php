<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>

<div class="detalle" style="width: 100%;">
	<div style="display: none;">
	<?php
	include("conexion.php");
	echo "</div>";
	$deposito=$_GET['deposito'];
	$peso=$_GET['peso'];
	$obs=$_GET['obs'];
	$cantidad=$_GET['cantidad'];
	echo "<a href='trabajos.php?deposito=$deposito&&peso=$peso&&cantidad=$cantidad&&obs=$obs'>";
?>
	<button style="float: right; margin-right: 0.5%; cursor: pointer;">Cerrar X</button></a>
	<div class="adentro" style="margin-left: 1.2%; padding-left: 2%;">
	<form method="POST">
	<label>
	<input type="checkbox" name="op[0]" value="CINCHOS"> CINCHOS</label><br><br>
	<label>
	<input type="checkbox" name="op[1]" value="GORRAS"> GORRAS</label><br><br>
	<label>
	<input type="checkbox" name="op[2]" value="ZAPATOS"> ZAPATOS</label><br><br>
	<label>
	<input type="checkbox" name="op[3]" value="DAMA"> DAMA</label><br><br>
	<label>
	<input type="checkbox" name="op[4]" value="HOMBRE"> HOMBRE</label><br><br>
	<label>
	<input type="checkbox" name="op[5]" value="NIÑO"> NIÑO<label><br><br>
	<label>
	<input type="checkbox" name="op[6]" value="VESTIDO"> VESTIDO</label><br><br>
	<label>
	<input type="checkbox" name="op[7]" value="JEANS"> JEANS<label><br><br>
	<label>
	<input type="checkbox" name="op[8]" value="INTERIOR"> INTERIOR</label><br><br>
	<label>
	<input type="checkbox" name="op[9]" value="CAMA"> CAMA<label><br><br>
	<label>
	<input type="checkbox" name="op[10]" value="VARIOS"> VARIOS</label><br><br>
	<label>
	<input type="checkbox" name="op[11]" value="FARDOS"> FARDOS</label><br><br>
	<label>
	<input type="checkbox" name="op[12]" value="PACAS">PACAS</label><br><br>
<input type="submit" name="" value="OK" class="boton3" style="padding: 1%;">
	</form>
	</div>
<?php
if($_POST)
{
	extract($_REQUEST);
$n=0;
$k=0;
while($n<=12)//12 numero de opciones
{
	if($op[$n]!='')
	{
		if($k==0)
		{
			$ta="$op[$n]";
			$k=1;
		}else
		{
			$ta="$ta ; $op[$n]";
		}
	}
	$n++;
}
echo "<script>location.replace('trabajos.php?deposito=$deposito&&peso=$peso&&ta=$ta&&cantidad=$cantidad&&obs=$obs')</script>";
}
?>
</div>
</body>
</html>