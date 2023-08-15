<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        //header("Location: indexProduccion.php");
        header('Location: index.php');
    }else{
        $usuario=$_SESSION['usuario'];
        $respuesta=$_SESSION['compania'];
        $fechaActual = date('Y-m-d');
        include('conexiones/conectar.php');
        $query =$dbEximp600->prepare("SELECT CONSECUTIVO, SIGUIENTE_CONSEC FROM ".$respuesta.".CONSECUTIVO_CI WHERE CONSECUTIVO='PRODUCCION'");
        $queryPaquete =$dbEximp600->prepare("SELECT PAQUETE FROM dbo.USUARIOBODEGA WHERE USUARIO='$usuario'");
        $queryPaquete->execute();
        $dataPaquete=$queryPaquete->fetchAll();
        foreach ($dataPaquete as $valores):
            $paqueteIventario=$valores['PAQUETE'];
        endforeach;
        $query->execute();
        $data = $query->fetchAll();
      
        function obtener_documento($data){
            foreach ($data as $valores):
                $documento=$valores['SIGUIENTE_CONSEC'];
            endforeach;
            return $documento;
        }
        $documento=obtener_documento($data);
        function obtener_consecutivo($documento){
            $consecutivo=preg_replace_callback('/\d+/',function($matches){
                return sprintf('%0'.strlen($matches[0]). 'd', intval($matches[0])+1);
            },$documento);
            return $consecutivo;

        }
        $documento_consecutivo=obtener_consecutivo($documento);
        //echo $documento;
        
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
    <!--<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />--->
    <link href="cssmenu/estilo.css" rel="stylesheet" />

   <!--- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />-->
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />-->
    <!--<link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />-->
    <link rel="stylesheet" href="cssmenu/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="cssmenu/select2.min.css" />
    <!-- Or for RTL support -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="cssmenu/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="cssmenu/jquery-ui.css">
    <link rel="stylesheet" href="cssmenu/datatables.min.css">
    <!--<link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />-->

    <!---<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css">---->
 
    <!---<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css" />-->






</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">
    <?php
            include('menuBootstrap.php');

        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Consulta Produccion</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Consulta Produccion</li>
                    </ol>
                    <div class="row">

                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">Fecha</label>
                            <input type="text" class="form-control" id="fecha">


                            <!-- Modal -->


                        </div>
                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">Usuario</label>
                            <select id="usuario" class="form-select" aria-label="Default select example">
                                <?php
                                                //$db=connectERP();
                                $query =$dbBodega->prepare("SELECT 'TODOS' USUARIO UNION ALL
                                SELECT USUARIO FROM USUARIO WHERE Digita=1
                                ");
                                $query->execute();
                                $data = $query->fetchAll();
                                foreach ($data as $valores):
                                    echo '<option value="'.$valores["USUARIO"].'">'.$valores["USUARIO"].'</option>';
                                endforeach;
                            ?>
                            </select>

                            <!-- Modal -->


                        </div>
                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">Estado</label>
                            <select id="estado" class="form-select" aria-label="Default select example">

                                <?php
                                                //$db=connectERP();
                                $query =$dbBodega->prepare("SELECT  'TODOS' ESTADO UNION ALL
                                SELECT distinct ESTADO  FROM REGISTRO GROUP BY Estado
                                ");
                                $query->execute();
                                $data = $query->fetchAll();
                                foreach ($data as $valores):
                                    echo '<option value="'.$valores["ESTADO"].'">'.$valores["ESTADO"].'</option>';
                                endforeach;
                            ?>
                            </select>

                            <!-- Modal -->


                        </div>
                        <div class="col-xl-3 col-md-4 mt-4">
                            <button type="button" id="generar" class="btn btn-success">Generar</button>
                        </div>


                        <div class="col-xl-3 col-md-6">
                            <div class="modal fade" tabindex="-1" id="modalForm" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Agregar Ubicacion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" id="formulario">
                                                <div class="mb-3">
                                                    <label class="mb-3" for="exampleInputEmail1"
                                                        class="form-label">Agregar Ubicacion</label>
                                                    <input type="text" class="form-control" id="ubicacion"
                                                        name="usuario" aria-describedby="emailHelp">

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Salir</button>
                                                    <button type="button" id="enviar"
                                                        class="btn btn-success">Guardar</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>


                    <div class="card mb-4 mt-4" style="width: 80rem;">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Tabla consulta produccion
                        </div>
                        <div class="card-body">
                            <table id="myTable" class="display" style="width:30%">

                            </table>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-1">
                            <button id="finalizar" type="button" class="btn btn-success">Finalizar</button>
                            <input type="text" id="documentoInventario" value="<?php echo ($documento) ?>"hidden>
                            <input type="text" id="documentoConsecutivo" value="<?php echo ($documento_consecutivo) ?>" hidden>
                            <input type="text" id="paquete" value="<?php echo ($paqueteIventario) ?>" hidden>
                            <input type="text" id="usuarioSession" value="<?php echo ($usuario) ?>" hidden>
                        </div>

                    </div>

                </div>
                <!-- Modal -->

            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; FernandoBlanco 2023</div>
                        <!---<div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>---->
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!---<script src="https://code.jquery.com/jquery-1.10.2.js"></script>---->
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
      
  
    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
      <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>                           
    <script src="js/select2.min.js"></script>
    <script type="text/javascript" src="js/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/scripts.js"></script>     
    <script src="js/scriptsConsultaComplemento.js"></script>
     

</body>

</html>