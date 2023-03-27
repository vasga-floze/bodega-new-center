<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
		.text{
			padding: 1%;
		}
		.ter
		{
			background-color: white;
			color: black;
		}
		.ter:hover
		{
			background-color: rgb(0,0,0,0.3);
			color: white;
			cursor: pointer;
		}
	</style>

	<script>
		$(document).ready(function(){
			$("#caja").hide();
			var ar=$("#ar").val();
			var de=$("#de").val();
			var pre=$("#pre").val();
			$("#articulo").val(ar);
			$("#descripcion").val(de);
			$("#precio_actual").val(pre);

			if($("#mantener").val()==1)
			{
				$(".detalle").show();
			}
			if($("#error").val()==1)
			{
				$("#form").hide();
				$(".detalle").hide();
			}else
			{
				$("#form").show();
			}
		})

		function cerrar()
		{
			$(".detalle").hide();
		}
		function abrir()
		{
			$(".detalle").show();
			$("#opc").val(1);
			$("#formart").submit();
		}
		function seleccion(e)
		{
			var articulos="#art"+e;
			var descripciones="#desc"+e;
			//alert(articulos);
			var precios="#precio"+e;
			//alert($(precios).val());
			$("#descripcion").val($(descripciones).val());
			$("#articulo"). val($(articulos).val());
			$("#precio_actual").val($(precios).val());
			//alert($(articulos).val());
			$(".detalle").hide();
		}
		function envio()
		{
			//alert();
			$("#op").val('1');
			$("#form").submit();
		}
		function finalizar()
		{
			//alert($("#precio_nuevo").val());
			if($("#precio_nuevo").val()<$("#precio_actual").val())
			{
				alert('CAMBIO DE PRECIO NO VALIDO');
				$("#form").submit(false);
			}else
			{
				$("#op").val('2');
				a$("#form").submit(true);
			}
		}
	</script>
</head>
<body>
<div id="caja" style="background-color: white; width: 100%; height: 100%; position: fixed; margin-left: -0.5%; margin-top: -0.5%; opacity: 0.5;">
<img src="load1.gif" style="margin-top: 10%; margin-left: 40%;">
</div>
<?php
include("conexion.php");
?>

<?php
$usuario=$_SESSION['usuario'];
$cusu=$conexion1->query("select * from usuariobodega where usuario='$usuario'")or die($conexion1->error());
$fcusu=$cusu->FETCH(PDO::FETCH_ASSOC);
$hamachi=$fcusu['HAMACHI'];
$bd=$fcusu['BASE'];

try {
	$conexion_tienda=new PDO("sqlsrv:Server=$hamachi;Database=$bd", "sa", "$0ftland");
	$error=0;
	} 
catch (Exception $e) {
	echo "AL PARECER TIENES APAGADO HAMACHI, DEBES ENCENDERLO Y ACTUALIZAR";

	$error=1;
}
echo "<input type='hidden' id='error' value='$error'>";
error_reporting(0);
?>
<div class="detalle" style="display: none; margin-top: -4%;">
	<a href="#" style="color: white; text-decoration: none; float: right; margin-right: 1%; " onclick="cerrar()">CERRAR X</a><br>
	<div class="adentro" style="margin-left: 2.5%; height: 93%; ">

		<form method="POST" id="formart" style="margin-left: 2%;">
			<input type="hidden" name="opc" id="opc">
			<input type="text" name="b" style="width: 35%; padding: 0.5%;" placeholder="ARTICULO O DESCRIPCION">
			<input type="submit" name="btn" id="bnt" value="BUSCAR" class="boton2">
		</form>
		<?php
		extract($_REQUEST);
		//echo "<script>alert('$opc')</script>";
		//falta que carge al modtrar detalle
		if($_POST and $opc==1 or $btn=='BUSCAR')
		{
			//echo "<script>alert('BUSQUEDA')</script>";
			echo "<input type='hidden' name='mantener' id='mantener' value='1'>";
			
			$c=$conexion_tienda->query("select consny.articulo.articulo,consny.articulo.descripcion,CONVERT(DECIMAL(10,2),consny.articulo_precio.precio) as precio from consny.articulo inner join consny.articulo_precio on consny.articulo.articulo=consny.articulo_precio.articulo where  (consny.articulo.articulo='$b' or consny.articulo.descripcion LIKE (SELECT '%'+REPLACE('$b',' ','%')+'%')) and consny.articulo.clasificacion_1!='DETALLE' and consny.articulo_precio.nivel_precio='REGULAR'
")or die($conexion_tienda->error());
		
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO NINGUN ARTICULO, VALIDO PARA CAMBIAR PRECIO</h3>";
		}else
		{
			echo "<table border='1' style='width:98%; border-collapse:collapse; margin-left:1%; margin-top:2%;' cellpadding='5'>";
			echo "<tr>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
				<td>PRECIO</td>
			</tr>";
			$n=0;
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$art=$f['articulo'];
				$desc=$f['descripcion'];
				$precio=$f['precio'];
				echo "<tr onclick='seleccion($n)' class='ter'>
				<td>$art
				<input type='hidden' name='art' id='art$n' value='$art'>

				</td>
				<td>$desc
				<input type='hidden' name='desc$n' id='desc$n' value='$desc'>
				</td>
				<td>$precio
				<input type='hidden' name='precio' id='precio$n' value='$precio'>
				</td>
			</tr>";
			$n++;
			}
			echo "</table>";
			echo "<input type='hidden' name='n' id='num' value='$n'>";
		}
	}


		
		?>


	</div>

</div>
	<center>
	<form method="POST" name="form" id="form" style="border: groove; border-color: blue; background-color: white; color: black; width: 50%; padding-bottom: 2%; margin-top: 5%;">
		<label>ARTICULO:<br>
		<input type="text" name="articulo" id="articulo" class="text" ondblclick="abrir()" onchange="envio()"></label>
		<br><br>

		<input type="hidden" name="op" id="op">

		<label>DESCRIPCION:<br>
		<input type="text" name="descripcion" id="descripcion" class="text" required readonly></label>

		<br><br>
		<label>PRECIO ACTUAL:<br>
		<input type="number" step="any" class="text" name="precio_actual" id="precio_actual" class="text"></label>

		<br><br>
		<label>PRECIO NUEVO:<br>
		<input type="number" step="any" class="text" name="precio_nuevo" id="precio_nuevo"></label>
		<br><br>
		<label>REFERENCIA:<br>
		<input type="text" step="any" class="text" name="ref" id="ref" required></label>
		<br><br>
		<label>FECHA:<br>
		<input type="date" step="any" class="text" name="fecha" id="fecha" value="<?php echo date('Y-m-d')?>" required></label>
		<br><br>
		<input type="submit" name="btn" value="GUARDAR CAMBIOS" class="boton3" style="background-color: white; color:black; border:groove; border-color: blue; float: right; margin-right: 1%;" onclick="finalizar()">
		<br><br>
	</form>

<?php

if($_POST)
{
	extract($_REQUEST);
	if($op==1)
	{
		$c=$conexion_tienda->query("select consny.articulo.articulo,consny.articulo.descripcion,CONVERT(DECIMAL(10,2),consny.articulo_precio.precio) as precio from consny.articulo inner join consny.articulo_precio on consny.articulo.articulo=consny.articulo_precio.articulo where consny.articulo.articulo='$articulo' and consny.articulo.clasificacion_1!='DETALLE' and consny.articulo_precio.nivel_precio='REGULAR'
")or die($conexion_tienda->error());

$n=$c->rowCount();
if($n==0)
	{
		echo "<script>alert('NO SE ENCONTRO ARTICULO VALIDO PARA CAMBIO DE PRECIO')</script>";
		echo "<script>location.replace('precios_articulos.php')</script>";
	}else
	{
		$f=$c->FETCH(PDO::FETCH_ASSOC);
		$ar=$f['articulo'];
		$de=$f['descripcion'];
		$pre=$f['precio'];
		echo "<input type='hidden' name='ar' id='ar' value='$ar'>";
		echo "<input type='hidden' name='de' id='de' value='$de'>";
		echo "<input type='hidden' name='pre' id='pre' value='$pre'>";


	}


	}else if($op==2)
	{
		//echo "<script>alert('$articulo $precio_nuevo')</script>";

		$conexion_tienda->query("update  consny.articulo_precio set precio='$precio_nuevo' where articulo='$articulo' and nivel_precio='REGULAR'")or die($conexion_tienda->error());
		$user=$_SESSION['usuario'];
		$paq=$_SESSION['paquete'];
		$conexion2->query("insert into PRECIOS_ARTICULOS(articulo,precio_actual,precio_nuevo,usuario,paquete,referencia,fecha,fecha_hora_crea) values('$articulo','$precio_actual','$precio_nuevo','$user','$paq','$ref','$fecha',getdate())")or die($conexion2->error());
		echo "<script>alert('CAMBIO GUARDADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('precios_articulos.php')</script>";
	}
}
?>

</body>
</html>