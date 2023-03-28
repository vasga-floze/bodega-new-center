<?php


require 'parametros.php';


$dbEximp600 = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE_EXIMP,USUARIO,PASSWORD);
$dbBodega = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE_BODEGA,USUARIO,PASSWORD);



?>