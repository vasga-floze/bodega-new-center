<?php
include('conexiones/conectar.php');
$bandera=$_GET["documento"];
//$codigo=$_POST['codigoBarra'];
$query =$dbBodega->prepare("SELECT        MAX(CASE WHEN Naturaleza = 'S' THEN Bodega END) AS 'BODEGA ORIGEN', MAX(CASE WHEN Naturaleza = 'E' THEN Bodega END) AS 'BODEGA DESTINO', TRANSACCION.Fecha, REGISTRO.Articulo, REGISTRO.Descripcion, 
TRANSACCION.CodigoBarra, REGISTRO.Libras
FROM            TRANSACCION INNER JOIN
REGISTRO ON TRANSACCION.CodigoBarra = REGISTRO.CodigoBarra
WHERE        (TRANSACCION.Documento_Inv = '$bandera')
GROUP BY TRANSACCION.Fecha, TRANSACCION.CodigoBarra, REGISTRO.Articulo, REGISTRO.Descripcion, REGISTRO.Libras
");
$query->execute();
$data = $query->fetchAll(\PDO::FETCH_ASSOC);

$json_resultados = json_encode($data);
//header('Content-Type: application/json');
echo $json_resultados;
 ?>


