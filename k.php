<!DOCTYPE html>
<html>
<head>
   <title></title>
   <link rel="stylesheet" type="text/css" href="style.css">
   <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
   <script>
      $(document).ready(function(){
         //$("#div").hide();
       // var d=hora();
        

      })
      function hora()
      {
        var d= new Date();
        var dia = d.getDate();
        var mes =d.getMonth();
        var anio=d.getFullYear();
        var hora=(d.getHours());
        var minutos=(d.getMinutes());
        var segundos=(d.getSeconds());
        var t=dia+'-'+mes+'-'+anio+' '+hora+':'+minutos+':'+segundos;
       $("#texto").text(t);
        return t;
      }
      function prueba(e)
      {
        location.replace('k.php?'+e);
      }
      function activar()
      {
         $("#div").toggle();
      }
      setInterval(hora, 1000);
   </script>
 

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=15" />
<style type="text/css">
   .a
   {
      background-color: #A3A7A6; color: black; text-decoration: none;
      padding: 2%;
      padding-right: 90%;
      border-color: red;
      border-collapse: collapse;
      width: 100%;
   }
   .a:hover{
      background-color: #A3A7A6; color: white;
   }
</style>

</head>
<body>
   <?php
   $i=$_GET['i'];
   ?>

   <img src='<?php  if($i==1){ echo "notificar1.png";}else{ echo "notificar.png";}?>' style="float: right; width: 3%; height: 3%; cursor: pointer; position: sticky; top: 0; margin-top: -0.5%;" onclick="activar()">
   <p id="texto" style="float: right; margin-right: 0.4%; background-color: skyblue; color: red; padding: 0.5%; border-color: black; border-radius: 50px 0px 50px 0px; text-align: center; padding-right: 30px; padding-left: 30px; position: sticky; top:0;">f</p>
   <div id='div' style="width: 30%; height: 450px; background-color: black; float: right; position: sticky; top: 7%; margin-top: 2%; margin-right: -3.5%; display: none; overflow-y: scroll; overflow-x: hidden; scrollbar-color:gray CadetBlue; scrollbar-width:thin;">

      <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>
       <br><hr>
      <a href="personal_produccion.php" class="a">PERSONAL</a>
      <br><hr>
       <a href="mesa.php" class="a">MESA</a>

      
   </div>

<?php
//error_reporting(0);
include("conexion.php");


{
  echo "$f[0]<br>";
}
/*$c=$conexion2->query("select  sessiones from traslado group by sessiones order by sessiones")or die($conexion1->error());
///echo "<div style='width:100%; height:300px; overflow:auto; background-color:skyblue;'>";
echo "<div style='margin-top:-5%;'>
<table border='1' style='border-color:black;'>";
echo "<tr style='-webkit-position:sticky;position: sticky; top: 0; background-color:black; color:white;'>
<td>SESSION</td>
<td>USUARIO</td>
<td>TOTAL</td>
</tr>";
while ($f=$c->FETCH(PDO::FETCH_ASSOC)) 
{
   $session=$f['sessiones'];
   $q=$conexion2->query("select usuario from traslado where sessiones='$session' group by usuario")or die($conexion2->error());
   $n=1;
   while($fq=$q->FETCH(PDO::FETCH_ASSOC))
   {
      
         echo "<tr style='color:black;>
      <td>$session</td>
      <td>".$fq['usuario']."</td>
      <td>$n</td>
      </tr>";
      
      
      $n++;
   }
}
echo "</div>";
$c=$conexion2->query("select eximp600.consny.articulo.articulo,eximp600.consny.articulo.descripcion,pruebabd.dbo.registro.barra from eximp600.consny.articulo inner join pruebabd.dbo.registro on eximp600.consny.articulo.articulo=pruebabd.dbo.registro.codigo")or die($conexion2->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
   echo "".$f['barra']."<br>";
}*/

$c=$conexion2->query("select barra,count(barra) as cantidad from registro group by barra")or die($conexion2-error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
   $ca=$f['cantidad'];
   $barra=$f['barra'];
   if($ca>1)
   {
      echo "<hr>$barra<hr>";
   }else
   {

     //echo "<hr>$barra<hr>";
   }
  
}
?>

  <?php
/*$c=$conexion2->query("select * from registro where year(fecha_documento)='2021'")or die($conexion2->error());
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
  $id=$f['id_registro'];
  $bod=$f['bodega_produccion'];
  $barra=$f['barra'];
  $q=$conexion2->query("select top 1 * from traslado where registro='$id'")or die($conexion2->error());
  $fq=$q->FETCH(PDO::FETCH_ASSOC);
  $nq=$q->rowCount();
  $ori=$fq['origen'];
  if($bod!=$ori and $nq!=0)
  {
    echo "<table border='1'><tr>
    <td>$barra</td>
    <TD>$bod</TD>
    <td>$ori</td>
  </tr>";
  }
}*/


$asiento="CG-1234567";
$asiento_estaba=$asiento;
$numero=explode("CG-", $asiento);
if(is_int($numero[1]+0))
{
  $tipo="VERDADERO";
}else
{
  $tipo="FALSO";
}

$t=explode($asiento,"CG-");

$cantidad=strlen($numero[1]);
echo "<script>alert('$t[0]  $t[1] $asiento $tipo $cantidad')</script>";
//$tu=$tipo - $numero[1];
//echo "<hr> $asiento / $t[0] / $numero[1] -- $cantidad --- ($tipo)";
if($t[0]=='CG-' and $cantidad>=7 and $tipo=='VERDADERO' and is_numeric($numero[1]))
{
  echo "<hr>valido $asiento<hr>"; //asiento valido
  
}else 
{
  echo "<hr>error $t[0]<hr>";
  //sCO ESQUEMA
  $usuarios=$_SESSION['usuario'];
  $cesquema=$conexion1->query("select esquema,correotienda from USUARIOBODEGA where USUARIO='$usuarios' and TIPO='TIENDA'
")or die($conexion1->error());
  $fcesquema=$cesquema->FETCH(PDO::FETCH_ASSOC);
  $esquema=$fcesquema['esquema'];
  $cua=$conexion1->query("select max(asiento) asiento from $esquema.asiento_de_diario where tipo_asiento='CG'")or die($conexion1->error());

  $ncua=$cua->rowCount();
  if($ncua==0)
  {
    $cua1=$conexion1-query("select max(asiento) asiento from $esquema.ASIENTO_MAYORIZADO where tipo_asiento='CG'")or die($conexion1->error());
    $fcua1=$cua1->FETCH(PDO::FETCH(PDO::FETCH_ASSOC));
    $asiento=$fcua1['asiento'];
  }else
  {
    $fcua=$cua->FETCH(PDO::FETCH_ASSOC);
    $asiento=$fcua['asiento'];
  }

  $text=explode("CG-", $asiento);
  $nuevo_asiento=$text[1]+1;
  $nuevo_asiento=str_pad($nuevo_asiento,7,'0',STR_PAD_LEFT);
  $asiento="CG-$nuevo_asiento";

  $texto=explode("CG-", $asiento);
  $numero_queda=$texto[1]+1;
  $numero_queda=str_pad($numero_queda,7,'0',STR_PAD_LEFT);
  $queda="CG-$numero_queda";
  echo "NUEVO ASIENTO: ($asiento) queda= $queda";
$correo=$fcesquema['correotienda'];
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$usuarioc=$_SESSION['usuario'];
//dirección del remitente
$headers .= "From: $usuarioc <$correo@newyorkcentersadcv.com>\r\n";


//ruta del mensaje desde origen a destino
$headers .= "Return-path: $correo@newyorkcentersadcv.com\r\n";

$asunto="ASIENTO CG NO VALIDO";
$tabla="SE OBTUVO UN NUMERO DE ASIENTO NO VALIDO $asiento_estaba -> SE BUSCO EL MAXIMO EN ASIENDO DIARIO Y SE OBTUVO -> $asiento y se aactualizo en el paquete cg con $queda";
mail("jlainez@newyorkcentersadcv.com", $asunto, $tabla,$headers);


}// cuando asiento no es valido
  ?>

</body>
</html>
