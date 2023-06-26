<?php
session_start();
include('conexiones/conectar.php');
$codigoBarra=$_POST["codigoDesglose"];
$valorDesglose=$_POST["valorDesglose"];
$respuesta=$_SESSION['compania'];
$usuario=$_SESSION['usuario'];
$nullMensaje="NULL";
$lote_del_proveedor='ND';
$IdTipoTransaccion="6";
$naturaleza="S";
$fechaVencimiento='1980-01-01 00:00:00';
$cantidadIngresada='0';
$estado='V';
$tipoIngreso='P';
$NoteExistsFlag='0';
$ultimoIngreso='0';
$consumo='CONSUMO';
$seleccionado='N';
$mensajeSistema='';
$aprobado='N';


/**
      ** EXTRAE DATOS DE LA TIENDA
      * TODO: EXTRAE DATOS DE LA TIENDA SI EL USUARIO ES DE TIPO TIENDA
      * @param respuesta contiene la compania de softland
      * @param usuario contiene el usuario que se logue
      * ? Esta validacion debe de retornar que el usuario sea de tipo tienda
      * ! En dado caso que no el arreglo estara vacio y no pasara al siguiente codigo
     */

$query=$dbEximp600->prepare(
                            "SELECT USUARIOBODEGA.BODEGA, 
                                    ".$respuesta.".BODEGA.NOMBRE, 
                                    USUARIOBODEGA.HAMACHI, 
                                    USUARIOBODEGA.BASE,
                                    USUARIOBODEGA.PAQUETE
                            FROM USUARIOBODEGA 
                            INNER JOIN
                                ".$respuesta.".BODEGA ON USUARIOBODEGA.BODEGA = ".$respuesta.".BODEGA.BODEGA
                            WHERE (USUARIOBODEGA.TIPO = 'TIENDA' AND USUARIO='$usuario') 
                            and hamachi is not null
                            ORDER BY 1
                        ");

$query->execute();
$data=$query->fetchAll();
$response=array();
foreach ($data as $valores) {
    $response[]=$valores;
}

if(empty($response)){
    $response["message"]="El usuario no es de tipo tienda";
    $response["success"]="false";
    echo(json_encode($response));
    return;
}

    
     /**
    
     
      *@param bodega contiene la bodega del arreglo de la consulta a la tiena
      *@param hamachi contiene la IP del arreglo de la consulta a la tienda
      *@param base contiene la base a la que se va a conectar
     */  
    

    foreach ($response as $key) {
        $bodega=$key["BODEGA"];
        $hamachi=$key["HAMACHI"];
        $base=$key["BASE"];
        $bodega=$key["BODEGA"];
        $paqueteInventario=$key["PAQUETE"];
        # code...
    }
    /**
      ** 1 VALIDACION VERIFICA QUE EL CODDIGO DE BARRA EXISTE
      * TODO: VALIDA SI EL CODIGO DE BARRA EXISTE EN LA BASE DE LA TIENDA
      * @param codigoBarra contiene el codigo de barra que digito el usuario
      * @param bodega contiene la bodega especifica a la que se va a conectar
      * ? Esta validacion debe de retornar que exista el articulo en la tienda
      * ! En dado caso que no exista no dejara pasar al otro bloque de codigo
     */
    
    
    //1PRIMERA VALIDACION QUE SI EL CODIGO DE BARRA EXISTA EN LA BODEGA
$queryValidacionCodigo=$dbBodega->prepare("SELECT count(*) 
                                    FROM REGISTRO
                                WHERE CodigoBarra='$codigoBarra' 
                                AND BodegaActual='$bodega'AND Activo=1");
    $queryValidacionCodigo->execute();
    $totalCodigo=$queryValidacionCodigo->fetchColumn();
    
   

    if($totalCodigo === 0){
        $response["message"]="El codigo no existe en la tienda actual ";
        $response["success"]="false";
        echo(json_encode($response));
        return;
    }
     /**
      ** 2 VALIDACION VERIFICA QUE NO ESTA EN OTRA TRANSACCION
      * TODO: ESTA VALIDACION IDENTIFICA QUE EL ARTICULO NO ESTE OCUPADO EN OTRA TRANSACCION
      * @param codigoBarra contiene el codigo de barra que digito el usuario
     
      * ? Esta funcion debe de retornar que no esta ocupado en otra transaccion
      * ! En dado caso que este ocupado no dejara pasar al otro nloque de codigo
     */

     //2 VALIDACION: VERIFICA QUE EL CODIGO DE BARRA NO ESTE EN OTRA TRANSACCION
     
    $queryValidacionTransaccion=$dbBodega->prepare(
                                                    "SELECT REGISTRO.CodigoBarra,
                                                            TRANSACCION.Estado
                                                      FROM REGISTRO INNER JOIN
                                                      TRANSACCION ON REGISTRO.CodigoBarra=TRANSACCION.CodigoBarra
                                                      WHERE (REGISTRO.CodigoBarra ='$codigoBarra')
                                                      AND transaccion.Estado IS NULL
                                                  ");
    $queryValidacionTransaccion->execute();
    $totalTransaccion=$queryValidacionTransaccion->fetchColumn();

    if($totalTransaccion > 0){
        $response["message"]="El codigo de barra esta siendo utilizado en otra transaccion";
        $response["success"]="false";
        echo(json_encode($response));
        return;
    }


     /**
      ** 3 VALIDACION VALIDA SI ESTA DESGLOSADO
      * TODO: ESTA VALIDACION FUNCIONA PARA SABER SI EL ARTICULO ESTA DESGLOSADO O NO
      * @param codigoBarra contiene el valor del codigo de barra digitado por el usuario
      
      * ? Esta funcion debe de retornar que no este desglosado el articulo
      * ! En dado caso que este desglosado no pasara 
     */






        //3 VALIDACION: VERIFICA QUE EL CODIGO DE BARRA NO ESTE DESFLOSADO
    $queryValidacionDesglosado=$dbBodega->prepare(
                                                "SELECT REGISTRO.CodigoBarra,
                                                            TRANSACCION.Estado
                                                     FROM REGISTRO 
                                                INNER JOIN 
                                                     TRANSACCION ON REGISTRO.CodigoBarra = TRANSACCION.CodigoBarra
                                                WHERE (REGISTRO.CodigoBarra ='$codigoBarra')
                                                AND transaccion.IdTipoTransaccion=6"
                                                );
    $queryValidacionDesglosado->execute();
    $totalDesglosado=$queryValidacionDesglosado->fetchColumn();

    if($totalDesglosado > 0){
        $response["message"]="El articulo esta desglosado";
        $response["success"]="false";
        echo(json_encode($response));
        return;
    }


     /**
      ** 4 VALIDACION VALIDA EL VALOR DEL FARDO
      * TODO: ESTA VALIDACION VALIDA SI EL VALORFARDO ESTA EN EL RANGO DE VALORMINIMO Y VALORMAXIMO 
      * @param valorMinimo contiene el valor minimo que trae la consulta de la base de datos
      * @param codigoBarra contiene el codigo de barra digitado por el usuario 
      * @param valorMaximo contiene el valor maximo que trae la consulta de la base de datos
      * @param valorDesglose contiene el valor desglose que digito el usuario
      * ? Esta funcion debe de retornar que si esta en el rango
      * ! Si no esta en el rango no dejara pasar al otro bloque de codigo
     */

    $totalValidacionPorcentaje=$dbBodega->prepare("SELECT REGISTRO.CodigoBarra
    SUM(DETALLEREGISTRO.Cantidad*DETALLEREGISTRO.PrecioUnitario) SUBTOTAL,
    SUM(ROUND((DETALLEREGISTRO.Cantidad*DETALLEREGISTRO.PrecioUnitario)/1.10,0)) VALORMINIMO,
    SUM(ROUND((DETALLEREGISTRO.Cantidad*DETALLEREGISTRO.PrecioUnitario)*1.10,0)) VALORMAXIMO
    FROM            DETALLEREGISTRO INNER JOIN
                             REGISTRO ON DETALLEREGISTRO.IdRegistro = REGISTRO.IdRegistro
    WHERE codigobarra='$codigoBarra'
    GROUP BY REGISTRO.CodigoBarra
    ");

    $totalValidacionPorcentaje->execute();
    $datosPorcentaje=$totalValidacionPorcentaje->fetchAll();

    foreach ($datosPorcentaje as $key) {
        $valorMinimo=$key["VALORMINIMO"];
        $valorMaximo=$key["VALORMAXIMO"];
        
    }

    $valorMinimo=isset($valorMinimo)?$valorMinimo:null;
    $valorMaximo=isset($valorMaximo)?$valorMaximo:null;

    if(!isset($valorMinimo) && isset($valorMaximo)){
        $response["message"]="los datos detalles no existen";
        $response["success"]="false";
        echo(json_encode($response));
        return;
    }

    if(!($valorDesglose>=$valorMinimo) && ($valorDesglose<=$valorMaximo)){
        $response["message"]="Algo no coincide con los registro".$valorMinimo."".$valorMaximo;
        $response["success"]="false";
        echo(json_encode($response));
        return;
    }

    
     /**
      ** QUINTA VALIDACION
      * TODO: ESTA VALIDACION VALIDA SI EXISTE LOS ARTICULO DETALLES EN EL PAQUETE 
      * @param codigoBarra contiene el codigo de barra que ha sido digitado por el usuario
      * 
      * ? Esta funcion debe de retornar una respuestaque si existe o no existe
     */


    
    $queryValidacionListadoDetalle=$dbBodega->prepare("SELECT
                                        REGISTRO.CodigoBarra, 
                                        REGISTRO.Clasificacion, 
                                        DETALLEREGISTRO.ArticuloDetalle, 
                                        DETALLEREGISTRO.Cantidad,
                                        DETALLEREGISTRO.PrecioUnitario,
                                        REGISTRO.Articulo,
                                        REGISTRO.BodegaActual
                                    FROM REGISTRO INNER JOIN
                                        DETALLEREGISTRO ON REGISTRO.IdRegistro = DETALLEREGISTRO.IdRegistro
                                    GROUP BY 
                                        REGISTRO.CodigoBarra, 
                                        REGISTRO.Clasificacion, 
                                        DETALLEREGISTRO.ArticuloDetalle,
                                        DETALLEREGISTRO.Cantidad,
                                        DETALLEREGISTRO.PrecioUnitario,
                                        REGISTRO.Articulo,
                                        REGISTRO.BodegaActual
                                    HAVING(REGISTRO.CodigoBarra = '$codigoBarra')
                                 ");
     $queryValidacionListadoDetalle->execute();
     $datosDetalle=$queryValidacionListadoDetalle->fetchAll();
     $numeroDatosDetalle=count($datosDetalle);

     if(!$datosDetalle>0){
        $response["message"]="Lo datos detalles no existen";
        $response["success"]="false";
        echo(json_encode($response));
        return;
     }

     foreach($datosDetalle as $detalles){
        $articulo=$detalles["ArticuloDetalle"];
        $cantidad=$detalles["Cantidad"];
        $precioUnitario=$detalles["PrecioUnitario"];
        $articuloPaquete=$detalles["Articulo"];
     }


     /**
      ** ESTA VALIDACION DEBE PROCESAR UNA CONEXION A CADA TIENDA
      * TODO: ESTA FUNCION DEBE DE VALIDAR SI LOS DATOS ANTERIORES EXISTEN 
      * @param hamachi contiene la ip a la cual apunta al servidor de la tienda
      * @param base contiene la base a la que se va a conectar
      * ? Esta validacion debe de retornar una respuestaque si existe o no existe
     */


     try { 
        $conexionServerHamachi = new PDO("sqlsrv:Server=".$hamachi.";Database=".$base."", "sa", "$0ftland");
        $response["message"]="Conexion exitosa al servidor SQL".$articulo;
        for ($i=0; $i <$numeroDatosDetalle ; $i++) { 
            $queryValidarDatosAnteriores=$conexionServerHamachi->prepare("SELECT COUNT(".$respuesta.".EXISTENCIA_BODEGA.ARTICULO)
            FROM ".$respuesta.".EXISTENCIA_BODEGA INNER JOIN
            ".$respuesta.".ARTICULO_PRECIO ON ".$respuesta.".EXISTENCIA_BODEGA.ARTICULO = ".$respuesta.".ARTICULO_PRECIO.ARTICULO
            WHERE (".$respuesta.".EXISTENCIA_BODEGA.ARTICULO = '$articulo') AND (".$respuesta.".EXISTENCIA_BODEGA.BODEGA = '$bodega')
            ");

            $queryValidarDatosAnteriores->execute();
            $datosAnteriores=$queryValidarDatosAnteriores->fetchAll();
            if (!$datosAnteriores>0) {
                $response["message"]="Lo datos anteriores no existen";
                $response["success"]="false";
                echo(json_encode($response));
                return;
                # code...
            }
        
            $response["message"]="Los datos anteriores existen";
            $response["success"]="true";

        }
     } catch (PDOException $e) {
        $response["success"]="false";
        $response["message"]="Error en la conexion al servidor SQL:".$e->getMessage();
      
     }

     $fechaActual = date("Y-m-d");

    $queryInsertarTransaccion=$dbBodega->prepare(
                            "INSERT 
                                INTO TRANSACCION
                                (
                                    CodigoBarra,
                                    IdTipoTransaccion,
                                    Fecha,
                                    Bodega,
                                    Naturaleza,
                                    UsuarioCreacion
                                ) 
                                
                                VALUES 
                                (
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?
                                )              ");
    if(!$queryInsertarTransaccion->execute([
                        $codigoBarra,
                        $IdTipoTransaccion,
                        $fechaActual,
                        
                        
                        $bodega,
                        $naturaleza,
                        $usuario])){


        $errorInfo = $queryInsertarTransaccion->errorInfo();
        $response["message"]="Tienda no esta en linea transaccion".$errorInfo[2];
        $response["success"]="false";
        //echo "CABALLOOOOOO " .$errorInfo[2];
        echo(json_encode($response));
        return;

    }


    

    /**
     * *REGISTRA EL DETALLE DE ARTICULOS QUE COMPONE EL FARDO
     * TODO: ESTA QUERY INSERTA EL DETALLEDESGLOSE EN LA TABLA
     * TODO: DETALLEDESGLOSE
     * !En dado caso que falle no dejara pasar al otro query
     */

    $queryInsertarDetalleDesglose=$dbBodega->prepare(
        "INSERT 
            INTO DETALLEDESGLOSE
            (
                IdRegistro,
                ArticuloDetalle,
                Cantidad,
                PrecioUnitario
              
            ) 
            
            VALUES((SELECT idregistro FROM REGISTRO WHERE codigobarra='$codigoBarra'), ?,?,?)");
        if(!$queryInsertarDetalleDesglose->execute([
                                                    $articulo,
                                                    $cantidad,
                                                    $precioUnitario,
                                                    ])){


        $errorInfo = $queryInsertarDetalleDesglose->errorInfo();
        $response["message"]="Tienda no esta en linea detlle desglose".$errorInfo[2];
        $response["success"]="false";
        //echo "CABALLOOOOOO " .$errorInfo[2];
        echo(json_encode($response));
        return;

        }

    $fechaActualFormateado = new DateTime();
    $anioActual = $fechaActualFormateado->format('Y');
    $anioProximo=$anioActual+1;
    $fechaProxima = $fechaActualFormateado->setDate($anioProximo,$fechaActualFormateado->format('m'),$fechaActualFormateado->format('d'));
    $fechaCompletaProxima =$fechaProxima->format('Y-m-d');



    


        //SELECCIONA EL DOCUMENTO INVENTARIO DE TIPO CONSUMO

        /**
         * *SELECCIONA EL DOCUMENTO INVENTARIO
         * 
         */

        $querySeleccionarDocConsumo=$dbEximp600->prepare(
                                                        "SELECT 
                                                            SIGUIENTE_CONSEC 
                                                        FROM ".$respuesta.".CONSECUTIVO_CI

                                                        WHERE CONSECUTIVO='CONSUMO'
                                                        ");

        $querySeleccionarDocConsumo->execute();
        $datosSeleccionarDoc=$querySeleccionarDocConsumo->fetchAll();

        foreach ($datosSeleccionarDoc as $key) {
            $siguienteConsec=$key["SIGUIENTE_CONSEC"];

            
        }

        $referencia='CONSUMO POR DESGLOSE FARDO: ' .$articuloPaquete. 'CODIGO BARRA ' .$codigoBarra;

        //INSERTAR LA TRANSACCION DE INVENTARIO DE SALIDA

        $queryTransaccionInventarioSalida=$dbEximp600->prepare("INSERT INTO
                                                                 ".$respuesta.".DOCUMENTO_INV
                                                                 (PAQUETE_INVENTARIO,
                                                                   DOCUMENTO_INV,
                                                                   CONSECUTIVO,
                                                                   REFERENCIA,
                                                                   
                                                                   FECHA_DOCUMENTO,
                                                                   SELECCIONADO,
                                                                   USUARIO,
                                                                   MENSAJE_SISTEMA,
                                                                   APROBADO,
                                                                   NoteExistsFlag
                                                                   )
                                                                   VALUES
                                                                   (
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?
                                                                   )");
           if(!$queryTransaccionInventarioSalida->execute([

                                                $paqueteInventario,
                                                $siguienteConsec,
                                                $consumo,
                                                $referencia,
                                                
                                                $fechaActual,
                                                $seleccionado,
                                                $usuario,
                                                $mensajeSistema,
                                                $aprobado,
                                                $NoteExistsFlag

                                                        ])){
                    $errorInfo = $queryTransaccionInventarioSalida->errorInfo();
                    $response["message"]="Tienda no esta en linea inventario salida".$errorInfo[2];
                    $response["success"]="false";
                    //echo "CABALLOOOOOO " .$errorInfo[2];
                    echo(json_encode($response));
                    return;
            

            }

            //INSERTAR LA TRANSACCION DE INVENTARIO DE SALIDA DEL FARDO(DETALLE)
            function obtener_consecutivo($siguienteConsec){
                $consecutivo=preg_replace_callback('/\d+/',function($matches){
                    return sprintf('%0'.strlen($matches[0]). 'd', intval($matches[0])+1);
                },$siguienteConsec);
                return $consecutivo;
    
            }
            $documento_consecutivo=obtener_consecutivo($siguienteConsec);
           
            $queryActualizarConsecutivo="UPDATE ".$respuesta.".consecutivo_ci SET SIGUIENTE_CONSEC=? WHERE consecutivo=?";
            $queryActualizarConsecutivoEjecutar=$dbEximp600->prepare($queryActualizarConsecutivo);
            if(!$queryActualizarConsecutivoEjecutar->execute([$documento_consecutivo,'CONSUMO'])){
                $errorInfo = $queryActualizarConsecutivoEjecutar->errorInfo();
                $response["message"]="Error en la actualizacion".$errorInfo[2];
                $response["success"]="false";
                //echo "CABALLOOOOOO " .$errorInfo[2];
                echo(json_encode($response));
                return;

            }

            $queryTransaccionInventarioSalidaDetalle=$dbEximp600->prepare("INSERT 
                                                                        INTO ".$respuesta.".LINEA_DOC_INV
                                                                    (
                                                                        PAQUETE_INVENTARIO,
                                                                        DOCUMENTO_INV,
                                                                        LINEA_DOC_INV,
                                                                        AJUSTE_CONFIG,
                                                                        ARTICULO,
                                                                        BODEGA,
                                                                        TIPO,
                                                                        SUBTIPO,
                                                                        SUBSUBTIPO,
                                                                        CANTIDAD,
                                                                        COSTO_TOTAL_LOCAL,
                                                                        PRECIO_TOTAL_LOCAL,
                                                                        PRECIO_TOTAL_DOLAR,
                                                                        COSTO_TOTAL_DOLAR,
                                                                        NoteExistsFlag 

                                                                    )
                                                                    VALUES(

                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?,
                                                                    ?

                                                                    )");
            if(!$queryTransaccionInventarioSalidaDetalle->execute([
                                                        $paqueteInventario,
                                                        $siguienteConsec,
                                                        '1',
                                                        '~CC~',
                                                        $articuloPaquete,
                                                        $bodega,
                                                        
                                                        'C',
                                                        'D',
                                                        'N',
                                                        1,
                                                        1,
                                                        1,
                                                        1,
                                                        1,
                                                        0       ])){
                $errorInfo = $queryTransaccionInventarioSalidaDetalle->errorInfo();
                $response["message"]="Tienda no esta en linea inventario salida detalle".$errorInfo[2];
                $response["success"]="false";
                //echo "CABALLOOOOOO " .$errorInfo[2];
                echo(json_encode($response));
                return;

            }
            $querySiguienteConsecutivoIng=$dbEximp600->prepare("SELECT SIGUIENTE_CONSEC 
                                                                FROM ".$respuesta.".CONSECUTIVO_CI 
                                                                WHERE CONSECUTIVO='ING'");

            $querySiguienteConsecutivoIng->execute();
            $datosConsecutivoIng=$querySiguienteConsecutivoIng->fetchAll();

            foreach ($datosConsecutivoIng as $key) {
                $consecutivoIng=$key["SIGUIENTE_CONSEC"];

            }





            //INSERTAR LA TRANSACCION DEINVENTARIO DE INGRESO DE PIEZAS DEL FARDO EN BUCLE
            function obtener_consecutivoIng($consecutivoIng){
                    $consecutivo=preg_replace_callback('/\d+/',function($matches){
                    return sprintf('%0'.strlen($matches[0]). 'd', intval($matches[0])+1);
                    },$consecutivoIng);
                    return $consecutivo;

            }
            $documentoConsecutivoING=obtener_consecutivoIng($consecutivoIng);

           
           



            /**
             * *ESCRIBIR EL FOREACH PARA EL LOTE


            */

            foreach ($datosDetalle as $value) {
                $queryInsertarCodigoBarraLote=$dbEximp600->prepare("INSERT INTO ".$respuesta.".LOTE
                                                                    (
                                                                        LOTE,
                                                                        ARTICULO,
                                                                        LOTE_DEL_PROVEEDOR,
                                                                        FECHA_ENTRADA,
                                                                        FECHA_VENCIMIENTO,
                                                                        FECHA_CUARENTENA,
                                                                        CANTIDAD_INGRESADA,
                                                                        ESTADO,
                                                                        TIPO_INGRESO,
                                                                        ULTIMO_INGRESO,
                                                                        NoteExistsFlag
                                                                    ) VALUES (
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?
                                                                    )");
                if(!$queryInsertarCodigoBarraLote->execute([
                                                        $codigoBarra,
                                                        $articulo,
                                                        'ND',
                                                        $fechaActual,
                                                        $fechaCompletaProxima,
                                                        $fechaVencimiento,
                                                        0,
                                                        'V',
                                                        'P',
                                                        0,
                                                        0
                                                          ])){
                $errorInfo=$queryInsertarCodigoBarraLote->errorInfo();
                $response["message"]="Tienda no esta en linea insertar codigo barra lote".$errorInfo[2];
                $response["success"]="false";
                echo json_encode($response);
                return;

                
                
                }
            }




            /**
             * *ENCABEZADO DOCUMENTO INV
             */


            $queryPiezaFardoEncabezado=$dbEximp600->prepare("INSERT INTO ".$respuesta.".DOCUMENTO_INV
                                    (
                                        PAQUETE_INVENTARIO,
                                        DOCUMENTO_INV,
                                        CONSECUTIVO,
                                        REFERENCIA,
                                        FECHA_HOR_CREACION,
                                        FECHA_DOCUMENTO,
                                        SELECCIONADO,
                                        USUARIO,
                                        MENSAJE_SISTEMA,
                                        APROBADO,
                                        NoteExistsFlag
                                    )

                                    VALUES(
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?


                                    )
                                        ");
                            if (!$queryPiezaFardoEncabezado->execute([
                                                                        $paqueteInventario,
                                                                        $consecutivoIng,
                                                                        'ING',
                                                                        $referencia,
                                                                        $fechaActual,
                                                                        $fechaActual,
                                                                        'N',
                                                                        $usuario,
                                                                        'NULL',
                                                                        'N',
                                                                        $NoteExistsFlag
                                                                        

                                                                    ])) {
                                    $errorInfo = $queryPiezaFardoEncabezado->errorInfo();
                                    $response["message"]="Tienda no esta en linea querypiezafardo".$errorInfo[2];
                                    $response["success"]="false";
                                    //echo "CABALLOOOOOO " .$errorInfo[2];
                                    echo(json_encode($response));
                                    return;
                                    # code...
                                    }




            
            

            /**
             * *lINEA DOCUMENTO INVENTARIO
             */
            $contador=1;
            foreach ($datosDetalle as $value) {
                    $queryTransaccionPiezaFardo=$dbEximp600->prepare(" INSERT INTO ".$respuesta.".LINEA_DOC_INV
                                                                    (
                                                                        PAQUETE_INVENTARIO,
                                                                        DOCUMENTO_INV,
                                                                        LINEA_DOC_INV,
                                                                        AJUSTE_CONFIG,
                                                                        ARTICULO,
                                                                        BODEGA,
                                                                        LOTE,
                                                                        TIPO,
                                                                        SUBTIPO,
                                                                        SUBSUBTIPO,
                                                                        CANTIDAD,
                                                                        COSTO_TOTAL_LOCAL,
                                                                        PRECIO_TOTAL_LOCAL,
                                                                        PRECIO_TOTAL_DOLAR,
                                                                        COSTO_TOTAL_DOLAR,
                                                                        NoteExistsFlag 
                                                                    )
                                                                    
                                                                    VALUES
                                                                    (
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?,
                                                                        ?



                                                                    )
                                                                ");
                        
                if(!$queryTransaccionPiezaFardo->execute([
                                                        $paqueteInventario,
                                                        $consecutivoIng,
                                                        $contador,
                                                        '~OO~',
                                                        $articulo,
                                                        $bodega,
                                                        $codigoBarra,
                                                        'O',
                                                        'D',
                                                        'L',
                                                        $cantidad,
                                                        1,
                                                        1,
                                                        1,
                                                        1,
                                                        0
                                                        ])){             
                    $errorInfo = $queryTransaccionPiezaFardo->errorInfo();
                    $response["message"]="Tienda no esta en linea inventario fallo en query pieza fardo".$errorInfo[2];
                    $response["success"]="false";
                    //echo "CABALLOOOOOO " .$errorInfo[2];
                    echo(json_encode($response));
                    return;
                    

                }

                
                $contador++;
            }



            //ACTUALIZAR AL ULTIMO DOCUMENTO ING

            $queryUpdateActualizarUltimoDocumento=$dbEximp600->prepare("UPDATE ".$respuesta.".CONSECUTIVO_CI SET SIGUIENTE_CONSEC=:documentoConsecutivo WHERE CONSECUTIVO='ING'");
            $queryUpdateActualizarUltimoDocumento->bindParam(":documentoConsecutivo",$documentoConsecutivoING);
           
            if(!$queryUpdateActualizarUltimoDocumento->execute()){

                $errorInfo= $queryUpdateActualizarUltimoDocumento->errorInfo();
                $response["message"]="Tienda actualizar ultimo documento".$errorInfo[2];
                $response["success"]="false";
                echo(json_encode($response));
                return;
            }

            //ACTUALIZAR EL ULTIMO DOCUMENTO CONSUMO
            $queryUpdateActualizarUltimoDocummentoConsumo=$dbEximp600->prepare(
                                                                        "UPDATE ".$respuesta.".CONSECUTIVO_CI
                                                                            SET SIGUIENTE_CONSEC=".$documento_consecutivo."
                                                                        WHERE CONSECUTIVO='CONSUMO'
                                                                        ");
            if (!$queryUpdateActualizarUltimoDocummentoConsumo) {
                $errorInfo=$queryUpdateActualizarUltimoDocummentoConsumo->errorInfo();
                $response["message"]="Tienda no esta en linea fallo en actualizar ultimo documento consumo".$errorInfo[2];
                $response["success"]="false";
                echo json_encode($response);
                return;
            }


            
            

        $response["message"]="Los datos se insertaron existosamente  DOCUMENTO CONSECUTIVO " .$documento_consecutivo. "DOCUMENTO CONSECUTIVO ING " .$documentoConsecutivoING;
        $response["success"]="true";
        echo(json_encode($response));

        ?>
