<?php
if($_POST)
{
	extract($_REQUEST);

	include("conexion.php");

$conte=$_SESSION['contenedor'];
	$fecha=$_SESSION['fecha'];
	$usu=$_SESSION['usuario'];
	$paque=$_SESSION['paquete'];
$c=$conexion2->query("select * from registro where contenedor='$conte' and fecha_documento='$fecha' and tipo='C1'")or die($conexion2->error);
$f=$n=$c->rowCount();
if($n==0)
{
	echo "<script>alert('SE PRODUJO UN ERROR INTENTELO NUEVAMENTE')</script>";
	echo "<script>location.replace('contenedor.php')</script>";
}else
{
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$bode=$f['bodega'];
}
if($op==1)
{
	

	$con=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.DESCRIPCION FROM consny.articulo inner join consny.EXISTENCIA_BODEGA ON consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO AND consny.EXISTENCIA_BODEGA.BODEGA='$bode' AND consny.ARTICULO.ACTIVO='S' AND consny.ARTICULO.CLASIFICACION_1!='DETALLE' WHERE consny.ARTICULO.ARTICULO='$cod'")or die($conexion1->error);
$ncon=$con->rowCount();
if($ncon==0)
{
	echo "<script>alert('NO SE ENCONTRO NINGUN REGISTRO DISPONIBLE DE: $cod')</script>";
	echo "<script>location.replace('contenedor.php')</script>";
}else
{
	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
	$art=$fcon['ARTICULO'];
	echo "<script>location.replace('contenedor.php?art=$art')</script>";
}

}else if($btn=="Add.")
{
	$con=$conexion2->query("select max(registro.session)as sessione from registro where tipo='CD' and fecha_documento='$fecha'")or die($conexion2->error);
	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
	$ns=$fcon['sessione'] +1;
	if($ns>=1 and $ns<10)
	{
		$nus="00$ns";
	}else if($ns>=10 and $ns<100)
	{
		$nus="0$ns";
	}else if($ns>=100)
	{
		$nus=$ns;
	}

	$letra=chr(rand(ord("A"), ord("Z")));
	$e=substr($fecha,0);
	$a="$e[2]$e[3]";
	$m="$e[5]$e[6]";
	$d="$e[8]$e[9]";
	$o="C";
	$barra="$letra$a$o$d$nus$m";

	$num=1;
	while($num!=0)
	{
		$k=$conexion2->query("select * from registro where barra='$barra'")or die($conexion2->error);
		$nk=$k->rowCount();
		if($nk!=0)
		{
			$ns=$ns+1;
			if($ns>=1 and $ns<10)
	{
		$nus="00$ns";
	}else if($ns>=10 and $ns<100)
	{
		$nus="0$ns";
	}else if($ns>=100)
	{
		$nus=$ns;
	}
	$barra="$letra$a$o$d$nus$m";
	$num=1;
			//final while
		}else
		{
			$num=0;
		}
	}
	$fecha_hora=date("Y-m-d h:i:s");
	//echo "<script>alert('$fecha_hora')</script>";
	$conexion2->query("insert into registro(fecha_documento,contenedor,codigo,cantidad,bodega,tipo,estado,paquete,usuario,barra,session,peso,fecha_ingreso,bodega_produccion) values('$fecha','$conte','$cod','1','$bode','CD','0','$paque','$usu','$barra','$ns','$peso','$fecha_hora','$bode')")or die($conexion2->error);
	if($cant>1)
	{
		$cant1=$cant -1;
		$ni=1;
while($ni <=$cant1)
{
$con=$conexion2->query("select max(registro.session)as sessione from registro where tipo='CD' and fecha_documento='$fecha'")or die($conexion2->error);
	$fcon=$con->FETCH(PDO::FETCH_ASSOC);
	$ns=$fcon['sessione'] +1;
	if($ns>=1 and $ns<10)
	{
		$nus="00$ns";
	}else if($ns>=10 and $ns<100)
	{
		$nus="0$ns";
	}else if($ns>=100)
	{
		$nus=$ns;
	}

	$letra=chr(rand(ord("A"), ord("Z")));
	$d=date("d");
	$m=date("m");
	$a=date("y");
	$o="C";
	$barra="$letra$a$o$d$nus$m";
	$num=1;
	while($num!=0)
	{
		$k=$conexion2->query("select *from registro where barra='$barra'")or die($conexion2->error);
		$nk=$k->rowCount();
		if($nk!=0)
		{
			$ns=$ns+1;
			if($ns>=1 and $ns<10)
	{
		$nus="00$ns";
	}else if($ns>=10 and $ns<100)
	{
		$nus="0$ns";
	}else if($ns>=100)
	{
		$nus=$ns;
	}
	$barra="$letra$a$o$d$nus$m";
	$num=1;
			//final while
		}else
		{
			$num=0;
		}
	}
	$conexion2->query("insert into registro(fecha_documento,contenedor,codigo,cantidad,bodega,tipo,estado,paquete,usuario,barra,session,peso,fecha_ingreso,bodega_produccion) values('$fecha','$conte','$cod','1','$bode','CD','0','$paque','$usu','$barra','$ns','$peso','$fecha_hora','$bode')")or die($conexion2->error);
	$ni++;
}
echo "<script>location.replace('contenedor.php?f=a')</script>";
	}else
	{
		echo "<script>location.replace('contenedor.php')</script>";
	}

	
 

}else
{
	echo "<script>location.replace('contenedor.php')</script>";
}

}
?>