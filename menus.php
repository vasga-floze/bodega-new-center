<!doctype html>
<html lang=''>
<head>
    <link rel="shortcut icon" href="logos.png">
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="cssmenu/styles.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="cssmenu/script.js"></script>
   <title>New York Center</title>



   <script>
         $(document).ready(function(){
   $("#cssmenu").hide();
   });

   function esconder()
   {
      $("#cssmenu").hide();
      $("#m").show();

   }
   function activarm()
   {
      $("#cssmenu").show();
      $("#m").hide();
   }
   $(document).click(function(e){
        if(e.target.id!='cssmenu')
        {
         

            
        }
        });

    $(document).on("click",function(e) {

        var container = $("#cssmenu");

            if (!container.is(e.target) && container.has(e.target).length === 0) { 
               if ($("#m").is(e.target) && $("#m").has(e.target).length === 0) { 
            $("#cssmenu").show();

            }else
            {
               $("#cssmenu").hide();
               $("#m").show();
            }
            

            }
   });
   
   </script>
</head>
<body>
   <?php
   $usu=$_SESSION['usuario'];
   $paq=$_SESSION['paquete'];
   ?>
<img src="menu.png"  style="width: 4%; height: 4.5%; float: left;  cursor: pointer; margin-top: 0.5%; padding-bottom: 5%;" onclick="activarm(); "id="m">
<div id='cssmenu' style="margin-left: -0.7%; position: fixed; margin-top: -0.5%; display: none; height: 100%; overflow: auto; scrollbar-color:gray CadetBlue; scrollbar-width:thin;">
<ul>
   
   <li><a href='#'><?php echo "<u>$usu ($paq)</u>"; ?></a></li>
</ul>
<ul>
   

         <li class='has-sub'><a href='#'>

         PRODUCCION</a>

            <ul>
               <li><a href='index.php'>NUEVA</a></li>
               <li><a href='producido.php'>PRODUCIDO</a></li>
               <li><a href='buscar4.php'>REPORTE</a></li>
               <li><a href='reporte_empacado.php'>REPORTE EMPACADO</a></li>
               <li><a href='reporte_producido.php'>REPORTE PERSONAL PRODUCCION</a></li>

               <li><a href='imprimire.php'>REIMPRIMIR</a></li>
            </ul>
         </li>
         <?php
         if($_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='gjurado' or $_SESSION['usuario']=='GJURADO' or $_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='OCAMPOS')
         {
         ?>
         <li class='has-sub'><a href='#'>

         PERSONAL</a>

            <ul>
               <li><a href='form_personal.php'>NUEVO</a></li>
               <li><a href='personal_produccion.php'>REGISTRADOS</a></li>
              
            </ul>
         </li>
         <?php
         }
         ?>


         <li><a href='comparacion.php'>RESUMEN</a></li>
         <li><a href='registros.php'>FARDOS</a></li>
         <li><a href='eliminar_registro.php'>ELIMINAR BARRA</a></li>
         <?php
         if($_SESSION['usuario']=='JALEXIS' or $_SESSION['usuario']=='jalexis' or $_SESSION['usuario']=='staana3')
      {
         ?>
         <li><a href='reporte_desglose.php'>REPORTE DESGLOSE</a></li>

          <li><a href='reporte_averia.php'>REPORTE AVERIA</a></li>
      <?php
         }
      ?>
      <li><a href='desglose_pendientes.php'>DESGLOSE SIN FINALIZAR</a></li>
      <li><a href='desglose_reporte.php'>REPORTE DESGLOSE</a></li>
      <li><a href='comprobar_documento.php'>COMPARACION DE DOCUMENTO</a></li>
      
      <li><a href='existencias_tiendas.php'>EXISTENCIAS</a></li>
         <li class='has-sub'><a href='#'>
         TRASLADO</a>

            <ul>
               <li><a href='trasladob.php'>NUEVO</a></li>
               <li><a href='pendiente_trasladob.php'>PENDIENTES</a></li>
               <li><a href='peso_traslado.php'>TRASLADO PESO</a></li>
               <li><a href='traslados_articulos.php'>TRASLADO ARTICULOS</a></li>
                <li><a href='movimientos_articulo.php'>MOVIMIENTO ARTICULOS</a></li>
                <li><a href='traslado_multiple.php'>TRASLADO PRODUCCION</a></li>
               <li><a href='rastrear.php'>RASTREAR</a></li>
               <li><a href='imprimiret.php'>REIMPRIMIR</a></li>
            </ul>
         </li>
         <li class='has-sub'><a href='#'>
         CONTENEDOR</a>

            <ul>
               <li><a href='contenedor.php'>NUEVO</a></li>
               <li><a href='continuar.php'>CONTINUAR CONTENEDOR</a></li>
               <li><a href='revisarconte.php'>REGISTRADOS</a></li>
               <li><a href='reporte_conte.php'>REPORTE</a></li>
               <li><a href='revisaconte.php'>RESUMEN</a></li>
               <li><a href='reimprimir_viñeta.php'>REIMPRIMIR</a></li>
               
            </ul>
         </li>

         <li class='has-sub'><a href='#'>
         RIPIO</a>

            <ul>
               <li><a href='ripio.php'>NUEVO</a></li>
               <li><a href='despacho_ripio.php'>DESPACHO</a></li>
               <li><a href='reporte_ripio.php'>REPORTE</a></li> 
               <li><a href='reimprimir_ripio.php'>REIMPRIMIR</a></li>              
            </ul>
         </li>


       <li class='has-sub'><a href='#'>
         AVERIA</a>

            <ul>
               <li><a href='averia.php'>NUEVO</a></li>
               <?php
               if($_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='OCAMPOS' or $_SESSION['usuario']=='staana3')
               {
               ?>
               <li><a href='asignar_averia.php'>ASIGNAR</a></li>
               <?php
                  }
               ?>
               <li><a href='desglose_averia.php'>DESGLOSE</a></li>
               <li><a href='reporte_averia.php'>REPORTE AVERIA</a></li>
                  <li><a href='reporte_averias.php'>REPORTE AVERIA/MERCADERIA</a></li>
               <li><a href='resumen_averia.php'>RESUMEN</a></li>
               
            </ul>
         </li>
         <?php
         if($_SESSION['usuario']=='staana3' or $_SESSION['usuario']=='salvarado' or $_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='gjurado' or $_SESSION['usuario']=='GJURADO')
         {
         ?> 
         <li class='has-sub'><a href='#'>
         PEDIDOS</a>

            <ul>
               <li><a href='bodegas_pedidos_confirmar.php'>CONFIRMAR</a></li>
               <li><a href='pedido_despacho.php'>DESPACHO</a></li>
               <li><a href='reporte_pedidos.php'>REPORTE</a></li>
               <li><a href='reimprimir_pedido_confirma.php'>REIMPRIMIR</a></li>
              
            </ul>
         </li>
         <?php
         }
         ?>
      
         <!--<li><a href='venta.php'>VENTA</a></li>!-->
         <li class='has-sub'><a href='#'>
         VENTA</a>

            <ul>
               <li><a href='venta.php'>VENTA C/BARRA</a></li>
               <li><a href='ventacod.php'>VENTA C/ARTICULO</a></li>
               <li><a href='reporte_venta3.php'>REPORTE</a></li>
                <li><a href='reimprimir_venta.php'>REIMPRIMIR</a></li>
              
            </ul>
         </li>
         <li class='has-sub'><a href='#'>
         INVENTARIO</a>

            <ul>
               <li><a href='inventario.php'>NUEVO</a></li>
               <li><a href='r_inventario.php'>REPORTES</a></li>
               <li><a href='con_inventario.php'>PENDIENTE</a></li>
               
            </ul>
         </li>
         <li class='has-sub'><a href='#'>
         BULTOS</a>

            <ul>
               <li><a href='mesa.php'>NUEVO</a></li>
               <li>
               <li><a href='continuar_mesa.php'>PENDIENTES</a></li>
               <li>
               <a href='final_mesa.php'>TRABAJADOS</a>
               </li>
               <li>
               <a href='resumen_mesa.php'>REPORTE</a>
               </li>
               
            </ul>
         </li>
         <li class='has-sub'><a href='#'>
         MESA</a>

            <ul>
               <li><a href='trabajos.php'>NUEVA</a></li>
               <li><a href='r_trabajos.php'>REPORTE</a></li>
               
            </ul>
         </li>

      </ul>

   </li>
   <ul>
   <li class='has-sub'><a href='#'>
         MERCADERIA (en proceso)</a>

            <ul>
               <li><a href='barriles.php'>NUEVA</a></li>
               <li><a href='barriles_mesa.php'>TRABAJADOS</a></li>
               <li><a href='baril_disponible.php'>DISPONIBLES</a></li>
               
            </ul>
         </li>

      </ul>

   </li>

<ul>
   <li><a href='resumen_liquidaciones.php'>LIQUIDACIONES</a></li>
   <li><a href='tiempo_bodega.php'>TIEMPO EN BODEGA</a></li>
    <li><a href='desactivar_gancho.php'>CONSUMIR INSUMOS</a></li>
   <?php
   if($_SESSION['usuario']=='ocampos' or $_SESSION['usuario']=='OCAMPOS')
   {
      echo "<li><a href='reporte_cuadro_venta.php'>CUADRO VENTA</a></li>";
      echo "<li><a href='reporte_gastos.php'>REPORTE GASTOS</a></li>";
       echo "<li><a href='dias_fardos.php'>TIEMPO EN TIENDA</a></li>";
   }
   ?>
   <li><a href='salir.php'>SALIR</a></li>
</ul>

</div>
<br><br><br>
</body>
<html>
