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
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<!-- Styles -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
	<!-- Or for RTL support -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

	<!-- Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
	
	
</head>
<body class="bg-primary">
	<div class="layoutAuthentication">
	<div id="layoutAuthentication_content">
	<main>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-5">
				<div class="card shadow-lg border-0 rounded-lg mt-5">
					<div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
						<div class="card-body">

						<form method="POST" >
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
								<select id="respuesta" name="respuesta" class="form-select" aria-label="Default select example">
									<option selected>Seleccione la empresa</option>
									
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
							
							
							<button type="submit" id="btnIngresar" class="btn btn-primary">Entrar</button>
						</form>
						
						</div>
					<div class="card-footer text-center py-3">
						<div class="small"><a href="">&#169; 2023 Fernando Blanco</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</main>
	</div>
	</div>

	<script src="js/conexiones.js"></script>

</body>
</html>

<?php


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(1);


/*if($_SESSION['tipo']=='')
{

}else if($_SESSION['tipo']==1)
{
	//echo "<script>location.replace('index.php')</script>";
}else if($_SESSION['tipo']==2)
{
echo "<script>location.replace('desgloseb.php')</script>";
}else if($_SESSION['tipo']==3)
{
echo "<script>location.replace('consultar.php')</script>";
}else if(strtoupper($_SESSION['usuario'])=='EGAMEZ')
{
	echo "<script>location.replace('inicio.php')</script>";
}*/
	if($_POST)
	{
		extract($_REQUEST);
		$fallo=0;

		try
		{
		$c=new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "$usuario", "$contrasenia");

			
		}
		catch(PDOException $e)
		{
			
			echo "<h2 style='margin-left:15%;'>USUARIO O CONTRASEÑA INCORRECTOS $respuesta </h2>";
			echo "<h2 style='margin-left:15%;'>$e </h2>";
			$fallo=1;
		}

		if($fallo!=1)
		{

		$con=$c->query("select * from dbo.USUARIOBODEGA where USUARIO='$usuario'")or die($c->error);
		$f=$con->FETCH(PDO::FETCH_ASSOC);
		session_start();
		$bodega=$f['BODEGA'];
		$paquete=$f['PAQUETE'];
		$_SESSION['valores']=$respuesta;
		$_SESSION['estado'] = "conectado";
		$_SESSION['paquete']=$paquete;
		$_SESSION['usuario']=$usuario;
		$_SESSION['bodega']=$bodega;
		$tipousu=substr($bodega, 0);
		$u=$tipousu[0];
		if($tipousu[0]=="S")
		{
			$_SESSION['tipo']=1;
		}else
		{
			$_SESSION['tipo']=2;
			if($_SESSION['usuario']=='staana3')
			{
				$_SESSION['tipo']=1;
			}else if($_SESSION['usuario']=='HARIAS' or $_SESSION['usuario']=='harias' or $_SESSION['usuario']=='AUDITORIA1' or $_SESSION['usuario']=='auditoria1' or $_SESSION['usuario']=='AUDITORIA2' or $_SESSION['usuario']=='auditoria2' or $_SESSION['usuario']=='AUDITORIA3' OR $_SESSION['usuario']=='auditoria3')
			{
				//echo "<script>alert('...')</script>";
				$_SESSION['tipo']=3;
				echo "<script>location.replace('consultar.php')</script>";
			}
			if($_SESSION['usuario']=='egamez' or $_SESSION['usuario']=='EGAMEZ')
			{
				$_SESSION['tipo']=4;
				echo "<script>location.replace('inicio.php')</script>";
			}
			if($_SESSION['usuario']=='JDELAO' OR $_SESSION['usuario']=='jdelao' or $_SESSION['usuario']=='falvarez' or $_SESSION['usuario']=='FALVAREZ' or $_SESSION['usuario']=='lrodriguez' or $_SESSION['usuario']=='LRODRIGUEZ')
		{
			$_SESSION['tipo']=5;
			//echo "<script>alert('bi')</script>";
			echo "<script>location.replace('reporte_cuadro_venta.php')</script>";
		}
			echo "<script>location.replace('desgloseb.php')</script>";
		}

		echo "<script>location.replace('indexProduccion.php')</script>";
		}else


		echo "<h3></h3>";
		

		
	}
?>