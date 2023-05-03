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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css"> 
    <!-- Or for RTL support -->

    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
</head>

<body>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">New York Center</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <!---<form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>--->
        <!-- Navbar-->
        <!----<ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Logout</a></li>
                    </ul>
                </li>
            </ul>---->
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Inicio
                        </a>
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Produccion
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="layout-static.html">Nueva</a>
                                <a class="nav-link" href="indexUbicacion.php">Ubicacion</a>
                                <a class="nav-link" href="layout-sidenav-light.html">Producido</a>
                                <a class="nav-link" href="layout-sidenav-light.html">Reporte</a>
                                <a class="nav-link" href="layout-sidenav-light.html">Reporte Empacado</a>
                                <a class="nav-link" href="layout-sidenav-light.html">Reporte Personal Produccion</a>
                                <a class="nav-link" href="layout-sidenav-light.html">Reimprimir</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Traslados
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                    aria-controls="pagesCollapseAuth">
                                    Authentication
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="login.html">Login</a>
                                        <a class="nav-link" href="register.html">Register</a>
                                        <a class="nav-link" href="password.html">Forgot Password</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseError" aria-expanded="false"
                                    aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>


                            </nav>

                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Contenedor
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                    aria-controls="pagesCollapseAuth">
                                    Authentication
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="login.html">Login</a>
                                        <a class="nav-link" href="register.html">Register</a>
                                        <a class="nav-link" href="password.html">Forgot Password</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseError" aria-expanded="false"
                                    aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>


                            </nav>

                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Ripio
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                    aria-controls="pagesCollapseAuth">
                                    Authentication
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="login.html">Login</a>
                                        <a class="nav-link" href="register.html">Register</a>
                                        <a class="nav-link" href="password.html">Forgot Password</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseError" aria-expanded="false"
                                    aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>


                            </nav>

                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Averia
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                    aria-controls="pagesCollapseAuth">
                                    Authentication
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="login.html">Login</a>
                                        <a class="nav-link" href="register.html">Register</a>
                                        <a class="nav-link" href="password.html">Forgot Password</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseError" aria-expanded="false"
                                    aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>


                            </nav>

                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Ventas
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                    aria-controls="pagesCollapseAuth">
                                    Authentication
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="login.html">Login</a>
                                        <a class="nav-link" href="register.html">Register</a>
                                        <a class="nav-link" href="password.html">Forgot Password</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseError" aria-expanded="false"
                                    aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>


                            </nav>

                        </div>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Resumen
                        </a>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Fardos
                        </a>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Eliminar Barra
                        </a>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Desglose sin finalizar
                        </a>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Comparacion de Documentos
                        </a>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Existencia
                        </a>
                        <a class="nav-link" href="close.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Salir
                        </a>


                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Produccion</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Produccion</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form>
                                <div class="row justify-content-start form-floating mb-3">

                                    <div class="col-6 ">
                                        <label class="mb-3" for="inputEmail ">Unidad</label>
                                        <input class="form-control mb-3" id="unidades" type="number" placeholder="Digite las unidades en numeros" />

                                    </div>
                                    <div class="col-6 ">
                                        <label class="mb-3" for="inputEmail ">Libras</label>
                                        <input class="form-control mb-3" id="libras" type="number" placeholder="Digite el numero de libras" />

                                    </div>



                                </div>
                                <div class="row justify-content-start form-floating mb-3">

                                    <div class="col-6 ">
                                        <label class="mb-3" for="exampleInputEmail1"
                                            class="form-label">Ubicacion</label>
                                        <select id="ubicacion" name="ubicacion" class="form-select"
                                            aria-label="Default select example" data-placeholder="Seleccione la ubicacion">
                                            <option selected></option>

                                            <?php
                                                //$db=connectERP();
                                                $query =$dbBodega->prepare("SELECT IdUbicacion,Ubicacion  FROM dbo.UBICACION");
                                                $query->execute();
                                                $data = $query->fetchAll();
                                                foreach ($data as $valores):
                                                    echo '<option value="'.$valores["IdUbicacion"].'">'.$valores["Ubicacion"].'</option>';
                                                endforeach;
                                            ?>

                                        </select>

                                    </div>
                                    <div class="col-6 ">
                                        <label class="mb-3" for="inputEmail ">Fecha Produccion</label>
                                        <input id="fechaProduccion"
                                            value="<?php echo(isset($fechaActual))?$fechaActual:'';?>"
                                            class="form-control mb-3" id="inputEmail" type="date" placeholder=""
                                            disabled />

                                    </div>


                                </div>
                                <div class="row justify-content-start form-floating mb-4">
                                    <div class="col-6 ">
                                        <label class="mb-3" for="inputEmail ">Empacado por</label>
                                        <select class="form-select" id="empacado" name="empacado"
                                            data-placeholder="Seleccionar usuarios empacadores" multiple>

                                            <?php
                                                    //$db=connectERP();
                                                    $query =$dbBodega->prepare("SELECT * FROM dbo.USUARIO WHERE Empaca=1 AND Activo=1");
                                                    $query->execute();
                                                    $data = $query->fetchAll();
                                                    foreach ($data as $valores):
                                                        echo '<option value="'.$valores["Nombre"].'">'.$valores["Nombre"].'</option>';
                                                    endforeach;
                                                ?>

                                        </select>

                                    </div>


                                    <div class="col-6 ">
                                        <label class="mb-3" for="inputEmail ">Tipo Empaque</label>
                                        <select id="empaque" name="empaque" class="form-select"
                                            aria-label="Default select example" data-placeholder="Seleccione el tipo de empaque">
                                            <option selected></option>

                                            <?php
                                                //$db=connectERP();
                                                $query =$dbBodega->prepare("SELECT IdTipoEmpaque,TipoEmpaque FROM dbo.TIPOEMPAQUE");
                                                $query->execute();
                                                $data = $query->fetchAll();
                                                foreach ($data as $valores):
                                                    echo '<option value="'.$valores["IdTipoEmpaque"].'">'.$valores["TipoEmpaque"].'</option>';
                                                endforeach;
                                            ?>

                                        </select>

                                    </div>



                                </div>

                                <div class="row justify-content-start form-floating mb-3">

                                    <div class="col-6 ">
                                        <label class="mb-3" for="inputEmail ">Digitado Por</label>
                                        <input id="usuario" value="<?php echo(isset($usuario))?$usuario:'';?> "
                                            class="form-control mb-3" id="inputEmail" type="text" placeholder=""
                                            disabled />

                                    </div>
                                    <div class="col-6 ">
                                        <label class="mb-3" for="inputEmail ">Producido Por</label>
                                        <select class="form-select" id="producido" name="producido"
                                            data-placeholder="Seleccionar usuarios de produccion" multiple>
                                            <?php
                                                //$db=connectERP();
                                                $query =$dbBodega->prepare("SELECT * FROM dbo.USUARIO WHERE Produce=1 AND Activo=1");
                                                $query->execute();
                                                $data = $query->fetchAll();
                                                foreach ($data as $valores):
                                                    echo '<option value="'.$valores["Nombre"].'">'.$valores["Nombre"].'</option>';
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>



                                </div>
                                <div class="row justify-content-start form-floating mb-3">
                                    <div class="col-6 ">
                                        <label class="mb-3" for="inputEmail ">Bodega</label>
                                        <select id="bodega" name="bodega" class="form-select"
                                            aria-label="Default select example" disabled>


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
                                    <div class="col-6 ">
                                        <label class="mb-3" for="inputEmail ">Tipo Registro</label>
                                        <select  id="tipoRegistro" name="tipoRegistro" class="form-select"
                                            aria-label="Default select example" disabled>


                                            <?php
                                                //$db=connectERP();
                                                $query =$dbBodega->prepare("SELECT IdTipoRegistro, TipoRegistro FROM dbo.TIPOREGISTRO WHERE IdTipoRegistro = 1");
                                                $query->execute();
                                                $data = $query->fetchAll();
                                                foreach ($data as $valores):
                                                    echo '<option value="'.$valores["IdTipoRegistro"].'">'.$valores["TipoRegistro"].'</option>';
                                                endforeach;
                                            ?>

                                        </select>

                                    </div>

                                    <div class="col-6 mt-4">
                                        <label class="mb-3" for="inputEmail ">Observaciones</label>
                                        <textarea class="form-control" id="observaciones" rows="3"></textarea>
                                        <input class="form-control mb-3" id="banderaArticulo" type="text" placeholder="Digite las unidades en numeros" hidden />       
                                    </div>


                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 row justify-content-start form-floating ">
                            <div class="d-grid gap-2 col-12">
                                <button type="button" id="ropa" name="ropa"
                                    class=" btn btn-secondary btn-lg" >Ropa</button>
                                <button type="button" id="cincho" class=" btn btn-secondary btn-lg">Cinchos</button>
                                <button type="button" id="juguete" class=" btn btn-secondary btn-lg">Juguetes</button>
                                <button type="button" id="otro" class="btn btn-secondary btn-lg">Otros</button>


                            </div>


                        </div>


                        <div class="col-6 row justify-content-start form-floating ">
                            <div class="d-grid gap-2 col-12  ">
                                <button type="button" id="cartera" name="cartera" class="btn btn-secondary btn-lg">Carteras</button>
                                <button type="button" id="gorra" class="btn btn-secondary btn-lg">Gorras</button>
                                <button type="button" id="zapato" class="btn btn-secondary btn-lg">Zapatos</button>
                                <button type="button" id="gancho" class="btn btn-secondary btn-lg">Ganchos</button>


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
        </div>
    </div>
    <div id="modal-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 100; display: none;"></div>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
   

</body>

</html>