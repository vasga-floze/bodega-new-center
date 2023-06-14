<?php
include('conexiones/conectar.php');
$fechaInicio=(empty($_GET["fechaInicio"]))? 'NULL' : "'".$_GET["fechaInicio"]."'";
$fechaFinal=(empty($_GET["fechaFinal"]))? 'NULL': "'".$_GET["fechaFinal"]."'";
$codigoArticulo=(empty($_GET["codigoArticulo"]))? 'NULL': "'".$_GET['codigoArticulo']."'";
$codigoBarra=(empty($_GET["codigoBarra"]))? 'NULL': "'".$_GET['codigoBarra']."'";
//$data="fechaInicio".$fechaInicio."fechaFinal".$fechaFinal."codigoArticulo".$codigoArticulo."codigoBarra".$codigoBarra;
//echo(json_encode($data));
$query=$dbBodega->prepare("DECLARE @CODIGOBARRA nvarchar(25)=$codigoBarra
DECLARE @articulo nvarchar(10)=$codigoArticulo
declare @fechai date=$fechaInicio
declare @fechaf date=$fechaFinal
SELECT        REGISTRO.Articulo, REGISTRO.Descripcion,REGISTRO.FechaCreacion, REGISTRO.Clasificacion, REGISTRO.Unidades, UBICACION.Ubicacion, REGISTRO.CodigoBarra
FROM            REGISTRO INNER JOIN
                         UBICACION ON REGISTRO.IdUbicacion = UBICACION.IdUbicacion
WHERE        (REGISTRO.CodigoBarra BETWEEN ISNULL(@codigobarra, N'A') AND ISNULL(@codigobarra, N'ZZZZZZZZZZZZZZZ'))
and Articulo  between isnull(@articulo,'A') and isnull(@articulo,'ZZZZZZZZZZZZZZZ')
and FechaCreacion between isnull(@fechai,'2000-01-01') and isnull(@fechaf,'2050-12-31')
AND Estado <>'ELIMINADO'
-----
SELECT        REGISTRO.CodigoBarra, DETALLEREGISTRO.ArticuloDetalle, ex.descripcion,  DETALLEREGISTRO.Cantidad
FROM            REGISTRO INNER JOIN
                         DETALLEREGISTRO ON REGISTRO.IdRegistro = DETALLEREGISTRO.IdRegistro
                                        INNER JOIN
                                        EXIMP600.consny.articulo ex On registro.articulo=ex.articulo
WHERE        (REGISTRO.CodigoBarra BETWEEN ISNULL(@codigobarra, N'A') AND ISNULL(@codigobarra, N'ZZZZZZZZZZZZZZZ')) AND (REGISTRO.Articulo BETWEEN ISNULL(@articulo, N'A') AND ISNULL(@articulo, 
                         N'ZZZZZZZZZZZZZZZ')) AND (REGISTRO.FechaCreacion BETWEEN ISNULL(@fechai, '2000-01-01') AND ISNULL(@fechaf, '2050-12-31'))
");
$query->execute();
$data = $query->fetchAll(\PDO::FETCH_ASSOC);
$json_resultado=json_encode($data);
echo($json_resultado);
?>