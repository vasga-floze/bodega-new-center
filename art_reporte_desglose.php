<div class="detalle" style="width: 110%; height: 110%; margin-left: -10%; margin-top: -5%; display: none;">
	<a href="#" onclick="cerrar()" style="margin-right: 2%; float: right; color: white; text-decoration: none; ">CERRAR X</a>
<div class="adentro" style="margin-top: 5%; margin-left: 10%; margin-top: 1%; width: 88%; ">
<form method="POST">
<input type="text" name="b" class="text" style="width: 30%; margin-left: 3%;" placeholder="ARTICULO/DESCRIPCION">
<input type="submit" name="btn" value="BUSCAR." class="btnfinal" style="padding: 0.5%; background-color: #5C8E70;">
	
</form>
<?php
echo "<div style='display:none;'>"
include("conexion.php");
echo "</div>";
if($_POST)
{
	extract($_REQUEST);
	if($btn=='BUSCAR.')
	{
		$c=$conexion1->query("select * from consny.articulo where articulo='$b' or descripcion like (SELECT '%'+REPLACE('$b',' ','%')+'%') and activo='S' and clasificacion_1!='detalle'")or die($conexion1->error());
	}else
	{
		$c=$conexion1->query("select * from consny.articulo where activo='s' and clasificacion_1!='detalle'")or die($conexion1->error());
	}
	$n=$c->rowCount();

	echo "<table border='1' cellpadding='10'>";
	echo "<tr>
		<td>ARTICULO</td>
		<td>DESCRIPCION</td>
	     </tr>";
	   while($f=$c->FETCH(PDO::FETCH_ASSOC))
	   {
	   	$art=$f['ARTICULO'];
	   	$desc=$f['DESCRIPCION'];
	   		echo "<tr>
		<td>$art</td>
		<td>$desc</td>
	     </tr>";
	   }

}
?>



</div>
	
</div>