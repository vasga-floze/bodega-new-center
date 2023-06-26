<?php
// Obtener los valores enviados desde la solicitud Ajax
$fechaHoraTemporal = $_POST['fechaHoraTemporal'];
$bodegaOrigen = $_POST['bodegaOrigen'];
$bodegaDestino = $_POST['bodegaDestino'];
$fechaOrigen = $_POST['fechaOrigen'];
$json = $_POST['json'];
$datosDecodificados=json_decode($json,true);
foreach ($datosDecodificados as $key) {
    $bodegaOrigen=$key["articulo"];
}

// Realizar el procesamiento necesario con los datos recibidos
// ...

// Ejemplo de impresión de los valores recibidos
echo "Fecha y hora temporal: " . $fechaHoraTemporal . "<br>";
echo "Bodega origen: " . $bodegaOrigen . "<br>";
echo "Bodega destino: " . $bodegaDestino . "<br>";
echo "Fecha origen: " . $fechaOrigen . "<br>";
echo "JSON: " . $bodegaOrigen;




?>