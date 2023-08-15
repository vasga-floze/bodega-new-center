<?php 
include('conexiones/conectar.php');
$documentoInventario=$_GET['fechaTemporal'];
//$codigo=$_POST['codigoBarra'];
$query =$dbBodega->prepare("SELECT        REGISTRO.Articulo, REGISTRO.Descripcion, COUNT(TRANSACCION.CodigoBarra)/2 AS Cantidad, SUM(REGISTRO.Libras)/2 AS Libras
FROM            TRANSACCION INNER JOIN
                         REGISTRO ON TRANSACCION.CodigoBarra = REGISTRO.CodigoBarra
WHERE       transaccion.Documento_Inv='$documentoInventario' AND (TRANSACCION.IdTipoTransaccion=7)
GROUP BY  REGISTRO.Articulo, REGISTRO.Descripcion
");
$query->execute();
$data = $query->fetchAll(\PDO::FETCH_ASSOC);

$json_resultados = json_encode($data);
//header('Content-Type: application/json');
echo $json_resultados;
 ?>