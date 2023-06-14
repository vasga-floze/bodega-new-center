<?php
include('conexiones/conectar.php');
$codigoBarra=$_POST['codigo'];

$query =$dbBodega->prepare("SELECT * FROM REGISTRO WHERE CodigoBarra='$codigoBarra'");
$query->execute();
$data = $query->fetchAll(\PDO::FETCH_ASSOC);

$json_resultados = json_encode($data);
header('Content-Type: application/json');
echo $json_resultados;





?>