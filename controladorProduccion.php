<?php
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

    $fechaActual= date('m.d.y');
    $fechaComoEntero=strtotime($fechaActual);
    $anio=date("y", $fechaComoEntero);
    $mes=date("m", $fechaComoEntero);
    $dia=date("d", $fechaComoEntero);
    $DesdeLetra = "a";
    $HastaLetra = "z";
    $letraAleatoria = chr(rand(ord($DesdeLetra), ord($HastaLetra)));
    $codigoBarra =("".$letraAleatoria.""."P".$dia."".$mes."".$anio);
    //$resultado = $dividendo / $divisor;
    error_reporting(E_ALL & ~E_NOTICE);
    include('conexiones/conectar.php');
    if(!empty($_POST['unidades'])){
        $unidades=$_POST['unidades'];
        $libras=$_POST['libras'];
        $ubicacion=$_POST['ubicacion'];
        $fecha=$_POST['fecha'];
        $empacado=$_POST['empacado'];
        $empaque=$_POST['empaque'];
        $usuario=$_POST['usuario'];
        $producido=$_POST['producido'];
        $bodega=$_POST['bodega'];
        $observaciones=$_POST['observaciones'];
        try{//echo("unidades".$unidades."libras".$libras."ubicacion".$ubicacion."fecha".$fecha."empacado".$empacado."usuario".$usuario."producido".$producido."bodega".$bodega."Observaciones".$observaciones);
        $query= ("INSERT INTO dbo.REGISTRO (Unidades,Libras,IdUbicacion,FechaCreacion,EmpacadoPor,IdTipoEmpaque,UsuarioCreacion,ProducidoPor,BodegaCreacion,Observaciones) VALUES (:unidades,:libras,:ubicacion,:fecha,:empacado,:empaque,:usuario,:producido,:bodega,:observaciones)");
        $stmt=$dbBodega->prepare($query);
        $stmt->bindParam("unidades", $unidades, PDO::PARAM_STR);
        $stmt->bindParam("libras", $libras, PDO::PARAM_STR);
        $stmt->bindParam("ubicacion", $ubicacion, PDO::PARAM_STR);
        $stmt->bindParam("fecha", $fecha, PDO::PARAM_STR);
        $stmt->bindParam("empacado", $empacado, PDO::PARAM_STR);
        $stmt->bindParam("empaque", $empaque, PDO::PARAM_STR);
        $stmt->bindParam("usuario", $usuario, PDO::PARAM_STR);
        $stmt->bindParam("producido", $producido, PDO::PARAM_STR);
        $stmt->bindParam("bodega", $bodega, PDO::PARAM_STR);
        $stmt->bindParam("observaciones", $observaciones, PDO::PARAM_STR);
        
        $stmt->execute();
        


        //echo("dia".$dia."mes".$mes."anio".$anio);
       // echo(".$query.");
       //$query->execute([$unidades,$libras,$ubicacion,$fecha,$empacado,$empaque,$usuario,$producido,$bodega,$observaciones]);
       echo($codigoBarra);
        
        }catch(PDOException $e){
            echo "Error".$e->getMessage()."<br/>";
    }
       
    }else{
        echo("No viene identificada");
    }






    


?>