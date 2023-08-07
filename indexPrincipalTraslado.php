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
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="cssmenu/estilo.css" rel="stylesheet" />
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>

</head>

<body class="sb-nav-fixed">
   
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
                        <li class="breadcrumb-item active">Ubicaciones</li>
                    </ol>
                    <div class="row">

                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <a href="indexTraslado.php" class="btn btn-success btn-lg mb-4" >
                                Crear Nuevo
                            </a>

                            <!-- Modal -->


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


                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Tabla Ubicaciones
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>BODEGA DESTINO</th>
                                        <th>BODEGA ORIGEN</th>
                                        <th>CANTIDAD</th>
                                       
                                        <th>DOCUMENTO INV</th>
                                        <th>ACCIONES</th>
                                       

                                    </tr>
                                </thead>
                                <!--- <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </tfoot>----->
                                <tbody>
                                    <?php 
                                    $query =$dbBodega->prepare("SELECT
                                    Documento_Inv,
                                    MAX(CASE WHEN Naturaleza = 'E' THEN Bodega END) AS 'BODEGA DESTINO',
                                    MAX(CASE WHEN Naturaleza = 'S' THEN Bodega END) AS 'BODEGA ORIGEN', 
                                       COUNT(CodigoBarra)/2 CANTIDAD
                                FROM TRANSACCION
                                WHERE (IdTipoTransaccion = 7) OR (IdTipoTransaccion=3) AND (Estado IS NULL) AND UsuarioCreacion='$usuario'
                                GROUP BY Documento_Inv
                                ");
                                    $query->execute();
                                    $data = $query->fetchAll();
                                    $url=1;

                                    foreach($data as $item){
                                        echo "<tr>";
                                        echo "<td>".$item["BODEGA DESTINO"]."</td>";
                                        echo "<td>".$item["BODEGA ORIGEN"]."</td>";
                                        echo "<td>".$item["CANTIDAD"]."</td>";
                                        echo "<td>".$item["Documento_Inv"]."</td>";
                                        echo "<td><a class='btn btn-success mt-0 continuarBtn' href='indexTraslado.php?doc=".$item["Documento_Inv"]."&pendiente=pendiente' role='button'>Continuar</a>&nbsp;<a class='btn btn-danger mt-0' href='' role='button'>Eliminar</a></td>";
                                        
                                        echo "</tr>";
                                    }
                                    ?>


                                </tbody>
                            </table>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="plugins/toastr/toastr.min.js"></script>
    <script src="popper/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="js/scriptsTraslado.js"></script>
</body>

</html>