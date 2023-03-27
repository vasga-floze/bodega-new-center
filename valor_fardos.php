<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
   <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
   <script>
   	$(document).ready(function()
   	{
   		
   		$(".detalle").hide();
   	}
   		
   		)
   	function enviar(e)
   	{
   		window.open('info_valor_fardo.php?id='+e);
   	}
   	function cerrar_bodegas()
   	{
   		$("#bode_emergente").hide();
   	}
   	function activabodega()
   	{
   		$("#bode_emergente").show();
   		if($("#bod0").is(':checked'))
   		{
   			//alert('si');
   		}

   	}

   	function seleciona_bodega()
   	{
   		$("#bo").val('');
   		var nume=$("#numero").val();
   		var num=0;
   		var encuentra=0;
   		//alert($("#bod"+num).val());
   		//alert(num);
   		while(num<=nume)
   		{
   			var t="#bod"+num;
   				if($(t).is(':checked'))
   				{
   					if(encuentra==0)
   					{
   						$("#bo").val($("#bo").val()+"'"+$(t).val()+"'");
   						encuentra++;

   					}else
   					{
   						$("#bo").val($("#bo").val()+",'"+$(t).val()+"'");
   					}
   				}

   			
   			
   			num++;
   		}
   			$("#bode_emergente").hide();
   	}

   	function todos()
   	{
   		//alert();
   		var num=0;
   		var numero=$("#numero").val();
   		while(num<=numero)
   		{
   			var t="#bod"+num;
   			if($("#todo").is(":checked"))
   			{
   				$(t).prop("checked",true);
   			}else
   			{
   				$(t).prop("checked",false);
   			}
   			
   			num++;
   		}
   	}
   </script>
</head>
<body>
<div class="detalle" style="width: 100%; height: 100%; margin-left: -0.5%; background-color: white; opacity: 0.5;">
	<center>
	<img src="load2.gif" style="padding-top: 5%;">
</center>
</div>

<?php
include("conexion.php");
?>
<center>
<div style="width: 80%; height: 80%; position: fixed; border: groove; border-color: white; background-color: white; margin-left: 10%; margin-top: 0%; overflow: auto; display: none;" id="bode_emergente">
	<?php
	$qb=$conexion1->query("select * from consny.BODEGA where (BODEGA like 'SM%' or BODEGA='CA00') and nombre not like '%(N)%'")or die($conexion1->error());

	

	echo "<a href='#' onclick='cerrar_bodegas()' style='float:right; margin-rigth:0.5%; position:sticky;top:0; background-color:white;'>Cerrar X</a><br><br>";
	echo "<table border='1' cellpadding='10' style='border-collapse:collapse;' width='98%'>";
	echo "<tr>
	<td><input type='checkbox'name='todo' id='todo' onclick='todos()'></td>
	<td>BODEGA</td>
	<td>NOMBRE</td>
	</tr>";
	$numero=0;
	while($fqb=$qb->FETCH(PDO::FETCH_ASSOC))
	{
		$bodes=$fqb['BODEGA'];
		echo "<tr>
		<td><input type='checkbox'name='bod$numero' id='bod$numero' value='$bodes'></td>
	<td>".$fqb['BODEGA']."</td>
	<td>".$fqb['NOMBRE']."</td>
	</tr>";
	$numero++;
	}
	echo "<input type='hidden' name='numero' id='numero' value='$numero' readonly>";
	echo "</table><br>";
	?>
<button style="float: right; position: sticky; bottom: 0;right: 0; padding: 2%;" class="boton2" onclick="seleciona_bodega()">OK</button>
</div>
</center>
<br>

<form method="POST" autocomplete="off" style="margin-top:1%; margin-left: 0%;">
	<input type="date" name="desde" class="text" style="width: 10%;" required>
	<input type="date" name="hasta" class="text" style="width: 10%;" required>
	<input type="text" name="art" placeholder="ARTICULO" class="text" style="width: 25%;">
	

<select name='familia' class='text' style='width: 10%;'>
	<option value="">FAMILIA</option>
	<?php
	$cf=$conexion1->query("select clasificacion_2 as familia  from consny.articulo where clasificacion_1!='detalle' and clasificacion_2 is not null group by clasificacion_2")or die($conexion1->error());
	while($fcf=$cf->FETCH(PDO::FETCH_ASSOC))
	{
	echo "<option>".$fcf['familia']."</option>";

	}
	?>
</select>
<input type="text" name="bo" id='bo' class="text" ondblclick="activabodega()" readonly style="width: 25%;" placeholder="BODEGAS">
	<input type="submit" name="btn" class="boton3" value="GENERAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($familia!='' and $bo!='' and $art=='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and categoria='$familia' and bodega_produccion in($bo) order by codigo,fecha
")or die($conexion2->error());
	}else if($familia!='' and $bo=='' and $art=='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and categoria='$familia' order by codigo,fecha
")or die($conexion2->error());
	}else if($familia=='' and $bo=='' and $art=='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null order by codigo,fecha
")or die($conexion2->error());
	}else if($familia=='' and $bo!='' and $art=='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and bodega_produccion in($bo) order by codigo,fecha
")or die($conexion2->error());
	}else if($familia!='' and $bo!='' and $art!='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and bodega_produccion in($bo) and subcategoria like  (SELECT '%'+REPLACE('$art',' ','%')+'%') and categoria='$familia' order by codigo,fecha
")or die($conexion2->error());
	}else if($familia=='' and $bo!='' and $art!='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null and bodega_produccion in($bo) and subcategoria like  (SELECT '%'+REPLACE('$art',' ','%')+'%') order by codigo,fecha
")or die($conexion2->error());
	}else if($familia!='' and $bo=='' and $art!='')
	{
		//echo "<script>alert('bghh')</script>";
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null  and subcategoria like  (SELECT '%'+REPLACE('$art',' ','%')+'%') and categoria='$familia' order by codigo,fecha
")or die($conexion2->error());
	}else if($familia=='' and $bo=='' and $art!='')
	{
		$c=$conexion2->query("select CATEGORIA AS FAMILIA,codigo AS ARTICULO,subcategoria as DESCRIPCION,lbs AS LIBRAS,fecha_documento as FECHA,id_registro,barra,observacion,fecha_eliminacion,bodega from registro where tipo='P' and fecha_documento between '$desde' and '$hasta' and codigo!='' and observacion!='cancelado sys' and fecha_eliminacion is null  and subcategoria like  (SELECT '%'+REPLACE('$art',' ','%')+'%') order by codigo,fecha
")or die($conexion2->error());

	}
	
	
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h2>NO ENCONTRO PRODUCCION CON LOS DATOS INGRESADOS</h2>";
	}else
	{
		echo "<br><a href='export_valores_fardos.php?desde=$desde&&hasta=$hasta&&familia=$familia&&bo=$bo&&art=$art' target='_Blank'>Exportar a Excel</a><br>";
		echo "<table border='1' style='border-collapse:collapse;' cellspadding='10' width='100%'>";
		echo "<tr>
		<td>#</td>
		<td>FECHA</td>
			<td>FAMILIA</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>PESO</td>
			<td>BODEGA PRODUCCION</td>
			<td>UNIDADES BODEGA</td>
			<td>UNIDADES TIENDA</td>
			<td>PRECIO BODEGA</td>
			<td>PRECIO TIENDA</td>
		</tr>";
		$numeros=1;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$registro=$f['id_registro'];
			$art=$f['ARTICULO'];
			$desc=$f['DESCRIPCION'];
			$peso=$f['LIBRAS'];
			$familia=$f['FAMILIA'];
			$mes=$f['FECHA'];
			//bodega producccion
			$cbp=$conexion2->query("select * from registro where id_registro='$registro'")or die($conexion2->error());
			$fcbp=$cbp->FETCH(PDO::FETCH_ASSOC);
			$bodega=$fcbp['bodega_produccion'];
			//fin bodega produccion

			$cb=$conexion1->query("select concat(bodega,': ',nombre) as bodegas from consny.bodega where bodega='$bodega'")or die($conexion1->error());
			$fcb=$cb->FETCH(PDO::FETCH_ASSOC);
			$bode=$fcb['bodegas'];

			//precio bodega
			$cd=$conexion2->query("select sum(isnull(detalle.cantidad,0)*(isnull(eximp600.consny.articulo.precio_regular,0))) as precio from detalle inner join EXIMP600.consny.articulo on detalle.articulo=EXIMP600.consny.ARTICULO.ARTICULO where detalle.registro='$registro'
")or die($conexion2->error());
			$fcd=$cd->FETCH(PDO::FETCH_ASSOC);
			$precio_bodega=$fcd['precio'];
			//fin precio bodega
			//unidades de produccion y tienda
			$cup=$conexion2->query("select sum(cantidad) as cantidad from detalle where registro='$registro'")or die($conexion2->error());
			$fcup=$cup->FETCH(PDO::FETCH_ASSOC);
			$unidades_bodega=$fcup['cantidad'];
			$cut=$conexion2->query("select sum(cantidad) as cantidad from desglose where registro='$registro'")or die($conexion2->error());
			$fcut=$cut->FETCH(PDO::FETCH_ASSOC);
			$unidades_tienda=$fcut['cantidad'];
			if($unidades_bodega=='' or $unidades_bodega<=0)
			{
				$qu=$conexion2->query("select * from registro where id_registro='$registro'")or die($conexion2->error());
				$fqu=$qu->FETCH(PDO::FETCH_ASSOC);
				$unidades_bodega=$fqu['und'];
			}
			if($unidades_tienda=='')
			{
				$unidades_tienda="0.00";
			}

			//fin unidades bodega y tienda

			//precio desglose

			$cdes=$conexion2->query("select sum(isnull(desglose.cantidad,0)*isnull(desglose.precio,0)) as precio from desglose  where registro='$registro'")or die($conexion2->error());
			$fcdes=$cdes->FETCH(PDO::FETCH_ASSOC);
			$precio_tienda=$fcdes['precio'];
			//fin precio desglose
			if($precio_tienda=='' or $precio_tienda==0)
			{
				//precio promedio
				$cpromedio=$conexion2->query("SELECT h.codigo, sum(h.expr1) CANT_FARDOS, sum(h.precio_total) TOTAL_PRECIO_DESGLOSE, Round(sum(h.precio_total)/sum(h.expr1),0) PRECIO_PROMEDIO from
(SELECT        E.PRECIO_TOTAL, COUNT(registro_1.codigo) AS Expr1, E.codigo
FROM            (SELECT        desglose.registro, registro.codigo, SUM(desglose.precio * desglose.cantidad) AS PRECIO_TOTAL
                          FROM            desglose INNER JOIN
                                                    registro ON desglose.registro = registro.id_registro
where registro.codigo='$art'
                          GROUP BY registro.codigo, desglose.registro) AS E INNER JOIN
                         registro AS registro_1 ON E.registro = registro_1.id_registro AND E.codigo = registro_1.codigo
GROUP BY E.PRECIO_TOTAL, E.registro, E.codigo
)as H
group by h.codigo
ORDER BY 1")or die($conexion2->error());
				$numero=$cpromedio->rowCount();
			$fcpromedio=$cpromedio->FETCH(PDO::FETCH_ASSOC);
			$precio_tienda=$fcpromedio['PRECIO_PROMEDIO'];
			//fin precio promedio
			if($numero==0)
			{
				$precio_tienda='0';	
			}
			}
			if($precio_bodega=='')
			{
				$precio_bodega='0.00';
			}
			echo "<tr class='tre' style='cursor:pointer;' onclick='enviar($registro)'>
			<td>$numeros</td>
			<td>$mes</td>
			<td>$familia</td>
			<td>$art</td>
			<td>$desc</td>
			<td>$peso</td>
			<td>$bode</td>
			<td>$unidades_bodega</td>
			<td>$unidades_tienda</td>
			<td>$precio_bodega</td>
			<td>$precio_tienda</td>
		</tr>";
		$numeros++;

		}
	}
}
?>
</body>
</html>