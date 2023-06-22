<?php

require 'parametros.php';
function conectarLogin($usuario,$password){
    
    
        $conexion=new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE_EXIMP,$usuario,$password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if($conexion){
           // $_SESSION['usuario']=$usuario;
            return true;
        }else{
            return false;
        }
   

}


?>