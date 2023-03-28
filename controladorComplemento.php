<?php
    session_start();
    $codigoBarra=$_SESSION['cod'];
    
    include('conexiones/conectar.php');
    if(!empty($_POST['descripcion'])){
        $descripcion=$_POST['descripcion'];
        $ropa=$_POST['ropa'];
        $codigo=$_POST['codigo'];
        
        
        //$variableSession=&valorMaximo();
        
       /* try{//echo("unidades".$unidades."libras".$libras."ubicacion".$ubicacion."fecha".$fecha."empacado".$empacado."usuario".$usuario."producido".$producido."bodega".$bodega."Observaciones".$observaciones);
       /*$query= (
            "UPDATE dbo.REGISTRO SET 
            Articulo=?,Descripcion=?,
            VALUES 
            (:codigoBarra,:unidades,:libras)");
        $stmt=$dbBodega->prepare($query);
        //$variableSession=&valorMaximo();
        

        
        $stmt->execute();*/
        


        //echo("dia".$dia."mes".$mes."anio".$anio);
       // echo(".$query.");
       //$query->execute([$unidades,$libras,$ubicacion,$fecha,$empacado,$empaque,$usuario,$producido,$bodega,$observaciones]);
       echo("Registro exitoso".$codigoBarra);
     
        
        /*}catch(PDOException $e){
            echo "Error".$e->getMessage()."<br/>";*/
    
    
       
    }else{
        echo("No viene identificada");
    }





    





    


?>