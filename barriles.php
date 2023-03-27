<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
	
	<script>
	function producidos()
	{
		$(".detalle").show();
	}
	function cerrar()
	{
		$(".detalle").hide();
	}
	function seleccion()
	{
		var n=$("#n").val();
		var encuentra=0;
		var num=0;
		$("#producido").val('');
		while(num<=n)
		{
		var t="#persona"+num;
		if($(t).is(':checked'))
		{
			if(encuentra==0)
			{
				$("#producido").val($(t).val());
				encuentra++;
			}else
			{
				$("#producido").val($("#producido").val()+','+$(t).val());
			}
		}
		num++;

		}
		$(".detalle").hide();
	}

	function calcular()
	{
		var peso=$("#pesoc").val();
		var tpeso=(peso/2.2)-19;
		$("#peso").val(tpeso.toFixed(2));
	}
	</script>
</head>
<body>
<?php
include("conexion.php");
?>
<div class="detalle" style="margin-top:-3.5%; display: none;">
	<a href="#" onclick="cerrar()" style="float: right; margin-right: 0.5%; text-decoration: none; color: white;">Cerrar X</a>
	<div class="adentro" style="margin-left: 2.5%; height: 94%; width: 95%;">
<?php
$c=$conexion1->query("select * from PRODUCCION_ACCPERSONAL where PRODUCE='1' and activo='1' order by nombre")or die($conexion1->error());
$n=0;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$nom=$f['NOMBRE'];
	echo "<label style='cursor:pointer;'><input type='checkbox' name='persona$n' id='persona$n' value='$nom'> $nom</label><br><br>";
	$n++;
}
echo "<input type='hidden' name='n' id='n' value='$n'>";
?>
<button class="boton2" style="float: right; margin-right: 0.5%; position: sticky; right: 0; bottom: 0; padding:2%;" onclick="seleccion()">OK</button>
	</div>
</div>

<form method="POST" autocomplete="off" style="width: 70%; background-color: white; height: 80%; border:groove; border-color: blue; margin-left: 10%;">
	<h3 style="text-align: center;">INGRESO DE MERCADERIA EN PROCESO</h3>
<input type="text" name="producido" id="producido" ondblclick="producidos()" class="text" required readonly placeholder="PRODUCIDO POR"><BR>
<br>
<select name="producto" id="producto" class="text" required style="width: 90.8%;">
	<option value="">PRODUCTO</option>
<option>CINCHOS</option>
	<option>ADULTO C/V P/TRABAJAR</option>
<option>ADULTO C/V REVISADO</option>
<option>BIG SIZE</option>
<option>CAMA</option>
<option>CAMISAS A CUADROS</option>
<option>COMANDO</option>
<option>DAMA</option>
<option>DEPORTIVO MANGA LARGA</option>
<option>DEPORTIVO P/TRABAJAR</option>
<option>FALDA</option>
<option>GORRAS</option>
<option>HOMBRE P/TRABAJAR</option>
<option>HOMBRE</option>
<option>INTERIOR</option>
<option>JEANS</option>
<option>LICRA</option>
<option>MATERNIDAD</option>
<option>MEDICO</option>
<option>MIX DE PLAYERA</option>
<option>MIXTO</option>
<option>NIÑO</option>
<option>PALAZZO + ENTERIZO</option>
<option>SACOS DE VESTIR</option>
<option>SHORT PREMIUM (H)</option>
<option>SHORT SEXI (D)</option>
<option>VARIOS</option>
<option>VESTIDO</option>
<option>ZAPATOS</option>

</select><BR><BR>

<select name='bodega' class="text" style="width: 90.8%;" required>
	<option value="">BODEGA</option>
	<?php
		$cb=$conexion1->query("select * from consny.bodega where bodega='SM00'  ORDER BY BODEGA")or die($conexion1->error());
		while($fcb=$cb->FETCH(PDO::FETCH_ASSOC))
		{
			$bod=$fcb['BODEGA'];
			$nom=$fcb['NOMBRE'];
			echo "<option value='$bod'>$bod: $nom</option>";
		}
		$hoy=date("Y-m-d");
	?>
</select>

<br></br>

<input type='text' onkeyup='calcular()' name='pesoc' id='pesoc' placeholder="CALCULAR PESO" class="text">
<br><br>
<input type="number" name="peso" id="peso" class="text" step="any" placeholder="PESO" required>
<br><br>
<input type="number" name="digitado" placeholder="# DIGITADO POR" class="text">
<br></br>
<input type="date" name="fecha" value="<?php echo $hoy;?>" class='text' required>
<br><br>

<input type="submit" name="btn" value="GUARDAR" class="boton3" style=" float: right; margin-right: 0.5%;">
<br><br>
</form>

<?php
if($_POST and $_SESSION['usuario']!='')
{
	extract($_REQUEST);
	$deposito='BARRILES';
	$usuario=$_SESSION['usuario'];
	$paquete=$_SESSION['paquete'];

	$ct=$conexion2->query("select max(sessiones) as id from trabajo")or die($conexion2->error());
	$fct=$ct->FETCH(PDO::FETCH_ASSOC);
	$id=$fct['id']+1;
	$k=0;
	while($k==0)
	{
		$cv=$conexion2->query("select * from trabajo where sessiones='$id'")or die($conexion2->error());
		$ncv=$cv->rowCount();
		if($ncv==0)
		{
			$k=1;
		}else
		{
			$k=0;
			$id++;
		}
	}
	if($producido=='')
	{
		$producido='BODEGA SAN MIGUEL';
	}
	$qp=$conexion1->query("select * from PRODUCCION_ACCPERSONAL where codigo='$digitado' and activo='1'")or die($conexion1->error());
	$nqp=$qp->rowCount();
	if($nqp==0)
	{
		echo "<script>alert('EL CODIGO DE DIGITADOR ES INCORRECTO INTENTA INGRESAR LA INFORMACION NUEVAMENTE')</script>";
		echo "<script>location.replace('barriles.php')</script>";
	}else
	{
		$fqp=$qp->FETCH(PDO::FETCH_ASSOC);
		$digitado=$fqp['NOMBRE'];
		$conexion2->query("insert into trabajo(producido,deposito,articulos,peso,fecha,fecha_ingreso,usuario,paquete,estado,bodega,digitado,sessiones) values('$producido','$deposito','$producto','$peso','$fecha',getdate(),'$usuario','$paquete','0','$bodega','$digitado','$id')")or die($conexion2->error());
	echo "<script>alert('GUARDADO CORRECTAMENTE\\nNUMERO DE ID: $id')</script>";
	echo "<script>location.replace('barriles.php')</script>";
	}
	



}
?>
</body>
</html>