<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
include("conexion.php");
?>
<form method="POST">
<input type="date" name="desde">
<input type="date" name="hasta">
<input type="text" name="bodega">
<input type="submit" name="btn" value="generar">	

</form>
</body>
</html>