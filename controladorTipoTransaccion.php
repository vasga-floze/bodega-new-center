<?php
    include('conexiones/conectar.php');

    $transaccion=$_POST['transaccion'];
    $bandera=false;
    $query =$dbBodega->prepare("INSERT INTO dbo.TIPOTRANSACCION (TipoTransaccion) VALUES (?)");
    $query->execute([$transaccion]);
    if($query){
        $bandera=true;
        echo($bandera);
    }else{
        echo("Ocurrio un error");
        
    }

?>