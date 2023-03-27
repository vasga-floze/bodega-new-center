
<?php
include("conexion.php");
error_reporting(0);
$id=$_GET['id'];
$doc=$_SESSION['doc'];
$i=$_GET['i'];
if($i=="")
{
echo "<script>
	if(confirm('SEGURO DESEA QUITAR EL REGISTRO'))
	{
		location.replace('eliminar.php?id=$id&&i=15')
	}else
	{
		location.replace('traslados.php?')
	}
</script>";
}



if($id=="" or $doc=="")
{
	echo "<script>location.replace('traslados.php')</script>";
}

$c=$conexion2->query("select * from traslado where id='$id' and sessiones='$doc'")or die($conexion2->error);
$n=$c->rowCount();
if($n==0 and $i==15)
{
	echo "<script>location.replace('traslados.php')</script>";
}else
{
	$conexion2->query("delete from traslado where id='$id' and sessiones='$doc'")or die($conexion2->error);
	echo "<script>location.replace('traslados.php')</script>";
}
?>