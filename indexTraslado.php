<?php
    session_start();

    if(!isset($_SESSION['usuario'])){
        //header("Location: indexProduccion.php");
        header('Location: index.php');
    
    }else{
        //include('conexiones/conectar.php');

       
        $usuario=$_SESSION['usuario'];
        $respuesta=$_SESSION['compania'];
        $suma = 0;
        //$doc_consecutivo_ci='';
       
        //echo($doc_consecutivo_ci);
        
        if(isset($_SESSION['datos'])){
            $datos=$_SESSION['datos'];
            foreach($datos as $data){
                $cantidad=$data['cantidad'];
                $suma += $cantidad; 
            }
        }
        //echo("La suma de la cantidad ".$suma);
        //echo("La suma de la cantidad ".$unidades);
        $fechaActual = date('Y-m-d');
        include('conexiones/conectar.php');

        if(isset($_GET['doc'])){
            

            $documento_inv=$_GET['doc'];
            $query =$dbBodega->prepare("SELECT        MAX(CASE WHEN Naturaleza = 'S' THEN transaccion.Bodega END) AS 'BODEGA ORIGEN', 
            MAX(CASE WHEN NATURALEZA='S' THEN ex.NOMBRE END) NOMBRE_BO, 
            MAX(CASE WHEN Naturaleza = 'E' THEN transaccion.Bodega END) AS 'BODEGA DESTINO',
        MAX(CASE WHEN NATURALEZA='E' THEN ex.NOMBRE END) NOMBRE_BD, 
            TRANSACCION.Fecha, 
                REGISTRO.Articulo, REGISTRO.Descripcion, TRANSACCION.CodigoBarra, REGISTRO.Libras
    FROM            TRANSACCION INNER JOIN
                REGISTRO ON TRANSACCION.CodigoBarra = REGISTRO.CodigoBarra INNER JOIN
                EXIMP600.".$respuesta.".BODEGA AS ex ON TRANSACCION.Bodega = ex.BODEGA
    WHERE        (TRANSACCION.Documento_Inv = '$documento_inv')
    GROUP BY TRANSACCION.Fecha, TRANSACCION.CodigoBarra, REGISTRO.Articulo, REGISTRO.Descripcion, REGISTRO.Libras
    ");
    $query->execute();
    $data = $query->fetchAll();
    var_dump($data);
    $bodegaOrigen='';
    $bodegaDestino='';
    $bodegaOrigenNombre='';
    $bodegaDestinoNombre='';
    $fecha='';
    foreach ($data as $key) {
        $bodegaOrigencodigo=$key['BODEGA ORIGEN'];   
        $bodegaDestinocodigo=$key['BODEGA DESTINO'];
        $bodegaOrigenNombre=$key['NOMBRE_BO'];
        $bodegaDestinoNombre=$key['NOMBRE_BD'];
        $fecha=$key['Fecha'];
        

        # code...
    }
    echo($documento_inv);
        }
        if(isset($_GET['pendiente'])){
            $pendiente=$_GET['pendiente'];
        }
        $documento=isset($_SESSION['documentoInventarioPdf'])?$_SESSION['documentoInventarioPdf']:'';

        //$documento_inv=$_GET['doc'];
       
        
        /*$queryConsecutivo=$dbEximp600->prepare("SELECT CONSECUTIVO, SIGUIENTE_CONSEC FROM ".$respuesta.".CONSECUTIVO_CI WHERE CONSECUTIVO='TRASLADO'");
        $queryConsecutivo->execute();
        $dataQueryConsecutivo=$queryConsecutivo->fetchAll();
        //var_dump($dataQueryConsecutivo);
        foreach ($dataQueryConsecutivo as $key) {
            $doc_consecutivo_ci=$key['SIGUIENTE_CONSEC'];
            # code...
        }*/
        
       
        //echo($bodegaOrigen."".$bodegaDestino);
       
        //session_destroy();
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Static Navigation - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="cssmenu/estilo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css">
    <!-- Or for RTL support -->

    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

</head>

<body>


    <div id="layoutSidenav">
        <?php
            include('menuBootstrap.php');

        ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Traslados</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Traslados</li>
                    </ol>
                    <!-----COMPLEMENTO DETALLE PAQUETE ----->




                    <!---COMPLEMENTO DETALLE-------->

                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="formularioFinalizar">
                                <div class="row justify-content-start form-floating mb-3">

                                    <?php if(empty($documento_inv)):?>
                                    <div class="col-4">
                                        <label class="mt-3" for="inputEmail ">Bodega origen</label>

                                        <select id="bodegaOrigen" name="bodegaOrigen" class="form-select">
                                            <option selected></option>
                                            <?php
                                                        //$db=connectERP();
                                                        $query =$dbEximp600->prepare("SELECT bodega,nombre FROM ".$respuesta.".BODEGA WHERE nombre not like '%(N)%' order by bodega");
                                                        $query->execute();
                                                        $data = $query->fetchAll();
                                                        foreach ($data as $valores):
                                                            echo '<option value="'.$valores["bodega"].'">'.$valores["bodega"].'--'.$valores["nombre"].'</option>';
                                                        endforeach;
                                                    ?>
                                        </select>

                                    </div>
                                    <?php else:?>

                                    <?php if($_GET['pendiente']){ 
                                        $pendiente=$_GET['pendiente'];

                                    ?>
                                    <div class="col-4">
                                        <label class="mt-3" for="inputEmail ">Bodega origen</label>
                                        <input type="text" id="documentoInv" value="<?php echo($documento)?>">
                                        <select id="bodegaOrigen2" name="bodegaOrigen2" class="form-select">
                                            <option value="<?php echo($bodegaOrigencodigo)?>">
                                                <?php echo($bodegaOrigencodigo)?> --- <?php echo($bodegaOrigenNombre)?>
                                            </option>

                                        </select>

                                    </div>

                                    <?php } ?>
                                    <?php endif;?>

                                    <?php if(empty($documento_inv)):?>
                                    <div class="col-4">
                                        <label class="mt-3" for="inputEmail ">Bodega destino</label>
                                        <select id="bodegaDestino" name="bodegaDestino" class="form-select">
                                            <option value=""></option>
                                            <?php
                                                    //$db=connectERP();
                                                    $query =$dbEximp600->prepare("SELECT bodega,nombre FROM ".$respuesta.".BODEGA WHERE nombre not like '%(N)%'");
                                                    $query->execute();
                                                    $data = $query->fetchAll();
                                                    foreach ($data as $valores):
                                                        echo '<option value="'.$valores["bodega"].'">'.$valores["bodega"].'--'.$valores["nombre"].'</option>';
                                                    endforeach;
                                                ?>

                                        </select>

                                    </div>
                                    <?php else:?>
                                    <div class="col-4">
                                        <label class="mt-3" for="inputEmail ">Bodega destino</label>

                                        <select id="bodegaDestino2" name="bodegaDestino2" class="form-select">
                                            <option id="opcion" value=""></option>
                                            <?php
                                                    //$db=connectERP();
                                                    $query =$dbEximp600->prepare("SELECT bodega,nombre FROM ".$respuesta.".BODEGA WHERE nombre not like '%(N)%'");
                                                    $query->execute();
                                                    $data = $query->fetchAll();
                                                    foreach ($data as $valores):
                                                ?>
                                            <option value="<?php echo $valores['bodega']?>">
                                                <?php echo $valores['bodega']?> ---- <?php echo $valores['nombre']?>
                                            </option>
                                            <?php
                                                   endforeach
                                                   ?>
                                        </select>

                                    </div>
                                    <div class="col-4">
                                        <label class="mt-3" for="inputEmail ">Bodega destino guardado</label>

                                        <?php if(empty($bodegaDestinocodigo)):?>
                                        <input type="text" class="form-control" id="bodegaInputDestino" value="">

                                        <?php else: ?>
                                        <input type="text" class="form-control" id="bodegaInputDestino"
                                            value="<?php echo($bodegaDestinocodigo)?>">
                                        <?php endif;?>
                                        <input type="text" class="form-control" id="bodegaLocal"
                                            value="<?php echo($bodegaDestinocodigo)?>">



                                    </div>

                                    <?php endif;?>

                                    <div class="col-4">

                                        <label class="mt-3" for="inputEmail ">Fecha origen</label>
                                        <input type="text" class="form-control" id="fecha" value="">

                                        <input type="text" class="forn-control" id="fechaLocal"
                                            value="<?php echo($fecha)?>" hidden>



                                    </div>
                                    <div class="col-4">
                                        <?php if(empty($fecha)):?>


                                        <input type="text" class="form-control" id="fechaInputDestino" value="" hidden>

                                        <?php else: ?>
                                        <label class="mt-3" for="inputEmail ">Fecha origen guardado</label>
                                        <input type="text" class="form-control" id="fechaInputDestino"
                                            value="<?php echo($fecha)?>" disabled>
                                        <?php endif;?>


                                    </div>

                                    <div class="col-2 ">
                                        <input class="form-control mb-3" id="codigoDetalle" name="" type="text"
                                            placeholder="" hidden />
                                        <input class="form-control mb-3" id="precioDetalle" name="precioDetalle"
                                            type="number" placeholder="" value="ROPA" hidden />
                                        <input class="form-control mb-3" id="detalleBandera" name="detalleBandera"
                                            type="text" placeholder="" value="" hidden />
                                    </div>

                                    <div class="row ">
                                        <div class="col-6">


                                        </div>
                                        <div class="col-6">

                                        </div>

                                    </div>



                                    <div class="row">
                                        <div class="col-3 mt-4">
                                            <label class="mt-2" for="inputEmail ">Codigo barra</label>
                                            <input type="text" class="form-control" id="introCodigo"
                                                aria-describedby="emailHelp">
                                        </div>
                                        <div class="col-3 mt-2">
                                            <button type="button" id="agregarDetalle" name="finalizarDetalle"
                                                class=" btn btn-warning " style="margin-top:40px;">Agregar</button>
                                            <button type="button" id="agregarDetalle" name="finalizarDetalle"
                                                class=" btn btn-info mt-5"
                                                style="margin-left: 40px; margin-bottom: 5px; " data-bs-toggle="modal"
                                                data-bs-target="#modalForm">Ver resumen</button>
                                        </div>
                                        <div class="col-4 offset-2">
                                            <input type="text" id="descripcion" hidden>
                                            <?php
                                                if(isset($_GET['pendiente'])){
                                                    $pendiente=$_GET['pendiente'];
                                                    
                                            ?>
                                            <input type="text" id="pendiente" value="<?php echo($pendiente)?>" hidden>



                                            <?php
                                                }else{
                                            ?>
                                            <input type="text" id="pendiente" value="" hidden>

                                            <?php }?>
                                            <input type="text" id="articulo" hidden>
                                            <input type="text" id="barraCodigo" hidden>
                                            <?php if(empty($documento_inv)):?>
                                            <input type="text" id="fechaHoraTemporal" hidden>
                                            <?php else: ?>
                                            <input type="text" id="fechaHoraTemporal"
                                                value="<?php echo($documento_inv)?>" hidden>
                                            <?php endif;?>
                                            <input type="text" id="libras" hidden>
                                            <input type="text" id="usuario" value="<?php echo($usuario) ?>" hidden>
                                            <input type="text" id="fecha" hidden>
                                            <button type="button" id="finalizar" name="finalizarDetalle"
                                                class="btn btn-success" style="margin-top: 55px;">Finalizar</button>
                                            <button type="button" id="cancelar" name="finalizarDetalle"
                                                class="btn btn-danger" style="margin-top: 55px;">Cancelar</button>
                                            <a href="indexPrincipalTraslado.php" type="button" id="pendiente"
                                                name="finalizarDetalle" class="btn btn-warning "
                                                style="margin-top:55px;">Pendiente</a>


                                        </div>

                                    </div>


                                </div>
                            </form>
                        </div>

                    </div>

                    <!---- TABLA COMPLEMENTO DETALLE ---->
                    <div class="card mb-4">
                        <div class="card-body overflow-auto">
                            <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
                                <table class="table table-bordered  mb-0" id="articulos">
                                    <thead>
                                        <tr>
                                            <td class="table-secondary"><strong>Articulo<strong></td>
                                            <td class="table-secondary"><strong>Descripcion<strong></td>
                                            <td class="table-secondary"><strong>Codigo barra<strong></td>
                                            <td class="table-secondary"><strong>Libras<strong></td>
                                            <td class="table-secondary"><strong>Acciones<strong></td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="col-xl-3 col-md-6">
                        <div class="modal fade" tabindex="-1" id="modalForm" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ver Resumen</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Articulo</th>
                                                    <th scope="col">Descripcion</th>
                                                    <th scope="col">Cantidad</th>
                                                    <th scope="col">Libras</th>

                                                </tr>

                                            </thead>
                                            <tbody id="tablaCuerpo">

                                            </tbody>
                                            <tfoot>
                                                <td class="table-success" colspan="2">Total:</td>
                                                <td class="table-success" id="sumaCantidad"></td>
                                                <td class="table-success" id="sumaLibras"></td>

                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; fernandoBlanco 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
            <div id="modal-overlay"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 100; display: none;">
            </div>
        </div>
    </div>
    </div>

    <script type="text/javascript" src="js/scriptsTraslado.js"></script>

    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js"
        integrity="sha512-oVbWSv2O4y1UzvExJMHaHcaib4wsBMS5tEP3/YkMP6GmkwRJAa79Jwsv+Y/w7w2Vb/98/Xhvck10LyJweB8Jsw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

</body>

</html>