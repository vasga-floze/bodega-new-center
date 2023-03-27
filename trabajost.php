<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function primero()
		{
			
			
		}
	</script>
</head>
<body>
<?php
include ("conexion.php");
?>
<table border="1">
<tr>
	<td>uno 1</td>
	<td>dos 1</td>
	<td>tres 1</td>
	<td>tres 1</td>
</tr>
<tr >
	
</tr>
<tr>
	<td rowspan='2'>primero</td>
	<td>dos 2</td>
	<td>tres 2</td>
	<td rowspan='2'>primero</td>
</tr>
<tr>
	<td>uno 3</td>
	<td>dos 3</td>
</tr>
</table>
</body>
</html>