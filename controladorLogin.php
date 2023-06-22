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
    echo($bandera);
    //header("location:pruebaLogin.php");
    return;
}




?>