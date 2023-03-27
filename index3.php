<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function empacador()
		{
			$("#op").val('1');
			$("#form").submit();
		}
		function cerrar()
		{
			//alert();
			$("#divempacado").hide();
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
if($_GET['c']=='')
{
	$hoy=date("Y-m-d");
	echo "<script>$(document).ready(function(){
		//alert('$hoy');
		$('#fecha').val('$hoy');
	})</script>";

}else
{
	//cambiar datos
}
?>
<?php
//SELECCIONAR EMPACADOR
extract($_REQUEST);
if($_POST and $op==1)
{
$ce=$conexion1->query("select * from PRODUCCION_ACCPERSONAL where EMPACA='1'")or die($conexion1->error());
echo "<div style='width: 80%; height:80%; border:groove; background-color:white; border-color:black; position:fixed; overflow:auto; margin-left:10%;' id='divempacado'>";

echo "<bottom style='background-color:red; color: white; float: right; margin-right:0%; position:sticky; top:0; rigth:0.3; padding:0.5%; cursor:pointer;' onclick='cerrar()'>X</bottom><br>";

echo "</div>";
}

//FIN SELECCION EMPACADOR
?>
<form method="POST" name="form" id="form">
<table style="width: 100%;" cellpadding="10">
	<tr>
		<td><LABEL>
			NUMERO DE FARDO<br>
			<input type="number" name="numero" id="numero" step="any" class="text">
		</LABEL>
	</td>
		<td><LABEL>
			LBS<br>
			<input type="number" name="lbs" id="lbs" step="any" class="text" required>
		</LABEL>
	</td>
		<td><LABEL>
			UND<br>
			<input type="number" name="und" id="und" class="text" required>
		</LABEL>
	</td>
	</tr>

	<tr>
		<td><label>FECHA PRODUCCION:<br>
			<input type="date" name="fecha" id="fecha" class="text" required>
		</label><br>

		</td>
		<td><label>UBICACION:<br>
			<input type="text" name="ubucacion" class="text">
		</label><br>

		</td>
		<td><label>TIPO EMPAQUE:<br>
			<select name="empaque" class="text" required>
			<option id="empaques" value="">SELECCIONE EMPAQUE</option>
			<option>CAJA GRANDE</option>
			<option>CAJA PEQUEÑA</option>
			<option>BOLSA GRANDE</option>
			<option>BOLSA PEQUEÑA</option>
			</select>
		</label><br>

		</td>
	</tr>
	<tr>
		<td colspan="3">
			<label>
				EMPACADO POR:<br>
				<input type="text" name="empacado" id="empacado" class="text" required readonly style="padding: 0.3%;" ondblclick="empacador()">
			</label>
		</td>
		
	</tr>
	<tr>
		<td colspan="3">
			<label>
				PRODUCIDO POR:<br>
				<input type="text" name="producido" id="producido" class="text" required readonly style="padding: 0.3%;">
			</label>
		</td>
	</tr>
	<tr>
		<td><label>BODEGA:<br>
			<select name="bodega" id="bodega" class="text" required>
				<option id="bodegas" value="">BODEGA</option>
			<?php
			$cb=$conexion1->query("select bodega,nombre from consny.bodega where bodega in('SM00','CA00')")or die($conexion1->error());
			while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
			{
				$bod=$fcb['bodega'];
				$nom=$fcb['nombre'];
				echo "<option value='$bod'>$bod: $nom</option>";
			}
			?>
			</select>
		</label>
	</td>
		<td><label>DIGITADO POR:<br>
		<input type="text" name="digitado" id="digitado" class="text">	
		</label></td>
		<td><label>OBSERVACION:<br>
			<input type="text" name="obs" id="obs" class="text">
		</label></td>
	</tr>
</table>
<hr>
<input type="submit" name="btn" value="ROPA" class="boton1" style="margin-left: 8.5%;">
<input type="submit" name="btn" value="CARTERAS" class="boton1">
<input type="submit" name="btn" value="CINCHOS" class="boton1">
<input type="submit" name="btn" value="GORRAS" class="boton1">
<input type="submit" name="btn" value="JUGUETES" class="boton1" style="margin-left: 8.5%;">	
<input type="submit" name="btn" value="ZAPATOS" class="boton1">
<input type="submit" name="btn" value="OTROS" class="boton1">
<input type="submit" name="btn" value="GANCHOS" class="boton1">
<input type="hidden" name="c" id="c" value="<?php echo $_GET['c'];?>">
<input type="hidden" name="op" id="op">
</form>
</body>
</html>