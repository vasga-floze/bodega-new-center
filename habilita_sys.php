<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
$(document).ready(function(){

$(".detalle").hide();
})

function opciones()
{
	$("#op").val('1');
}

function muestra(e)
{
	//alert();
		if(e==1)
		{
		$("#documentos").show();
		$("#ajp").attr('required',true);
		$("#ajn").attr('required',true);

		}else
		{
			$("#documentos").hide();
		$("#ajp").attr('required',false);
		$("#ajn").attr('required',false);

		}

}

function habilitar()
{
	$("#opc").val('1');

}

function muetraliq(e)
{
	if(e==1)
	{
		$("#documentosliq").show();
		$("#ajn_liq").attr('required',true);
		$("#ajp_liq").attr('required',true);
	}else
	{
		$("#documentosliq").hide();
		$("#ajn_liq").attr('required',false);
		$("#ajp_liq").attr('required',false);
	}
}

function ejecutarliq()
{
	$("#opc").val('2');
	//$("#formliquidacion").submit()
}
function muestra_averia(e)
{
	if(e==1)
	{
		$("#divaveria").show();
		$("#ajuste_averia").attr('required',true);
	}else
	{
		$("#divaveria").hide();
		$("#ajuste_averia").attr('required',false);

	}
}
function finaveria()
{
$("#opc").val('3');

}
</script>
</head>
<body>
<?php
include("conexion.php");
?>
<div class="detalle" style="background-color: white; margin-top: 0; width: 100%; height: 100%; margin-left: 0;">
	<img src="loadf.gif" style="margin-left: 45%; margin-top: 15%;">
</div>
<form method="POST">
TRANSACCION A HABILITAR: 
<select name="transaccion" class="text" required class="text" style="width: 30%;">
	<option value=""></option>
	<option value="1">DESGLOSE</option>
	<option value="2">LIQUIDACIONES</option>
	<option value="3">AVERIAS/MERCA NO VENDIBLE</option>
</select>
<input type="hidden" name="op" id="op" readonly>
<input type="submit" name="btn" value="SIGUIENTE" onclick="opciones()" class="boton2">
</form>

<?php
if($_POST)
{
	extract($_REQUEST);
	//echo "<script>alert('$op')</script>";
	if($op==1)
	{
		if($transaccion==1)//desgloses
		{
			echo "<div style='border:groove; background-color:white; width:40%; height:40%; border-color:blue; position:fixed; margin-left:25%;'>
			<a href='habilita_sys.php'><button style='float:right; margin-rigth:2%; cursor:pointer;'>X</button></a>
			<h3>HABILITAR DESGLOSE</h3>
			<form method='POST'>
			<input type='hidden' name='opc' id='opc' readonly>
			<label>
			CODIGO DE BARRA:<input type='text' class='text' name='barra' required></label>
			<br>FUE APLICADO EL DESGLOSE:<br><label><input type='radio' name='aplicado' id='aplicado' value='SI' onclick='muestra(1)' required> SI
			</label>
			<label><input type='radio' name='aplicado' id='aplicado' value='NO' onclick='muestra(2)' required> NO</label>
			</label>
			<div id='documentos' style='display:none;'>
			<label>DOCUMENTO AJUSTE POSITIVO: <input type='text' name='ajp' id='ajp' class='text'></label>
			<label>DOCUMENTO AJUSTE NEGATIVO: <input type='text' name='ajn' id='ajn' class='text'></label>
			</div>
			<button class='btnfinal' style='padding:0.5%; float:right; margin-rigth:1%; margin-top:1.5%;' onclick='habilitar()'>HABILITAR</button>
			</form>
			
			
			</div>";
		}else if($transaccion==2){
			//echo "<script>alert('liquidaciones')</script>";
			echo "<div style='background-color:white; border: groove; border-color: red; height:60%; width:40%; position:fixed; margin-left:25%;'>
			<a href='habilita_sys.php'><button style='float:right; margin-rigth:2%; cursor:pointer;'>X</button></a>
			<h3>ELIMINAR LIQUIDACION MAL DIGITADAS</h3>

			<form method='POST' id='formliquidacion'>
			<input type='hidden' name='opc' id='opc'>
			<label>DOCUMENTO CONSUMO: <input type='text' name='liq_consumo' class='text' id='liq_consumo' required></label><br><br>
			<label>DOCUMENTO INGRESO: <input type='text' name='liq_ing' class='text' id='liq_ing' required></label>
			<P>YA FUE APLICADA?</p>
			<label><input type='radio' name='aplicaliq' id='aplicaliq' value='SI' onclick='muetraliq(1)' required> SI</label>
			<label><input type='radio' name='aplicaliq' id='aplicaliq' value='NO' onclick='muetraliq(2)' required> NO</label>
			<div id='documentosliq' style='display:none;'>
			
			<label>DOCUMENTO AJUSTE POSITIVO: <input type='text' name='ajp_liq' id='ajp_liq' class='text'></label>
			<label>DOCUMENTO AJUSTE NEGATIVO: <input type='text' name='ajn_liq' id='ajn_liq' class='text'></label>
			</div>
			<button class='boton3' style='background-color:red; color:white; float:right; margin-rigth:2%; margin-top:2%;' onclick='ejecutarliq()'>ELIMINAR</button>
			</form>
			</div>";
		}else if($transaccion==3)
		{
			echo "<div style='background-color:white; border: groove; border-color: red; height:60%; width:40%; position:fixed; margin-left:25%;'>
			<a href='habilita_sys.php'><button style='float:right; margin-rigth:2%; cursor:pointer;'>X</button></a>
			<h3>ELIMINAR AVERIAS/MERCA NO VENDIBLE MAL DIGITADAS</h3>
			<form method='POST' id='formaverias'>
			<input type='hidden' name='opc' id='opc' readonly>
			<label>DOCUMENTO:
			<input type='text' name='averia_doc' id='averia_doc' required class='text' required>
			</label><br>
			¿YA FUE APLICADO?<br>
			<label> <input type='radio' name='averia_aplicado' id='averia_aplicado' value='SI' onClick='muestra_averia(1)'>SI</label>
			<label> <input type='radio' name='averia_aplicado' id='averia_aplicado' value='NO' onClick='muestra_averia(2)'>NO</label>
			<div id='divaveria' style='display:none;'>
			<label>DOCUMENTO AJUSTE:<br>
			<input type='text' name='ajuste_averia' id='ajuste_averia' class='text'>
			</label>
			</div>
			<br>
			<button class='boton3' style='background-color:red; color:white; float:right; margin-rigth:2%;' onClick='finaveria()'>ELIMINAR</button>
			</div>";
		}//else transaccion
	}//op
	if($opc==1){
		//habilitar desglose
		//echo "<script>alert('$aplicado')</script>";

	if($aplicado=='SI')
	{
		//aplicado
		$c_consumo=$conexion1->query("select * from consny.AUDIT_TRANS_INV where aplicacion='$ajn'")or die($conexion1->error());
		$nc_consumo=$c_consumo->rowCount();
		if($nc_consumo!=0)
		{
			$c_ing=$conexion1->query("select * from consny.AUDIT_TRANS_INV where aplicacion='$ajp'")or die($conexion1->error());
			$nc_ing=$c_ing->rowCount();
			if($nc_ing!=0)
			{
				//echo "<script>alert('$barra - $consumo - $ing')</script>";
				$conexion2->query("declare @id int=(select id_registro from registro where barra='$barra')


delete from desglose where registro=@id

update registro set fecha_desglose='',desglosado_por='',digita_desglose='',documento_inv_consumo='',documento_inv_ing='', ajp_habilitacion='$ajp',ajn_habilitacion='$ajn' where barra='$barra' 


")or die($conexion2->error());
		echo "<script>alert('HABILITADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('habilita_sys.php')</script>";
			}else
			{
				echo "<script>alert('$ajp NO HA SIDO APLICADO')</script>";

			}

		}else
		{
			echo "<script>alert('$ajn NO HA SIDO APLICADO')</script>";
		}
	}else
	{
		//echo "<script>alert()</script>";
		//validacion de que no esta aplicado
		$qv=$conexion2->query("select documento_inv_consumo,documento_inv_ing from registro where barra='$barra' and fecha_desglose!=''")or die($conexion2->error());
		$nqv=$qv->rowCount();
		if($nqv==0)
		{
			echo "<script>alert('DESGLOSE NO HA SIDO FINALIZADO O EL CODIGO DE BARRA NO EXISTE')</script>";
			echo "<script>location.replace('habilita_sys.php')</script>";
		}else
		{
			$fqv=$qv->FETCH(PDO::FETCH_ASSOC);
			$consumo=$fqv['documento_inv_consumo'];
			$ing=$fqv['documento_inv_ing'];

			$cva=$conexion1->query("select documento_inv,'NO'  APLICADO from consny.DOCUMENTO_INV where DOCUMENTO_INV in('$consumo','$ing')
union all
select APLICACION,'SI' from consny.AUDIT_TRANS_INV where APLICACION in('$consumo','$ing')")or die($conexion1->error());
			$comentario='DOCUMENTO_INV  || APLICADO\\n';
			while($fcva=$cva->FETCH(PDO::FETCH_ASSOC))
			{
				$docu=$fcva['documento_inv'];
				$apli=$fcva['APLICADO'];
				$comentario.="$docu  || $apli\\n";

			}
			$ncva=$cva->rowCount();
			if($ncva!=0)
			{
				echo "<script>alert('$comentario')</script>";
				echo "<script>location.replace('habilita_sys.php')</script>";
			}else
			{
				$conexion2->query("declare @id int=(select id_registro from registro where barra='$barra')


delete from desglose where registro=@id

update registro set fecha_desglose='',desglosado_por='',digita_desglose='',documento_inv_consumo='',documento_inv_ing='' where barra='$barra' 


")or die($conexion2->error());
		echo "<script>alert('HABILITADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('habilita_sys.php')</script>";

			}



		}
		//echo "<script>alert('$barra')</script>";

		//validacion de documentos
	}


	}else if($opc==2)
	{
		//echo "<script>alert('$liq_consumo -> $liq_ing $aplicaliq -> $ajn_liq-$ajp_liq')</script>";

		if($aplicaliq=='SI')
		{
			//liquidacion aplicada
			$ql=$conexion2->query("select * from liquidaciones where documento_inv_consumo='liq_consumo' and documento_inv_ing='$liq_ing'")or die($conexion2->error());
			$nql=$ql->rowCount();
			if($nql!=0)
			{
			//echo "<script>alert('$ajp_liq - $ajn_liq $liq_consumo $liq_ing')</script>";
				$qajn=$conexion1->query("select * from consny.audit_trans_inv where aplicacion='$ajn_liq'")or die($conexion1->error());
				$nqajn=$qajn->rowCount();
				if($nqajn!=0)
				{
					$qajp=$conexion1->query("select * from consny.audit_trans_inv where aplicacion='$ajp_liq'")or die($conexion1->error());
					$nqajp=$qajp->rowCount();
					if($nqajp!=0)
					{
						//echo "<script>alert('ELIMINAR LIQUIDACION APLICADO')</script>";

					}else
					{
					echo "<script>alert('$ajp_liq  NO HA SIDO APLICADO')</script>";
					echo "<script>location.replace('habilita_sys.php')</script>";

					}
				}else
				{
					echo "<script>alert('$ajn_liq  NO HA SIDO APLICADO')</script>";
					echo "<script>location.replace('habilita_sys.php')</script>";
				}
			}

		}else
		{
			//liquidacion no aplicada
			$cl=$conexion2->query("select * from liquidaciones where documento_inv_consumo='$liq_consumo' and documento_inv_ing='$liq_ing'")or die($conexion2->error());
			$ncl=$cl->rowCount();
			if($ncl==0)
			{
				echo "<script>alert('NO SE ENCONTRO UNA LIQUIDACION CON LOS DOCUMENTOS DIGITADOS')</script>";
				echo "<script>location.replace('habilita_sys.php')</script>";

			}else
			{

				$clv=$conexion1->query("select documento_inv from consny.documento_inv where documento_inv in('$liq_consumo','$liq_ing')
					union all
					select aplicacion from consny.audit_trans_inv where aplicacion in('$liq_consumo','$liq_ing')")or die($conexion1->error());

				$nclv=$clv->rowCount();
				if($nclv!=0)
				{
					echo "<script>alert('UNO O LOS DOS DE LOS DOCUMENTOS NO SE HA BORRADO O FUE APLICADO')</script>";
					echo "<script>location.replace('habilita_sys.php')</script>";
				}else
				{
		//echo "<script>alert('$liq_consumo - $liq_ing')</script>";

					$conexion2->query("declare @consumo varchar(250)='$liq_consumo'
declare @ing varchar(250)='$liq_ing'

insert into liquidaciones_eliminadas(id_liquidacion,autoriza,fecha,fechaingreso,usuario,paquete,
bodega,art_origen,art_destino,cantidad,sessiones,documento_inv_consumo,documento_inv_ing,
estado,precio_origen,precio_destino,digitado,observacion,ajn_eliminacion,ajp_eliminacion)

select id_liquidacion,autoriza,fecha,fechaingreso,usuario,paquete,bodega,art_origen,
art_destino,cantidad,sessiones,documento_inv_consumo,documento_inv_ing,estado,precio_origen,
precio_destino,digitado,observacion,'','' from   liquidaciones where documento_inv_consumo=@consumo and 
documento_inv_ing=@ing

delete from liquidaciones where documento_inv_consumo=@consumo and 
documento_inv_ing=@ing


")or die($conexion2->error());
		echo "<script>alert('ELIMINADA CORRECTAMENTE')</script>";
		echo "<script>location.replace('habilita_sys.php')</script>";
				}
			}
		}
	}else if($opc==3)
	{
		if($averia_aplicado=='SI')
		{
			$ca=$conexion1->query("select * from consny.audit_trans_inv where aplicacion='$averia_doc'")or die($conexion1->error());
			$nca=$ca->rowCount();
			if($nca==0)
			{
				echo "<script>alert('DOCUMENTO $averia_doc no HA SIDO APLICADO')</script>";
				echo "<script>location.replace('habilita_sys.php')</script>";
			}else
			{
				$caj=$conexion2->query("select * from consny.audit_trans_inv wher(e aplicacion='$ajuste_averia'")or die($conexion1->error());
								$ncaj=$caj->rowCount();
								if($ncaj==0)
								{
									echo "<script>alert('DOCUMENTO DE AJUSTE DE LA AVERIAS NO LO HAN APLICADO')</script>";
									echo "<script>location.replace('habilita_sys.php')</script>";
								}else
								{
									$conexion2->query("insert into averias_eliminadas(id_averias,usuario,paquete,bodega,fecha,fecha_hora_crea,tipo,estado,articulo,
				precio,cantidad,marchamo,digitado,observacion,session,documento_inv,documento_ajuste)
				
				select id,usuario,paquete,bodega,fecha,fecha_hora_crea,tipo,estado,articulo,precio,
				cantidad,marchamo,digitado,observacion,session,documento_inv,'$ajuste_averia' from averias
				where documento_inv='$averia_doc'
				delete from averias where documento_inv='$averia_doc'
				")or die($conexion2->error());//insert
								echo "<script>alert('ELIMINADO CORRECTAMENTE')</script>";
								ECHO "<script>location.replace('habilita_sys.php')</script>";
								}
							}
						}else
						{
							$cav=$conexion1->query("select documento_inv from consny.documento_inv where documento_inv='$averia_doc'
								union all select aplicacion from consny.audit_trans_inv where aplicacion='$averia_doc'")or die($conexion1->error());
							$ncav=$cav->rowCount();
							if($ncav==0)
							{
								$conexion2->query("insert into averias_eliminadas(id_averias,usuario,paquete,bodega,fecha,fecha_hora_crea,tipo,estado,articulo,
				precio,cantidad,marchamo,digitado,observacion,session,documento_inv,documento_ajuste)
				
				select id,usuario,paquete,bodega,fecha,fecha_hora_crea,tipo,estado,articulo,precio,
				cantidad,marchamo,digitado,observacion,session,documento_inv,'$ajuste_averia' from averias
				where documento_inv='$averia_doc'
				delete from averias where documento_inv='$averia_doc'
				")or die($conexion2->error());//insert
								echo "<script>alert('ELIMINADO CORRECTAMENTE')</script>";
								ECHO "<script>location.replace('habilita_sys.php')</script>";

							}else
							{
								echo "<script>alert('$averia_doc  NO HAN ELIMINADO DEL PAQUETE O YA FUE APLICADO REVISAR')</script>";
								ECHO "<script>location.replace('habilita_sys.php')</script>";
							}


		}
	}
}//if post 
?>
</body>
</html>