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
    <link rel="stylesheet" href="cssmenu/jquery-ui.js">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
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
                    <h1 class="mt-4">Valor contenedores</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Contenedores</li>
                    </ol>
                    <div class="row">

                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->

                            <label for="">Fecha</label>
                            <input type="text" class="form-control" id="fecha">

                        </div>


                        <div class="col-xl-3 col-md-4">
                            <label for="">Contenedor</label>
                            <input type="email" class="form-control" id="contenedor" aria-describedby="emailHelp">

                        </div>
                        <div class="col-xl-3 col-md-4">
                            <label for="">Gasto</label>
                            <input type="email" class="form-control" id="gasto" aria-describedby="emailHelp">

                        </div>




                        <div class="col-xl-3 col-md-4 mt-4">
                            <button type="button" id="generar" class="btn btn-success">Generar</button>
                        </div>


                    </div>


                    <div class="card mb-4 mt-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Tabla informacion contenedores
                        </div>
                        <div class="card-body">
                            <table id="myTable" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Articulo</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Porcentaje</th>

                                        <th scope="col">Total articulo</th>
                                        <th scope="col">Precio unitario</th>
                                        <th scope="col">Acciones</th>
                                        <!---<th scope="col">Precio Unitario</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Porcentaje</th>
                                        <th scope="col">Gasto </th>
                                        <th scope="col">Total articulo</th>----->
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>

                                        <th>Total</th>
                                        <th></th>
                                        <th></th>
                                        <th id="total"></th>
                                        <th></th>
                                        <th id="totalArticulo"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    
                    </div>
                    <div class="col d-flex flex-row-reverse">
                        <button class="btn btn-primary" id="finalizar">Finalizar</button>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" data-bs-backdrop="static" id="modalEditar" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Calcular el contenedor</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="exampleFormControlInput1" class="form-label" readonly>Articulo</label>
                                            <input type="text" class="form-control" id="articulo" readonly>
                                        </div>
                                        <div class="col-6">
                                            <label for="exampleFormControlInput1" class="form-label">Descripcion</label>
                                            <input type="text" class="form-control" id="descripcion" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="exampleFormControlInput1" class="form-label">Cantidad</label>
                                            <input type="email" class="form-control" id="cantidad" placeholder="" readonly>



                                        </div>
                                        <div class="col-6">
                                            <label for="exampleFormControlInput1" class="form-label">Precio</label>
                                            <input type="email" class="form-control" id="precio" placeholder="">
                                        </div>

                                    </div>




                                </div>
                                <div class="modal-footer">
                                    <button id="cerrar" type="button" class="btn btn-primary"
                                        data-dismiss="modal">Cerrar</button>
                                    <button type="button" id="guardar" class="btn btn-primary">Calcular</button>
                                </div>
                            </div>
                        </div>
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
    <script type="text/javascript" src="js/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/scriptsValorIngreso.js"></script>



</body>

</html>