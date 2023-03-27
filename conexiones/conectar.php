<?php


require 'parametros.php';


$dbEximp600 = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE_EXIMP,USUARIO,PASSWORD);
$dbBodega = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE_BODEGA,USUARIO,PASSWORD);

function connectERP(){

    $server ="192.168.0.44";
    $user ="MCAMPOS";
    $password ="exmcampos";
    $bd ="EXIMP600";


}

?>