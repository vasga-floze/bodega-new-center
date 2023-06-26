<?php
 session_start();
include('conexiones/conectar.php');
$bandera=$_SESSION['banderaArticulo'];
$respuesta=$_SESSION['compania'];
if($_REQUEST['empid']){
            $query =$dbEximp600->prepare(
                "SELECT        ".$respuesta.".ARTICULO.ARTICULO, ".$respuesta.".ARTICULO.DESCRIPCION, ".$respuesta.".ARTICULO_PRECIO.PRECIO
                FROM            ".$respuesta.".ARTICULO INNER JOIN
                                         ".$respuesta.".ARTICULO_PRECIO ON ".$respuesta.".ARTICULO.ARTICULO = ".$respuesta.".ARTICULO_PRECIO.ARTICULO
                WHERE        (".$respuesta.".ARTICULO.ACTIVO = 'S') AND (".$respuesta.".ARTICULO.UNIDAD_ALMACEN = '59') 
                AND (".$respuesta.".ARTICULO.CLASIFICACION_2 = '$bandera') AND (".$respuesta.".ARTICULO.USA_LOTES = 'S') AND 
                                         (".$respuesta.".ARTICULO_PRECIO.NIVEL_PRECIO = 'REGULAR')
                                         AND (".$respuesta.".ARTICULO.ARTICULO ='".$_REQUEST['empid']."')
                                         ORDER BY ".$respuesta.".ARTICULO.DESCRIPCION, 
                                         ".$respuesta.".ARTICULO.ARTICULO
                
                ");

        $query->execute();
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);

        
        echo json_encode($data);
        
}else{
    echo json_encode($respuesta);
}
?>