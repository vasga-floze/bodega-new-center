<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	
<link rel="shortcut icon" href="logos.png">


	

</head>
<script>
	$(document).ready(function(){
	$(".menu").hide();
	});

	function esconder()
	{
		$(".menu").hide();
		$("#m").show();
	}
	function activarm()
	{
		$(".menu").show();
		$("#m").hide();
	}
	$(document).click(function(e){
        if(e.target.id!='menu')
        {
        	

            
        }
        });

	 $(document).on("click",function(e) {

        var container = $(".menu");

            if (!container.is(e.target) && container.has(e.target).length === 0) { 
            	if ($("#m").is(e.target) && $("#m").has(e.target).length === 0) { 
            $(".menu").show();

            }else
            {
            	$(".menu").hide();
            	$("#m").show();
            }
            

            }
   });
	
</script>
<body>
	<p style="float: right; text-decoration: underline;">
<?php
	$usut=$_SESSION['usuario'];
	$paquet=$_SESSION['paquete'];
	echo "USUARIO: $usut PAQUETE: $paquet";
?>
</p>
<img src="menu.png" style="width: 4%; height: 4.5%; float: left;  cursor: pointer; margin-top: 0.5%; padding-bottom: 5%;" onclick="activarm()" id="m">
	
	<div class="menu" id="menu" style="margin-left: -0.6%; overflow: auto; scrollbar-color:gray CadetBlue; scrollbar-width:thin;">
	<img src="cierra.png" onclick="esconder()" style="float: right; margin-top: -0.2%; width: 9%; height: 5%; cursor: pointer;">
	<!--<a href="buscar4.php">
	<img src="lupa.png" width="3.5%" height="2.9%" style="float: left; margin-top: 0.5%; cursor: pointer;"></a>

	<a href="revisaconte.php">
	<img src="conte.png" width="3.5%" height="2.9%" style="float: left; margin-top: 0.5%; margin-left: 4.2%; cursor: pointer;"></a>-->
	<?php
		if($_SESSION['usuario']=='GJURADO' or $_SESSION['usuario']=='gjurado' or $_SESSION['usuario']=='staana3')
		{
	?>
	<a href="imprimire.php">
	<img src="impresora.png" width="4.5%" height="2.9%" style="float: left; margin-top: 0.5%; margin-left: 4.2%; cursor: pointer;"></a>
	<?php
		}
		if($_SESSION['tipo']==1)
		{
		
	?>
	<br><br>

		<hr>
		<a href="index.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">PRODUCCION</p></a>
		<hr>
		<a href="traslados.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">TRASLADO</p></a>
		<hr>
		<a href="pendiente_t.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">TRASLADOS PENDIENTE</p></a>

		<hr>
		<a href="traslados_articulos.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">TRASLADOS ARTICULOS</p></a>

		<hr>
		<a href="desglose.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">DESGLOSE</p></a>
		<hr>
		<a href="comparacion.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">RESUMEN</p></a>
		<hr>
		<a href="registros.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">FARDOS</p></a>
		<hr>
		<a href="r_inventario.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">INVENTARIOS</p></a>
		<hr>
		
		<a href="contenedor.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">CONTENEDOR</p></a>
		<hr>
		
		<a href="producido.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">PRODUCIDO</p></a>
		<hr>
		<a href="mesa.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">BULTOS P/T</p></a>
		<hr>
		<a href="final_mesa.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">BULTOS T</p></a>
		<hr>
		<a href="trabajos.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">MESA</p></a>
		<hr>
		<a href="r_trabajos.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">PRODUCCION MESAS</p></a>
		<?php
			}else if($_SESSION['tipo']==2)
			{
				$dia=date("Y-m-d");
				$dia=strtotime($dia);
				$dia=date("w",$dia);
				if($dia==6 or $dia==0)
				{
				?>
			<a href="consultas.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">CONSULTAR</p></a>
		<?php

				}
		?>
		<br>
		<a href="inicio_tienda.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">ESTADISTICAS</p></a>
		<a href="reporte_averias.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE AVERIA/MERCADERIA</p></a>
		<a href="desglose.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">DESGLOSE</p></a>
		<a href="averias.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">AVERIA/MERCADERIA NO VENDIBLE</p></a>

		<hr>
		<a href="resumen3.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">RESUMEN</p></a>
		<hr>
		<a href="registros.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">FARDOS</p></a>
		<hr>
		<a href="liquidaciones.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">NUEVA LIQUIDACION</p></a>
		<hr>
		<a href="pedidos.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">PEDIDO ARTICULOS</p></a>
		<hr>
		<a href="pedido_pendiente.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">PEDIDO PENDIENTE</p></a>
		<hr>
		<a href="reporte_pedido_tienda.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE PEDIDOS</p></a>

		<hr>
		<a href="resumen_liquidaciones.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE LIQUIDACIONES</p></a>
		<hr>
		<a href="pendientes_liquidaciones.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">LIQUIDACIONES SIN FINALIZAR</p></a>
		<hr>
		<a href="vendidostienda.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">FARDOS VENDIDOS</p></a>
		<hr>
		<a href="barra_vendidos.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE FARDOS VENDIDOS</p></a>
		<hr>
		<a href="ver_cuadroventa.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE CUADRO VENTA</p></a>
		<a href="desactivar_gancho.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">DESACTIVAR GANCHOS/INSUMOS</p></a>


		<hr>
		<a href="precios_articulos.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">CAMBIO PRECIOS</p></a>

		<a href="dias_fardos.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">TIEMPO DE FARDOS</p></a>

		<a href="activa_tarjeta.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">ACTIVAR TARJETA</p></a>
		<a href="desactiva_tarjeta.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">DESACTIVAR TARJETA</p></a>

		<hr>

		<a href="cuadrar.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">CUADRO VENTA</p></a>
		
		<?php
			}else
			{
				echo '<a href="consultar.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">CONSULTAR</p></a>';
		echo '<a href="rastrear.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">RASTREAR</p></a>';
		echo '<a href="registros.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">FARDOS</p></a>';


		echo '<hr><a href="inventario.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">INVENTARIOS</p></a>';
		echo '<a href="con_inventario.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">INVENTARIOS SIN FINALIZAR</p></a>';
		echo '<a href="desactivar_por_inventario.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">DESACTIVAR POR INVENTARIO</p></a>';

		echo '<hr><a href="trasladob.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">NUEVO TRASLADO</p></a>';
		echo '<a href="pendiente_trasladob.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">TRASLADO PENDIENTE</p></a>';


		echo '<hr>
		<a href="desglose_pendientes.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">DESGLOSES SIN FINALIZAR</p></a>';
		echo '<a href="comprobar_documento.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">COMPARACION DE DOCUMENTOS</p></a>';
		echo '<a href="habilita_sys.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">HABILITACION DE TRANSACCIONES</p></a>';

		echo '<hr>
		<a href="resumen_liquidaciones.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE LIQUIDACIONES</p></a>';
		echo '<a href="barra_vendidos.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE FARDOS VENDIDOS</p></a>';
		echo '<a href="desglose_reporte.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE DESGLOSES</p></a>';
		echo '<a href="reporte_averias.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE AVERIAS/MERCADERIA NO VENDIBLE</p></a>';
		echo '<a href="reporte_cuadro_venta_sin_detalle.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE CUADRO VENTA (GLOBAL)</p></a>';
		echo '<a href="reporte_cuadro_venta.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE CUADRO VENTA (DETALLE)</p></a>';
		echo '<a href="reporte_gastos.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE DE GASTOS</p></a>';
		echo '<a href="reportedesglosesresumenpiezas.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE UNIDADES FARDOS</p></a>';
	echo '<a href="reportehistorialfardostiendas.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE HISTORIAL FARDOS TIENDAS</p></a>';
			}
		?>
		<hr>
		<?php
		$bodega=$_SESSION['bodega'];
		$tipousu=substr($bodega, 0);
		if($tipousu[0]=='U' or $_SESSION['usuario']=='staana3')
		{
			echo '<hr>
		<a href="traslados.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">TRASLADO</p></a>
		<hr>';
		echo '
		<a href="utraslado.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REGISTROS TRASLADOS</p></a>
		<hr>';
		
		}
		if($_SESSION['usuario']=='JALEXIS' or $_SESSION['usuario']=='jalexis' or $_SESSION['usuario']=='staana3')
		{
			echo '
		<a href="reporte_desglose.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE DESGLOSE</p></a>';
		}
		?>
		<a href="salir.php" style="text-decoration: none; color: white;">

		<?php
		if($_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='SALVARADO')
		{
			echo '<hr>
		<a href="reporte_conte.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">REPORTE CONTE...</p></a>
		<hr>
		<a href="venta.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">VENTA</p></a>
		<hr>
		<a href="peso_traslado.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">PESO TRASLADOS</p></a>
		<hr>';
		}
		?>
		<a href="salir.php" style="text-decoration: none; color: white;">
		<p class="btnmenu">CERRAR SESION</p></a>
		<br><br><br><br>
	</div>
<br><br><br>


</body>
</html>