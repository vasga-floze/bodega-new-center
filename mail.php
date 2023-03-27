<?php
/*include ("phpmailer.php");
include ("smtp.php");

$email_user = "lainez.madrid10@gmail.com";
$email_password = "************";
$the_subject = "Phpmailer prueba";
$address_to = "juan.clainez92@gmail.com";
$from_name = "Evilnapsis";
$phpmailer = new PHPMailer();

// ———- datos de la cuenta de Gmail ——————————-
$phpmailer->Username = $email_user;
$phpmailer->Password = $email_password; 
//———————————————————————–
// $phpmailer->SMTPDebug = 1;
$phpmailer->SMTPSecure = 'ssl';
$phpmailer->Host = "smtp.gmail.com"; // GMail
$phpmailer->Port = 465;
$phpmailer->IsSMTP(); // use SMTP
$phpmailer->SMTPAuth = true;

$phpmailer->setFrom($phpmailer->Username,$from_name);
$phpmailer->AddAddress($address_to); // recipients email

$phpmailer->Subject = $the_subject;	
$phpmailer->Body .="<h1 style='color:#3498db;'>Hola Mundo!</h1>";
$phpmailer->Body .= "<p>Mensaje personalizado</p>";
$phpmailer->Body .= "<p>Fecha y Hora: ".date("d-m-Y h:i:s")."</p>";
$phpmailer->IsHTML(true);

$phpmailer->Send();*/
include("conexion.php");
$barra='GP200710521';
$c=$conexion2->query("select 'DESGLOSE' AS transaccion,registro.barra,concat(EXIMP600.consny.articulo.articulo,': ',
EXIMP600.consny.articulo.descripcion) as art,SUM(ISNULL(lbs,0)+isnull(peso,0)) as peso,registro.bodega from 
registro inner join EXIMP600.consny.articulo on EXIMP600.consny.ARTICULO.ARTICULO=registro.codigo where 
registro.barra='$barra' group by registro.barra,concat(EXIMP600.consny.articulo.articulo,': ',EXIMP600.consny.articulo.descripcion),
registro.bodega")or die($conexion2->error());

$f=$c->FETCH(PDO::FETCH_ASSOC);
$tabla="<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
$tabla.="<tr>
	<td>CODIGO BARRA</td>
	<td>ARTICULO</td>
	<td>PESO</td>

</tr>";
$tabla.="<tr>
	<td>$barra</td>
	<td>".$f['art']."</td>
	<td>".$f['peso']."</td>
</tr>";

$c1=$conexion2->query("select CONCAT(EXIMP600.consny.ARTICULO.ARTICULO,': ',EXIMP600.consny.ARTICULO.DESCRIPCION) as articulo,
desglose.cantidad,desglose.precio,(desglose.cantidad*desglose.precio) as total from desglose inner join 
EXIMP600.consny.ARTICULO on EXIMP600.consny.ARTICULO.ARTICULO=desglose.articulo where 
desglose.registro in(select id_registro from registro where barra='$barra')")or die($conexion2->error());
$tabla.="</table><br><br>";
$tabla.="<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
$tabla.="<tr>
	<td>ARTICULO</td>
	<td>CANTIDAD</td>
	<td>PRECIO</td>
	<td>TOTAL</td>
</tr>";
while($fc1=$c1->FETCH(PDO::FETCH_ASSOC))
{
	$tabla.="<tr>
	<td>".$fc1['articulo']."</td>
	<td>".$fc1['cantidad']."</td>
	<td>".$fc1['precio']."</td>
	<td>".$fc1['total']."</td>
</tr>";

}
$tabla.="</table>";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

//dirección del remitente
$headers .= "From: tienda <coreotienda@newyorkcentersadcv.com>\r\n";


//ruta del mensaje desde origen a destino
$headers .= "Return-path: coreotienda@newyorkcentersadcv.com\r\n";

$asunto="DESGLOSE BARRA $barra";
if(mail('jlainez@newyorkcentersadcv.com,pandrade@newyorkcentersadcv.com', $asunto, $tabla,$headers))
{
	echo "envia";
}else
{
	echo "nell";
}
echo $tabla;