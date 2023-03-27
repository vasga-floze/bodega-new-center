<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		function enviar()
		{

			
				if($("#digita").is(":checked")|| $("#produce").is(":checked")|| $("#empaca").is(":checked"))
				{
					$("#form").submit();
				}else
				{
					alert('SELECCIONA UNA OPCION');
				}
				
			
		}
		function seleccion()
		{
			$("#op").val(1);
		}
	</script>
</head>
<body>
<?php
  include("conexion.php");
?>
<center>
<input type="hidden" id="op" name="op">
<form method="POST" id="form" style="margin-left: 5%;">
<br><br><br><br>
<input type="text" name="nom" required class="text" style="padding: 0.5%; width: 80%;" placeholder="NOMBRE COMPLETO"><br><br>
<label>
<input type="checkbox" onclick="seleccion()" name="produce" id="produce" value="1">PRODUCE</label><br><br>

<label>
<input type="checkbox" onclick="seleccion()" name="digita" id="digita" value="1">DIGITA</label><br><br>

<label>
<input type="checkbox" onclick="seleccion()" name="empaca" id="empaca" value="1">EMPACA</label><br><br>


</form>
<input type="button" name="btn" value="GUARDAR" class="btnfinal" style="padding: 0.5%; border-radius: 0; background-color: #90BBAD;" onclick="enviar()">

<?php
error_reporting(0);
if($_POST)
{
	extract($_REQUEST);
	if($digita=='')
	{
		$digita=0;
	}
	if($produce=='')
	{
		$produce=0;
	}
	if($empaca=='')
	{
		$empaca=0;
	}
	$activo=1;
	$c=$conexion1->query("select  top 1 codigo as numero from produccion_accpersonal where convert(int,isnumeric(codigo))>0 order by codigo desc

")or die($conexion1->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$codigo=$f['numero'] + 1;

	$q=1;
	while($q==1)
	{
		$cv=$conexion1->query("select * from produccion_accpersonal where codigo='$codigo'")or die($conexion1->error());
		$ncv=$cv->rowCount();
		if($ncv==0)
		{
			$q=0;
		}else
		{
			$codigo++;
			$q=1;
		}
	}

	$cv=$conexion1->query("select * from produccion_accpersonal where nombre='$nom' and activo='1'")or die($conexion1->error());
$ncv=$cv->rowCount();
if($ncv==0)
{
	//echo "<script>alert('$codigo')</script>";
	$conexion1->query("insert into produccion_accpersonal(codigo,nombre,digita,produce,empaca,activo) values('$codigo','$nom','$digita','$produce','$empaca','$activo')")or die($conexion1->error());
	echo "<script>alert('GUARDADO CORRETAMENTE')</script>";
}else
{
	echo "<script>alert('$nom YA FUE REGISTRADO ANTES')</script>";
}
	
}
?>
</body>
</html>