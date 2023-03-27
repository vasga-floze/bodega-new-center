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
   
   <li><a href='#'><?php echo "<u>$usu</u>"; ?></a></li>
</ul>
<ul>
   
         <li><a href='inicio_tienda.php'>INICIO</a></li>

         <?php
     if($_SESSION['tipo']==2)
      {
        $dia=date("Y-m-d");
        $dia=strtotime($dia);
        $dia=date("w",$dia);
      }
        if($dia==9 or $dia==10)
        {
          ?>
       <li><a href='consultas.php'>CONSULTAR</a></li>
    <?php

        }
    ?>

       

         <li class='has-sub'><a href='#'>DESGLOSE</a>

            <ul>
               <li><a href='desgloseb.php'>NUEVO</a></li>
              <li><a href='resumen3.php'>RESUMEN</a></li>
            </ul>
         </li>

         <li class='has-sub'><a href='#'>LIQUIDACIONES</a>

            <ul>
               <li><a href='liquidaciones.php'>NUEVA</a></li>
               <li><a href='pendientes_liquidaciones.php'>SIN FINALIZAR</a></li>
              <li><a href='resumen_liquidaciones.php'>REPORTE</a></li>
            </ul>
         </li>

         <li class='has-sub'><a href='#'>AVERIA/MERCADERIA NO VENDIBLE</a>

            <ul>
               <li><a href='averias.php'>NUEVA</a></li>
               <li><a href='reporte_averias.php'>REPORTE</a></li>
            </ul>
         </li>

         <li class='has-sub'><a href='#'>PEDIDOS</a>

            <ul>
               <li><a href='pedidos.php'>NUEVO</a></li>
               <li><a href='reporte_pedido_tienda.php'>REPORTE</a></li>
               <li><a href='pedido_pendiente.php'>PENDIENTE</a></li>
            </ul>
         </li>

         <li class='has-sub'><a href='#'>CUADRO VENTA</a>

            <ul>
               <li><a href='cuadrar.php'>NUEVO</a></li>
               <li><a href='ver_cuadroventa.php'>REPORTE</a></li>
            </ul>
         </li>
         <li class='has-sub'><a href='#'>TARJETAS</a>

            <ul>
               <li><a href='activa_tarjeta.php'>ACTIVAR</a></li>
               <li><a href='desactiva_tarjeta.php'>DESACTIVAR</a></li>
            </ul>
         </li>

         <li><a href='registros.php'>FARDOS</a></li>

         <li class='has-sub'><a href='#'>FARDO VENDIDO</a>

            <ul>
         <li><a href='vendidostienda.php'>NUEVO</a></li>
          <li><a href='barra_vendidos.php'>REPORTE</a></li>
        </ul>


         
        
        <li><a href='desactivar_gancho.php'>DESACTIVAR GANCHOS/INSUMOS</a></li>
        <li><a href='precios_articulos.php'>CAMBIAR PRECIO ARTICULO</a></li>
        <li><a href='dias_fardos.php'>TIEMPO FARDOS</a></li>
         <li><a href='salir.php'>SALIR</a></li>
         
   



</div>
<br><br><br><br><br><br>
</body>
<html>
