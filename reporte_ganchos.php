<!DOCTYPE html>
<html>
<head>
	<title>NYC</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
		$("#load").hide();
		})

		function bodegas()
		{
			$("#contebodegas").show();
		}

		function cerrar()
		{
			$("#contebodegas").hide();
		}

		function selecciona(e)
		{
			if($("#lineabodega"+e).is(':checked'))
			{
				//alert();
				$("#lineabodega"+e).prop('checked',false);
			}else
			{
				$("#lineabodega"+e).prop('checked',true);

			}
			
		}

		function finalseleccion(e)
		{
			var num=0;
			while(num<=e)
			{
				if($("#lineabodega"+num).is(":checked"))
				{
					var t=$("#lineabodega"+num).val();
					if($("#bodega").val()=='')
					{
						
						$("#bodega").val("'"+t+"'");
					}else
					{
						$("#bodega").val($("#bodega").val()+",'"+t+"'");
					}
					
				}
				num++;
			}
			$("#contebodegas").hide();
		}

		function activartodas()
		{
			var n=$("#cantidadbodega").val();
		var num=0;
		while(num<=n)
		{
			if($("#todas").is(":checked"))
			{
				$("#lineabodega"+num).prop('checked',true);
				//var k=1;
			}else
			{
				//var k=2;
				$("#lineabodega"+num).prop('checked',false);
			}
			num++;
		}
		//alert(k);
		}
		

	</script>
</head>
<body>
<div style="background-color: white; width: 100%; height: 100%; position: fixed;" id="load">
	<img src="loadf.gif" style="margin-left: 38%; margin-top: 15%;">
</div>
<?php
include("conexion.php");
?>
<form method="POST">
<input type="text" name="bodega" id="bodega" class="text" style="width: 40%;" readonly required placeholder="BODEGAS" ondblclick="bodegas()">
<input type="date" name="desde" class="text" style="width: 20%;" required>
<input type="date" name="hasta" class="text" style="width: 20%;" required>
<input type="submit" name="btn" value="GENERAR" class="boton3" style="padding: 0.8%;">

</form>
<div style="background-color: white; width: 60%; height: 80%; overflow: auto; border: groove; border-color: blue; margin-top: -5%; position: fixed; margin-left: 15%; display: none;" id="contebodegas">
<button style="background-color: red; color: white; float: right; margin-right: 0.5%; position: sticky; top: 0; right: 0; cursor: pointer;" onclick="cerrar()">X</button>
<br>
	<?php
		$cb=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%' and bodega not like 'SM%' and bodega!='CA00' order by nombre")or die($conexion1->error());

		echo "<table border='1' style='border-collapse:collapse; width:98%;' cellspadding='8'>";
		echo "<tr>
			<td><input type='checkbox' name='todas' id='todas' onclick='activartodas()'></td>
			<td>BODEGA</td>
			<td>NOMBRE</td>
		</tr>";
		$n=0;
		while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
		{
			$bod=$fcb['BODEGA'];
			echo "<tr onclick='selecciona($n)' style='cursor:pointer;' class='tre'>";
			echo "<td><input type='checkbox' name='lineabodega$n' id='lineabodega$n' value='$bod'></td>";
			echo "<td>$bod</td><td>".$fcb['NOMBRE']."</td></tr>";
			$n++;
		}
		echo "</table>";
		echo "<input type='hidden' name='cantidadbodega' id='cantidadbodega' value='$n'>";
		echo "<button class='boton2' style='float:right; position:sticky; bottom:0; right:0; padding:1%;' onclick='finalseleccion($n)'>OK</button>";
	?>
</div>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($bodega=='')
	{
		$c=$conexion2->query("select traslado.fecha,EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,COUNT(EXIMP600.consny.ARTICULO.CLASIFICACION_2) as cantidad,concat(EXIMP600.consny.bodega.bodega,': ',EXIMP600.consny.bodega.nombre) as destino from traslado inner 
join EXIMP600.consny.ARTICULO on traslado.articulo=EXIMP600.consny.ARTICULO.ARTICULO
inner join EXIMP600.consny.bodega on traslado.destino=EXIMP600.consny.bodega.bodega
 where traslado.destino!='CA00' and destino not like 'SM%' and
EXIMP600.consny.ARTICULO.DESCRIPCION like '%ganc%' 
and traslado.fecha between '$desde' and '$hasta'
group by traslado.fecha,EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,concat(EXIMP600.consny.bodega.bodega,': ',EXIMP600.consny.bodega.nombre) order by
traslado.fecha,concat(EXIMP600.consny.bodega.bodega,': ',EXIMP600.consny.bodega.nombre)")or die($conexion2->error());
	}else
	{
		$c=$conexion2->query("select traslado.fecha,EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,COUNT(EXIMP600.consny.ARTICULO.CLASIFICACION_2) as cantidad,concat(EXIMP600.consny.bodega.bodega,': ',EXIMP600.consny.bodega.nombre) as destino from traslado inner 
join EXIMP600.consny.ARTICULO on traslado.articulo=EXIMP600.consny.ARTICULO.ARTICULO
inner join EXIMP600.consny.bodega on traslado.destino=EXIMP600.consny.bodega.bodega
 where traslado.destino!='CA00' and destino not like 'SM%' and
EXIMP600.consny.ARTICULO.DESCRIPCION like '%ganc%' and traslado.destino in($bodega)
and traslado.fecha between '$desde' and '$hasta'
group by traslado.fecha,EXIMP600.consny.ARTICULO.ARTICULO,EXIMP600.consny.ARTICULO.DESCRIPCION,concat(EXIMP600.consny.bodega.bodega,': ',EXIMP600.consny.bodega.nombre) order by
traslado.fecha,concat(EXIMP600.consny.bodega.bodega,': ',EXIMP600.consny.bodega.nombre)")or die($conexion2->error());
	}

	$n=$c->rowCOUNT();
	if($n==0)
	{
		echo "<H3>NO SE ENCONTRO NINGUN MOVIMIENTOS DE GANCHOS...</h3>";
	}else
	{
		echo "<table border='1' style='border-collapse:collapse;' width='98%'>
		<tr>
		<td>FECHA</td>
		<td>BODEGA</td>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		</tr>";
		$total=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			echo "<tr>
		<td>".$f['fecha']."</td>
		<td>".$f['destino']."</td>
		<td>".$f["ARTICULO"]."</td>
		<td>".$f["DESCRIPCION"]."</td>
		<td>".$f['cantidad']."</td>
		</tr>";
		$total=$total+$f['cantidad'];
		}

		echo "<tr>
		<td colspan='4'>TOTAL</td>
		<td>$total</td>
		</tr>";
	}

}
?>
</body>
</html>