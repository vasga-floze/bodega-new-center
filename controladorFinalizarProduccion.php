<?php
    session_start();
    include('conexiones/conectar.php');
    //DATOS DEL JAVASCRIPT
    $paquete=$_POST["paquete"];
    $elementos=$_POST["elementos"];
    $documentoInventario=$_POST["documentoInventario"];
    $documentoConsecutivo=$_POST["documentoConsecutivo"];
    $produccion=$_POST["produccion"];
    $seleccionado=$_POST["seleccionado"];
    $fecha=$_POST["fechaCreacion"];
    //$fechaFormateada=date("Y-m-d h:i:s",strtotime($fecha));
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
   
   
   
    //var_dump($elementosDecodificados);
    if(empty($elementosDecodificados)){
        echo("Elementos vacios");
        return;
    }
   
    /*echo("paquete " .$paquete.
         "documentoInventario " .$documentoInventario.
         "documentoConsecutivo".$documentoConsecutivo.
         "produccion " .$produccion.
         "referencia " .$referencia.
         "fecha " .$fecha.
         "N " .$n.
         "usuario " .$usuario.
         "mensajeSistema " .$mensajeSistema.
         "USUARIO_APRO" .$nullMensaje.
         "FECHA_HORA_APROB" .$nullMensaje.
         "N " .$n.
         "Aprobado " .$aprobado.
         "Elementos".$elementos);*/
    try {
       
        $query= ("INSERT INTO consny.DOCUMENTO_INV (PAQUETE_INVENTARIO,DOCUMENTO_INV, CONSECUTIVO, REFERENCIA,FECHA_DOCUMENTO, SELECCIONADO, USUARIO, MENSAJE_SISTEMA, USUARIO_APRO,  APROBADO, NoteExistsFlag) VALUES (:paquete,:documentoInventario,:produccion,:referencia,:fecha,:n,:usuario,:mensajeSistema,:nullMensaje,:n0,:aprobado)");
        $stmt=$dbEximp600->prepare($query);
        $stmt->bindParam(":paquete",$paquete);
        $stmt->bindParam(":documentoInventario",$documentoInventario);
        $stmt->bindParam(":produccion",$produccion);
        $stmt->bindParam(":referencia",$referencia);
        $stmt->bindParam(":fecha",$fecha);
        $stmt->bindParam(":n",$n);
        $stmt->bindParam(":usuario",$usuario);
        $stmt->bindParam(":mensajeSistema",$mensajeSistema);
        $stmt->bindParam(":nullMensaje",$nullMensaje);
        $stmt->bindParam(":n0",$n0);
        $stmt->bindParam(":aprobado",$aprobado);
        if($stmt->execute()){
            
            foreach ($elementosDecodificados as $elemento) {
               
                try{
                    $articulo=$elemento['Articulo'];
                    $bodegaCreacion=$elemento['BodegaCreacion'];
                    $codigoBarra=$elemento['CodigoBarra'];
                    $fechaObjeto=$elemento['FechaCreacion'];
                    $usuario=$elemento['UsuarioCreacion'];
                    $i++;
                    $query =$dbEximp600->prepare("INSERT INTO consny.LINEA_DOC_INV (PAQUETE_INVENTARIO,
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
                    if($query->execute([$paquete,$documentoInventario,$i,'AJP',$articulo,$bodegaCreacion,'O','D','L','1','1','1','0','0','0','0','0'])){
                            
                                        $query1 =$dbBodega->prepare("INSERT INTO TRANSACCION (CodigoBarra,
                                                                                        IdTipoTransaccion,
                                                                                        Fecha,
                                                                                        Bodega,
                                                                                        Naturaleza,
                                                                                        UsuarioCreacion
                                                                                        ) VALUES (?,?,?,?,?,?)");
                                        if($query1->execute([$codigoBarra,2,$fechaObjeto,$bodegaCreacion,'E',$usuario])){
                                            $query2= "UPDATE dbo.REGISTRO SET BodegaActual=?, Estado=?,Activo=? WHERE CodigoBarra=?";
                                            $stmt1=$dbBodega->prepare($query2);
                                            if($stmt1->execute([$bodegaCreacion,'FINALIZADO','1',$codigoBarra])){
                                                echo("Registro Exitoso");
                                            }else{
                                                $errorInfo=$stmt1->errorInfo();
                                                echo "Error en la actualizacion de la tabla REGISTRO: {$errorInfo[2]} (Code: {$errorInfo[0]})";
                                                return;

                                            }
                                        }else{
                                            $errorInfo=$query1->errorInfo();
                                            echo "Error en la insercion de LINEA_DOC_INV: {$errorInfo[2]} (Code: {$errorInfo[0]})";
                                            return;
                                        }
                    }else{
                        $errorInfo=$query->errorInfo();
                        echo "Error en la insercion de Linea_Doc_Inventario: {$errorInfo[2]} (Code: {$errorInfo[0]})";
                        return;
                    }
                    
                }catch(PDOException $e){
                    echo "Error".$e->getMessage()."<br/>";
                }
                 
               
             }
             
             
           
            
        }else{
            $errorInfo=$stmt->errorInfo();
            echo "Error en la insercion en Documento_INV: {$errorInfo[2]} (Code: {$errorInfo[0]})";
            return;
        }
        $query3= "UPDATE consny.consecutivo_ci SET SIGUIENTE_CONSEC=? WHERE consecutivo=?";
        $stmt2=$dbEximp600->prepare($query3);
        if($stmt2->execute([$documentoConsecutivo,'PRODUCCION'])){
        echo("Registro Exitoso");
        }else{
            $errorInfo=$stmt2->errorInfo();
            echo "Error en la actualizacion de la tabla REGISTRO: {$errorInfo[2]} (Code: {$errorInfo[0]})";

    }
        //$result=$stmt->fetchAll();
    } catch (PDOException $e) {
        $dbEximp600->rollBack();
        echo "Error:".$e->getMessage();
        //throw $th;
    }



?>