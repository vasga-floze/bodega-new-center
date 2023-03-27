<!DOCTYPE html> <html> <head> <title></title>%; <link rel="stylesheet"
type="text/css" href="style.css"> <script type="text/javascript"
src="jquery-3.4.1.min.js"></script> </head> <body> <?php
include("conexion.php"); $origen=$_GET['origen']; $destino=$_GET['destino'];
$arti=$_GET['art']; ?> <div class="detalle" style="width: 100%; height: 100%;
margin-top: -4.5%;"> <?php echo "<a
href='traslado_piezas.php?art=$arti&&origen=$origen&&destino=$destino'
style='color: white; float: right; margin-right: 1.5%;'>Cerrar X</a><br>"; ?>

	<div class="adentro" style="margin-left: 0.5%; height: 93%; width: 98%;">
		<center>
		<form method="POST">
			<input type="text" name="b" placeholder="ARTICULO O DESCRIPCION" class="text" style="width: 30%;">
			<input type="submit" name="btn" value="BUCAR" class="boton3">
		</form>
		</center>
		<?php
		$empresa=$_SESSION['empresa_tpieza'];
		if($_POST)
		{
			extract($_REQUEST);
			$c=$conexion1->query("select * from $empresa.articulo where articulo='$b' or DESCRIPCION LIKE (SELECT '%'+REPLACE('$b',' ','%')+'%') and activo='s'")or die($conexion1->error());
		}else
		{
			$c=$conexion1->query("select * from $empresa.articulo where activo='s'")or die($conexion1->error());
		}
		$n=$c->rowCount();
		if($n==0)
		{
			echo "<h3>NO SE ENCONTRO ARTICULOS DISPONIBLES</h3>";
		}else
		{
			echo "<table border='1' cellpadding='10' style='margin-left:1%; border-collapse:collapse; width:98%; margin-top:1%;'>";
			echo "<tr>
					<td>ARTICULO</td>
					<td>DESCRIPCION</td>

			</tr>";
			while($f=$c->FETCH(PDO::FETCH_ASSOC))
			{
				$art=$f['ARTICULO'];
				$desc=$f['DESCRIPCION'];
				echo "<tr>
					<td><a href='traslado_piezas.php?art=$art&&origen=$origen&&destino=$destino' style='color:black; text-decoration:none;'>$art</a></td>
					<td><a href='traslado_piezas.php?art=$art&&origen=$origen&&destino=$destino' style='color:black; text-decoration:none;'>$desc</a></td>

			</tr>";
			}
		}
		
		?>
	</div>
</div>
</body>
</html>