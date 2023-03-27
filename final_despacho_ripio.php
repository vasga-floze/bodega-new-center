<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<?php
include("conexion.php");
if($_POST)
{
	extract($_REQUEST);
	$doc=$_SESSION['doc_ripio'];
	$user=$_SESSION['usuario'];
	$paquete=$_SESSION['paquete'];
	if($btn=='CANCELAR')
	{
		$conexion2->query("delete from traslado_ripio where session='$doc' and usuario='$user'")or die($conexion2->error());
		echo "<script>alert('CANCELADO CORRECTAMENTE')</script>";
		echo "<script>location.replace('despacho_ripio.php')</script>";
	}else  if($btn=='FINALIZAR')
	{   
		$c=$conexion2->query("select * from traslado_ripio where session='$doc' and usuario='$user'")or die($conexion2->error());
		while($f=$c->FETCH(PDO::FETCH_ASSOC))
		{
			$id=$f['ripio'];
			$conexion2->query("update ripio set fecha_despacho='$fecha',fecha_hora_despacho=getdate(),despacho='S',usuario_despacho='$user' where id='$id'")or die($conexion2->error());
		}
		$conexion2->query("update traslado_ripio set fecha='$fecha' where session='$doc' and usuario='$user'")or die($conexion2->error());

		echo "<script>location.replace('imprimir_despacho_ripio.php?doc=$doc&&user=$user')</script>";
	}
}
?>