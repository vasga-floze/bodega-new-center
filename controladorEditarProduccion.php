<?php
    include('conexiones/conectar.php');
    $articulo=$_POST["articulo"];
    $descripcion=$_POST["descripcion"];
    $clasificacion=$_POST["clasificacion"];
    $libras=$_POST["libras"];
    $codigoBarra=$_POST['codigoBarra'];
    $bandera='';
    

    $query =$dbEximp600->prepare("SELECT ARTICULO, DESCRIPCION, CLASIFICACION_2 from consny.ARTICULO where ARTICULO='$articulo'");
    $query->execute();
    $data = $query->fetchAll();


    foreach($data as $value){
        $clasificacionBase=$value["CLASIFICACION_2"];
        $descripcionBase=$value["DESCRIPCION"];
    }

    if($clasificacion != $clasificacionBase){
        $bandera='0';
        echo($bandera);

    }else{
        $bandera='1';
        try{
            $query= "UPDATE dbo.REGISTRO SET Articulo=?, Descripcion=?, Clasificacion=?,Libras=? WHERE CodigoBarra=?";
             $stmt=$dbBodega->prepare($query);
             $stmt->execute([$articulo,$descripcionBase,$clasificacion,$libras,$codigoBarra]);
     
             //echo("dia".$dia."mes".$mes."anio".$anio);
            // echo(".$query.");
            //$query->execute([$unidades,$libras,$ubicacion,$fecha,$empacado,$empaque,$usuario,$producido,$bodega,$observaciones]);
            echo("1");
          
             
        }catch(PDOException $e){
            echo "Error".$e->getMessage()."<br/>";
        }
    }

  

   

?>