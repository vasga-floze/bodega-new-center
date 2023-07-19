<?php

session_start();
if(!isset($_SESSION['usuario'])){
    
    header('Location: index.php');
}else{
    $usuario=$_SESSION['usuario'];
    $respuesta=$_SESSION['compania'];
    $fechaActual = date('Y-m-d');
    include('conexiones/conectar.php');
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
    <link rel="stylesheet" href="cssmenu/select2-bootstrap-5-theme.min.css">
    <!--<link rel="stylesheet" type="text/css" href="cssmenu/jquery-ui.js">-->
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
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
                    <h1 class="mt-4">Trabajo de mesa</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Trabajo de mesa</li>
                    </ol>
                    <div class="row">

                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->


                            <label for="">Fecha</label>

                            <input type="email" class="form-control" id="fecha" value="" aria-describedby="emailHelp">

                        </div>

                        <div class="col-xl-3 col-md-4">
                            <label for="">Bodega</label>
                            <select id="bodega" name="bodega" class="form-select" aria-label="Default select example">
                                <option value=""></option>

                                <?php
                                    //$db=connectERP();
                                    $query =$dbEximp600->prepare("SELECT BODEGA, NOMBRE FROM " .$respuesta. ".BODEGA WHERE bodega LIKE'%00'");
                                    $query->execute();
                                    $data = $query->fetchAll();
                                    foreach ($data as $valores):
                                        echo '<option value="'.$valores["BODEGA"].'">'.$valores["BODEGA"].'-'.$valores["NOMBRE"].'</option>';
                                    endforeach;
                                ?>

                            </select>
                        </div>



                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">Codigo barra</label>
                            <input type="email" class="form-control" id="codigoBarra" aria-describedby="emailHelp">
                            <!-- Modal -->
                        </div>

                        <div class="col-xl-3 col-md-4 mt-4">
                            <button id="agregar" type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                    </div>
                    <span id="totalLibras" class="badge bg-warning text-dark mt-4" style="font-size: 20px"></span>

                    <div class="card mb-4 mt-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Tabla informacion contenedores
                        </div>
                        <div id="layer1" class="card-body">
                            <table id="tablaContenedores" class="table table-bordered" cellspacing="0"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">Articulo
                                        </th>
                                        <th class="th-sm">Descripcion
                                        </th>
                                        <th class="th-sm">Libras
                                        </th>
                                        <th class="th-sm">Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                
                            </table>
                        </div>


                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button id="finalizar" class="btn btn-primary me-md-2" type="button">Finalizar</button>

                    </div>
                    <div class="row justify-content-end">
                        <div class="col-1">

                            <input type="text" id="documentoInventario" value="<?php echo ($documento) ?>" hidden>
                            <input type="text" id="documentoConsecutivo" value="<?php echo ($documento_consecutivo) ?>"
                                hidden>
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
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="http://localhost/bodega-new-center/cssmenu/jquery-ui.js"></script>
    <script src="js/scriptsTrabajoMesa.js"></script>
</body>

</html>