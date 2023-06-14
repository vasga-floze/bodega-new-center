<?php
include('conexiones/conectar.php');
$documentoInventario=$_GET['fechaTemporal'];
$codigo=$_GET['codigo'];
$accion=$_GET['accion'];

if(!$accion="1"){
    $query =$dbBodega->prepare("DELETE FROM TRANSACCION where IdTipoTransaccion=3 and 
    Documento_Inv='$documentoInventario' and CodigoBarra='$codigo' and Estado is NULL
    ");
}else{
    $query =$dbBodega->prepare("DELETE FROM TRANSACCION where IdTipoTransaccion=3 and 
Documento_Inv='$documentoInventario' and CodigoBarra='$codigo' and Estado is NULL
");
}

//echo("documento".$documentoInventario."codigo".$codigo);
//$codigo=$_POST['codigoBarra'];


if($query->execute()){
    echo("Se elimino correctamente");
}else{
    $errorInfo =$query->errorInfo();
    echo "Error al eliminar los datos:". $errorInfo[2];

}



//$json_resultados = json_encode($data);
//header('Content-Type: application/json');
//echo $json_resultados;


?>