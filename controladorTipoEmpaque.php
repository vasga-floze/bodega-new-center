<?php
    include('conexiones/conectar.php');

    $empaque=$_POST['empaque'];
    $bandera=false;
    $query =$dbBodega->prepare("INSERT INTO dbo.TIPOEMPAQUE (TipoEmpaque) VALUES (?)");
    $query->execute([$empaque]);
    if($query){
        $bandera=true;
        echo($bandera);
    }else{
        echo("Ocurrio un error");
        
    }

?>