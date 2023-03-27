<!DOCTYPE html>
<html>
<head>
	<title></title>
<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<style>
		.text
		{
			padding: 1%;
		}
		</style>

		<script>
			$(document).ready(function(){
			if($("#barra1").val()!='')
			{
				$("#barra").val($("#barra1").val());
			}
			if($("#articulo_barra").val()!='')
			{
				$("#art").val($("#articulo_barra").val());
				$("#descripcion").val($("#descripcion_barra").val());
			}
			if($("#bodega_barra").val()!='')
			{
				$("#bodega").val($("#bodega_barra").val());
			}
			if($("#articulo_nuevo").val()!='')
			{
				$("#articulo").val($("#articulo_nuevo").val());
			}
			if($("#descripcion_nueva").val()!='')
			{
				$("#desc").val($("#descripcion_nueva").val());
			}

			})

			function buscas()
			{
				//alert();
				$("#op").val('1');
				$("#form").submit();
			}

			function buscar_art()
			{
				$("#op").val('2');
				$("#form").submit();
			}
			function enviar()
			{
				$("#op").val('3');
			}
		</script>
</head>
<body>
<?php
include("conexion.php");
?>
<h3 style=" margin-left: 30%;">CAMBIO DE ARTICULO POR ERROR DIGITACION(CONTENEDOR)</h3>

<form method="POST" style="text-align: left; width: 40%; height: 60%; border: groove; border-color: rgb(0,0,0.5); margin-left: 30%; background-color: rgb(0,0,0,0.2);" autocomplete="off" name="form" id="form">
<br><br>

<label>CODIGO BARRA</label><br>

<input type="text" name="barra" id="barra" placeholder="CODIGO DE BARRA" class="text" style="padding: 1%;" onchange="buscas()" required>
<br><br>
<label>ARTICULO ACTUAL</label><br>
<input type="text" name="art" id="art" placeholder="ARTICULO ACTUAL" readonly class="text" style="padding: 1%;" >
<br><br>
<label>DESCRIPCION ACTUAL</label><br>

<input type="text" name="descripcion" id='descripcion' placeholder="DESCRIPCION ACTUAL" class="text" readonly>
<br><br>
<label>BODEGA</label><br>

<input type="text" name="bodega" id="bodega" placeholder="BODEGA ACTUAL" class="text" readonly>

<br><br>
<label>ARTICULO NUEVO</label><br>
<input type="text" name="articulo" id="articulo" placeholder="ARTICULO NUEVO" class="text" onchange="buscar_art()">
<br><br>
<label>DESCRIPCION NUEVA</label><br>

<input type="text" name="desc" id='desc' placeholder="DESCRIPCION NUEVA" class="text" readonly>
<br><br>
<input type="hidden" name="op" id="op">

<input type="submit" name="btn"  value="GUARDAR CAMBIOS" class="boton2" style="border: groove; border-color: rgb(0,0,0.5); background-color: white; color: black; float: right; margin-right: 0.5%;" onclick="enviar()">
<br><br>
</form>
<?php
if($_POST)
{
	extract($_REQUEST);

	if($op==1)
	{

		$c=$conexion2->query("select * from registro where (bodega like 'SM%' or bodega='CA00') and barra='$barra' and activo is null  and tipo='CD'")or die($conexion2->error());
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<script>alert('CODIGO DE BARRA NO ESTA DISPONIBLE O NO ES DE CONTENEDOR o ESTA EN TIENDA')</script>";
			echo "<script>location.replace('cambio_articulo.php')</script>";
		}
		
			$vali=validacion_disponible($barra);
					if($vali=='FARDO NO SE PUEDE USAR POR:')
					{
						//echo "<script>alert('$barra')</script>";
						$f=$c->FETCH(PDO::FETCH_ASSOC);
			$cod=$f['codigo'];
			$bodega=$f['bodega'];
			$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$art_barra=$fca['ARTICULO'];
			$desc_barra=$fca['DESCRIPCION'];
			echo "<input type='hidden' name='articulo_barra' id='articulo_barra' value='$art_barra' readonly >";
			echo "<input type='hidden' name='descripcion_barra' id='descripcion_barra' value='$desc_barra' readonly >";
			echo "<input type='hidden' name='bodega_barra' id='bodega_barra' value='$bodega' readonly >";
			echo "<input type='hidden' name='barra1' id='barra1' value='$barra' readonly >";

					}else
					{
						echo "<script>alert('$vali')</script>";
						echo "<script>location.replace('cambio_articulo.php')</script>";
					}
			
		
		
	}else if($op==2)
	{
		$ca=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on 
consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO where ACTIVO='S' and BODEGA='$bodega' and consny.ARTICULO.ARTICULO='$articulo'")or die($conexion1->error());
		$nca=$ca->rowCount();
		if($nca==0)
		{
			echo "<script>alert('EL ARTICULO $articulo NO SE ENCUENTRA DISPONIBLE O NO SE ENCUENTRA ASOCIADO A LA BODEGA $bodega')</script>";
		}else
		{
			$fca=$ca->FETCH(PDO::FETCH_ASSOC);
			$articulo=$fca['ARTICULO'];
			$descripcion_nueva=$fca['DESCRIPCION'];
			echo "<input type='hidden' name='articulo_barra' id='articulo_barra' value='$art' readonly>";
			echo "<input type='hidden' name='descripcion_barra' id='descripcion_barra' value='$descripcion' readonly>";
			echo "<input type='hidden' name='bodega_barra' id='bodega_barra' value='$bodega' readonly>";
			echo "<input type='hidden' name='barra1' id='barra1' value='$barra' readonly>";

			echo "<input type='hidden' name='articulo_nuevo' id='articulo_nuevo' value='$articulo' readonly>";
			echo "<input type='hidden' name='descripcion_nueva' id='descripcion_nueva' value='$descripcion_nueva' readonly>";

		}
	}else if($op==3)
	{
		//echo "hacer cambio";
		echo "<input type='hidden' name='articulo_barra' id='articulo_barra' value='$art' readonly>";
			echo "<input type='hidden' name='descripcion_barra' id='descripcion_barra' value='$descripcion' readonly>";
			echo "<input type='hidden' name='bodega_barra' id='bodega_barra' value='$bodega' readonly>";
			echo "<input type='hidden' name='barra1' id='barra1' value='$barra' readonly>";

			echo "<input type='hidden' name='articulo_nuevo' id='articulo_nuevo' value='$articulo' readonly>";
			echo "<input type='hidden' name='descripcion_nueva' id='descripcion_nueva' value='$desc' readonly>";

			//consecutivo AJN
			$cajn=$conexion1->query("select * from consny.consecutivo_ci where consecutivo='AJN'")or die($conexion1->error());
			$fcajn=$cajn->FETCH(PDO::FETCH_ASSOC);
			$ajn=$fcajn['SIGUIENTE_CONSEC'];
			$fila=explode("AJN-",$ajn);
			$queda_ajn=$fila[1]+1;
			$queda_ajn=str_pad($queda_ajn,10,"0",STR_PAD_LEFT);
			$queda_ajn="AJN-$queda_ajn";
			$q=0;
			while($q==0)
			{
				$cv=$conexion1->query("select * from consny.documento_inv where documento_inv='$ajn'")or die($conexion1->error());
				$ncv=$cv->rowCount();
				if($ncv==0)
				{
					$q=1;
				}else
				{
				$fila=explode("AJN-", $ajn);
				$ajn=$fila[1]+1;
				$ajn=str_pad($ajn,10,"0",STR_PAD_LEFT);
				$ajn="AJN-$ajn";
				$queda_ajn=$fila[1]+2;
				$queda_ajn=str_pad($queda_ajn,10,"0",STR_PAD_LEFT);
				$queda_ajn="AJN-$queda_ajn";
				$q=0;

				}
				
			}
			//echo "S:$ajn queda: $queda_ajn<br>";

			//fin consecutivo AJN
			//inicia consecutivo AJP
			$cajp=$conexion1->query("select * from consny.consecutivo_ci where consecutivo='AJP'")or die($conexion1->error());
			$fcajp=$cajp->FETCH(PDO::FETCH_ASSOC);
			$ajp=$fcajp['SIGUIENTE_CONSEC'];
			$fila1=explode("AJP-", $ajp);
			$queda_ajp=$fila1[1]+1;
			$queda_ajp=str_pad($queda_ajp,10,"0",STR_PAD_LEFT);
					$queda_ajp="AJP-$queda_ajp";
			$k=0;
			while($k==0)
			{
				$cvp=$conexion1->query("select * from consny.documento_inv where documento_inv='$ajp'")or die($conexion1->error());
				$ncvp=$cvp->rowCount();
				if($ncvp==0)
				{
					$k=1;
				}else
				{
					$fila1=explode("AJP-", $ajp);
					$ajp=$fila1[1]+1;
					$ajp=str_pad($ajp,10,"0",STR_PAD_LEFT);
					$ajp="AJP-$ajp";
					$queda_ajp=$fila1[1]+2;
					$queda_ajp=str_pad($queda_ajp,10,"0",STR_PAD_LEFT);
					$queda_ajp="AJP-$queda_ajp";
					$k=0;
				}
			}
			//echo "S: $ajp queda: $queda_ajp";

			//fin consecutivo ajp

			//insert documento_inv ajp 
			$paquete=$_SESSION['paquete'];
			$usuario=$_SESSION['usuario'];
			$hoy=date("Y-m-d");
			$conexion1->query("insert into consny.documento_inv(paquete_inventario,
				documento_inv,
				consecutivo,
			referencia,
			fecha_hor_creacion,
			fecha_documento,
			seleccionado,
			usuario,
			NoteExistsFlag) values('$paquete',
			'$ajp',
			'AJP',
			'CAMBIO ARTICULO $art a $articulo ($barra)',
			getdate(),
			'$hoy',
			'N',
			'$usuario',
			'0'
		)")or die($conexion1->error());
			//fin insert documento_inv ajp

			//insert linea_doc ajp
			$conexion1->query("insert into consny.linea_doc_inv(paquete_inventario,documento_inv,
				linea_doc_inv,ajuste_config,articulo,bodega,tipo,subtipo,subsubtipo,cantidad,
				costo_total_local,costo_total_dolar,precio_total_local,precio_total_dolar,
				NoteExistsFlag) values('$paquete','$ajp','1','AJP','$articulo','$bodega',
				'O','D','L','1','1','1','1','1','0')")or die($conexion1->error());
			//fin insert linea_doc ajp

			//insert documento_inv ajn
			$conexion1->query("insert into consny.documento_inv(paquete_inventario,
				documento_inv,
				consecutivo,
			referencia,
			fecha_hor_creacion,
			fecha_documento,
			seleccionado,
			usuario,
			NoteExistsFlag) values('$paquete',
			'$ajn',
			'AJN',
			'CAMBIO ARTICULO $art a $articulo ($barra)',
			getdate(),
			'$hoy',
			'N',
			'$usuario',
			'0'
		)")or die($conexion1->error());
			//fin documento_inv ajn

				//insert linea_doc ajn
			$conexion1->query("insert into consny.linea_doc_inv(paquete_inventario,documento_inv,
				linea_doc_inv,ajuste_config,articulo,bodega,tipo,subtipo,subsubtipo,cantidad,
				costo_total_local,costo_total_dolar,precio_total_local,precio_total_dolar,
				NoteExistsFlag) values('$paquete','$ajn','1','AJN','$art','$bodega',
				'C','D','N','1','1','1','1','1','0')")or die($conexion1->error());
			//fin insert linea_doc ajn



			//registro
			$cr=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error());
			$fcr=$cr->FETCH(PDO::FETCH_ASSOC);
			$idr=$fcr['id_registro'];
			//fin registro
			//insert cambio_articulo
			$conexion2->query("insert into cambio_articulo(registro,articulo_actual,articulo_nuevo,documento_ajn,documento_ajp,bodega,fecha_hora,usuario,paquete) values('$idr','$art','$articulo','$ajn','$ajp','$bodega',getdate(),'$usuario','$paquete')")or die($conexion2->error());
			$conexion2->query("update registro set cambio_articulo='S',codigo='$articulo' where barra='$barra'")or die($conexion2->error());

			$conexion1->query("update consny.consecutivo_ci set SIGUIENTE_CONSEC='$queda_ajp' where consecutivo='AJP'")or die($conexion1->error());
			$conexion1->query("update consny.consecutivo_ci set SIGUIENTE_CONSEC='$queda_ajn' where consecutivo='AJN'")or die($conexion1->error());
			echo "<script>alert('guardado corectamente')</script>";
			//fin cambio_articulo



	}
}
?>
</body>
</html>