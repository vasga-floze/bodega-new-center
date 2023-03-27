<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
	$(document).ready(function(){
		$("#detalle").hide();
		});
	function articulo()
	{
		location.replace('art_registros.php');
	}
	function cambia()
	{
		$("#op").val('1');

	}
	function envio()
	{
		document.form.submit()
	}
	function enviar()
	{
		$("#op").val('2');
	}
	</script>
	<style>
  .preloader {
  width: 70px;
  height: 70px;
  border: 10px solid #eee;
  border-top: 10px solid skyblue;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 3s;
  animation-iteration-count: infinite;

}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
</head>
<body>

	<div class="detalle" id="detalle" style="background-color: rgb(211,211,211,0.5); width: 100%; height: 110%; margin-left: -0.5%; ">
	<div class="preloader" id="preloader" style="margin-top: 15%; margin-left: 50%; ">
	</div>
	</div>


<?php
include("conexion.php");
error_reporting(0);
$art=$_GET['art'];
//echo "<script>alert('$art')</script>";
$car=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
$fcar=$car->FETCH(PDO::FETCH_ASSOC);

?>
<h3 style="text-align: center; text-decoration: underline;">REPORTE DE FARDOS</h3>
<button class="boton4" style="margin-left: 3%;" onclick="articulo()" >ARTICULOS</button>
<form method="POST" name="form" style="margin-left: 3%;">
<input type="text" name="art" placeholder="ARTICULO" class="text" style="width: 12%;" onkeypress="cambia()" onchange="envio()" value='<?php echo "".$fcar['ARTICULO']."";?>'>
<input type="text" name="des" placeholder="DESCRIPCION" class="text" style="width: 30%;" value='<?php echo "".$fcar['DESCRIPCION']."";?>'>
<input type="hidden" name="op" id="op" >
<select class="text" name="bodega" style="width: 18%;">
	
<?php
$bodega=$_SESSION['bodega'];

	$c=$conexion1->query("select * from consny.bodega where bodega='$bodega' order by bodega")or die($conexion1->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	if($bodega!='')
	{
echo "<option value=".$f['BODEGA'].">".$f['BODEGA'].": ".$f['NOMBRE']."</option>";
	}
	
		$tipousu=substr($bodega,0);
	if($tipousu[0]=='U')
	{

		$c=$conexion1->query("select * from dbo.usuariobodega where usuario like 'U%' order by bodega")or die($conexion1->error());
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$bo=$f['BODEGA'];
			$cub=$conexion1->query("select * from consny.bodega where bodega='$bo'")or die($conexion1->error());
			$fcub=$cub->FETCH(PDO::FETCH_ASSOC);

			echo "<option value=".$f['BODEGA'].">".$fcub['BODEGA']."".$fcub['NOMBRE']."</option>";
		}
	}else if($_SESSION['tipo']==3)
	{
		$c=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%' ORDER BY bodega")or die($conexion1->error());
	}

if($_SESSION['tipo']==1 or $_SESSION['tipo']==3 or $_SESSION['tipo']==4)
{
	$c=$conexion1->query("select * from consny.bodega where nombre not like '%(N)%' ORDER BY bodega")or die($conexion1->error());
	while($f=$c->FETCH(PDO::FETCH_ASSOC))
	{
		echo "<option value=".$f['BODEGA'].">".$f['BODEGA'] .": ".$f['NOMBRE']."</option>";
	}
}

?>
</select>
ABIERTOS: 
<label>
<input type="radio" name="fardo" value="SI" required> SI</label>
<label>
	<input type="radio" name="fardo" value="NO" required> NO</label>
<input type="submit" name="btn" value="BUSCAR" class="boton3" onclick="enviar()">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	
	if($op==1)
	{
		if($art=='')
		{
			echo "<script>location.replace('registros.php')</script>";
		}
		$c=$conexion1->query("select * from consny.articulo where articulo='$art'")or die($conexion1->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('NO SE ENCONTRO ARTICULO $art')</script>";
			echo "<script>location.replace('registros.php')</script>";
		}else
		{
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			echo "<script>location.replace('registros.php?art=".$f['ARTICULO']."')</script>";
		}
	}else if($op==2)
	{
		if($art=='' and $bodega!='')
		{
			$c=$conexion2->query("select * from registro where bodega='$bodega' and tipo!='C1' and activo is null order by codigo")or die($conexion2->error());
		}else if($art!='' and $bodega=='')
		{
			$c=$conexion2->query("select * from registro where codigo='$art' and tipo!='C1' and activo is null order by codigo")or die($conexion2->error());
		}else if($art!='' and $bodega!='')
		{
			$c=$conexion2->query("select * from registro where bodega='$bodega' and codigo='$art' and tipo!='C1' and activo is null order by codigo")or die($conexion2->error());
		}
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h2>NO SE ENCONTRO NINGUN FARDO</h2>";
		}else
		{
			echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:3%; margin-top:1%;'>";
			echo "<tr>
			<td colspan='8'><a href='expor_fardo.php?art=$art&&bodega=$bodega&&fardo=$fardo' target='_black'>";if(strtoupper($_SESSION['usuario'])=='STATECLA1' OR strtoupper($_SESSION['usuario'])=='STATECLA2' )
			{
					echo "<img src='excelteclas.png' width='20%' height='5%'>";
				}ELSE{ECHO " EXPORTAR A EXCEL";}ECHO "</a></td>
			</tr>";
			echo "<tr>
			<td>#</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>BODEGA</td>
			<td>BARRA</td>
			<td>PESO</td>
			<td>FECHA</td>
			<td>FARDO ABIERTO</td>
			</tr>";
			$k=1;
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				
				$arti=$f['codigo'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$arti' ")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			if($f['fecha_traslado']!='')
			{
				$fecha=$f['fecha_traslado'];
			}else
			{
				$fecha=$f['fecha_documento'];
			}
			$peso=$f['lbs'] + $f['peso'];
			$cd=$conexion2->query("select * from desglose where registro='".$f['id_registro']."'")or die($conexion2->error());
			$ncd=$cd->rowCount();
			if($ncd==0)
			{
				$o='NO';
			}else
			{
				$o='SI';
			}
			if($fardo==$o)
			{
				echo "<tr>
				<td>$k</td>
			<td>".$fca['ARTICULO']."</td>
			<td>".$fca['DESCRIPCION']."</td>
			<td>".$f['bodega']."</td>
			<td>".$f['barra']."</td>
			<td>$peso</td>
			<td>$fecha</td>
			<td>$o</td>
			</tr>";
			$k++;
			}
			}
			
		}
	}
}
?>
</body>
</html>