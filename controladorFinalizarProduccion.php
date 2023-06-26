<?php
    session_start();
    include('conexiones/conectar.php');
    //DATOS DEL JAVASCRIPT
    $respuesta=$_SESSION['compania'];
    $paquete=$_POST["paquete"];
    $elementos=$_POST["elementos"];
    $documentoInventario=$_POST["documentoInventario"];
    $documentoConsecutivo=$_POST["documentoConsecutivo"];
    $produccion=$_POST["produccion"];
    $seleccionado=$_POST["seleccionado"];
    $fecha=$_POST["fechaCreacion"];
    $usuario=$_POST["usuario"];


    //DATOS DE PHP
    $referencia="Produccion del dia ".$fecha;
    $n="N";
    $n0="N";
    $nullMensaje="NULL";
    $nullMensaje0="NULL";
    $mensajeSistema='NULL';
    $aprobado=0;
    $i=0;
    $elementosDecodificados=json_decode($elementos,true);
    if(empty($elementosDecodificados)){
        echo("Elementos vacios");
        return;
    }
   
    
   
       
        $query= ("INSERT INTO ".$respuesta.".DOCUMENTO_INV (PAQUETE_INVENTARIO,DOCUMENTO_INV, CONSECUTIVO, REFERENCIA,FECHA_DOCUMENTO, SELECCIONADO, USUARIO, MENSAJE_SISTEMA,   APROBADO, NoteExistsFlag) VALUES (:paquete,:documentoInventario,:produccion,:referencia,:fecha,:n,:usuario,:mensajeSistema,:n0,:aprobado)");
        $stmt=$dbEximp600->prepare($query);
        $stmt->bindParam(":paquete",$paquete);
        $stmt->bindParam(":documentoInventario",$documentoInventario);
        $stmt->bindParam(":produccion",$produccion);
        $stmt->bindParam(":referencia",$referencia);
        $stmt->bindParam(":fecha",$fecha);
        $stmt->bindParam(":n",$n);
        $stmt->bindParam(":usuario",$usuario);
        $stmt->bindParam(":mensajeSistema",$mensajeSistema);
        //$stmt->bindParam(":nullMensaje",$nullMensaje);
        $stmt->bindParam(":n0",$n0);
        $stmt->bindParam(":aprobado",$aprobado);
        if(!$stmt->execute()){
            $errorInfo = $stmt->errorInfo();
            echo "Error en la ejecución de la consulta Documento Inv: " . $errorInfo[2];
            return;

        }
            
            foreach ($elementosDecodificados as $elemento) {
                $articulo=$elemento['Articulo'];
                $bodegaCreacion=$elemento['BodegaCreacion'];
                $codigoBarra=$elemento['CodigoBarra'];
                $fechaObjeto=$elemento['FechaCreacion'];
                $usuario=$elemento['UsuarioCreacion'];
                $i++;
                $queryLineaDocumento=$dbEximp600->prepare("INSERT INTO ".$respuesta.".LINEA_DOC_INV (PAQUETE_INVENTARIO,
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
                                                                                COSTO_TOTAL_DOLAR,
                                                                                PRECIO_TOTAL_LOCAL,
                                                                                PRECIO_TOTAL_DOLAR,
                                                                                NoteExistsFlag,
                                                                                COSTO_TOTAL_LOCAL_COMP,
                                                                                COSTO_TOTAL_DOLAR_COMP) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                if(!$queryLineaDocumento->execute([$paquete,$documentoInventario,$i,'AJP',$articulo,$bodegaCreacion,'O','D','L','1','1','1','0','0','0','0','0'])){
                    $errorInfo = $query1->errorInfo();
                    echo "Error en la ejecución de la consulta Linea Documento: " . $errorInfo[2];

                }                
            }


            $queryTransaccion=$dbBodega->prepare("INSERT INTO TRANSACCION (
                                                    CodigoBarra,
                                                    IdTipoTransaccion,
                                                    Fecha,
                                                    Bodega,
                                                    Naturaleza,
                                                    UsuarioCreacion,
                                                    Estado
                                                    ) VALUES (?,?,?,?,?,?,?)");
            if(!$queryTransaccion->execute([$codigoBarra,2,$fechaObjeto,$bodegaCreacion,'E',$usuario,'F'])){
                $errorInfo = $queryTransaccion->errorInfo();
                echo "Error en la ejecución de la consulta Transaccion: " . $errorInfo[2];
                return;
            }

            $queryActualizarRegistro= "UPDATE dbo.REGISTRO SET BodegaActual=?, Estado=?,Activo=? WHERE CodigoBarra=?";
            $queryActualizarRegistroEjecutar=$dbBodega->prepare($queryActualizarRegistro);

            if(!$queryActualizarRegistroEjecutar->execute([$bodegaCreacion,'FINALIZADO','1',$codigoBarra])){
                $errorInfo = $queryActualizarRegistroEjecutar->errorInfo();
                echo "Error en la ejecución de la consulta editar registro: " . $errorInfo[2];
                return;
            }


            $queryActualizarConsecutivo="UPDATE ".$respuesta.".consecutivo_ci SET SIGUIENTE_CONSEC=? WHERE consecutivo=?";
            $queryActualizarConsecutivoEjecutar=$dbEximp600->prepare($queryActualizarConsecutivo);
            if(!$queryActualizarConsecutivoEjecutar->execute([$documentoConsecutivo,'PRODUCCION'])){
                $errorInfo = $queryActualizarConsecutivoEjecutar->errorInfo();
                echo "Error en la ejecución de la consulta editar registro: " . $errorInfo[2];
                return;

            }
            echo("Se pudo ejecutar");


?>