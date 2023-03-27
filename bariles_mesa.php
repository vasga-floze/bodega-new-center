<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			//alert($("#rn").val());
			$("#caja").hide(200);
			//alert('error');
			if($("#rn").val()==0)
			{
				//alert('nel');
			}else
			{
				//alert('bien');
				$("#baril").val($("#r_baril").val());
				$("#producido").val($("#r_producido").val());
				$("#mesa").val($("#r_mesa").val());
				$("#obs").val($("#r_obs").val());
				$("#trabajado").val($("#r_trabajo").val());
				$("#fecha").val($("#r_fecha").val());
				$("#digitado").val($("#r_digitado").val());
				$("#producto").val($("#r_articulo").val());
				//alert($("#r_fecha").val());
				//$("#mesa").focus();
			}



		})
		function enviar()
		{
			$("#op").val(1);
			$("#form").submit();
		}
		function enviar1()
		{
			$("#op").val('2');
		}
		function seleccion()
		{
			//alert($("#persona0").val());
			var nume=$("#num").val();
			var numero=0;
			var encuentra=0;
			//alert(nume);

			while(numero<=nume)
			{
				var t="#persona"+numero;
				//alert(t);
				if($(t).is(':checked'))
				{
					if(encuentra==0)
					{
						$("#trabajado").val($(t).val());
						encuentra++;
					}else
					{
						$("#trabajado").val($("#trabajado").val()+','+$(t).val())
					}
				}
				numero++;
			}
			$(".detalle").hide();
		}

		function trabajos()
		{
			$(".detalle").show();
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
?>
<div style="background-color: white; width: 100%; height: 100%; position: fixed; text-align: center; opacity: 0.8" id="caja">
	<img src="loadf.gif" style="margin-top: 10%;">
</div>
<div class="detalle" style="margin-top: -3%; display: none;">
<a href="#" style="float: right; margin-right: 0.5%; text-decoration:none; color:white;">Cerrar X</a><br>
	<div class="adentro" style="margin-left: 2.5%; height: 92%;">
	<?php
	$cp=$conexion1->query("select * from PRODUCCION_ACCPERSONAL where PRODUCE='1' and activo=1")or die($conexion1->error());
	$num=0;
	while($fcp=$cp->FETCH(PDO::FETCH_ASSOC))
	{
		$nombre=$fcp['NOMBRE'];
		echo  "<label><input type='checkbox' name='persona$num' id='persona$num' value='$nombre'>$nombre</label><br><br>";
		$num++;
	}
	echo "<input type='hidden' name='num' id='num' value='$num'>";

	?>
	<button class="boton3" style="padding: 2%; position: sticky; bottom: 0;right: 0; float: right;" onclick="seleccion()">OK</button>
	</div>
</div>
<center>
<form method="POST" id="form" style="width: 80%; border:groove; border-color: blue;">
	<h3>MERCADERIA EN PROCESO (EN MESA)</h3>
	<label>ID BARIL:<BR>
<input type="number" name="baril" id="baril" placeholder="ID BARIL" class="text" required onchange="enviar()">
<input type="hidden" name="op" id="op">
	</label><BR><BR>
	<label>
		PRODUCIDO POR:<BR>
<input type="text" name="producido" id="producido" class="text" readonly>
	</label><br><br>
<label>
		PRODUCTO:<BR>
<input type="text" name="producto" id="producto" class="text" readonly>
	</label><br><br>

	<label>
		TRABAJADO POR:<BR>
<input type="text" name="trabajado" id="trabajado" class="text" required readonly ondblclick="trabajos()" required>
	</label><br><br>
	<label>
		MESA:<br>
<input type="text" name="mesa" id="mesa" class="text">
	</label><br><br>
	<label>
		OBSERVACION:<br>
<input type="text" name="obs" id="obs" class="text">
	</label><br><br>
	<label>
		FECHA MESA:<br>
		<input type="date" name="fecha" id="fecha" class="text" required>
	</label><br><br>
	<label>
		DIGITADO POR:<br>
		<input type="text" name="digitado" id='digitado' class="text" required>
	</label><br><br>
<input type="submit" name="btn" value="GUARDAR" class="boton3" style="float: right; margin-right: 0.5%;" onclick="enviar1()">
<br><br>
</form>
</center>

<?php
if($_POST)
{
	extract($_REQUEST);
	echo "<input type='text' name='r_trabajo' id='r_trabajo' value='$trabajado'>";
	echo "<input type='hidden' name='r_mesa' id='r_mesa' value='$mesa'>";
	echo "<input type='hidden' name='r_obs' id='r_obs' value='$obs'>";
	echo "<input type='hidden' name='r_fecha' id='r_fecha' value='$fecha'>";
	echo "<input type='hidden' name='r_digitado' id='r_digitado' value='$digitado'>";
	//echo "<script>alert('$fecha')</script>";
	if($op==1)
	{
		//echo $baril;
	$c=$conexion2->query("select * from trabajo where sessiones='$baril' and estado='0'")or die($conexion2->error());
	$n=$c->rowCount();
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$pro=$f['producido'];
	$articulo=$f['articulos'];
	echo "<input type='hidden' name='rn' id='rn' value='$n'>";

	if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO NINGUN BARIL DISPONIBLE CON ID: $baril')</script>";
		echo "<script>location.replace('bariles_mesa.php')</script>";
	}else
	{
		//echo "zxczcxzc";
	echo "<input type='hidden' name='r_producido' id='r_producido' value='$pro'>";
	echo "<input type='hidden' name='r_baril' id='r_baril' value='$baril'>";
	echo "<input type='hidden' name='r_articulo' id='r_articulo' value='$articulo'>";

	}
	}else if($op==2)
	{
		$conexion2->query("update trabajo set estado='1',fecha_mesa='$fecha',fecha_mesa_ingreso=getdate(),mesa='$mesa',observacion='$obs',digita_mesa='$digitado',trabajado_mesa='$trabajado' where sessiones='$baril' and estado='0'")or die($conexion2->error());
		echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
		//echo "<script>location.replace('bariles_mesa.php')</script>";
	}
}
?>
</body>
</html>