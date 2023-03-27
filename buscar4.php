<!DOCTYPE html>
<html>
<head>
	<title></title>
	


	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
	$(document).ready(function(){
		$(".detalle").hide();
	});
		function cambiar()
		{
			$("#op").val("1");
		}
		function enviar()
		{
			document.form.submit();
		}
		function enviar1()
		{
			$("#op").val("2");
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
<center>
	<div class="detalle" style="background-color: white;">

	

<div class="preloader" style="margin-top: 15%;">
</div>
</div>
</center>
<body>
<?php
include("conexion.php");
error_reporting(0);
if($_SESSION['usuario']=='SALVARADO' or $_SESSION['usuario']=='staana3' or $_SESSION['usuario']=="salvarado" or $_SESSION['usuario']=='gjurado' or $_SESSION['usuario']=='GJURADO')
		{
$art=$_GET['art'];
$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$art'")or die($conexion1->error());
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$codi=$fca['ARTICULO'];
$de=$fca['DESCRIPCION'];
?>
<form method="POST" name="form">
	<input type="text" name="cod" placeholder="ARTICULO" class="text" style="width: 20%;" onkeypress="cambiar()" onchange="enviar()" value='<?php echo "$codi";?>'>
	<input type="text" name="de" placeholder="DESCRIPCION" class="text" style="width: 40%;" value='<?php echo "$de";?>'>
	<input type="hidden" name="op" id="op">
	<br><br>
	DESDE: <input type="date" name="d" class="text" style="width: 15%;">
	HASTA: <input type="date" name="h" class="text" style="width: 15%;">
	 <select class="text" name="clasi" style="width: 10%;">
		<option value="">CLASIFICACION</option>
		<?php
		$cc=$conexion2->query("select categoria from registro where categoria!='' group by categoria")or die($conexion2->error());
		while($fcc=$cc->FETCH(PDO::FETCH_ASSOC))
		{
			$cate=$fcc['categoria'];
			echo "<option>$cate</option>";
		}
		?>
	</select>
	<label>
		<input type="radio" name="opc" value="1" checked>RESUMEN</label>
		<label>
		<input type="radio" name="opc" value="2">DETALLE</label>
	<input type="submit" name="" value="BUSCAR" class="boton2" onclick="enviar1()">
</form><a href="buscar4.php"> <button style="float: left;">LIMPIAR</button></a><br>
<?php
if($_POST)
{
	extract($_REQUEST);
	//echo "<script>alert('NO SE ENCONTRO ARTICULOD $op')</script>";
	if($op==1)
	{

		$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$cod'")or die($conexion1->error());
		$nca=$ca->rowCount();
		if($nca==0)
		{
			echo "<script>alert('NO SE ENCONTRO ARTICULO')</script>";
			echo "<script>location.replace('buscar4.php')</script>";
		}else
		{
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$arti=$fca['ARTICULO'];
			echo "<script>location.replace('buscar4.php?art=$arti')</script>";
		}
	}else if($op==2)
	{
		if($opc==1)
		{
			if($cod=='' and $d!='' and $h!='' and $clasi=='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where  tipo='P' and fecha_documento between '$d' and '$h' and observacion!='ELIMINADO SYS...' and estado!='2'  group by codigo 
")or die($conexion2->error());
			}else if($cod=='' and $d!='' and $h=='' and $clasi=='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where  tipo='P' and fecha_documento='$d' and observacion!='ELIMINADO SYS...' and estado!='2' group by codigo 
")or die($conexion2->error());
			}else if($cod=='' and $d=='' and $h!='' and $clasi=='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where  tipo='P' and fecha_documento='$h' and estado!='2' and observacion!='ELIMINADO SYS...' group by codigo 
")or die($conexion2->error());
			}

			else if($cod=='' and $d=='' and $h=='' and $clasi=='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where  tipo='P' and observacion!='ELIMINADO SYS...' and estado!='2'  group by codigo 
")or die($conexion2->error());
			}else if($cod!='' and $d=='' and $h=='' and $clasi!='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where codigo='$cod' and tipo='P' and estado!='2' and observacion!='ELIMINADO SYS...' group by codigo 
")or die($conexion2->error());
			}else if($cod=='' and $d=='' and $h=='' and $clasi!='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where categoria='$clasi' and tipo='P' and observacion!='ELIMINADO SYS...' and estado!='2'    group by codigo 
")or die($conexion2->error());
			}
			elseif($cod=='' and $d!='' and $h!='' and $clasi!='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where categoria='$clasi' and tipo='P' and fecha_documento between '$d' and '$h' and estado!='2' and observacion!='ELIMINADO SYS...' group by codigo 
")or die($conexion2->error());
			}else if($cod=='' and $d!='' and $h=='' and $clasi!='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where categoria='$clasi' and tipo='P' and fecha_documento='$d' and estado!='2' and observacion!='ELIMINADO SYS...' group by codigo 
")or die($conexion2->error());
			}else if($cod=='' and $h!='' and $d=='' and $clasi!='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where categoria='$clasi' and tipo='P' and fecha_documento='$h' and estado!='2' and observacion!='ELIMINADO SYS...' group by codigo 
")or die($conexion2->error());//---
			}else if($cod!='' and $d!='' and $h!='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where codigo='$cod' and tipo='P' and fecha_documento between '$d' and '$h' and estado!='2' and observacion!='ELIMINADO SYS...' group by codigo 
")or die($conexion2->error());
			}else if($cod!='' and $d!='' and $h=='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where codigo='$cod' and tipo='P' and fecha_documento='$d' and estado!='2' and observacion!='ELIMINADO SYS...' group by codigo 
")or die($conexion2->error());
			}else if($cod!='' and $d=='' and $h!='')
			{
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where codigo='$cod' and tipo='P' and fecha_documento='$h' and estado!='2' and observacion!='ELIMINADO SYS...' group by codigo 
")or die($conexion2->error());
			}else if($cod!='' and $d=='' and $h=='' and $clasi=='')
			{
				//echo "<script>alert('akii')</script>";
				$c=$conexion2->query("select count(registro.codigo) as cantidad,codigo,SUM(und) as unidades,SUM(lbs)as libras from registro where codigo='$cod' and tipo='P' and estado!='2' and observacion!='ELIMINADO SYS...' group by codigo 
")or die($conexion2->error());
			}

			
			$n=$c->rowCount();
	if($n==0)
	{
		echo "<h2>NO SE ENCONTRO REGISTROS.</h2>";
	}else
	{
		echo "<table class='tabla' border='1' cellpadding='10'>";
		echo "
		<tr>
			<td>CLASIFICACION</td>
			<td># FARDOS</td>
			<td>ARTICULO</td>
			<td>DESCRIPCION</td>
			<td>TOTAL</td>
			<td>UNIDADES</td>
			<td>LIBRAS</td>
		</tr>";
		$tp=0; $tlb=0;
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$codigo=$f['codigo'];
			$unidades=$f['unidades'];
			$libras=$f['libras'];
			$numero=$f['cantidad'];
			$tlb=$tlb+$libras;
			$k=$conexion2->query("select categoria from registro where codigo='$codigo' and tipo='P'")or die($conexion2->error());
			$fk=$k->FETCH(PDO::FETCH_ASSOC);
			$clasificacion=$fk['categoria'];
			$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$codigo'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$art=$fca['ARTICULO'];
			$de=$fca['DESCRIPCION'];
		if($d!='' and $h!='')
		{
			$cd=$conexion2->query("select count(detalle.articulo)as numero,detalle.articulo,sum(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where tipo='P' and registro.codigo='$codigo' and registro.fecha_documento between '$d' and '$h' group by detalle.articulo 
")or die($conexion1->error());
		}else if($d!='' and $h=='')
		{
			$cd=$conexion2->query("select count(detalle.articulo)as numero,detalle.articulo,sum(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where tipo='P' and registro.codigo='$codigo' and registro.fecha_documento='$d' group by detalle.articulo 
")or die($conexion1->error());
		}else if($h!='' and $d=='')
		{
			$cd=$conexion2->query("select count(detalle.articulo)as numero,detalle.articulo,sum(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where tipo='P' and registro.codigo='$codigo' and registro.fecha_documento='$h' group by detalle.articulo 
")or die($conexion1->error());
		}else if($d=='' and $h=='')
		{
			$cd=$conexion2->query("select count(detalle.articulo)as numero,detalle.articulo,sum(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where tipo='P' and registro.codigo='$codigo'  group by detalle.articulo 
")or die($conexion1->error());
		}
		$t=0; $tf=0;
	while($fcd=$cd->FETCH(PDO::FETCH_ASSOC))
	{
		$arti=$fcd['articulo'];
		$cant=$fcd['cantidad'];
		$cad=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$arti'")or die($conexion1->error());
		$fcad=$cad->FETCH(PDO::FETCH_ASSOC);
		$precio=$fcad['PRECIO_REGULAR'];
		$t=$cant * $precio;
		$tf=$tf+$t;
		


	}
	echo "<tr>
			<td>$clasificacion</td>
			<td>$numero</td>
			<td>$art</td>
			<td>$de</td>
			<td>$$tf</td>
			<td>$unidades</td>
			<td>$libras</td>
		</tr>";
		$tp=$tp+$tf;

		}
		echo "<tr><td colspan='4'>TOTAL</td><td>$$tp</tp><td></td><td>$tlb</td></tr>";
		echo "</table>";
	}


		}else
		{
			if($cod!='' and $d=='' and $h=='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.tipo='P'  and detalle.articulo='$cod' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod!='' and $d!='' and $h=='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.tipo='P' and registro.fecha_documento= '$d'  and detalle.articulo='$cod' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod!='' and $d=='' and $h!='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.tipo='P' and registro.fecha_documento= '$h'  and detalle.articulo='$cod' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod!='' and $d!='' and $h!='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.tipo='P' and registro.fecha_documento between '$d' and '$h' and detalle.articulo='$cod' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod=='' and $d!='' and $h!='' and $clasi=='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.tipo='P' and registro.fecha_documento between '$d' and '$h' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod=='' and $d!='' and $h=='' and $clasi=='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.tipo='P' and registro.fecha_documento ='$d'  group by detalle.articulo
")or die($conexion2->error());
			}else if($cod=='' and $h!='' and $d=='' and $clasi=='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.tipo='P' and registro.fecha_documento='$h' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod=='' and $d=='' and $h=='' and $clasi=='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.tipo='P' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod=='' and $d=='' and $h=='' and $clasi!='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.categoria='$clasi' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod=='' and $d!='' and $h!='' and $clasi!='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.categoria='$clasi' and registro.fecha_documento between '$d' and '$h' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod=='' and $d!='' and $h=='' and $clasi!='')
			{
					$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.categoria='$clasi' and registro.fecha_documento= '$d' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod=='' and $d=='' and $h!='' and $clasi!='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where registro.categoria='$clasi' and registro.fecha_documento= '$h' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod!='' and $d!='' and $h!='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where detalle.articulo='$cod' and registro.fecha_documento between '$d' and '$h' group by detalle.articulo
")or die($conexion2->error());
			}else if($cod!='' and $d=='' and $h=='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where detalle.articulo='$cod'  group by detalle.articulo
")or die($conexion2->error());
			}else if($cod!='' and $d=='' and $h!='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where detalle.articulo='$cod' and registro.fecha_documento='$h'  group by detalle.articulo
")or die($conexion2->error());
			}else if($cod!='' and $h=='' and $d!='')
			{
				$c=$conexion2->query("SELECT COUNT(detalle.articulo)as numero,detalle.articulo,SUM(detalle.cantidad) as cantidad from registro inner join detalle on detalle.registro=registro.id_registro where detalle.articulo='$cod' and registro.fecha_documento='$d'  group by detalle.articulo
")or die($conexion2->error());
			}
			
			$n=$c->rowCount();
			if($n==0)
			{
			if($opc==2 and $cod!='' and $d!='' and $h!='')
			{
				$c=$conexion2->query("select count(detalle.articulo)as numero,detalle.articulo,sum(detalle.cantidad)as cantidad,registro.codigo from detalle inner join registro on  detalle.registro=registro.id_registro and registro.codigo='$cod' and registro.fecha_documento between '$d' and '$h' group by detalle.articulo,registro.codigo")or die($conexion2->error());

			}else if($cod!='' and $opc==2 and $d!='' and $h=='')
			{
				$c=$conexion2->query("select count(detalle.articulo)as numero,detalle.articulo,sum(detalle.cantidad)as cantidad,registro.codigo from detalle inner join registro on  detalle.registro=registro.id_registro and registro.codigo='$cod' and registro.fecha_documento='$d'  group by detalle.articulo,registro.codigo")or die($conexion2->error());
			}else if($opc==2 and $cod!='' and $h!='' and $d=='')
			{
				$c=$conexion2->query("select count(detalle.articulo)as numero,detalle.articulo,sum(detalle.cantidad)as cantidad,registro.codigo from detalle inner join registro on  detalle.registro=registro.id_registro and registro.codigo='$cod' and registro.fecha_documento='$h' group by detalle.articulo,registro.codigo")or die($conexion2->error());
			}else if($opc==2 and $cod!='' and $d=='' and $h=='')
			{
				$c=$conexion2->query("select count(detalle.articulo)as numero,detalle.articulo,sum(detalle.cantidad)as cantidad,registro.codigo from detalle inner join registro on  detalle.registro=registro.id_registro and registro.codigo='$cod' group by detalle.articulo,registro.codigo")or die($conexion2->error());
			}	
		}
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO REGISTRO</h3>";
			}else
			{
				echo "<table class='tabla' border='1' cellpadding='10'>";
				echo "<tr>
					<td>CLASIFICACION</td>
					<td>ARTICULO</td>
					<td>DESCRIPCION</td>
					<td>PRECIO</td>
					<td>CANTIDAD</td>
					<td>TOTAL</td>
				</tr>";
				$t=0; $tf=0; $tp=0;
				while($f=$c->FETCH(PDO::FETCH_ASSOC))
				{
					$codigo=$f['articulo'];
					$numero=$f['numero'];
					$cant=$f['cantidad'];
					$ca=$conexion1->query("select * from consny.ARTICULO where ARTICULO='$codigo'")or die($conexion1->error());
					$fca=$ca->FETCH(PDO::FETCH_ASSOC);
					$precio=$fca['PRECIO_REGULAR'];
					$art=$fca['ARTICULO'];
					$de=$fca['DESCRIPCION'];
					$clasificacion=$fca['CLASIFICACION_2'];
					$t=$cant * $precio;
					$tf=$t + $tf;
					echo "<tr>
					<td>$clasificacion</td>
					<td>$art</td>
					<td>$de</td>
					<td>$precio</td>
					<td>$cant</td>
					<td>$$t</td>
				</tr>";

				}
				echo "<tr>
					<td colspan='5'>TOTAL:</td>
					<td>$$tf</td>
				</tr>";
			}
		}
	}
	
}
}else
{
	echo "<script>alert('NO TIENES AUTORIZACION')</script>";
	echo "<script>location.replace('desglose.php')</script>";
}

?>







</body>
</html>