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

        echo($_SESSION['BODEGA']);
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
    <link href="cssmenu/estilo.css" rel="stylesheet" />
    <link rel="stylesheet" href="cssmenu/select2.min.css" />
    <!---<link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />---->
    <!--<link rel="stylesheet" href="plugins/toastr/toastr.min.css"> -->
    <!-- Or for RTL support -->


    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>

</head>

<body>

    <div id="layoutSidenav">
        <?php
            include('menuBootstrap.php');

        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Desglose</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Desglose</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-4 mt-3">
                                        <label for="exampleFormControlInput1" class="form-label">Codigo de barra</label>
                                        <input type="text" class="form-control" id="codigoDesglose" placeholder="">

                                    </div>
                                    <div class="col-4 mt-3">
                                        <label for="exampleFormControlInput1" class="form-label">Valor desglose</label>
                                        <input type="text" class="form-control" id="valorDesglose" placeholder="">

                                    </div>
                                    <div class="col-4">
                                        <button type="button" id="desglosar"
                                            class="btn btn-warning mt-5">Desglosar</button>

                                    </div>

                                </div>



                            </form>
                        </div>
                    </div>

                    <div class="row">





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
        </div>
    </div>
    <div id="modal-overlay"
        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 100; display: none;">
    </div>
    <script src="js/scriptsDesglose.js"></script>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
    <script src="js/sweetalert2@11.js"></script>


</body>

</html>