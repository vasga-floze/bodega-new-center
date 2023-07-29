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
        $articuloGet=$_GET["articulo"];
        $libras=$_GET["libras"];
        $CodigoBarra=$_GET["codigoBarra"];
        $dirigido=$_GET["dirigido"];
        $query =$dbEximp600->prepare("SELECT ARTICULO, DESCRIPCION, CLASIFICACION_2 FROM ".$respuesta.".ARTICULO WHERE ARTICULO='$articuloGet'");
        $query->execute();
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $valores) {

            $articulo=$valores['ARTICULO'];
            $descripcion=$valores['DESCRIPCION'];
            $clasificacion=$valores['CLASIFICACION_2'];
          
            # code...
        }

        //echo($articulo);
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css" />






</head>

<body class="sb-nav-fixed">

    <div id="layoutSidenav">
        <?php
            include('menuBootstrap.php');
       ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Editar articulos produccion</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Consulta Produccion</li>
                    </ol>
                    <div class="row">

                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">Articulo</label>
                            <input id="articulo" type="text" value="<?php echo($articulo) ?>" class="form-control"
                                id="">
                        </div>
                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">Descripcion</label>
                            <input id="descripcion" disabled type="text" value="<?php echo($descripcion)?>"
                                class="form-control" id="">
                        </div>
                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">Clasificacion</label>
                            <input id="clasificacion" disabled type="text" value="<?php echo($clasificacion)?>"
                                class="form-control" id="">

                        </div>
                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">Libras</label>
                            <input id="libras" type="text" value="<?php echo($libras)?>" class="form-control" id="">

                        </div>
                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">CodigoBarra</label>
                            <input id="codigoBarra" disabled type="text" value="<?php echo($CodigoBarra)?>"
                                class="form-control" id="">

                        </div>
                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <label for="">Dirigido</label>
                            <select id="dirigido" class="form-select">
                                <option value="<?php echo($dirigido)?>"><?php echo($dirigido)?></option>
                                <option value="carisma">Carisma</option>
                                <option value="cany">Cany shop</option>
                                <option value="boutique">La boutique</option>
                                <option value="nys">NYS(SM1)</option>
                                <option value="nyc">NYC(ST1)</option>
                            </select>



                        </div>


                    </div>
                    <div class="row">


                        <div class="col-2">
                            <div class="d-flex justify-content-start mt-5">
                                <button type="button" id="editar" class="btn btn-primary btn-lg">Editar</button>

                            </div>

                        </div>
                        <div class="col-2">
                            <div class="d-flex justify-content-start mt-5">
                                <a class="btn btn-info btn-lg" href="indexConsultaComplemento.php"
                                    role="button">Cancelar</a>

                            </div>

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
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script src="js/scriptsConsultaComplemento.js"></script>

</body>

</html>