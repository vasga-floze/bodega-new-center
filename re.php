
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<script>
		$(document).ready(function(){
		
		$("#detalle").hide();
		$("#df").val($("#t").val());

		});

		function envia()
		{

			df = $("#df").val();
			alert(df);
			//$("#t").val(t);
			$("#form").submit();

		}
		function text()
		{
			var df = $("#df").val();
			if(df!='')
			{
				//$("#t").val(df);
			}
			
		}
	</script>
	<style>
  .preloader {
  width: 70px;
  height: 70px;
  border: 10px solid #eee;
  border-top: 10px solid #666;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 10s;
  animation-iteration-count: infinite;

}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
</head>
<body>
	<center>
	<div class="detalle" id="detalle" style="background-color: white; text-align: margin-top:5%;">
	<div class="preloader" id="preloader">
	</div>
	</div>
	<?php
	include("conexion.php");
	?>
	<input type="text" name="t" id="t">
	<br><br>
<form method="POST" id="form">
	<input type="text" name="df" id="df" onchange="text()"></form>
	<input type="submit" name="btn" onclick="envia()">

<?php
	if($_POST)
	{
		extract($_REQUEST);
	//secho "<script>alert('envio 2')</script>";
	$c=$conexion2->query("select * from registro where tipo='p'")or die($conexion2->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		echo "".$f['codigo']."<br>";
	}
	}else
	{
		$c=$conexion1->query("declare @fecha datetime ='2020-10-15'
declare @bodega nvarchar(4)='CA11'

SELECT        consny.DOC_POS_LINEA.DOCUMENTO, consny.DOC_POS_LINEA.TIPO, consny.DOC_POS_LINEA.ARTICULO, consny.ARTICULO.DESCRIPCION,consny.DOC_POS_LINEA.CANTIDAD, 
                         ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA * 1.13, 2) AS PRECIO, ROUND(consny.DOC_POS_LINEA.DESCUENTO_LINEA * 1.13, 2) AS DESCUENTO,
                                        (ROUND(consny.DOC_POS_LINEA.DESCUENTO_LINEA * 1.13, 2)/ ROUND(consny.DOC_POS_LINEA.PRECIO_VENTA * 1.13, 2))*100 AS PORCENTAJE, 
                                         consny.DOCUMENTO_POS.FCH_HORA_COBRO
FROM            consny.DOC_POS_LINEA INNER JOIN
                         consny.DOCUMENTO_POS ON consny.DOC_POS_LINEA.DOCUMENTO = consny.DOCUMENTO_POS.DOCUMENTO AND consny.DOC_POS_LINEA.TIPO = consny.DOCUMENTO_POS.TIPO AND 
                         consny.DOC_POS_LINEA.CAJA = consny.DOCUMENTO_POS.CAJA INNER JOIN
                         consny.ARTICULO ON consny.DOC_POS_LINEA.ARTICULO = consny.ARTICULO.ARTICULO
WHERE        (consny.DOC_POS_LINEA.BODEGA = @bodega) AND (consny.DOCUMENTO_POS.FCH_HORA_COBRO BETWEEN DATEADD(MI, 1, @fecha) AND DATEADD(MI, 1439, 
                         @fecha)) AND (consny.DOC_POS_LINEA.TIPO = 'F') and (consny.DOC_POS_LINEA.ARTICULO not like '0%' and consny.DOC_POS_LINEA.ARTICULO not like 'BC%') 
")or die($conexion1->error());
	
	$n=count($c->fetchAll());
	echo $n;

	}






?>
</body>
</html>