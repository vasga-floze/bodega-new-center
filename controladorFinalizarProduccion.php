<?php
    session_start();
    include('conexiones/conectar.php');
    /**
     * *DATOS JAVASCRIPT
     */
    $respuesta=$_SESSION['compania'];
    $paquete=$_POST["paquete"];
    $elementos=$_POST["elementos"];
    $documentoInventario=$_POST["documentoInventario"];
    $documentoConsecutivo=$_POST["documentoConsecutivo"];
    $consecutivo=$_POST["produccion"];
    $seleccionado=$_POST["seleccionado"];
    $fecha=$_POST["fechaCreacion"];
    $usuario=$_POST["usuario"];

    /**
     * *DATOS PHP
     */
    
    $referencia="Produccion del dia ".$fecha;
    $n="N";
    $n0="N";
    $nullMensaje="NULL";
    $nullMensaje0="N";
    $mensajeSistema='NULL';
    $aprobado=0;
    $i=0;
    $noteExistsFlag=0;
    $elementosDecodificados=json_decode($elementos,true);
    if(empty($elementosDecodificados)){
        echo("Elementos vacios");
        return;
    }


    $queryInsertarDocumentoInv=(" INSERT INTO ".$respuesta.".DOCUMENTO_INV(
                                                            PAQUETE_INVENTARIO,
                                                            DOCUMENTO_INV,
                                                            CONSECUTIVO,
                                                            REFERENCIA,
                                                            FECHA_DOCUMENTO,
                                                            SELECCIONADO,
                                                            USUARIO,
                                                            MENSAJE_SISTEMA,
                                                            APROBADO,
                                                            NoteExistsFlag
                                                            )VALUES(
                                                             :paquete,
                                                             :documentoInventario,
                                                             :consecutivo,
                                                             :referencia,
                                                             :fecha,
                                                             :seleccionado,
                                                             :usuario,
                                                             :mensajeSistema,
                                                             :aprobado,
                                                             :noteExistsFlag
                                                             


                                                            )");
    $stmt=$dbEximp600->prepare($queryInsertarDocumentoInv);
    $stmt->bindParam(":paquete",$paquete);
    $stmt->bindParam(":documentoInventario",$documentoInventario);
    $stmt->bindParam(":consecutivo",$consecutivo);
    $stmt->bindParam(":referencia",$referencia);
    $stmt->bindParam(":fecha",$fecha);
    $stmt->bindParam(":seleccionado",$seleccionado);
    $stmt->bindParam(":usuario",$usuario);
    $stmt->bindParam(":mensajeSistema",$mensajeSistema);
    $stmt->bindParam(":aprobado",$n0);
    
    $stmt->bindParam(":noteExistsFlag",$noteExistsFlag);

    if(!$stmt->execute()){
        $errorInfo=$stmt->errorInfo();
        echo "Error en insertar documento inv". $errorInfo[2];
        return;
    }   

    

    /**
     * *EL FOR DE ARTICULOS EN PROCESO
     * 
     */
    $contador=0;
    foreach ($elementosDecodificados as $key) {
                    $articulo=$key['Articulo'];
                    $bodegaCreacion=$key['BodegaCreacion'];
                    $codigoBarra=$key['CodigoBarra'];
                    $fechaObjeto=$key['FechaCreacion'];
                    $usuario=$key['UsuarioCreacion'];
                    $queryLineaDocumento=$dbEximp600->prepare("INSERT INTO ".$respuesta.".LINEA_DOC_INV (
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
                                                                                            COSTO_TOTAL_DOLAR,
                                                                                            PRECIO_TOTAL_LOCAL,
                                                                                            PRECIO_TOTAL_DOLAR,
                                                                                            NoteExistsFlag,
                                                                                            COSTO_TOTAL_LOCAL_COMP,
                                                                                            COSTO_TOTAL_DOLAR_COMP) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            if(!$queryLineaDocumento->execute([ $paquete,
                                                $documentoInventario,
                                                $contador,
                                                'AJP',
                                                $articulo,
                                                $bodegaCreacion,
                                                'O',
                                                'D',
                                                'L',
                                                '1',
                                                '1',
                                                '1',
                                                '0',
                                                '0',
                                                '0',
                                                '0',
                                                '0'])){
                $errorInfo = $query1->errorInfo();
                echo "Error en la ejecución de la consulta Linea Documento: " . $errorInfo[2];

            }
            $queryTransaccion=$dbBodega->prepare("INSERT INTO TRANSACCION (
                                                                CodigoBarra,
                                                                IdTipoTransaccion,
                                                                Fecha,
                                                                Bodega,
                                                                Naturaleza,
                                                                UsuarioCreacion,
                                                                Estado,
                                                                Documento_Inv
                                                                ) VALUES (?,?,?,?,?,?,?,?)");
            if(!$queryTransaccion->execute([$codigoBarra,
                                            2,
                                            $fechaObjeto,
                                            $bodegaCreacion,
                                            'E',
                                            $usuario,
                                            'F',
                                            $documentoInventario])){
                $errorInfo = $queryTransaccion->errorInfo();
                echo "Error en la ejecución de la consulta Transaccion: " . $errorInfo[2];
                return;
            }
            $queryActualizarRegistro= "UPDATE dbo.REGISTRO 
                                        SET BodegaActual=?, 
                                        Estado=?,
                                        Activo=? 
                                        WHERE 
                                        CodigoBarra=?";
            $queryActualizarRegistroEjecutar=$dbBodega->prepare($queryActualizarRegistro);

            if(!$queryActualizarRegistroEjecutar->execute([
                                                            $bodegaCreacion,
                                                            'FINALIZADO',
                                                            '1',
                                                            $codigoBarra])){
                $errorInfo = $queryActualizarRegistroEjecutar->errorInfo();
                echo "Error en la ejecución de la consulta editar registro: " . $errorInfo[2];
                return;
            }
            $contador++;                
    }
    /**
     * *CIERRE DEL FOR ARTICULOS PROCESO
     */

    
    $queryActualizarConsecutivo="UPDATE ".$respuesta.".consecutivo_ci SET SIGUIENTE_CONSEC=? WHERE consecutivo=?";
    $queryActualizarConsecutivoEjecutar=$dbEximp600->prepare($queryActualizarConsecutivo);
    if(!$queryActualizarConsecutivoEjecutar->execute([$documentoConsecutivo,'PRODUCCION'])){
        $errorInfo = $queryActualizarConsecutivoEjecutar->errorInfo();
        echo "Error en la ejecución de la consulta editar registro: " . $errorInfo[2];
        return;

    }
    echo("Se pudo ejecutar");


?>