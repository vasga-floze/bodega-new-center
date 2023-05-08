<?php
    include('conexiones/conectar.php');
    $estado=$_POST["estado"];
    $codigoBarra=$_POST["codigoBarra"];
    $bandera='ELIMINADO';
    //echo("estado".$estado."codigoBarra".$codigoBarra);

    //echo("ESTADO".$estado."CODIGOBARRA".$codigoBarra)
    //echo("ESTADO".$estadoBase);

    if($estado=='ELIMINADO'){

        echo("eliminado");
        return;
    }

    if($estado=='FINALIZADO'){
        echo("finalizado");
        return;
    }

  



    try{
        $query= "UPDATE dbo.REGISTRO SET Estado=? WHERE CodigoBarra=?";
         $stmt=$dbBodega->prepare($query);
         $stmt->execute([$bandera,$codigoBarra]);
 
         //echo("dia".$dia."mes".$mes."anio".$anio);
        // echo(".$query.");
        //$query->execute([$unidades,$libras,$ubicacion,$fecha,$empacado,$empaque,$usuario,$producido,$bodega,$observaciones]);
        echo("1");
      
         
    }catch(PDOException $e){
        echo "Error".$e->getMessage()."<br/>";
    }
	

    
  



?>