<?php
include('conexiones/conectarLogin.php');
session_start();
$usuario=$_POST['usuario'];
$password=$_POST['password'];
$compania=$_POST['compania'];
$_SESSION['compania']=$compania;
$bandera=true;
//echo("usuario:".$usuario."password".$password)
//conectarLogin($usuario,$password,SERVIDOR,DATABASE_EXIMP);

if(conectarLogin($usuario,$password)){
    echo($bandera);
    $_SESSION['usuario']=$usuario;
    //header("location:pruebaLogin.php");
    return;
}




?>