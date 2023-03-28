<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

    $fechaActual= date('m.d.y');
    $fechaComoEntero=strtotime($fechaActual);
    $anio=date("y", $fechaComoEntero);
    $mes=date("m", $fechaComoEntero);
    $dia=date("d", $fechaComoEntero);
    $DesdeLetra = "a";
    $HastaLetra = "z";
    $letraAleatoria = chr(rand(ord($DesdeLetra), ord($HastaLetra)));
    $letraMayuscula = strtoupper($letraAleatoria);
    $variableSession=&valorMaximo();
    $codigoBarra =("".$letraMayuscula.""."P".$dia."".$mes."".$variableSession."".$anio);
    $_SESSION["cod"]=$codigoBarra;
    
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
        $tipo=$_POST['tipoRegistro'];
        $observaciones=$_POST['observaciones'];
        //$variableSession=&valorMaximo();
        
        try{//echo("unidades".$unidades."libras".$libras."ubicacion".$ubicacion."fecha".$fecha."empacado".$empacado."usuario".$usuario."producido".$producido."bodega".$bodega."Observaciones".$observaciones);
        $query= ("INSERT INTO dbo.REGISTRO (CodigoBarra,Unidades,Libras,IdUbicacion,FechaCreacion,EmpacadoPor,IdTipoEmpaque,UsuarioCreacion,ProducidoPor,BodegaCreacion,Observaciones,IdTipoRegistro,Sesion) VALUES (:codigoBarra,:unidades,:libras,:ubicacion,:fecha,:empacado,:empaque,:usuario,:producido,:bodega,:observaciones,:tipoRegistro,:variableSession)");
        $stmt=$dbBodega->prepare($query);
        //$variableSession=&valorMaximo();
        $stmt->bindParam("codigoBarra", $codigoBarra, PDO::PARAM_STR);
        $stmt->bindParam("unidades", $unidades, PDO::PARAM_STR);
        $stmt->bindParam("libras", $libras, PDO::PARAM_STR);
        $stmt->bindParam("ubicacion", $ubicacion, PDO::PARAM_STR);
        $stmt->bindParam("fecha", $fecha, PDO::PARAM_STR);
        $stmt->bindParam("empacado", $empacado, PDO::PARAM_STR);
        $stmt->bindParam("empaque", $empaque, PDO::PARAM_STR);
        $stmt->bindParam("usuario", $usuario, PDO::PARAM_STR);
        $stmt->bindParam("producido", $producido, PDO::PARAM_STR);
        $stmt->bindParam("bodega", $bodega, PDO::PARAM_STR);
        $stmt->bindParam("tipoRegistro", $tipo, PDO::PARAM_STR);
        $stmt->bindParam("observaciones", $observaciones, PDO::PARAM_STR);
        $stmt->bindParam("variableSession", $variableSession, PDO::PARAM_STR);

        
        $stmt->execute();
        


        //echo("dia".$dia."mes".$mes."anio".$anio);
       // echo(".$query.");
       //$query->execute([$unidades,$libras,$ubicacion,$fecha,$empacado,$empaque,$usuario,$producido,$bodega,$observaciones]);
       echo("Registro exitoso");
     
        
        }catch(PDOException $e){
            echo "Error".$e->getMessage()."<br/>";
    }
    
       
    }else{
        echo("No viene identificada");
    }





    /**
     * Retorna un valor maximo de la base de datos Bodega
     * de la tabla dbo.REGISTRO
     * 
     * @return the value of .
     */
    function &valorMaximo(){
        include('conexiones/conectar.php');
        $fechaActual = date('Y-m-d');
        $query =$dbBodega->query("SELECT isnull (max(sesion), 1) AS maximo FROM dbo.REGISTRO WHERE FechaCreacion='".$fechaActual."'AND IdTipoRegistro=1 ");
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        $devuelve=$sesion['maximo'];
        $devuelve=$devuelve+1;
        if($devuelve>0 && $devuelve<10){
            $numero="00$devuelve";
        }else if($devuelve>9 and $devuelve<100){
            $numero="0$devuelve";
        }else{
            $numero=$devuelve;
        }

        return $numero;
    }





    


?>