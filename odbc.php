<?php 

$dir=realpath("pruebas.mdb") ;
echo $dir;

    try {
      $db_connection = new COM("ADODB.Connection");

$db_connstr = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=". realpath("pruebas.mdb") .";DefaultDir=". realpath("./");

  $db_connection->open($db_connstr);

        
    }
    catch (PDOException $e) {
    echo "NO SE PUEDE CONECTAR CON EL ARCHIVO .mdb<br><br>".$e->getMessage();
    }

session_start();
//$_SESSION['usuario']='juan';
//$_SESSION['paquete']='1234';




?> 