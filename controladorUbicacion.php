<?php
    include('conexiones/conectar.php');

    $ubicacion=$_POST['ubicacion'];
    $bandera=false;
    $query =$dbBodega->prepare("INSERT INTO dbo.UBICACION (Ubicacion) VALUES (?)");
    $query->execute([$ubicacion]);
    if($query){
        $bandera=true;
        echo($bandera);
    }else{
        echo("Ocurrio un error");
        
    }

?>