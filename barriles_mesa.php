<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
	$(document).ready(function(){
		//alert($("#min_fecha").val());
		$("#barril").val($("#r_barril").val());
		$("#fecha").val($("#r_fecha").val());
		$("#digitado").val($("#r_digitado").val());
		$("#producido").val($("#r_producido").val());
		$("#producto").val($("#r_producto").val());
		$("#trabajado").val($("#r_trabajado").val());
		$("#porcentaje").prop('max',$("#disp").val());
		$("#fecha").prop('min',$("#min_fecha").val());
		$("#observacion").val($("#r_observacion").val());

		if($("#r_producido").val()=='')
		{
			alert();
		$("#producido").val($("#r_producido1").val());

		}
		if($("#producto").val()=='')
		{
			$("#producto").val($("#r_producto1").val());
		}
	})
		function envio()
		{
			$("#op").val(1);
			$("#form").submit();
		}
		function cerrar()
		{
			$(".detalle").hide();
		}
		function activar()
		{
			//alert();
			$(".detalle").show();
		}
		function seleccion()
		{
			var numero=$("#np").val();
			var num=0;
			var encuentra=0;
			$("#trabajado").val('');
			while(num<=numero)
			{
				var t="#persona"+num;
				if($(t).is(":checked"))
				{
					if(encuentra==0)
					{
						encuentra=1;
						//alert($(t).val());
						$("#trabajado").val($(t).val());
					}else
					{
						$("#trabajado").val($("#trabajado").val()+','+$(t).val());
					}
				}
				num++;

			}
			$(".detalle").hide();
		}
		function final()
		{
			$("#op").val(2);
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
?>
<div class="detalle" style="margin-top: -4%; display: none;">
<a href="#" style="float: right; margin-right: 0.5%; color: white; text-decoration: none;" onclick="cerrar()">Cerrar X</a><br>
<div class="adentro" style="margin-left: 2.5%; height: 92%;">
<?php
	$cp=$conexion1->query("select * from PRODUCCION_ACCPERSONAL where PRODUCE=1 and activo=1")or die($conexion1->error());
	$np=0;
	while($fcp=$cp->FETCH(PDO::FETCH_ASSOC))
	{
		$nom=$fcp['NOMBRE'];
		echo "<label class='tre' style='cursor:pointer;'><input type='checkbox' value='$nom' name='persona$np' id='persona$np'>$nom</label><br><br>";
		$np++;
	}
	echo "<input type='hidden' name='np' id='np' value='$np'>";
?>
<button class="boton3" style="float: right;  position: sticky; bottom: 0; right: 0; padding: 2%;" onclick="seleccion()">OK</button>
</div>
</div>

<form method="POST" id="form" style="border:groove; border-color: blue; width: 70%; margin-left: 5%; padding-top: 1%;">
	<input type="hidden" name="op" id='op'>
<label>ID BARRIL:<br>
<input type="text" name="barril" id="barril" placeholder="ID BARRIL" class="text" onchange="envio()">
</label><br><br>

<label>PRODUCIDO POR:<BR>
<input type="text" name="producido" id="producido" placeholder="PRODUCIDO POR" class="text" readonly>
</label><br><br></label>

<label>PRODUCTO:<BR>
<input type="text" name="producto" id="producto" placeholder="PRODUCTO" class="text" readonly>
</label><br><br></label>

<label>FECHA:<br>
<input type="date" name="fecha" id="fecha" value='<?php echo date("Y-m-d")?>' class="text">
</label><br><br>

<label>PORCENTAJE:<br>
<input type="number" name="porcentaje" id="porcentaje"  class="text" min="0" required>
</label>
</label><br><br>
<label>TRABAJADO POR:<br>
<input type="text" name="trabajado" id="trabajado" class="text" readonly required    ondblclick="activar()">
</label><br><br>
<label>DIGITADO POR:<br>
<input type="text" name="digitado" id="digitado" class="text">
</label><br><br>
<label>OBSERVACION:<br>
<input type="text" name="observacion" id="observacion" class="text">
</label><br><br>
<input type='submit' name='btn' id="btn" value="GUARDAR" onclick="final()" style='float:right; margin-right: 0%; padding:1%;' class='boton3'><br><br>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	
	if($op==1)
	{
		echo "<input type='hidden' name='r_barril' id='r_barril' value='$barril'>";
	//echo "<input type='hidden' name='r_producido' id='r_producido' value='$producido'>";
	echo "<input type='hidden' name='r_fecha' id='r_fecha' value='$fecha'>";
	echo "<input type='hidden' name='r_digitado' id='r_digitado' value='$digitado'>";
	echo "<input type='hidden' name='r_trabajado' id='r_trabajado' value='$trabajado'>";
	echo "<input type='hidden' name='r_observacion' id='r_observacion' value='$observacion'>";

		$c=$conexion2->query("select * from trabajo where sessiones='$barril' and estado='0' and isnull(porcentaje_trabajado,0)<100")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO NINGUN BARRIL DISPONIBLE CON ID: $barril O YA FUE TRABAJADO EL 100%')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$pro=$f['producido'];
			$producto=$f['articulos'];
			$fechas=$f['fecha'];
			//echo "<script>alert('$pro')</script>";
			echo "<p style='display:none;'></p>";
			echo "<input type='hidden' name='min_fecha' id='min_fecha' value='$fechas'>";
			echo "<input type='hidden' name='r_producido' id='r_producido' value='$pro'>";
			echo "<input type='hidden' name='r_producto1' id='r_producto1' value='$producto'>";
			//porcentaje disponible
			$cp=$conexion2->query("select (100-isnull(SUM(ISNULL(porcentaje,0)),0)) as cantidad from trabajo_mesa where trabajo_sessiones='$barril'")or die($conexion2->error());
			$fcp=$cp->FETCH(PDO::FETCH_ASSOC);
			$disp=$fcp['cantidad'];
			echo "<input type='hidden' name='disp' id='disp' value='$disp'>";

		}

	}else if($op==2)
	{
		//echo "<input type='hidden' name='r_barril' id='r_barril' value='$barril'>";
	//echo "<input type='hidden' name='r_producido' id='r_producido' value='$producido'>";
	//echo "<input type='hidden' name='r_fecha' id='r_fecha' value='$fecha'>";
	echo "<input type='hidden' name='r_digitado' id='r_digitado' value='$digitado'>";
	echo "<input type='hidden' name='r_trabajado' id='r_trabajado' value='$trabajado'>";
	//echo "<input type='hidden' name='r_producto' id='r_producto' value='$producto'>";
	//echo "<input type='hidden' name='r_observacion' id='r_observacion' value='$observacion'>";

	//sacar potcentaje total del barril para hacer update en trabajo
		$cp=$conexion2->query("select ISNULL(SUM(porcentaje),0)+$porcentaje as porcentaje from trabajo_mesa  where trabajo_sessiones='$barril'")or die($conexion2->error());
		$fcp=$cp->FETCH(PDO::FETCH_ASSOC);
		$porcentajef=$fcp['porcentaje'];
		echo $porcentajef;
		$usuario=$_SESSION['usuario'];
		$conexion2->query("insert into trabajo_mesa(trabajo_sessiones,fecha,porcentaje,usuario,digitado,observacion,fecha_crea,trabajado_por) values('$barril','$fecha','$porcentaje','$usuario','$digitado','$observacion',getdate(),'$trabajado')")or die($conexion1->error());

		$conexion2->query("update trabajo set porcentaje_trabajado='$porcentajef' where sessiones='$barril'")or die($conexion2->error());
		if($porcentajef==100)
		{
			$conexion2->query("update trabajo set estado=1,trabajado_mesa='$trabajado',fecha_mesa_ingreso=getdate(),usuario_mesa='$usuario',digita_mesa='$digitado',fecha_mesa='$fecha' where sessiones='$barril'")or die($conexion2->error());
		}
		echo "<script>alert('GUARDADO CORRECTAMENTE')</script>";
		//echo "<script>location.replace('barriles_mesa.php')</script>";

	}
}
?>
</body>
</html>