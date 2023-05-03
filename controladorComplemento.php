<?php
    session_start();
    $codigoBarra=$_SESSION['cod'];
    $bandera=$_SESSION['banderaArticulo'];
    include('conexiones/conectar.php');
   
    $query=$dbBodega->prepare("SELECT IdRegistro from REGISTRO where CodigoBarra='".$codigoBarra."'");
    $query->execute();
    $data = $query->fetchAll();
    foreach ($data as $key) {
        $idRegistro=$key['IdRegistro'];
        
    }
    
    if(!empty($_POST['json'])){
        $datos=json_decode($_POST['json'],true);
        $_SESSION['datos'] = $datos;
        foreach($datos as $valores){

            try{
            $articuloDetalle=$valores['codigo'];
            $cantidad=$valores['cantidad'];
            $precioUnitario=$valores['precio'];
            $query =$dbBodega->prepare("INSERT INTO dbo.DETALLEREGISTRO (IdRegistro,ArticuloDetalle,Cantidad,PrecioUnitario) VALUES (?,?,?,?)");
            $query->execute([$idRegistro,$articuloDetalle,$cantidad,$precioUnitario]);
        }catch(PDOException $e){
            echo "Error".$e->getMessage()."<br/>";
        }
            
        }

        echo("REGISTRO EXITOSO");
        
        
       
       
    }else if(!empty($_POST['descripcion'])){
        $descripcion=$_POST['descripcion'];
        //$ropa=$_POST['ropa'];
        $codigo=$_POST['codigo'];
        
       try{
       $query= "UPDATE dbo.REGISTRO SET Articulo=?, Descripcion=?, Clasificacion=? WHERE CodigoBarra=?";
        $stmt=$dbBodega->prepare($query);
        $stmt->execute([$codigo,$descripcion,$bandera,$codigoBarra]);

        //echo("dia".$dia."mes".$mes."anio".$anio);
       // echo(".$query.");
       //$query->execute([$unidades,$libras,$ubicacion,$fecha,$empacado,$empaque,$usuario,$producido,$bodega,$observaciones]);
       echo("Actualizacion exitosa".$codigoBarra);
     
        
        }catch(PDOException $e){
            echo "Error".$e->getMessage()."<br/>";
        }
       
    }else{
        echo("No viene identificada");
    }


?>