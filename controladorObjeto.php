<?php
 $datos=json_decode($_POST['json'],true);
    $contador=1;
    foreach ($datos as $key) {
        var_dump($key);
        $descripcion=$key['Descripcion'];
        $codigoBarra=$key['CodigoBarra'];
        echo($contador);
        $contador++;
    }

    echo(json_encode($datos));



?>