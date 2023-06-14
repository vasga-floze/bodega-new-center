<?php
 session_start();
include('conexiones/conectar.php');
$documento_inv="5/23/2023, 3:17:24 PM";


            $query =$dbBodega->prepare(
                "select top 1 bodega from TRANSACCION where naturaleza='E' and Documento_Inv='$documento_inv'");

        $query->execute();
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);

        
        echo json_encode($data);
        

?>