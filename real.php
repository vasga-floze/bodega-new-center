<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<section class="principal">

	<h1>BUSQUEDA EN AGENDA</h1>

	<div class="formulario">
		<label for="caja_busqueda">Buscar</label>
		<input type="text" name="caja_busqueda" id="caja_busqueda"></input>

		
	</div>

	<div id="datos1"></div>
	<?PHP
	include("conexion.php");
	$id=1;

	 print"declare @id=$id;
	 select * from agenda where id=$id";
	
		
		
	
	?>
</body>
<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="main.js"></script>
</html>