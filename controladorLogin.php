<?php
include('conexiones/conectarLogin.php');
session_start();
$usuario=$_POST['usuario'];
$password=$_POST['password'];
$compania=$_POST['compania'];
$_SESSION['compania']=$compania;
//$usuarioTienda=isset($_SESSION["usuarioTienda"])? $_SESSION["usuarioTienda"]:'';
$bandera=true;

//echo("usuario:".$usuario."password".$password)
//conectarLogin($usuario,$password,SERVIDOR,DATABASE_EXIMP);

if(conectarLogin($usuario,$password)){
    $_SESSION['usuario']=$usuario;
    $_SESSION['password']=$password;
    $dbEximp600 = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE_EXIMP,USUARIO,PASSWORD);
    $query=$dbEximp600->prepare(
        "SELECT USUARIOBODEGA.BODEGA, 
                ".$compania.".BODEGA.NOMBRE, 
                USUARIOBODEGA.HAMACHI, 
                USUARIOBODEGA.BASE,
                USUARIOBODEGA.PAQUETE
        FROM USUARIOBODEGA 
        INNER JOIN
            ".$compania.".BODEGA ON USUARIOBODEGA.BODEGA = 
            ".$compania.".BODEGA.BODEGA
        WHERE (USUARIOBODEGA.TIPO = 'TIENDA' 
        AND USUARIO='$usuario') 
        and hamachi is not null
        ORDER BY 1
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
        if (!empty($response)) {
            foreach ($response as $key) {
                $bodega=$key["BODEGA"];
                $hamachi=$key["HAMACHI"];
                $base=$key["BASE"];
                $bodega=$key["BODEGA"];
                $paqueteInventario=$key["PAQUETE"];
                # code...
            }
            $_SESSION['BASE']=$base;
            $_SESSION['PAQUETE']=$paqueteInventario;
        }

        echo($bandera);
    //header("location:pruebaLogin.php");
    return;
}




?>