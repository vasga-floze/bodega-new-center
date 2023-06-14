<?php

	include('conexiones/conectar.php');

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>Login - SB Admin</title>
	<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
	<link href="cssmenu/estilo.css" rel="stylesheet" />
	
	<!-- Styles -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
	<!-- Or for RTL support -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

	<!-- Scripts -->
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
	<link href="cssmenu/estilo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css"> 
    <!-- Or for RTL support -->
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css"> 
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
	<div class="layoutAuthentication">
	<div id="layoutAuthentication_content">
	<main>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-5">
				<div class="card shadow-lg border-0 rounded-lg mt-5">
					<div class="circulo">

					</div>
						<div class="card-body">

						
							<div class="mb-3">
								<label class="mb-3" for="exampleInputEmail1" class="form-label">Usuario</label>
								<input type="text" class="form-control" id="usuario" name="usuario" aria-describedby="emailHelp">
								
							</div>
							<div class="mb-3">
								<label class="mb-3" for="exampleInputEmail1" class="form-label">Contrasenia</label>
								<input type="password" name="contrasenia" id="contrasenia" class="form-control" id="usu" name="usu" aria-describedby="emailHelp">
								
							</div>
							
							<div id="" class="mb-3">
								<label class="mb-3" for="exampleInputEmail1" class="form-label">Empresa</label>
								<select id="respuesta" name="compania" id="compania" class="form-select" aria-label="Default select example">
									<option selected value="">Seleccione la empresa</option>
									
									<?php
										//$db=connectERP();
										$query =$dbEximp600->prepare("SELECT CONJUNTO FROM dbo.conjunto");
										$query->execute();
										$data = $query->fetchAll();
										foreach ($data as $valores):
											echo '<option value="'.$valores["CONJUNTO"].'">'.$valores["CONJUNTO"].'</option>';
										endforeach;
									?>
									
								</select>
							</div>
							<button  id="btnIngresar" class="btn btn-primary">Entrar</button>
						
						
						</div>
					<div class="card-footer text-center py-3">
						<div class="small"><a href="">&#169; 2023 Fernando Blanco and Pablo Andrade</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</main>
	</div>
	</div>
	<script src="js/conexiones.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="plugins/toastr/toastr.min.js"></script>

</body>
</html>