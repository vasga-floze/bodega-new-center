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
        
        $query =$dbBodega->prepare("SELECT NumeroDocumento,Fecha,COUNT(codigobarra) CANTIDAD FROM TRANSACCION
                                    WHERE IdTipoTransaccion='1' AND Estado='P'
                                    GROUP BY IdTransaccion,NumeroDocumento,Fecha
                                    ");
        $query->execute();
        $data = $query->fetchAll();
        

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
                    <h1 class="mt-4">Contenedor</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Contenedor</li>
                    </ol>
                    <div class="row">

                        <div class="col-xl-3 col-md-4">
                            <!-- Enlace para abrir el modal -->
                            <a class="btn btn-success btn-lg mb-4" href="indexNuevoContenedor.php">
                                Crear Nuevo
                            </a>

                            <!-- Modal -->


                        </div>



                    </div>


                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Tabla contenedores
                        </div>
                        <div class="card-body">
                        <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                      
                                        <th>Numero</th>
                                        <th>Fecha</th>
                                        <th>Cantidad</th>
                                        <th>Acciones</th>
                                        


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
                                    foreach($data as $valores){
                                        echo "<tr>";
                                        
                                        echo "<td>".$valores['NumeroDocumento']."</td>";
                                        echo "<td>".$valores['Fecha']."</td>";
                                        echo "<td>".$valores['CANTIDAD']."</td>";
                                        echo "<td><a href='indexNuevoContenedor.php?fecha=".urlencode($valores["Fecha"])."&numero=".$valores["NumeroDocumento"]."' class='btn btn-primary'>Continuar</td>";
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
    <script src="js/scripts.js"></script>
</body>

</html>