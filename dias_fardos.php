<!DOCTYPE html>
<html>
<head>
	<title></title>


	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".detalle").hide();
		})

		function cerrar()
		{
			$("#bodegas").hide();
		}
		function activar()
		{
			//alert();
			$("#bodegas").show();
		}

		function seleccion()
		{
			//alert();
			var numero=0;
			var num=$("#num").val();
			var k=0;
			$("#bodega").val('');
			while(numero<=num)
			{
				var t="#seleciona"+numero;


				if($(t).is(":checked"))
				{
					if(k==0)
					{
						$("#bodega").val("'"+$(t).val()+"'");
						k=1;
					}else
					{
						$("#bodega").val($("#bodega").val()+",'"+$(t).val()+"'");
					}
				}
				numero++;
			}
			$("#bodegas").hide();
		}
		function todo()
		{
			
				var numeros=0;
				var num=$("#num").val();
				while(numeros<=num)
				{
					var t="#seleciona"+numeros;
					if($("#todas").is(":checked"))
					{
					$(t).prop('checked',true);

					}else 
					{

						$(t).prop('checked',false);
					}
					numeros++;

				}
			
		}
		

	</script>
</head>
<body style="">
<div class="detalle" style="background-color: white; margin-left: -5%; opacity: 0.4;">
<img src="loadf.gif" style="margin-left: 50%; margin-top: 10%; background-color: rgb(0,0,0,0.5);">
	
</div>

<?php
include("conexion.php");
?>
<div id="bodegas" style="position: fixed; width: 50%; height: 70%; float: center; background-color: white; margin-left: 15%; border: groove; border-color: blue; overflow: auto; display: none;">
	<a href="#" style="color: black; float: right; margin-right: 0.5%; " onclick="cerrar()">X</a><br>
<?php
if($_SESSION['tipo']==2)
{
	$bod=$_SESSION['bodega'];
	$cb=$conexion1->query("select * from consny.bodega where bodega='$bod'")or die($conexion1->error());
}else
{
	$cb=$conexion1->query("select * from consny.bodega where bodega not like 'SM%' and bodega!='CA00' and nombre not like '%(N)%' order by nombre")or die($conexion1->error());
}

echo "<table border='1' style='border-collapse:collapse; width:95%; margin-left:2.5%;'>";
echo "<tr>
		<td><input type='checkbox' name='todas' id='todas' onclick='todo()'></td>
		<td>BODEGA</td>
		<td>NOMBRE</td>
</tr>";
$n=0;
while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
{
	$bode=$fcb['BODEGA'];
	echo "<tr>
		<td><input type='checkbox' id='seleciona$n' name='seleciona$n' value='$bode'></td>
		<td>".$fcb['BODEGA']."</td>
		<td>".$fcb['NOMBRE']."</td>
</tr>";
$n++;
}
echo "<input type='hidden' value='$n' name='num' id='num'>";
echo "</table><hr>";
?>
<button class="boton2" style="position: sticky; bottom: 0; right: 0; padding: 2%; float: right;" onclick="seleccion()">OK</button>
</div>
<h3 style="font-family: cursive; text-align: center;">REPORTE TIEMPO FARDOS EN TIENDAS</h3>
<form method="POST" autocomplete="off">
<input type="text" name="bodega" id="bodega" placeholder="BODEGA" class="text" style="width: 40%;" ondblclick="activar()" required>
<input type="submit" name="btn" class="boton3" value="GENERAR">	
</form>

<?php
if($_POST)
{
	extract($_REQUEST);

	$c=$conexion2->query("select concat(EXIMP600.consny.BODEGA.bodega,':',EXIMP600.consny.BODEGA.nombre) as bodega,registro.barra,
registro.fecha_traslado,concat(EXIMP600.consny.articulo.articulo,':',eximp600.consny.articulo.descripcion) as art,
datediff(day,registro.fecha_traslado,GETDATE()) as dias,registro.fecha_desglose,registro.activo,
(isnull(registro.lbs,0)+isnull(registro.peso,0)) as peso,registro.barra,eximp600.consny.articulo.clasificacion_2 as familia from registro inner join eximp600.consny.bodega
on EXIMP600.consny.BODEGA.BODEGA=registro.bodega inner join eximp600.consny.articulo on registro.codigo=
eximp600.consny.articulo.articulo where registro.bodega  in($bodega) and 
(registro.fecha_desglose is  null or registro.fecha_desglose='') and registro.activo is null   order by dias desc")or die($conexion2->error());

	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN FARDO DISPONIBLE</h3>";
	}else
	{
		$bodega=str_replace("'",'-', $bodega);
		$bod=base64_encode($bodega);
		echo "<a href='export_dias_fardos.php?bodega=$bod' target='_blank'>EXPORTAR A EXCEL</a>";
		echo "<table border='1' style='border-collapse:collapse; width:100%;'>";

		echo "<tr>
			<td>#</td>
			<td>CODIGO BARRA</td>
			<td>FECHA RECIBIDO</td>
			<td>BODEGA</td>
			<td>FAMILIA</td>
			<td>ARTICULO</td>
			<TD>PESO</TD>
			<TD>DIAS EN TIENDA</TD>
		</tr>";
		$k=1;
		$tpeso=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
			<td>$k</td>
			<td>".$f['barra']."</td>
			<td>".$f['fecha_traslado']."</td>
			<td>".$f['bodega']."</td>
			<td>".$f['familia']."</td>
			<td>".$f['art']."</td>
			<TD>".$f['peso']."</TD>
			<TD>".$f['dias']."</TD>
		</tr>";
		$tpeso=$tpeso+$f['peso'];
		$k++;
		}

		echo "<tr>
			<td colspan='6'>TOTAL</td>
			<td>$tpeso</td>
			<td></td>
		</tr>";
	}
}
?>
</body>
</html>