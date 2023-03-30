<?php

include('conexiones/conectar.php');

if($_REQUEST['empid'] && $_REQUEST['ropa']){
            $query =$dbEximp600->prepare(
                "SELECT ARTICULO, DESCRIPCION, PRECIO_REGULAR
                FROM consny.ARTICULO
                WHERE (ACTIVO = 'S') 
                AND (ARTICULO ='".$_REQUEST['empid']."')
                AND (CLASIFICACION_1 = 'DETALLE') 
                AND (CLASIFICACION_2 = '".$_REQUEST['ropa']."')
                AND (USA_LOTES = 'S')
                ORDER BY DESCRIPCION, ARTICULO, PRECIO_REGULAR ");

        $query->execute();
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);

        
        echo json_encode($data);
        
}else{
    echo 0;
}
?>