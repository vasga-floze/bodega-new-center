<!DOCTYPE html>
<html>
<head>
<?php
error_reporting(0);
include("conexion.php");
session_start();
$i=$_GET['i'];
$b=$_GET['b'];
$codb=$_GET['codb'];
$bu=$_GET['bu'];
$registroid=$_GET['artb'];

	$k=$conexion2->query("select * from dbo.registro where id_registro='$registroid'")or die($conexion2->error);



$fk=$k->FETCH(PDO::FETCH_ASSOC);
$ark=$fk['codigo'];
$nomk=$fk['subcategoria'];
$idregistro=$fk['id_registro'];
$documento=$_SESSION['doc'];
if($codb=="")
{
	$ko=$conexion2->query("select * from traslado where documento_inv='$documento' and origen='- -'")or die($conexion2->error);
	$fko=$ko->FETCH(pdo::FETCH_ASSOC);
	$codb=$fko['destino'];
}


$q=$conexion1->query("select * from consny.BODEGA where BODEGA='$codb'")or die($conexion1->error);
$fq=$q->FETCH(PDO::FETCH_ASSOC);
$bodegac=$fq['BODEGA'];
$nombreb=$fq['NOMBRE'];
$paquete=$_SESSION['paquete'];
$usuario=$_SESSION['usuario'];

?>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

	<script>
		$(document).ready(function(){
			$(".detalle").hide();
			$(".nodetalle").hide();
			$("#div1").hide();
			$(".coneditar").hide();
			if($("#i").val()==1)
			{
				$(".detalle").show();
			}else if($("#i").val()==2)
			{
				$(".nodetalle").show();
			}else if($("#i").val()==3)
			{
				$("#div1").show(500);
			}else if($("#i").val()==4)
			{
				$("#div1").show(100);
			}else if($("#i").val()==5)
			{
				$(".coneditar").show(300);
			}

			

		});
		function activar()
		{
			$(".detalle").show(500);
		}

		function cerrar()
		{
			$(".detalle").hide(500);
		}

		function activar1()
		{
			$(".nodetalle").show(500);
		}

		function cerrar1()
		{
			$(".nodetalle").hide(500);
		}

		function enviarform()
		{
			$("#op").val("1");
			document.form.submit();
		}
		function enviacambia()
		{

			$("#opc").val("1");
			document.formb.submit();
		}

		function enviaboton()
		{
			$("#opc").val("2");
			document.formb.submit();
		}
		function cancelar()
		{
			location.replace('detalle_traslado.php?i=4&&b &&bu ');
		}
	</script>
</head>	
<body>


<input type="hidden" name="i" id="i" value='<?php echo $i;?>'>

<div class="coneditar">
		<div class="editar" >
		<?php
			$ide=$_GET['id'];

			if($i==5 and $ide=="")
			{
				echo "<script>location.replace('detalle_traslado.php?i=4&&b= &&bu= ')</script>";
			}
			$cont=$conexion2->query("select * from traslado where id='$ide'")or die($conexion2->error);
			$ncont=$cont->rowCOUNT();
			if($ncont==0 and $i==5)
			{
				echo "<script>location.replace('detalle_traslado.php?i=4&&b= &&bu= ')</script>";
			}else
			{
				$fcont=$cont->FETCH(PDO::FETCH_ASSOC);
				$orig=$fcont['origen'];
				$cantt=$fcont['cantidad'];
			}
			
		?>

			<form method="POST" name="edita">
			BODEGA ORIGEN: <select name="bodegacambio" class="text" style="width: 20%;">
	<?php
		echo "<option>$orig</option>";
		$qu=$conexion1->query("select * from consny.BODEGA where BODEGA LIKE 'S%' and nombre not like'%(N)%' and BODEGA!='$orig'")or die($conexion1->error);
		while($fqu=$qu->FETCH(PDO::FETCH_ASSOC))
		{
			$bog=$fqu['BODEGA'];
			echo "<option>$bog</option>";
		}
	?>	
			</select name='opcion'><br><br>
			<label>
		<input type="checkbox" name="acti" value="1">

		APLICAR ESTA BODEGA A TODOS</label><br><br>
		<input type="button" name="btn" value="Cancelar" onclick="cancelar()">
		<input type="submit" name="btn" value="Guardar">
				
			</form>
		</div>
	</div>

<div class="detalle" style="margin-top: 0.1%;">
<a href="#" onclick="cerrar()" style="text-decoration: none; float: right; margin-right: 0.5%; color: white">Cerrar X</a><br>

<div class="adentro" style="height: 93%; margin-left: 2.5%; float: center;">
<form method="POST">
	<input type="text" name="busca" placeholder="CODIGO O NOMBRE BODEGA" class="text" style="width: 30%; margin-left: 27%;">
	<input type="submit" name="btn" value="Buscar"><br><br>
</form>
<center>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=="Buscar")
	{
	  echo "<script>location.replace('detalle_traslado.php?b=$busca&&i=1')</script>";
	}
	
}else
{
	$c=$conexion1->query("select * from consny.BODEGA where BODEGA='$b' OR NOMBRE like'%$b%' AND nombre not like'%(N)'
")or die($conexion1->error);
	$n=$c->rowCOUNT();
	if($n==0)
	{
		echo "<h3>NO SE ENCONTRO NINGUN REGISTRO</h3>";
	}else
	{
		echo "<table class='tabla' border='1' cellpadding='10'>
		<tr>
			<td>BODEGA</td>
			<td>NOMBRE</td>

		</tr>
		";
		while($fc=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$bod=$fc['BODEGA'];
			$nom=$fc['NOMBRE'];
			echo "<tr>
			<td><a href='detalle_traslado.php?codb=$bod' style='text-decoration:none; color:black;'>$bod</a></td>
			<td><a href='detalle_traslado.php?codb=$bod' style='text-decoration:none; color:black;'>$nom</a></td>
			</tr>";
		}
		echo "</table>";
	}
}
?>
	
</div>

	
</div>


<div class="nodetalle">
<a href="#" onclick="cerrar1()" style="float: right; margin-right: 0.5%; text-decoration: none; color: white;">Cerrar X</a>
	<div class="adentro	" style="margin-left: 2%; margin-top: 0.8%; height: 94%;">
	<form method="POST">
		<input type="text" name="bu" placeholder="CODIGO O NOMBRE ARTICULO" class="text" style="width: 20%; margin-left: 27%;">
		<input type="submit" name="btn" value="Buscar."><br>
	</form>
	<?php
		if($_POST)
		{
			extract($_REQUEST);
			if($btn=="Buscar.")
			{
			  echo "<script>location.replace('detalle_traslado.php?i=2&&bu=$bu')</script>";
			}
		}else
		{
			if($bu=="" or empty($bu))
					{
						$ca=$conexion2->query("select * from dbo.registro")or die($conexion2->error);
					}else
					{

			$ca=$conexion2->query("select * from dbo.registro where codigo='$bu' or subcategoria LIKE '%$bu%' or categoria LIKE '%$bu%'")or die($conexion2->error);
					}
			$nca=$ca->rowCOUNT();
			if($nca==0)
			{
				echo "<br><h2 style='margin-left:25%;'>NO SE ENCONTRO NINGUN REGISTRO DISPONIBLE</h2>";
			}else
			{
				echo "<table class='tabla' border='1' cellpadding='10' style='margin-left:2%;'>
				<tr>
					<td>ARTICULO</td>
					<td>DESCRIPCION</td>
					<td>FECHA PRODUCION</td>
				</tr>";
				while($fca=$ca->FETCH(PDO::FETCH_ASSOC))
				{
					$reg=$fca['id_registro'];
					$art=$fca['codigo'];
					$desc=$fca['subcategoria'];
					$fpro=$fca['fecha_producion'];
					
					$com=$conexion2->query("select * from dbo.desglose where registro='$reg'")or die($conexion2->error);

					$ncom=$com->rowCOUNT();
					if($ncom==0)
					{
						echo "<tr>
					  	<td><a href='detalle_traslado.php?artb=$reg&b= &&bu &&codb=$codb&&i=4' style='text-decoration:none; color:black;'>$art</a></td>
					  	<td><a href='detalle_traslado.php?artb=$reg&b= &&bu &&codb=$codb&&i=4' style='text-decoration:none; color:black;'>$desc</a></td>
					  	<td><a href='detalle_traslado.php?artb=$reg&b= &&bu &&codb=$codb&&i=4' style='text-decoration:none; color:black;'>$fpro</a></td>
					  </tr>";
					}else
					{
					  
					}
				}

				echo "</table>";
			}
		}
	?>

	</div>
	
</div>



<button onclick="activar()">DESTIDO</button>
<form method="POST" name="form" method="POST">
	
	<input type="text" name="bodega1" onchange="enviarform()" placeholder="BODEGA" class="text" style="width: 20%;" value='<?php echo $codb; ?>'>
	<input type="text" name="nomb" placeholder="NOMBRE BODEGA" class="text" style="width: 50%;" value='<?php echo $nombreb; ?>'>
	<input type="text" name="op" id="op">
	<input type="submit" name="btn" value="Siguiente">

	
</form>
<br>
<div id="div1">
<button onclick="activar1()">ARTICULOS</button>
<form method="POST" name="form1" id="form1">
<input type="text" name="art" class="text" readonly placeholder="CODIGO" style="width: 20%;" value='<?php echo $ark;?>' >
<input type="text" name="desc" class="text" readonly placeholder="NOMBRE" style="width: 50%;" value='<?php echo $nomk; ?>'>

<input type="number" name="cant" placeholder="CANTIDAD" class="text" style="width: 10%;">
<input type="text" name="idr" value='<?php echo $idregistro;?>'>
<input type="submit" name="btn" value="Add."><br>
<?php
	if($_POST)
	extract($_REQUEST);
if($btn=='Add.')
{
	$docn=$_SESSION['doc'];
	$con=$conexion2->query("select * from traslado where documento_inv='$docn' order by(id) asc")or die($conexion2->error);
	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
	$bod=$fcon['destino'];



	$conexion2->query("insert into traslado(destino,registro,cantidad,origen,estado,documento_inv,paquete,usuario)
		values('$bod','$idr','$cant','SM02','0','$docn','$paquete','$usuario')")or die($conexion2->error);
	echo "<script>location.replace('detalle_traslado.php?i=4&&b=&&bu=codb=$bodega1')</script>";
}

//muestra articulos agregados al traslado
$docun=$_SESSION['doc'];
$consu=$conexion2->query("select * from traslado where documento_inv='$docun' and origen!='- -'")or die($conexion2->error);
$nconsu=$consu->rowCOUNT();
if($nconsu=0)
{

}else
{
echo "<hr><table class='tabla' border='1' cellpadding='10'>
	<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
		<td>CANTIDAD</td>
		<td>ORIGEN</td>
		<td>EDITAR</td>
	</tr>";
while($fconsu=$consu->FETCH(PDO::FETCH_ASSOC))
{
	$idre=$fconsu['registro'];
	$can=$fconsu['cantidad'];
	$origen=$fconsu['origen'];
	$id=$fconsu['id'];
	$cr=$conexion2->query("select * from registro where id_registro='$idre'")or die($conexion2->error);
	$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
	$descripcion=$fcr['subcategoria'];
	$articulo=$fcr['codigo'];


	echo "<tr>
		<td>$articulo</td>
		<td>$descripcion</td>
		<td>$can</td>
		<td>$origen</td>
		<td><a href='detalle_traslado.php?i=5&&b= &&bu= &&id=$id'>CAMBIAR</a></td>";
		
}
echo "</table>";


}


?>
</div>


</form>
</body>
</html>


<?php
	if($_POST)
	{
		extract($_REQUEST);

	if($btn=="Siguiente")
	{
		//verificar si bodega existe
		$cve=$conexion1->query("select * from consny.bodega where bodega='$bodega1' and nombre='$nomb' and nombre not like'%(N)%'")or die($conexion1->error);
		$ncve=$cve->rowCOUNT();
		if($ncve==0)
		{
			echo "<script>alert('BODEGA:$bodega1 NO EXISTE O NO SE ENCUENTRA DISPONIBLE')</script>";
		}
		echo "<script>alert('entra')</script>";

		$valor=$_SESSION['doc'];
		if($valor=="")
		{
			echo "<script>alert('entra1')</script>";
			$cdoc=$conexion1->query("select CONSECUTIVO,SIGUIENTE_CONSEC from consny.CONSECUTIVO_CI where CONSECUTIVO='traslado'
")or die($conexion1->error);
		
			$fcdoc=$cdoc->FETCH(PDO::FETCH_ASSOC);
			$ndoc=$fcdoc['SIGUIENTE_CONSEC'];
			
			$_SESSION['doc']=$ndoc;

			$conexion2->query("insert into traslado(paquete,usuario,destino,cantidad,origen,documento_inv,estado) values('$paquete','$usuario','$bodega1','0','- -','$ndoc','0')")or die($conexion2->error);

			echo "<script>location.replace('detalle_traslado.php?i=4&&b=%20&&bu=%20&&codb=$bodega1&&i=3')</script>";


		}else
		{
			echo "<script>alert('entra2')</script>";
			$conexion2->query("update traslado set destino='$bodega1' where documento_inv='$valor'")or die($conexion2->error);
			echo "<script>alert('entra2')</script>";

			echo "<script>location.replace('detalle_traslado.php?i=3&&codb=$bodega1&&i=4')</script>";
		}
	}

		if($op==1)
		{
			$query=$conexion1->query("select * from consny.BODEGA where BODEGA='$bodega1' OR NOMBRE like'%$bodega1%' AND nombre not like'%(N)'")or die($conexion1->error);
		}	$nquery=$query->rowCOUNT();
		if($nquery==0)
		{
			echo "<script>alert('NO SE ENCONTRO REGISTRO DE $bodega1')</script>";
		}else
		{
			echo "<script>location.replace('detalle_traslado.php?codb=$bodega1')</script>";
		}
	}
?>