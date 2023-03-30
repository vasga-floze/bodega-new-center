<?php
    session_start();
    $codigoBarra=$_SESSION['cod'];
    
    include('conexiones/conectar.php');
    $nulo=102;
    if(!empty($_POST['json'])){
        $datos=json_decode($_POST['json'],true);
        foreach($datos as $valores){

            try{
            $articuloDetalle=$valores['codigo'];
            $cantidad=$valores['cantidad'];
            $precioUnitario=$valores['precio'];
            $query =$dbBodega->prepare("INSERT INTO dbo.DETALLEREGISTRO (IdRegistro,ArticuloDetalle,Cantidad,PrecioUnitario) VALUES (?,?,?,?)");
            $query->execute([$nulo,$articuloDetalle,$cantidad,$precioUnitario]);
        }catch(PDOException $e){
            echo "Error".$e->getMessage()."<br/>";
        }
            
        }

        echo("REGISTRO EXITOSO");
        
        
       
       
    }else if(!empty($_POST['descripcion'])){
        $descripcion=$_POST['descripcion'];
        $ropa=$_POST['ropa'];
        $codigo=$_POST['codigo'];
        
       try{
       $query= "UPDATE dbo.REGISTRO SET Articulo=?, Descripcion=?, Clasificacion=? WHERE CodigoBarra=?";
        $stmt=$dbBodega->prepare($query);
        $stmt->execute([$codigo,$descripcion,$ropa,$codigoBarra]);

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