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
		function personal()
		{
			//alert();
			$(".detalle").show();
		}
		function cerrar()
		{
			$(".detalle").hide();
		}
		function SELECCION(e)
		{
			 var texto=$("#t"+e).text();
			 $("#persona").val(texto);
			 $(".detalle").hide();
		}
	</script>
</head>
<body>
	<div id="caja" style="position: fixed; background-color: white;  width: 100%; height: 100%;">
		<center>
		<img src="loadf.gif" style="margin-left: 5%; margin-top: 8%;">
	</center>
	</div>
	<?php
	include("conexion.php");
	?>
	<div class="detalle" style="margin-top: -3%; display: none;">
		<a href="#" onclick="cerrar()" style="float: right; margin-right: 0.5%; text-decoration: none; color:white;">Cerrar X</a><br>
		<div class="adentro" style="margin-left: 2.5%; height: 92%; padding-left: 2%;">
		<?php
		$cp=$conexion1->query("select * from PRODUCCION_ACCPERSONAL where PRODUCE=1 and activo=1")or die($conexion1->error());
		$num=0;
		while($fcp=$cp->FETCH(PDO::FETCH_ASSOC))
		{
		echo"<h3 onclick='SELECCION($num)' id='t$num' style='cursor:pointer;' class='tre'>".$fcp['NOMBRE']."</h3>";
		$num++;

		}
		?>


		</div>
	</div>
	<form method="POST" autocomplete="off">
<input type="text" name="persona" id="persona" class="text" class="text" style="width: 35%;" placeholder="PERSONA" ondblclick="personal()">
<input type="date" name="desde" class="text" class="text" style="width: 20%;" required>
<input type="date" name="hasta" class="text" class="text" style="width: 20%;" required>
<select name="tipo" required class="text" style="width: 10%;">
	<option value="">TIPO TRANSACCION</option>
	<option value="1">PRODUCCION</option>
	<option value="2">TRABAJADO</option>
</select>
<input type="submit" name="btn" value="GENERAR" class="boton2">
</form>

<?php
if($_POST)
{
	extract($_REQUEST);
	if($tipo==1)
	{
		//produccion
		$c1=$conexion2->query("select  articulos from trabajo where producido like '%$persona%'and fecha between '$desde' and '$hasta' group by articulos
")or die($conexion2->error());
		$c2=$conexion2->query("select  producido as persona from trabajo where producido like '%$persona%'and fecha between '$desde' and '$hasta' group by producido
")or die($conexion2->error());
		$tipo='PRODUCIDO';
	}else
	{
		//mesa
		$c1=$conexion2->query("select  articulos from trabajo where trabajado_mesa like '%$persona%'and fecha_mesa between '$desde' and '$hasta' group by articulos
")or die($conexion2->error());
		$c2=$conexion2->query("select  producido as persona from trabajo where trabajado_mesa like '%$persona%'and fecha_mesa between '$desde' and '$hasta' group by producido
")or die($conexion2->error());
		$tipo='TRABAJADO';
	}
	//FIIN QUERYS
	$td='';
	$ntd=0;
	$numero=0;
	while($fc1=$c1->FETCH(PDO::FETCH_ASSOC))
	{
		$td.="<td>".$fc1['articulos']."</td>";
		$ntd++;
		$fila[$numero]=$fc1['articulos'];
		$numero++;
	}
echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:200%;'>";
echo "<tr>
	<td>TRANSACCION</td>
	<td>PERSONAL</td>
	<td colspan='$ntd' style='text-align:center;'>PRODUCTOS</td>
	<td>TOTAL</td>
</tr>";

echo "<tr>
	<td>$tipo</td>
	<td>- - -</td>
	$td
	<td>- - -</td>
</tr>";
$totalf=0;
	while($fc2=$c2->FETCH(PDO::FETCH_ASSOC))
	{
		$personas=$fc2['persona'];
		echo"<tr><td></td><td>".$fc2['persona']."</td>";
		$num=0;
		$total=0;
		while($num<$numero)
		{
			$producto=$fila[$num];
			if($tipo=='PRODUCIDO')
			{
			$ctl=$conexion2->query("select isnull(SUM(peso),0) as total from trabajo where producido='$personas' and fecha between '$desde' and '$hasta' and articulos='$producto'")or die($conexion2->error());

		}else
		{
			$ctl=$conexion2->query("select isnull(SUM(peso),0) as total from trabajo where trabajado_mesa='$personas' and fecha_mesa between '$desde' and '$hasta' and articulos='$producto'")or die($conexion2->error());

		}
			$fctl=$ctl->FETCH(PDO::FETCH_ASSOC);
			$total=$total+$fctl['total'];
			$totalf=$totalf+$total;
			echo "<td>".$fctl['total']."</td>";

			$num++;

		}
		echo "<td>$total</td></tr>";
	}

}//FIN POST
?>
</body>
</html>