<link rel="stylesheet" type="text/css" href="style.css">
<?php
include('conexion.php');
echo "<br><br>";
if($_POST)
{
	extract($_REQUEST);
	if($btn=='GENERAR NUEVO')
	{
		$q=$conexion1->query("select consny.ARTICULO.ARTICULO,consny.ARTICULO.ACTIVO,consny.EXISTENCIA_BODEGA.BODEGA from consny.ARTICULO inner join consny.EXISTENCIA_BODEGA on consny.ARTICULO.ARTICULO=consny.EXISTENCIA_BODEGA.ARTICULO and consny.ARTICULO.ACTIVO='S' where consny.ARTICULO.ARTICULO='$articulo' and consny.EXISTENCIA_BODEGA.BODEGA='$bodega'")or die($conexion1->error);
		$nq=$q->ROWCOUNT();
		if($nq==0)
		{
			echo "<script>alert('EL ARTICULO: $articulo NO SE PUEDE AGREGAR PORQUE NO SE ENCUENTRA DISPONIBLE O NO EXISTE EN LA BODEGA: $bodega')</script>";
			echo "<script>location.replace('nuevobarra.php?b=$barra&&arti=$articulo')</script>";
		}else
		{


		$fe=$fecha;
		$e=substr($fecha,0);
	$a="$e[2]$e[3]";
	$m="$e[5]$e[6]";
	$d="$e[8]$e[9]";
	$c=$conexion2->query("select max(session) as session from registro where fecha_documento='$fecha' and tipo='$tipo'")or die($conexion2->error());
	$f=$c->FETCH(PDO::FETCH_ASSOC);
	$session=$f['session'] + 1;
	$letra=chr(rand(ord("A"), ord("Z")));
	if($session>0 and $session<10)
	{
		$num="00$session";
	}else if($session>9 and $session<100)
	{
		$num="0$session";
	}else
	{
		$num=$session;
	}
	if($tipo=='P')
	{
		$o='P';
		$barras="$letra$o$d$m$num$a";
	}else
	{
		$o='C';
		$barras="$letra$o$d$m$num$a";
	}

	$nk=1;
	while($nk!=0)
	{
		$k=$conexion2->query("select * from registro where barra='$barras'")or die($conexion2->error());
		$nk=$k->rowCount();
		if($nk!=0)
		{
			$session=$session + 1;
	$letra=chr(rand(ord("A"), ord("Z")));
	if($session>0 and $session<10)
	{
		$num="00$session";
	}else if($session>9 and $session<100)
	{
		$num="0$session";
	}else
	{
		$num=$session;
	}
	$barras="$letra$o$d$m$num$a";
	$nk=1;
		}else
	{
		$nk=0;
	}
	
		
	}
	$ns=$session;
	$paquete=$_SESSION['paquete'];
	$usuario=$_SESSION['usuario'];
	$conexion2->query("insert into registro(fecha_documento,contenedor,codigo,cantidad,bodega,tipo,estado,paquete,usuario,barra,session,peso,observacion,fecha_ingreso) values('$fecha','G-BARRA','$articulo','1','$bodega','CD','1','$paquete','$usuario','$barras','$ns','$peso','BARRA GENERADO',getdate())")or die($conexion2->error);;
$c=$conexion1->query("select * from consny.articulo where articulo='$articulo'")or die($conexion1->error());
$f=$c->FETCH(PDO::FETCH_ASSOC);
$de=$f['DESCRIPCION'];
$des ="$articulo: $de";
$de=substr($des, 0,30);

echo "<div class='barra'><h2>$des</h2><img src='barcode/barcode.php?text=$barras\n&size=80&codetype=Code39&print=true'/></div>";

echo "<div class='barra'><h2>$des</h2><img src='barcode/barcode.php?text=$barras\n&size=80&codetype=Code39&print=true'/></div>";

	}
}
}
?>