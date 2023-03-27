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
<h3 style="text-decoration: underline;">REGISTRO DE TARJETA DE REGALO</h3>
<form method="POST" style="background-color: white; border-color: black; border:groove; width: 50%; height: auto; margin-left: 15%;"><br>
	<label>
		CODIGO DE TARJETA:<BR>
		<input type="text" name="codigo" id='codigo' class="text">
	</label><br><br>
	<label>
		VALOR($): <br>
		<input type="number" step="any" name="valor" id='valor' class="text">
	</label><BR><BR>
	<label>FECHA:<br>
		<input type="date" name="fecha" id class="text" id="fecha" value="<?php echo date('Y-m-d');?>">
	</label><br><br>
	<label>
		BODEGA:<br>
		<select name="bodega" id="bodega" class="text">
			<option id="bod">BODEGA</option>
			<?php
			$cb=$conexion1->query("select concat(bodega,': ',nombre) as bodega from consny.bodega where bodega not like 'SM%' and nombre not like '%(N)%' and bodega!='CA00' order by nombre")or die($conexion1->error());
			while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
			{
				echo "<option>".$fcb['bodega']."</option>";
				//echo "<script>alert('4')</script>";
			}
			?>
		</select>
	</label><br><br>
	<input type="submit" name="btn" value="GUARDAR" class="boton2" style="float: right; margin-right: 0.5%; padding: 1%;"><br><br>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$b=explode(": ", $bodega);
	echo $b[0];
	echo "<script>$(document).ready(function(){
		$('#bod').val('$bodega');
		$('#valor').val('$valor');
		$('#fecha').val('$fecha');
	})</script>";
	$cv=$conexion2->query("select * from tarjeta where codigo='$codigo'")or die($conexion2->error());
	$ncv=$cv->rowCount();
	if($ncv!=0)
	{
		echo "<script>alert('CODIGO: $codigo YA FUE REGISTRADO ANTES, VERIFICA LA INFORMACION')</script>";
	}else
	{
		$qv=$conexion2->query("select max(id) as id from tarjeta")or die($conexion2->error());
		$fqv=$qv->FETCH(PDO::FETCH_ASSOC);
		$id=$fqv['id']+1;
		$k=0;
		while($k=0)
		{
			$qv1=$conexion2->query("select * from tarjeta where id='$id'")or die($conexion2->error());
			$nqv1=$qv1->rowCount();
			if($nqv1==0)
			{
				$k=1;
			}else
			{
				$id++;
				$k=0;
			}
		}
		$usuario=$_SESSION['usuario'];
		$paquete=$_SESSION['paquete'];
		if($conexion2->query("insert into tarjeta(id,codigo,valor,bodega,fecha_ingreso,estado) values('$id','$codigo','$valor','$b[0]','$fecha','R')")or die($conexion2->error()))
		{
			$conexion2->query("insert into transaccion_tarjeta(id_tarjeta,fecha_hor_crea,usuario,paquete,referencia) values('$id',getdate(),'$usuario','$paquete','REGISTRO DE TARJETA $codigo')")or die($conexion2->error());
			echo "<script>alert('GUARDADO CORECTAMENTE')</script>";
			echo "<script>location.replace('tarjeta.php')</script>";


		}else
		{
			echo "<script>alert('ERROR: NO SE PUDO GUARDAR LA INFORMACION, INTENTALO NUEVAMENTE...')</script>";
			echo "<script>alert('tarjeta.php')</script>";
		}
	}
}
?>
</body>
</html>
