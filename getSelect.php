<?php

include('conexiones/conectar.php');

if($_REQUEST['empid']){
            $query =$dbEximp600->prepare(
                "SELECT 
        ARTICULO,
        DESCRIPCION,
        CLASIFICACION_2

        FROM consny.ARTICULO
        WHERE activo='S' AND ARTICULO ='".$_REQUEST['empid']."'
        
        AND clasificacion_1<>'DETALLE'
        AND clasificacion_2='ROPA'
        ORDER BY len(articulo),articulo
                            ");

        $query->execute();
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);

        
        echo json_encode($data);
        
}else{
    echo 0;
}
?>