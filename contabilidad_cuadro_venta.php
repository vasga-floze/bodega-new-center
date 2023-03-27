<!DOCTYPE html>
<html>
<head>
	<title></title>	
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	<script>
		$(document).ready(function()
		{
			$("#caja").hide();
		})
		function cerrar()
		{
			$(".detalle").hide();
		}

		function seleccion(e)
		{
			$("#bodega").val($("#b"+e).val()+': '+$("#nom"+e).val());
			$(".detalle").hide();
		}
		function activarbodega()
		{
			$(".detalle").show();
		}
		function vaciar()
		{
			$("#bodega").val('');
		}
	</script>
</head>
<body>
<?php
include("conexion.php");
if($_SESSION['tipo']==5)
{

}else
{
	echo "<script>alert('MODULO NO DISPONIBLE PARA TU USUARIO')</script>";
	echo "<script>location.replace('conexiones.php')</script>";
}
?>

<div id="caja" style="width: 100%; height: 100%; background-color: white; position: fixed;">
	<img src="loadf.gif" width="25%" height="25%" style="margin-left: 45%; margin-top: 5%;">
</div>
<div class="detalle" style="margin-top: -8%; display: none;">
	<button style="float:right; margin-right:0%; background-color:red; padding:0.5%; color: white; position:sticky; top:0; right:0; cursor: pointer;" onclick="cerrar()">X</button><br><br>
	<div class="adentro" style="width: 70%; height: 90%; margin-left: 16.5%;">
	<?php
	$cb=$conexion1->query("select consny.bodega.bodega,consny.bodega.nombre,usuariobodega.esquema as empresa from 
consny.bodega inner join dbo.usuariobodega on
consny.bodega.BODEGA=dbo.usuariobodega.bodega where consny.bodega.bodega not like 'SM%' and consny.bodega.nombre not like '%(N)%' and consny.bodega.bodega!='CA00' and consny.BODEGA.BODEGA!='us01' and usuariobodega.tipo='TIENDA' order by consny.bodega.bodega")or die($conexion1->error());
echo "<table style='border-collapse:collapse; width:98%;' border='1' cellspadding='5'>";
echo "<tr>
	<td>EMPRESA</td>
	<td>BODEGA</td>
	<td>NOMBRE</td>
</tr>";
$n=0;
while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
{
	$empresa=$fcb['empresa'];
	$bod=$fcb['bodega'];
	$nom=$fcb['nombre'];
	echo "<tr class='tre' style='cursor:pointer;' onclick='seleccion($n)'>
	<td>$empresa</td>
	<td>$bod</td>
	<td>$nom</td>
	<input type='hidden' name='b$n' id='b$n' value='$bod'>
	<input type='hidden' name='nom$n' id='nom$n' value='$nom'>";

	echo "<input type='hidden' name='num' id='num' value='$n'>
</tr>";
$n++;
}


ECHO "</table>";
	?>
	</div>
	
</div>
<form method="POST" autocomplete="off">
<input type="text" name="bodega" id="bodega" class="text" style="width: 50%" ondblclick="activarbodega()" onkeyup="vaciar()" required placeholder="BODEGA">
<input type="date" name="desde" id="desde" class="text" style="width: 15%;" required>
<input type="date" name="hasta" id="hasta" class="text" style="width: 15%;" required>

<input type="submit" name="btn" class="boton2" value="GENERAR">
</form>
<?php
if($_POST)
{
	extract($_REQUEST);
	if($btn=='GENERAR')
	{
		$e=explode(":", $bodega);
		$bod=$e[0];
		$c=$conexion1->query("select * from CUADRO_VENTA where BODEGA='$bod' and FECHA between '$desde' and '$hasta' and monto_banco is null order by fecha")or die($conexion1->error());

		echo "<script>
		$(document).ready(function()
		{
			$('#bodega').val('$bodega');
			$('#desde').val('$desde');
			$('#hasta').val('$hasta');	
			})
		</script>";

		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO INFORMACION DIGITADA EN EL FILTRO REALIZADO</h3>";
		}else
		{
			echo "<table border='1' style='border-collapse:collapse; border-color: blue; margin-top:2.5%;' cellpadding='10' width='100%'>";
			echo "<tr>
			<td colspan='7'>$bodega DESDE: $desde HASTA: $hasta</td>
			</tr>";

			echo "<tr>
			<td>FECHA</td>
			<td>EMPRESA</td>
			<td>MONTO TIENDA</td>
			<td>FECHA DEPOSITO BANCO</td>
			<td>REFERENCIA</td>
			<td>MONTO DEPOSITADO</td>
			<td>BANCO</td>
			</tr>";
			echo "<form method='POST' name='form1' id='form1' autocomplete='off'>";
			$n=0;
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$fecha=$f['FECHA'];
				$monto=$f['MONTO_LIQUIDO'];
				$bodegas=$f['BODEGA'];
				$id=$f['ID'];
				
				$ce=$conexion1->query("select * from usuariobodega where tipo='TIENDA' and bodega='$bodegas'")or die($conexion1->error());
				$fce=$ce->FETCH(PDO::FETCH_ASSOC);
				$empresa=$fce['ESQUEMA'];
				echo "<input type='hidden' readonly name='esquema' id='esquema' value='$empresa'>";
				echo "<tr>
						<td>$fecha</td>
						<td>$empresa</td>
						<td>$monto</td>
						<td><input type='date' name='fecha_banco[$n]' id='fecha_banco[$n]' min='$fecha' class='text'>
						<input type='hidden' readonly name='id[$n]' id='id[$n]' value='$id'>

						<input type='hidden' readonly name='bode[$n]' id='bode[$n]' value='$bodegas'>

						<input type='hidden' readonly name='fecha[$n]' id='fecha[$n]' value='$fecha'>
						</td>
						<td><input type='text' name='referencia[$n]' id='referencia[$n]' class='text' style='width:100%;'></td>

						<td><input type='number' step='any' name='monto_banco[$n] id='monto_banco[$n]' class='text' min='0'></td>
						
						<td>";

						$cb=$conexion1->query("select cuenta_contable,descripcion from $empresa.cuenta_contable where cuenta_contable like '1-02-01-003-001-00%' and acepta_datos='S'")or die($conexion1->error());

						echo "<select name='banco[$n]' id='banco[$n]' class='text'>";
						echo "<option value=''>BANCO</option>";
						if($bodegas!='CA29')
						{


						while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
						{
							$cuenta=$fcb['cuenta_contable'];
							$desc=$fcb['descripcion'];
							echo "<option value='$cuenta'>$desc</option>";
						}
					}else
					{
						echo "<option value='1-02-01-003-001-002'>Banco Agricola</option>";
					}
						echo "</select>";
						echo "</td>
					</tr>";
					$n++;




			}

			echo "<tr>
			<td colspan='7'>
			<input type='hidden' name='numero' id='numero' value='$n' readonly>
			<label><input type='checkbox' required>HE TERMINADO</label>
			<input type='submit' name='btn' id='btn' value='FINALIZAR' class='btnfinal' style='padding:0.5%; float: right; margin-right:0.5%; margin-bottom: 0.5%;'>
			</td>
			</tr>";
		}
	}else if($btn=='FINALIZAR')
	{
		$num=0;
		$linea=0;
		//echo "<script>alert('$banco[0] <-')</script>";
		while($num<=$numero)
		{
			if($fecha_banco[$num]!='' and $monto_banco[$num]!='' and $banco[$num]!='' and $bode[$num]!='CA29')
			{
				
				
				
						//paquete cb 
					$cp=$conexion1->query("select ultimo_asiento from $esquema.paquete where paquete='CB'") or die($conexion1->error());
					$fcp=$cp->FETCH(PDO::FETCH_ASSOC);
					$asiento=$fcp['ultimo_asiento'];
					$texto=explode("CB-", $asiento);
					$queda=$texto[1]+1;
					$queda=str_pad($queda,7,"0",STR_PAD_LEFT);
					$queda="CB-$queda";
					$k=0;
					while($k==0)
					{
						$cv=$conexion1->query("select * from $esquema.asiento_de_diario where asiento='$asiento'")or die($conexion1->error());
						$ncv=$cv->rowCount();
						if($ncv==0)
						{
							$k=1;
						}else
						{
							$asiento=$queda;
							$text=explode("CB-", $asiento);
							$asiento=$text[1]+1;
							$asiento=str_pad($asiento,7,"0",STR_PAD_LEFT);
							$asiento="CB-$asiento";

							$texto=explode("CB-", $asiento);
							$queda=$texto[1]+1;
							$queda=str_pad($queda,7,"0",STR_PAD_LEFT);
							$queda="CB-$queda";
							$k=0;
						}
					}
					

					$ci=$conexion1->query("select CUADRO_VENTA.monto_liquido,CUADRO_VENTA.FECHA,USUARIOBODEGA.CENTRO_COSTO from
					CUADRO_VENTA inner join USUARIOBODEGA on CUADRO_VENTA.BODEGA=USUARIOBODEGA.BODEGA where
					CUADRO_VENTA.ID='$id[$num]' and CUADRO_VENTA.BODEGA='$bode[$num]' and USUARIOBODEGA.TIPO='TIENDA'")or die($conexion1->error());
					$fci=$ci->FETCH(PDO::FETCH_ASSOC);
					$centro_costo=$fci['CENTRO_COSTO'];
					$monto_liquido=$fci['monto_liquido'];
					//echo " id: $id[$num] fecha: $fecha[$num] referencia: $referencia[$num] fecha b: $fecha_banco[$num]  montob: $monto_banco[$num] $esquema $asiento - $queda bodega: $bode[$num] banco: $banco[$num] centro: $centro_costo<br>";
					$usuario=$_SESSION['usuario'];
					$paquete=$_SESSION['paquete'];
					
					if($conexion1->query("insert into $esquema.ASIENTO_DE_DIARIO(asiento,paquete,tipo_asiento,fecha,contabilidad,origen,clase_asiento,total_debito_loc,total_debito_dol,total_credito_loc,total_credito_dol,ultimo_usuario,fecha_ult_modif,marcado,total_control_loc,total_control_dol,usuario_creacion,FECHA_CREACION,NoteExistsFlag) values(
'$asiento','CB','CB','$fecha_banco[$num]','A','CB','N','$monto_banco[$num]','0','$monto_banco[$num]','0','$usuario',getdate(),'N','$monto_banco[$num]','0','$usuario',getdate(),'0')"))
					{
				if($conexion1->query("insert into $esquema.diario (asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,debito_local,debito_dolar,credito_local,credito_dolar,NoteExistsFlag) values('$asiento','1','ND','$centro_costo','$banco[$num]','$fecha[$num]','$referencia[$num]','$monto_banco[$num]','0','0','0','0')")or die($conexion1->error()))
					{
						if($conexion1->query("insert into $esquema.diario (asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,credito_local,credito_dolar,NoteExistsFlag) values('$asiento','2','ND','$centro_costo','1-02-01-001-000-000','$fecha[$num]','$referencia[$num]','$monto_banco[$num]','0','0')"))
						{

						if($conexion1->query("update cuadro_venta set monto_banco='$monto_banco[$num]',fecha_banco='$fecha_banco[$num]',referencia_banco='$referencia[$num]', asiento_banco='$asiento',fecha_hor_banco=getdate() where id='$id[$num]'"))
						{

						$conexion1->query("update $esquema.paquete set ultimo_asiento='$queda' where paquete='CB'")or die($conexion1->error());

						//echo "<hr>A: $asiento - Q: $queda - u: $usuario - P: $paquete - mb: $monto_banco[$num] fecha: $fecha[$num] referencia: $referencia[$num] fechabanco: $fecha_banco[$num] - centro costo: $centro_costo esquema: $esquema banco: $banco[$num] -> EXITO<hr>";


						}else
						{
							echo "<br>error cuadro_venta";
						}

							
						}else
						{
						echo "<br>ERROR LINEA 2 DIARIO";

						}
					}else
					{
						echo "<br>ERROR LINEA 1 DIARIO";
					}


					}else
					{
						echo "<br> error asiento_de_diario";
					}

					

					

				
			} else if($fecha_banco[$num]!='' and $monto_banco[$num]!='' and $banco[$num]!='' and $bode[$num]=='CA29')
			{
				//cara sucia

				//insert new york
				$cnyc=$conexion1->query("select ultimo_asiento from  newyork.paquete where paquete='CB'")or die($conexion1->error());
				$fcnyc=$cnyc->FETCH(PDO::FETCH_ASSOC);
				$asientonyc=$fcnyc['ultimo_asiento'];
				$t=explode('CB-', $asientonyc);
				$quedanyc=$t[1]+1;
				$quedanyc=str_pad($quedanyc,7,"0",STR_PAD_LEFT);
				$quedanyc="CB-$quedanyc";

				$k1=0;
				while($k1==0)
				{
					$cv1=$conexion1->query("select * from newyork.asiento_de_diario where asiento='$asientonyc'")or die($conexion1->error());
					$ncv1=$cv1->rowCount();
					if($ncv1==0)
					{
						$k1=1;
					}else
					{
						$asientonyc=$quedanyc;
					$t=explode('CB-', $asientonyc);
					$quedanyc=$t[1]+1;
					$quedanyc=str_pad($quedanyc,7,"0",STR_PAD_LEFT);
					$quedanyc="CB-$quedanyc";
					$k1=0;
					}
					

				}
				$usuario=$_SESSION['usuario'];
				if($conexion1->query("insert into newyork.ASIENTO_DE_DIARIO(asiento,paquete,tipo_asiento,fecha,contabilidad,origen,clase_asiento,total_debito_loc,total_debito_dol,total_credito_loc,total_credito_dol,ultimo_usuario,fecha_ult_modif,marcado,total_control_loc,total_control_dol,usuario_creacion,FECHA_CREACION,NoteExistsFlag) values(
'$asientonyc','CB','CB','$fecha_banco[$num]','A','CB','N','$monto_banco[$num]','0','$monto_banco[$num]','0','$usuario',getdate(),'N','$monto_banco[$num]','0','$usuario',getdate(),'0')"))
				{
					if($conexion1->query("insert into newyork.diario (asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,debito_local,debito_dolar,credito_local,credito_dolar,NoteExistsFlag) values('$asientonyc','1','ND','00-00-000','1-02-01-003-001-002','$fecha[$num]','$referencia[$num]','$monto_banco[$num]','0','0','0','0')"))
					{
						if($conexion1->query("insert into newyork.diario (asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,credito_local,credito_dolar,NoteExistsFlag) values('$asientonyc','2','ND','00-00-000','1-02-03-001-000-000','$fecha[$num]','$referencia[$num]','$monto_banco[$num]','0','0')"))
						{

						}else
						{
							echo "ERROR INSERT NYC 2 DIARIO";
						}

					}else
					{
						echo "ERROR PRIMER INSERT DIARIO NYC";
					}

				}else
				{
					echo "ERROR EN EL INGRESO DE ASIENTO DE DIARIO";
				}




				//fin newyork

				//INICIA CARISMA CA29
				$cc=$conexion1->query("select ultimo_asiento from carisma.paquete where paquete='CG'")or die($conexion1->error());
				$fcc=$cc->FETCH(PDO::FETCH_ASSOC);
				$asientoc=$fcc['ultimo_asiento'];
				$tex=explode("CG-", $asientoc);
				$quedac=$tex[1]+1;
				$quedac=str_pad($quedac,7,"0",STR_PAD_LEFT);
				$quedac="CG-$quedac";
				$k2=0;
				while($k2==0)
				{
					$cv2=$conexion1->query("select * from carisma.asiento_de_diario where asiento='$asientoc'")or die($conexion1->error());
					$ncv2=$cv2->rowCount();
					if($ncv2==0)
					{
						$k2=1;
					}else
					{
						$asientoc=$quedac;
						$tex=explode("CG-", $asientoc);
						$quedac=$tex[1]+1;
						$quedac=str_pad($quedac,7,"0",STR_PAD_LEFT);
						$quedac="CG-$quedac";
						$k2=0;

					}
				}

				if($conexion1->query("insert into carisma.ASIENTO_DE_DIARIO(asiento,paquete,tipo_asiento,fecha,contabilidad,origen,clase_asiento,total_debito_loc,total_debito_dol,total_credito_loc,total_credito_dol,ultimo_usuario,fecha_ult_modif,marcado,total_control_loc,total_control_dol,usuario_creacion,FECHA_CREACION,NoteExistsFlag) values(
'$asientoc','CG','CG','$fecha_banco[$num]','A','CG','N','$monto_banco[$num]','0','$monto_banco[$num]','0','$usuario',getdate(),'N','$monto_banco[$num]','0','$usuario',getdate(),'0')"))
				{
					if($conexion1->query("insert into carisma.diario (asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,debito_local,debito_dolar,credito_local,credito_dolar,NoteExistsFlag) values('$asientoc','1','ND','01-02-033','3-02-02-001-000-000','$fecha[$num]','$referencia[$num]','$monto_banco[$num]','0','0','0','0')"))
					{
						if($conexion1->query("insert into carisma.diario (asiento,consecutivo,nit,centro_costo,cuenta_contable,fuente,referencia,credito_local,credito_dolar,NoteExistsFlag) values('$asientoc','2','ND','01-02-033','1-02-01-001-000-000','$fecha[$num]','$referencia[$num]','$monto_banco[$num]','0','0')"))
						{
							if($conexion1->query("update cuadro_venta set monto_banco='$monto_banco[$num]',fecha_banco='$fecha_banco[$num]',referencia_banco='$referencia[$num]', asiento_banco='$asientonyc',fecha_hor_banco=getdate() where id='$id[$num]'"))
							{
								$conexion1->query("update newyork.paquete set ultimo_asiento='$quedanyc' where paquete='CB'")or die($conexion1->error());
								$conexion1->query("update carisma.paquete set ultimo_asiento='$quedac' where paquete='CG'")or die($conexion1->error());
								echo "<u>GUARDADO CORRECTAMENTE</u>";
								$bo="CA29";
							}else{
								echo "<script>alert('error cuadro_venta')</script>";

							}
						}else
						{
							echo "<script>alert('ERROR EN DIARIO CARISMA 2')</script>";
						}


					}else
					{
						echo "<h3>ERROR DIARIO CARISMA 1</h3>";
					}
				}else
				{
					echo "<h3>ERROR EN ASIENTO DE DIARIO CARISMA</h3>";
				}


				//FIN CARISMA CA29
			}


			
			$num++;
					}
					if($bodegas!='CA29')
					{
					echo "<script>alert('GUARDO CORRECTAMENTE')</script>";
					echo "<script>location.replace('contabilidad_cuadro_venta.php')</script>";

				}else
				{

				}
		}

	
}
?>
</body>
</html>