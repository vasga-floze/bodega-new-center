<?php
echo "<div style='display:none;'>";
include("conexion.php");
echo "</div>";
$c=$conexion2->query("select * from registro where barra='A19C0503010' or barra='N19C1403111' ")or die($conexion2->error());
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
}
?>