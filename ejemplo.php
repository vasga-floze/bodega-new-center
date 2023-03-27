<style type="text/css">
	.load 
	{
		width: 100%;
		height: 100%;
	}
</style>
<script type="text/javascript">

</script>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>

<div style="display: none;">
<img src="cargar.gif" width="100%" height="100%">
	
</div>

<body>
	<button style="background-image: url('logo.png'); width: 10%; height: 10%; background-repeat: no-repeat; background-position: center; background-size: 100% 100%; color: green; font-size: 40px; text-align: center;" class="boton1-1">
		nuevo
	</button>
	<form method="POST">
		<input type="email" name="">
		<input type="date" name="fecha">
	</form>
</body>
<?php


echo "<div style='display:none;'>";
include("conexion.php");
//$_SESSION['doc']=1112;
//$_SESSION['id']=200;
$_SESSION['inv']=4;
echo "</div>";
//$_SESSION['id']=137;// <- para mesa.php
$c=$conexion2->query("select COUNT(barra) as num,barra from registro group by barra")or die($conexion2->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$num=$f['num'];
	$barra=$f['barra'];
	if($num>1)
	{
		echo "$barra<br>";
	}else
	{ 
		//
	}
}
$hoy = getdate();
		//print_r($hoy['month']);


/*$c=$conexion2->query("select * from registro where barra='B19C0725011' or barra='E19C0725111' OR barra='R19C0624811'")or die($conexion2->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
$cod=$f['codigo'];
$barra=$f['barra'];
$ca=$conexion1->query("select * from consny.articulo where articulo='$cod'")or die($conexion1->error());
$fca=$ca->FETCH(PDO::FETCH_ASSOC);
$art=$fca['ARTICULO'];
$de=$fca['DESCRIPCION'];
$text="$art: $de";
$de=substr($text, 0,30);
echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
echo "<div class='barra'><h2>$de</h2><img src='barcode/barcode.php?text=$barra\n&size=80&codetype=Code39&print=true'/><br></div>";
}*/
//$_SESSION['inv']=2;
//$_SESSION['venta']=5;

/*$_SESSION['usuario']='HARIAS';
$_SESSION['paquete']='';
$_SESSION['tipo']=3;*/
//$_SESSION['doc']=654;

//echo "".$_SESSION['venta']."";
if($_POST)
{
	extract($_REQUEST);

	$ano=substr($fecha, 0,4);
	$mes=substr($fecha, 5);
	$dia=substr($fecha, 7);
	$mes=substr($mes, 0,2);
	$dia=substr($dia, 1,2);
	//echo "año: $ano ; mes: $mes ; dia: $dia";
	$fecha1=strtotime('-1 day',strtotime($fecha));
	$fecha1=date('Y-m-d',$fecha1);

	echo "$fecha1";
}else
{
	$mysql=new mysqli("localhost","root","","respaldo")or die("desconectado");
	$c=$mysql->query("select * from mesa")or die(mysqli_error());
	while($f=mysqli_fetch_array($c))
	{
		//echo $f[1];
	}
}
?>


