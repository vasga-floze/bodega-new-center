<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        //header("Location: indexProduccion.php");
        header('Location: index.php');
    }else if(!isset($_SESSION['banderaArticulo'])){
        header('Location: indexProduccion.php');
    }else{
        $usuario=$_SESSION['usuario'];
        $respuesta=$_SESSION['compania'];
        $bandera=$_SESSION['banderaArticulo'];
        $unidades=$_SESSION['unidades'];
        $suma = 0;
        if(isset($_SESSION['datos'])){
            $datos=$_SESSION['datos'];
            foreach($datos as $data){
                $cantidad=$data['cantidad'];
                $suma += $cantidad; 
            }
        }
        
        
        //echo("La suma de la cantidad ".$suma);
        //echo("La suma de la cantidad ".$unidades);
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
    <link href="cssmenu/datatableStyle.css" rel="stylesheet" />
    <link href="cssmenu/estilo.css" rel="stylesheet" />
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />-->
   <link rel="stylesheet" href="cssmenu/select2.min.css" />
   <link rel="stylesheet" href="cssmenu/select2-bootstrap-5-theme.min.css">
   <link href="cssmenu/toastr.css" rel="stylesheet"/>
    <!--<link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />-->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css"> 
    <!-- Or for RTL support -->

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css">
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script src="js/select2.full.min.js"></script>
   
</head>

<body>
    
    
    <div id="layoutSidenav">
    <?php
            include('menuBootstrap.php');

        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Complemento</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Complemento</li>
                    </ol>
                    <!-----COMPLEMENTO DETALLE PAQUETE ----->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form>
                                <div class="row justify-content-start form-floating mb-3">

                                    <div class="col-2 ">
                                        <label class="mb-3" for="inputEmail ">Buscar paquete</label>
                                        <button type="button" id="buscarModal" name="buscarModal" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal">
                                            Buscar
                                        </button>

                                    </div>
                                    <div class="col-3 ">
                                        <label class="mb-3" for="inputEmail ">Listar Paquete</label>
                                        <select id="empaque" name="empaque" class="form-select"
                                            aria-label="Default select example">
                                            <option selected>Seleccione el paquete</option>

                                            <?php
                                                //$db=connectERP();
                                                $query =$dbEximp600->prepare(
                                                    "SELECT 
                                            ARTICULO,
                                            DESCRIPCION,
                                            CLASIFICACION_2
                    
                                            FROM ".$respuesta.".ARTICULO
                                            WHERE activo='S'
                                            AND clasificacion_1<>'DETALLE'
                                            AND clasificacion_2='$bandera'
                                            ORDER BY len(articulo),articulo
                                                                ");
                                                $query->execute();
                                                $data = $query->fetchAll();
                                                foreach ($data as $valores){
                                                    ?>
                                            <option value="<?php echo $valores["ARTICULO"]; ?>">
                                                <?php echo $valores["ARTICULO"]?> --
                                                <?php echo $valores["DESCRIPCION"]?></option>
                                            <?php
                                                }
                                            ?>

                                        </select>

                                    </div>
                                    <div class="col-4">
                                        <label class="mb-3" for="inputEmail ">Descripcion</label>
                                        <input class="form-control mb-3" id="descripcion" name="descripcion" type="text"
                                            placeholder="" disabled />
                                       
                                        <input class="form-control mb-4" id="codigo" name="codigo" type="text"
                                            placeholder="" hidden />
                                        <input class="form-control mb-4" id="unidadesSession" name="unidadesSession" type="text"
                                        placeholder=""value=<?php echo($unidades) ?>  hidden/>



                                    </div>
                                    <div class="col-2">
                                        <label class="mb-3" for="inputEmail ">Dirigido a</label>
                                        <select id="dirigido" class="form-select" aria-label="Default select example" >
                                            <option selected></option>
                                            <option value="carisma">Carisma</option>
                                            <option value="cany">Cany shop</option>
                                            <option value="boutique">La boutique</option>
                                            <option value="nys">NYS(SM1)</option>
                                            <option value="nyc">NYC(ST1)</option>

                                        </select>
                                    </div>
                                    <div class="col-4">

                                        <button type="button" id="finalizar" name="finalizar"
                                            class="btn btn-warning mt-5 ">Finalizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <h1 class="mt-4">Complemento Detalle</h1>

                    <!---COMPLEMENTO DETALLE-------->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="formularioFinalizar">
                                <div class="row justify-content-start form-floating mb-3">

                                    
                                    <div class="col-3 ">
                                        <label class="mb-3" for="inputEmail ">Listar articulo detalle</label>
                                        <select id="empaqueDetalle" name="empaqueDetalle" class="form-select"  >
                                        <option selected></option>
                                          

                                            <?php
                                                //$db=connectERP();
                                                $query =$dbEximp600->prepare(
                                                    "SELECT        ".$respuesta.".ARTICULO.ARTICULO, ".$respuesta.".ARTICULO.DESCRIPCION, ".$respuesta.".ARTICULO_PRECIO.PRECIO
                                                    FROM            ".$respuesta.".ARTICULO INNER JOIN
                                                                             ".$respuesta.".ARTICULO_PRECIO ON ".$respuesta.".ARTICULO.ARTICULO = ".$respuesta.".ARTICULO_PRECIO.ARTICULO
                                                    WHERE        (".$respuesta.".ARTICULO.ACTIVO = 'S') AND (".$respuesta.".ARTICULO.UNIDAD_ALMACEN = '59') AND (".$respuesta.".ARTICULO.CLASIFICACION_2 = '$bandera') AND (".$respuesta.".ARTICULO.USA_LOTES = 'S') AND 
                                                                             (".$respuesta.".ARTICULO_PRECIO.NIVEL_PRECIO = 'REGULAR')
                                                    ORDER BY ".$respuesta.".ARTICULO.DESCRIPCION, ".$respuesta.".ARTICULO.ARTICULO");
                                                $query->execute();
                                                $data = $query->fetchAll();
                                                foreach ($data as $valores){
                                                    ?>
                                            <option value="<?php echo $valores["ARTICULO"]; ?>">
                                                <?php echo $valores["ARTICULO"]?> --
                                                <?php echo $valores["DESCRIPCION"]?></option>
                                            <?php
                                                }
                                            ?>

                                        </select>

                                    </div>
                                    <div class="col-4 ">
                                        <label class="mb-3" for="inputEmail ">Descripcion</label>
                                        <input class="form-control mb-3" id="descripcionDetalle"
                                            name="descripcionDetalle" type="text" placeholder=""  />




                                    </div>
                                    <div class="col-2 ">

                                        <label class="mb-3" for="inputEmail ">Cantidad</label>
                                        <input class="form-control mb-3" id="cantidadDetalle" name="cantidadDetalle"
                                            type="number" placeholder="" value="" />
                                        <input class="form-control mb-3" id="codigoDetalle" name=""
                                        type="text" placeholder="" hidden/>
                                        <input class="form-control mb-3" id="precioDetalle" name="precioDetalle"
                                            type="number" placeholder="" value="ROPA" hidden  />
                                        <input class="form-control mb-3" id="detalleBandera" name="detalleBandera"
                                        type="text" placeholder="" value="" hidden /> 




                                    </div>
                                    
                                    
                                    <div class="row">
                                        <div class="col-4">

                                            <button type="button" id="agregarDetalle" name="finalizarDetalle"
                                                class=" btn btn-warning mt-5 ">Agregar</button>
                                            <button type="button" id="guardarDetalle"
                                                class="btn btn-success mt-5 ">Guardar</button>
                                           
                                        </div>
                                        <div class="col-4 mt-5">
                                            <div class="form-check form-switch ">
                                                <input class="form-check-input" type="checkbox" role="switch" id="imprimir">
                                                <label class="form-check-label" for="flexSwitchCheckDefault">Imprimir viñeta prenda</label>
                                            </div>
                                            <div class="form-check form-switch ">
                                                <input class="form-check-input" type="checkbox" role="switch" id="imprimirAdhesivo">
                                                <label class="form-check-label" for="flexSwitchCheckDefault">Imprimir viñeta adhesiva</label>
                                            </div>
                                        </div>
                                       
                                        
                                    </div>








                                </div>






                            </form>
                        </div>
                    </div>

                    <!---- TABLA COMPLEMENTO DETALLE ---->
                    <div class="card mb-4">
                        <div class="card-body overflow-auto">
                            <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
                                <table class="table table-bordered  mb-0" id="articulos">
                                    <tr>
                                        <td>Codigo</td>
                                        <td>Nombre</td>
                                        <td>Cantidad</td>
                                        <td>Precio</td>
                                        <td>Acciones</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                   
                    <div class="badge bg-primary  col-10 text-right " style="width:20rem; font-size:20px" id="total">
                     
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" id="Boton" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>TIPO EMPAQUE</th>
                                                <th>Cargar</th>

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
                                    $query =$dbEximp600->prepare(
                                        "SELECT 
                                            ARTICULO,
                                            DESCRIPCION,
                                            CLASIFICACION_2
                    
                                            FROM ".$respuesta.".ARTICULO
                                            WHERE activo='S'
                                            AND clasificacion_1<>'DETALLE'
                                            AND clasificacion_2='$bandera'
                                            ORDER BY len(articulo),articulo
                                                                ");
                                    $query->execute();
                                    $data = $query->fetchAll();

                                    foreach($data as $item){
                                    ?>
                                            </tr>
                                            <td data-valor='valor2' class='click'><?php echo $item["ARTICULO"]?></td>
                                            <td data-valor='valor3' class='click'><?php echo $item["DESCRIPCION"]?></td>
                                            <td><button type='button' class='btn btn-warning' id="miBoton"
                                                    value='7'>+</button></td>
                                            </tr>

                                            <?php
                                    }
                                    ?>



                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Salir</button>
                                    <button type="button" class="btn btn-primary">Guardar</button>
                                </div>
                                
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
            <div id="modal-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 100; display: none;"></div>
        </div>
        </div>
    </div>
    
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>  
    <script src="js/datatables-simple-demo.js"></script>
    <script src="js/toastr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </script>

</body>

</html>