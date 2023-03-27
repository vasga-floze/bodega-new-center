<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta charset="utf-8">
<?php
ini_set('max_execution_time', 9000);
    try {
        $conexion1 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=eximp600", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("!!ERROR!! SE PERDIO CONEXION CON EL SERVIDOR: " );
    }

    try {
        $conexion2 = new PDO("sqlsrv:Server=192.168.0.44\serverpet620;Database=pruebabd", "sa", "$0ftland");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }
   
    
     header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=produccion-mesa.xls');
	$f=$_GET['f'];
    $c=$conexion2->query("select * from trabajo where fecha ='$f' and deposito is not null")or die($conexion2->error());
   echo "<table border='1' cellpadding='10'>";
   echo "<tr>
   <td>FECHA</td>
   <td>PRODUCIDO</td>
   <td>MESA</td>
   <td>DEPOSITO</td>
   <td>ARTICULO</td>
   <td>PESO</td>
   <td>CANTIDAD</td>
   <td>TOTAL</td>
   <td>OBSERVACION</td>
   </tr>";
   $total=0;
   $t=0;
   while($f=$c->FETCH(PDO::FETCH_ASSOC))
   {
   	$total=$f['peso'] * $f['cantidad'];
   	$t=$t + $total;
   	  echo "<tr>
   <td>".$f['fecha']."</td>
   <td>".$f['producido']."</td>
   <td>".$f['mesa']."</td>
   <td>".$f['deposito']."</td>
   <td>".$f['articulos']."</td>
   <td>".$f['peso']."</td>
   <td>".$f['cantidad']."</td>
   <td>$total</td>
   <td>".$f['observacion']."</td>
   </tr>";
   }
   echo "<tr>
   <td colspan='7'>TOTAL</td>
   <td>$t</td>
   <td></td>
   </tr>";
?>