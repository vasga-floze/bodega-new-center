<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function(){
			//$("#re1").attr('checked',true);
		})
		function enviar()
		{
			$("#form").submit();
		}
		function selecionas()
		{
			//alert($("#op").val());
			var num=$("#num").val();
			var n=0;
			if($("#op").val()=='1')
			{
				//alert('verdad');
				var n=0;
				//alert($("#re2").val());
				while(n<=num)
				{
					//alert(num+"-"+n);
					var nom="#re"+n;
					//alert(nom);
					//$(nom).attr('checked',false);

					$(nom).prop('checked',true);
					n++;
				}
				$("#op").val(2);
				
			}else
			{
				//alert('falso');
				
				var n=0;
				//alert(""+num+"-"+n+"");
				while(n<=num)
				{
					
					var nom="#re"+n;
					$(nom).attr('checked',false);
					n++;
				}

				$("#op").val(1);

			}
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
if($_SESSION['usuario']=='MFUENTES' or $_SESSION['usuario']=='MFUENTES' or $_SESSION['usuario']=='staana3' )
{

}else
{
	echo "<script>alert('OPCION UNICAMENTRE HABILITADO PARA EL USUARIO: MFUENTES')</script>";
	echo "<script>location.replace('conexiones.php')</script>";
}
?>
<form method="POST" name="form" id="form">
	FECHA PRODUCCCION: <input type="date" name="fecha" onchange="enviar()" class="text" style="width: 20%;">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	$c=$conexion2->query("select * from registro where activo is null and bodega='SM00'  and fecha_documento='$fecha' and tipo='p' and codigo!='' and id_registro not in(select registro from traslado where estado=0) and id_registro not in(select registro from detalle_mesa) and id_registro not in(select registro from venta where registro is not null) and fecha_traslado is null order by codigo,subcategoria")or die($conexion2->error());
	$n=$c->rowCount();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO PRODUCCCION DISPONIBLE PARA TRASLADO DE LA FECHA: $fecha</h3>";
	}else
	{
		echo "<form method='POST' action='final_traslado_multiple.php'>";
		echo "<input type='hidden' name='fecha' value='$fecha'>";
		echo "<input type='hidden' name='op' id='op' value='1'>";
		echo "<table border='1' style='border-collapse:collapse; margin-top:1%; width:100%;'>
			<tr>
				<td><label style=' cursor:pointer;'><input type='checkbox' name='ree1' id='ree1' onclick='selecionas()' >SELECCIONAR</label></td>
				<td>ARTICULO</td>
				<td>DESCRIPCION</td>
				<td>PESO</td>
			</tr>";
			$num=0;
			 while($f=$c->FETCH(PDO::FETCH_ASSOC))
			 {
			 	$id=$f['id_registro'];
			 	echo "<tr style='text-align:left;'>
					<td><label><input type='checkbox' name='re[$num]' id='re$num' value='$id'>".$f['barra']."</label></td>
					<td>".$f['codigo']."</td>
					<td>".$f['subcategoria']."</td>
					<td>".$f['lbs']."</td>
				</tr>";
				$num++;
			 }

			 echo "<tr>
			 	<td colspan='4'>
			 	<input type='hidden' name='num' id='num' value='$num'>
			 	<input type='submit' value='SIGUIENTE' class='boton3' style='float:right; margin-right:1.5%;'></form>
			 	</td>
			 		</tr>";
	}
}
?>
</body>
</html>