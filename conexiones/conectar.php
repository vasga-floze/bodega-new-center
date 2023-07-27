<?php


require 'parametros.php';


$dbEximp600 = new PDO("sqlsrv:server=".SERVIDOR.";database=".SOFTLAND,USUARIO,PASSWORD);
$dbBodega = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE_BODEGA,USUARIO,PASSWORD);



?>