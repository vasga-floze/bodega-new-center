<?php
include('conexiones/conectarLogin.php');
session_start();
$usuario=$_POST['usuario'];
$password=$_POST['password'];


//$usuarioTienda=isset($_SESSION["usuarioTienda"])? $_SESSION["usuarioTienda"]:'';
$bandera=true;

//echo("usuario:".$usuario."password".$password)
//conectarLogin($usuario,$password,SERVIDOR,DATABASE_EXIMP);

if(conectarLogin($usuario,$password)){
    $_SESSION['usuario']=$usuario;
    $_SESSION['password']=$password;
    $dbEximp600 = new PDO("sqlsrv:server=".SERVIDOR.";database=".SOFTLAND,USUARIO,PASSWORD);
    $query=$dbEximp600->prepare(
        "SELECT BODEGA, 
                HAMACHI, 
                BASE, 
                PAQUETE, 
                ESQUEMA, 
                TIPO
        FROM USUARIOBODEGA
        WHERE (USUARIO = '$usuario') AND (HAMACHI IS NOT NULL)
        ORDER BY BODEGA
    ");

        $query->execute();
        $data=$query->fetchAll();
        $response=array();
        foreach ($data as $valores) {
            $response[]=$valores;
        }

        /**


        *@param bodega contiene la bodega del arreglo de la consulta a la tiena
        *@param hamachi contiene la IP del arreglo de la consulta a la tienda
        *@param base contiene la base a la que se va a conectar
        */  
     
            foreach ($response as $key) {
                $bodega=$key["BODEGA"];
                $hamachi=$key["HAMACHI"];
                $base=$key["BASE"];
                $bodega=$key["BODEGA"];
                $paqueteInventario=$key["PAQUETE"];
                $esquema=$key["ESQUEMA"];
                # code...
            }
            $_SESSION['BASE']=$base;
            $_SESSION['PAQUETE']=$paqueteInventario;
            $_SESSION['compania']=$esquema;
            //$esquemaInformacion=$_SESSION['compania'];            

        echo($bandera);
    //header("location:pruebaLogin.php");
    return;
}




?>