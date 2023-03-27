<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
include("conexion.php");
?>

<h1 style="color: black; text-decoration: underline;">REGISTRO DE RIPIO</h1>
<div style="background-color: rgb(0,0,0,0.5); border-radius: 10px; width: 60%; padding: 0.5%; margin-left: 25%;">
<form method="POST" style="float: center; background-color: white; color: black;  width: 95%; border-radius: 10px; padding: 2%; text-align: center;">

<label>CLASIFICACION:<br>
<SELECT name='clasificacion' class='text' required style="width: 80%; padding: 1%;">
<option></option>
<?php
$cc=$conexion1->query("select clasificacion_2 from consny.ARTICULO where CLASIFICACION_2 is not null group by CLASIFICACION_2")or die($conexion1->error());
while($fcc=$cc->FETCH(PDO::FETCH_ASSOC))
{
	echo "<option>".$fcc['clasificacion_2']."</option>";
}
?>
</SELECT>
</label><br>
<label>PESO:<br><input type="number" step="any" name="peso" required style="width: 78%; padding: 1%;"></label><br>	

<label>BODEGA:<br><select name="bodega" required style="width: 80%; padding: 1%;"> 

<?php
$cb=$conexion1->query("select * from consny.BODEGA where bodega='SM00'")or die($conexion1->error());
while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
{
	$bodegas=$fcb['BODEGA'];
	echo "<option value='$bodegas'>".$fcb['BODEGA'].": ".$fcb['NOMBRE']."</option>";
}
?>
</select></label><br>
<label>FECHA:<br>
<input type="date" name="fecha" class="text" required style="width: 78%; padding: 1%;">
</label><br>
<label>OBSERVACION:<br>
<input type="text" name="obs" class="text" style="width: 78%; padding: 1%;" required>
</label><br><br>
<input type="submit" name="btn" value="GUARDAR" class="boton2" style="padding: 1.5%; float: right; margin-right: 0.5%;"><br><br>

</form>
</div>
<?php
if($_POST)
{
	extract($_REQUEST);
	echo "->$clasificacion";
	$c=$conexion2->query("declare @fecha date='$fecha'
	select DAY(@fecha) as dia,MONTH(@fecha) as mes,Right(Cast(Year(@Fecha) As Char(4)),2) as anio,max(session) as session from ripio where fecha_documento='$fecha'")or die($conexion2->error());

	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$dia=$f['dia'];
	$mes=$f['mes'];
	$anio=$f['anio'];
	$letra=chr(rand(ord("A"), ord("Z")));
	$session=$f['session'] +1;
	$session1=str_pad($session,3,"0",STR_PAD_LEFT);
			$mes=str_pad($mes,2,"0",STR_PAD_LEFT);
			$dia=str_pad($dia,2,"0",STR_PAD_LEFT);

	$barra="R$anio$session1$mes$dia$letra";
	$k=0;
	while($k==0)
	{
		$con=$conexion2->query("select * from ripio where barra='$barra'")or die($conexion2->error());
		$ncon=$con->rowCount();
		if($ncon==0)
		{
			$k=1;
		}else
		{
			$session++;
			$k=0;
			$session1=str_pad($session,3,"0",STR_PAD_LEFT);
			$mes=str_pad($mes,2,"0",STR_PAD_LEFT);
			$dia=str_pad($dia,2,"0",STR_PAD_LEFT);
			
			echo "$mes <br>";
	$barra="R$anio$session1$mes$dia$letra";
	echo "$session<hr>";
		}
	}
	echo "$barra";
	$usuario=$_SESSION['usuario'];
	$conexion2->query("insert into ripio(clasificacion,peso,fecha_documento,session,barra,bodega,usuario_creacion,fecha_hora_creacion,despacho,comentario) values('$clasificacion','$peso','$fecha','$session','$barra','$bodega','$usuario',getdate(),'N','$obs')")or die($conexion2->error());
	echo "<script>location.replace('imprimir_ripio.php?b=$barra')</script>";

	

}
?>
</body>
</html>