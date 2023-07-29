<?php
    session_start();
    include('conexiones/conectar.php');
    $articulo=$_POST["articulo"];
    $descripcion=$_POST["descripcion"];
    $clasificacion=$_POST["clasificacion"];
    $respuesta=$_SESSION['compania'];
    $libras=$_POST["libras"];
    $codigoBarra=$_POST['codigoBarra'];
    $dirigido=$_POST['dirigido'];
    $bandera='';
    
    $response=array();

    $query =$dbEximp600->prepare("SELECT ARTICULO, DESCRIPCION, CLASIFICACION_2 from ".$respuesta.".ARTICULO where ARTICULO='$articulo'");
    $query->execute();
    $data = $query->fetchAll();


    //
    foreach($data as $value){
        $clasificacionBase=$value["CLASIFICACION_2"];
        $descripcionBase=$value["DESCRIPCION"];
    }



    if($clasificacion != $clasificacionBase){

        $response["success"]="0";
        $response["message"]="No se puede editar";
        echo(json_encode($response));
        return;
    }

    $query= "UPDATE dbo.REGISTRO SET Articulo=?, 
                                Descripcion=?, 
                                Clasificacion=?,
                                Libras=?,
                                EmpresaDestino=? WHERE CodigoBarra=?";


    $stmt=$dbBodega->prepare($query);
    if(!$stmt->execute([ $articulo,
                        $descripcionBase,
                        $clasificacion,
                        $libras,
                        $dirigido,
                        $codigoBarra ])){
        $errorInfo=$stmt->errorInfo();
        $response["success"]="0";
        $response["message"]="No se puede editar la produccion".$errorInfo[2];
        echo(json_encode($response));
        return;

    }

    $response["success"]="1";
    $response["message"]="Editado correctamente";
    echo(json_encode($response));


?>