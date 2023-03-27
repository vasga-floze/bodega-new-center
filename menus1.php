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
<div id='cssmenu' style="margin-left: -0.7%; position: absolute; margin-top: -0.5%; display: none; height: 100%; overflow: auto; scrollbar-color:gray CadetBlue; scrollbar-width:thin; ">
<ul>
   
   <li><a href='#'><?php echo "<u>$usu</u>"; if($_SESSION['tipo']==4){ ?></a></li>
</ul>
<ul>
   
         <li><a href='inicio.php'>INICIO</a></li>
         <li><a href='consultar.php'>CONSULTA DESGLOSES</a></li>
         <li class='has-sub'><a href='#'>
                  REPORTE PRODUCCION</a>

            <ul>
               <li><a href='reporte_produccion.php'>DIARIO</a></li>
              <li><a href='reporte_produccion_mensual.php'>MENSUAL</a></li>
              <li><a href='reporte_empacado.php'>REPORTE EMPACADORES</a></li>
              <li><a href='reporte_producido.php'>REPORTE PERSONAL DE PRODUCCION</a></li>
              
            </ul>
         </li>


         
         <li><a href='reporte_trasladob.php'>REPORTE TRASLADO</a></li>
          <li><a href='desglose_reporte.php'>REPORTE DESGLOSES</a></li>
           <li><a href='resumen_liquidaciones.php'>REPORTE LIQUIDACIONES</a></li>
           <li><a href='reporte_averias.php'>REPORTE AVERIAS/MERCADERIA NO VENDIBLE</a></li>

            <li><a href='reporte_detalle_mayoreo.php'>REPORTE DETALLE/MAYOREO</a></li>

         <li class='has-sub'><a href='#'>
                  REPORTE CUADRO VENTA</a>

            <ul>
               <li><a href='reporte_cuadro_venta.php'>DETALLE</a></li>
               <li><a href='reporte_cuadro_venta_sin_detalle.php'>SIN DETALLE</a></li>
              
            </ul>
         </li>
         <li><a href='registros.php'>FARDOS</a></li>

        <li><a href='valor_fardos.php'>PRECIO FARDOS</a></li>
        <li><a href='dias_fardos.php'>TIEMPO FARDOS</a></li>
         <li><a href='reporte_gastos.php'>REPORTE GASTOS</a></li>
         <li><a href='reporte_gastos_doc.php'>REPORTE GASTOS(DOC)</a></li>
         <li><a href='tiempo_bodega.php'>TIEMPO EN BODEGA</a></li>
         <li><a href='reporte_ganchos.php'>REPORTE DE GANCHOS</a></li>
         </li>
       
   <li><a href='salir.php'>SALIR</a></li>
</ul>
<?php
}else if($_SESSION['tipo']==5)
{?>
  <li class='has-sub'><a href='#'>
                  REPORTE CUADRO VENTA</a>

            <ul>
               <li><a href='reporte_cuadro_venta.php'>DETALLE</a></li>
               <li><a href='reporte_cuadro_venta_sin_detalle.php'>SIN DETALLE</a></li>
              
            </ul>
         </li>
         <li><a href='reporte_gastos.php'>REPORTE DE GASTOS</a></li>
         <li><a href='reporte_gastos_doc.php'>REPORTE GASTOS(DOC)</a></li>
          <li><a href='contabilidad_cuadro_venta.php'>INGRESO REMESA A BANCO</a></li>


         <li><a href='salir.php'>SALIR</a></li>
         
         <?php
}?>


</div>
<br><br><br><br><br><br>
</body>
<html>
