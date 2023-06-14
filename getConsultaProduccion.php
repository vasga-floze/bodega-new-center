<?php
include('conexiones/conectar.php');
$estado=$_GET["estado"];
$fecha=$_GET["fecha"];
$usuario=$_GET["usuario"];
//$fecha='2023-04-29';
//$usuario='TODOS';

if($estado==='TODOS'){
    $estado='NULL';
}
if($usuario==='TODOS'){
    $usuario='NULL';
}
$query =$dbBodega->prepare("DECLARE @ESTADO nvarchar(25) = '".$estado."'
DECLARE @FECHA DATE = '$fecha'
DECLARE @USUARIO NVARCHAR(50) = '".$usuario."'

IF @ESTADO='NULL'
SET @ESTADO=NULL

IF @USUARIO='NULL'
SET @USUARIO=NULL

SELECT Articulo,BodegaCreacion, Descripcion, Clasificacion, CodigoBarra, Libras, Unidades, UsuarioCreacion, Estado,FechaCreacion
FROM REGISTRO
WHERE (FechaCreacion = @FECHA) AND (Estado BETWEEN ISNULL(@ESTADO, N'A') AND ISNULL(@ESTADO, N'ZZZZZZZZZZZZZZZZZ')) 
AND (UsuarioCreacion BETWEEN ISNULL(@USUARIO, N'A') AND ISNULL(@USUARIO, N'ZZZZZZZZZZZZZZZZZ'))
");
$query->execute();
$data = $query->fetchAll(\PDO::FETCH_ASSOC);

$json_resultados = json_encode($data);
//header('Content-Type: application/json');
echo $json_resultados;
?>