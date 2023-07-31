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
    <link rel="stylesheet" href="cssmenu/select2.min.css" />
    <!---<link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />---->
    <!--<link rel="stylesheet" href="plugins/toastr/toastr.min.css"> -->
    <!-- Or for RTL support -->

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css">
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
                                        <input class="form-control mb-3" id="unidades" type="text" placeholder="Digite las unidades en numeros" />

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
                                            value=""
                                            class="form-control mb-3" id="inputEmail" type="text" placeholder=""
                                            />

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
                                                $query =$dbEximp600->prepare("SELECT BODEGA, NOMBRE FROM " .$respuesta. ".BODEGA WHERE bodega LIKE'%00' ORDER BY BODEGA  DESC");
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
                                        <label class="mb-3" for="inputEmail ">Dirigido a</label>
                                        <select id="dirigido" class="form-select" aria-label="Default select example" disabled>
                                            <option selected></option>
                                            <option value="carisma">Carisma</option>
                                            <option value="cany">Cany shop</option>
                                            <option value="boutique">La boutique</option>
                                            <option value="nys">NYS(SM1)</option>
                                            <option value="nyc">NYC(ST1)</option>

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
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>                                    
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
   

</body>

</html>