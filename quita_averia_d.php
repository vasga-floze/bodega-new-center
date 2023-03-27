<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<script>
	$(document).ready(function(){
		if($("#i").val()=='')
		{
			if(confirm('SEGURO DESEA QUITAR EL ARTICULO'))
		{
			$("#i").val('1');
			$("#form").submit();

		}else
		{
			$("#i").val('1');
			location.replace('desglose_averia.php');
		}
		}
		
	});

</script>
<form method="POST" id="form" name="form">
	<input type="hidden" name="i" id="i">
</form>
<?php
include("conexion.php");
$id=$_GET['id'];
if($id!="")
{
	$conexion2->query("delete from averia where id='$id'")or die($conexion2->error());
	echo "<script>location.replace('desglose_averia.php')</script>";
}else
{
	echo "<script>alert('ERROR! INTENTELO NUEVAMENTE')</script>";
	echo "<script>location.replace('desglose_averia.php')</script>";
}
?>
</body>
</html>