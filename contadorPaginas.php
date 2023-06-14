<?php
 include('conexiones/conectar.php');
 //$contador=$_GET['contador'];
    $codigoBarra=$_POST["codigoBarra"];
    $accion=$_POST["accion"];
    $response=array();
   
 if($accion==="1"){
    $query =$dbBodega->prepare("SELECT DETALLEREGISTRO.ContadorImpresiones FROM DETALLEREGISTRO INNER JOIN REGISTRO ON DETALLEREGISTRO.IdRegistro = REGISTRO.IdRegistro where CodigoBarra='$codigoBarra'");
    $query->execute();
    $data = $query->fetchAll();

    if(!$data > 0){
        $response["message"]="El registro no existe en el detalle";
        $response["status"]="error";
        echo json_encode($response);
        return;
    }


    foreach ($data as $key) {
        $contadorImpresiones=$key["ContadorImpresiones"];
    }

    $contadorImpresiones++;
    $query1=$dbBodega->prepare("UPDATE DETALLEREGISTRO SET ContadorImpresiones=? WHERE IdRegistro=(SELECT IdRegistro FROM REGISTRO WHERE CodigoBarra=?)");
    $resultad=$query1->execute([$contadorImpresiones,$codigoBarra]);

    if(!$resultad){
        $errorInfo=$query1->errorInfo();
        $response["message"]="No se ha podido registrar".$errorInfo[2];
        echo json_encode($response);
        return;
    }
    $response["message"]="Se ha actualizado el contador de paginas";
    $response["status"]="success";
    echo json_encode($response);

 }else if($accion==="2"){
    $query =$dbBodega->prepare(
        "SELECT ContadorImpresiones FROM dbo.REGISTRO WHERE CodigoBarra='$codigoBarra'");
    $query->execute();
    $data = $query->fetchAll();
    foreach ($data as $key) {
        $contadorImpresiones=$key["ContadorImpresiones"];
    }
    $insertarContador=$contadorImpresiones+1;
    $query1=$dbBodega->prepare("UPDATE dbo.REGISTRO SET ContadorImpresiones=? WHERE CodigoBarra=?");
    $resultad=$query1->execute([$insertarContador,$codigoBarra]);
    if($resultad===TRUE){
        echo("CAMBIOS GUARDADOS");
    }else{
        $errorInfo=$query1->errorInfo();
        echo("OCURRIO UN ERROR".$errorInfo[2]);
    }
}else{
    $query =$dbBodega->prepare("SELECT DETALLEREGISTRO.ContadorImpresiones FROM DETALLEREGISTRO INNER JOIN REGISTRO ON DETALLEREGISTRO.IdRegistro = REGISTRO.IdRegistro where CodigoBarra='$codigoBarra'");
    $query->execute();
    $data = $query->fetchAll();

    if(!$data > 0){
        $response["message"]="El registro no existe en el detalle";
        $response["status"]="error";
        echo json_encode($response);
        return;
    }


    foreach ($data as $key) {
        $contadorImpresiones=$key["ContadorImpresiones"];
    }

    $contadorImpresiones++;
    $query1=$dbBodega->prepare("UPDATE DETALLEREGISTRO SET ContadorImpresiones=? WHERE IdRegistro=(SELECT IdRegistro FROM REGISTRO WHERE CodigoBarra=?)");
    $resultad=$query1->execute([$contadorImpresiones,$codigoBarra]);

    if(!$resultad){
        $errorInfo=$query1->errorInfo();
        $response["message"]="No se ha podido registrar".$errorInfo[2];
        echo json_encode($response);
        return;
    }
    $response["message"]="Se ha actualizado el contador de paginas adhesivas";
    $response["status"]="success";
    echo json_encode($response);
}

?>