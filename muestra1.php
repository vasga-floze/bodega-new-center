<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	function buscador(busca)
	{
		$.ajax({
		url: 'muestra1.php' ,
		type: 'POST' ,
		dataType: 'html',
		data: {busca: busca},
	})
	.done(function(respuesta){
		$("#datos").html(respuesta);
	})
	.fail(function(){
		console.log("error");
	});
	}
	$(document).on('keyup','#b',function(){
		var b=$("#b").val();
		if(b!='')
		{
			buscador(b);
		}else
		{
			buscador();
		}
		
	})
</script>
</head>
<body>
<div id="cajas">
<?php
include("conexion.php");
?>
</div>
<form method="POST">
<input type="text" name="b" id="b">

</form>
<div id="datos">
	

<?php
extract($_REQUEST);
if($busca=='')
{
	$c=$conexion2->query("select * from agenda")or die($conexion2->error());
}else
{
	$c=$conexion2->query("select * from agenda where nombre like '%$busca%' or telefono like '%$busca%'")or die($conexion2->error());
}
$n=$c->rowCount();
if($n==0)
{
	echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
}else
{
	echo "<table border='1'>";
	echo "<tr>
			<td>NOMBRE</td>
			<td>TELEFONO</TD>
		</tr>";

	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		$nom=$f['nombre'];
		$tel=$f['telefono'];
		echo "<tr>
			<td>$nom</td>
			<td>$tel</TD>
		</tr>";
	}
}
?>
</div>
</body>
</html>