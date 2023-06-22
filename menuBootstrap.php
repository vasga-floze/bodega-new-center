<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="cssmenu/estilo.css" rel="stylesheet" />

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <!-- Or for RTL support -->

    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">New York Center</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

        </form>
        <!-- Navbar-->
        <ul class="d-none d-md-inline-block form-inline navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link " id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">BIENVENIDO: <i class="fas fa-user fa-fw"></i><?php echo($usuario) ?></a>


            </li>
        </ul>

    </nav>

    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Inicio</div>
                    <a class="nav-link" href="indexDashboard.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Estadisticas
                    </a>
                    <div class="sb-sidenav-menu-heading">PRODUCCION</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                        aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fab fa-dropbox"></i></div>
                        Produccion
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="indexProduccion.php">Nuevo</a>
                            <a class="nav-link" href="indexConsultaComplemento.php">Consulta produccion</a>
                            <a class="nav-link" href="indexTipoTransaccion.php">Tipo transaccion</a>
                            <a class="nav-link" href="indexTipoEmpaque.php">Tipo empaque</a>
                            <a class="nav-link" href="indexUbicacion.php">Ubicacion</a>


                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2"
                        aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-right-left"></i></div>
                        Traslados
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="indexPrincipalTraslado.php">Nuevo</a>

                        </nav>
                    </div>

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3"
                        aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-print"></i></div>
                        Reimprimir
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="indexConsultaReimpresion.php">Reimprimir viñeta</a>

                        </nav>
                    </div>
                    <div class="sb-sidenav-menu-heading">DESGLOSE</div>
                    <a class="nav-link" href="indexDesglose.php">
                        <div class="sb-nav-link-icon"><i class="fab fa-dropbox"></i></div>
                        Desglose
                    </a>
                    <div class="sb-sidenav-menu-heading">SALIR DEL SISTEMA</div>
                    <a class="nav-link" href="salir.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-right-from-bracket"></i></div>
                        Salir
                    </a>
                   
                    

                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small"></div>
                Start Bootstrap 5
            </div>
        </nav>
    </div>









</body>

</html>