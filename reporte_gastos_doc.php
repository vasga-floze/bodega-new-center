<!DOCTYPE html>
<html>
<head>
	<title></title>	
<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#caja").hide();
		})

		function bodegas()
		{
			$("#cajas").show();
		}
		function cerrar()
		{
			$("#cajas").hide();
		}
		function seleciona(e)
		{
			//alert(e);
			if($("#linea"+e).is(":checked"))
			{
				$("#linea"+e).prop("checked", false);
			}else
			{
				$("#linea"+e).prop("checked", true);
			}
			
		}

		function selecciontoda()
		{
			var num=$("#num").val();
			//alert(num);
			var n=0;
			while(n<=num)
			{
				if($("#toda").is(":checked"))
				{
					$("#linea"+n).prop("checked", true);
				}else
				{
					$("#linea"+n).prop("checked", false);
				}
				n++;
			}
			//alert(n);
		}
		function finalbodega()
		{
		var num=$("#num").val();
		var numero=0;
		var k=0;
		$("#bodega").val('');
		while(numero<=num)
		{
			if($("#linea"+numero).is(":checked"))
			{
				if(k==0)
				{
					k=1;
					$("#bodega").val("'"+$("#linea"+numero).val()+"'");
				}else
				{
					$("#bodega").val($("#bodega").val()+",'"+$("#linea"+numero).val()+"'");
				}

			}
			numero++;
		}
		$("#cajas").hide();
	}

	function vaciar()
	{
		$("#bodega").val('');
	}

	</script>
</head>
<body>
<?php
include("conexion.php");
error_reporting(0);
?>
<div id="caja" style="position: fixed; width: 100%; height: 100%; background-color: white ">
	<img src="loadf.gif" style="width: 25%; height: 25%; margin-left: 45%; margin-top: 5%;"/>
	
</div>

<?php
	echo "<div style='position:fixed; width:100%; height:120%; background-color: #a4a2a1; margin-top:-5%; display:none;' id='cajas'><br>";
	echo "<button style='float: right; margin-right: 0.5%; ; color:white; cursor:pointer; padding:0.5%;' onclick='cerrar()'>X</button>";
	echo "<div style='background-color:white; width:90%; height:90%; overflow:auto;'>";

	$cb=$conexion1->query("select bodega,nombre from consny.bodega where bodega!='CA00' and nombre not like '%(N)%' and bodega not like 'SM%' ")or die($conexion1->error());
	echo "<table border='1' style='border-collapse :collapse ; border-color:black; width:100%; height:100%; margin-bottom:3%; margin-top:-5%;' cellspadding='10'>";
	echo "<tr>
		<td><input type='checkbox' name='toda' id='toda' onclick='selecciontoda()'></td>
		<td>BODEGA</td>
		<td>NOMBRE</td>
	</tr>";
	$n=0;
	while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
	{
		$bode=$fcb['bodega'];
		$nom=$fcb['nombre'];
		echo "<tr class='tre' onclick='seleciona($n)' style='cursor:pointer;'>
		<td><input type='checkbox' name='linea$n' id='linea$n' value='$bode'></td>
		<td>$bode</td>
		<td>$nom</td>
	</tr>";
	$n++;
	}
echo"<button class='boton2' style='float:right; margin-right:0.5%; position:sticky; top:35%; padding:1%;' onclick='finalbodega()'>OK</button>";
echo "<input type='hidden' name='num' id='num' value='$n'>";
	echo "</table><br><br>
<br>

	</div></div>";
//------------------revisar todas las piezas de la ca02 desde el 27-9-21(desgloses, aaverias,)


?>

<h3 style="text-align: center; text-decoration: underline;">REPORTE DE DOCUMENTOS(GASTOS)</h3>


<form method="POST" id="form" name="form" autocomplete="off">
<input type="text" name="bodega" id="bodega" required class="text" style="width: 40%;" placeholder="bodegas" ondblclick="bodegas()" onkeyup="vaciar()"> 
<input type="date" name="desde" id="desde" class="text" style="width: 10%;">

<input type="date" name="hasta" id="hasta" class="text" style="width: 10%;">
<label><input type="checkbox" name="tarjeta" value="1">INCLUIR VENTA CON TARJETA/BITCOIN</label>
<input type="submit" name="btn" value="GENERAR" class="boton3" >
<input type="hidden" name="op" id="op" readonly>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($tarjeta==1)
	{

		$c=$conexion1->query("SELECT        CUADRO_VENTA.BODEGA, consny.BODEGA.NOMBRE, CUADRO_VENTA.FECHA, CUADRO_VENTA.ESTADO, CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO, CUADRO_VENTA_DETALLE.CONCEPTO, 
                         CUADRO_VENTA_DETALLE.MONTO
FROM            CUADRO_VENTA INNER JOIN
                         CUADRO_VENTA_DETALLE ON CUADRO_VENTA.ID = CUADRO_VENTA_DETALLE.CUADRO_VENTA INNER JOIN
                         consny.BODEGA ON CUADRO_VENTA.BODEGA = consny.BODEGA.BODEGA
WHERE   CUADRO_VENTA_DETALLE.TIPO_TRANSACCION='SALIDA' AND     CUADRO_VENTA.FECHA between '$desde' and '$hasta' and CUADRO_VENTA.BODEGA in($bodega) and CUADRO_VENTA_DETALLE.CONCEPTO not  like '%FALTANTE DE REMESA%' order by 1,3")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("SELECT        CUADRO_VENTA.BODEGA, consny.BODEGA.NOMBRE, CUADRO_VENTA.FECHA, CUADRO_VENTA.ESTADO, CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO, CUADRO_VENTA_DETALLE.CONCEPTO, 
                         CUADRO_VENTA_DETALLE.MONTO
FROM            CUADRO_VENTA INNER JOIN
                         CUADRO_VENTA_DETALLE ON CUADRO_VENTA.ID = CUADRO_VENTA_DETALLE.CUADRO_VENTA INNER JOIN
                         consny.BODEGA ON CUADRO_VENTA.BODEGA = consny.BODEGA.BODEGA
WHERE    CUADRO_VENTA_DETALLE.TIPO_TRANSACCION='SALIDA' AND    CUADRO_VENTA.FECHA between '$desde' and '$hasta' and CUADRO_VENTA.BODEGA in($bodega) and CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO!='VENTA CON TARJETA' and CUADRO_VENTA_DETALLE.TIPO_DOCUMENTO!='BITCOIN' and CUADRO_VENTA_DETALLE.CONCEPTO not  like '%FALTANTE DE REMESA%' order by 1,3")or die($conexion1->error());
	}

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO INFORMACION CON EL FILTRO DADO</h3>";
	}else
	{
		$bodega=str_replace("'", "-", $bodega);
		echo "<br><a href='export_reporte_gastos_doc.php?bodega=$bodega&&d=$desde&&h=$hasta&&t=$tarjeta' target='_blank'>Exportar a Excel</a>";
		echo "<table border='1' style='border-collapse:collapse; width:98%;'>";
		echo "<tr>
		<td>BODEGA</td>
		<td>NOMBRE</td>
		<td>FECHA</td>
		<td>ESTADO</td>
		<td>TIPO DOCUMENTO</td>
		<td>CONCEPTO</td>
		<td>MONTO</td>
		</tr>";
		$total=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
				<td>".$f['BODEGA']."</td>
				<td>".$f['NOMBRE']."</td>
				<td>".$f['FECHA']."</td>
				<td>".$f['ESTADO']."</td>
				<td>".$f['TIPO_DOCUMENTO']."</td>
				<td>".$f['CONCEPTO']."</td>
				<td>".$f['MONTO']."</td>
				</tr>";
				$total=$total+$f['MONTO'];
		}

		echo "<tr>
		<td COLSPAN='6'>TOTAL</td>
		<td>$total</td>
		</tr>";
		echo "</table>";
	}
}
?>
</body>
</html>