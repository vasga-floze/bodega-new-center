<?php
if($_POST)
{
	include("conexion.php");
	extract($_REQUEST);
	$n=0;
	$ct=$conexion2->query("select max(sessiones) as session from traslado")or die($conexion2->error());
	$fct=$ct->FETCH(PDO::FETCH_ASSOC);
	$doc=$fct['session']+1;
	$usu=strtoupper($_SESSION['usuario']);
	$paquete=$_SESSION['paquete'];
	while($num>=$n)
	{
		if($re[$n]!='')
			$id=$re[$n];
			$c=$conexion2->query("select * from registro where id_registro='$re[$n]'")or die($conexion2->error());
			$f=$c->FETCH(PDO::FETCH_ASSOC);
			$nc=$c->rowCount();
			$id=$f['id_registro'];
			$origen='SM00';
			$destino='CA00';
			$art=$f['codigo'];
			if($nc!=0)
			{
				$conexion2->query("insert into traslado(registro,destino,origen,documento_inv,paquete,usuario,estado,sessiones,articulo,fecha_ingreso) values('$id','$destino','$origen','- -','$paquete','$usu','0','$doc','$art',getdate())")or die($conexion2->error());
			}
			
		$n++;
	}
	$_SESSION['traslado']=$doc;
	echo "<script>location.replace('trasladob.php')</script>";
}else
{
	echo "<script>location.replace('traslado_multiple.php')</script>";
}
?>