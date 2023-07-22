<?php
include('conexiones/conectar.php');

$fecha = $_POST["fecha"];
$documento = $_POST["documento"];

$response["data"] = array();
$consulta = "SELECT ROW_NUMBER() OVER(
    ORDER BY articulo) AS RowNum, 
     articulo, descripcion, sum(Libras) Cantidad, BodegaActual
        FROM registro WHERE fechaCreacion='$fecha' AND 
        documento_inv='$documento' AND estado='PROCESO'
        GROUP BY articulo, descripcion, BodegaActual,libras";

$resultado = $dbBodega->prepare($consulta);

if (!$resultado->execute()) {
    $errorInfo = $resultado->errorInfo();
    $response["mensaje"] = "No se puede obtener los datos" . $errorInfo[2];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    return;
}

$data = $resultado->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key) {
    $row=array();
    $row["nombreArticulo"]=$key["articulo"];
    $row["articulo"]=$key["RowNum"];
    $row["descripcion"]=$key["descripcion"];
    $row["Cantidad"]=$key["Cantidad"];
    $row["bodega"]=$key["BodegaActual"];
    $row["subtotal"]=0;
    $row["porcentaje"]=0;
    $row["totalArticulo"]=0;
    $row["precioUnitario"]=0;
    $response["data"][]=$row;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
