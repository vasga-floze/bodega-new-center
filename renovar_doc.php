<?php
include("conexion.php");
$c=$conexion2->query("select top 2000 barra,documento_inv_consumo,documento_inv_ing from registro where (fecha_desglose!='' or fecha_desglose is not null) and documento_inv_consumo='CON-0000214003' group by barra,documento_inv_consumo,documento_inv_ing")or die($conexion2->error());

echo "<table border='1'>";
echo "<tr>
<td>barra</td>
<td>consumo actual</td>
<td>ing actual</td>
<td>exactus consumo</td>
<td>exactus ing</td>
</tr>";
$n=0;
while($f=$c->FETCH(PDO::FETCH_ASSOC))
{
	$n++;
	$barra=$f['barra'];
	$con=$f['documento_inv_consumo'];

	$ing=$f['documento_inv_ing'];

	$cl=$conexion1->query("select documento_inv from consny.documento_inv where referencia like '%$barra%' and consecutivo='CONSUMO' group by documento_inv")or die($conexion1->error());
	$ncl=$cl->rowCount();
	if($ncl==0)
	{
		$ca=$conexion1->query("select aplicacion from consny.audit_trans_inv where referencia like '%$barra%' and consecutivo='CONSUMO' group by aplicacion")or die($conexion1->error());
		$fca=$ca->FETCH(PDO::FETCH_ASSOC);
		$consumo_ex=$fca['aplicacion'];
	}else
	{
		$fcl=$cl->FETCH(PDO::FETCH_ASSOC);
		$consumo_ex=$fcl['documento_inv'];
	}

	$cl1=$conexion1->query("select documento_inv from consny.documento_inv where referencia like '%$barra%' and consecutivo='ING' group by documento_inv")or die($conexion1->error());
	$ncl1=$cl1->rowCount();
	if($ncl1==0)
	{
		$ca1=$conexion1->query("select aplicacion from consny.audit_trans_inv where referencia like '%$barra%' and consecutivo='ING' group by aplicacion")or die($conexion1->error());

		$fca1=$ca1->FETCH(PDO::FETCH_ASSOC);
		$ing_ex=$fca1['aplicacion'];
	}else
	{
		$fcl1=$cl1->FETCH(PDO::FETCH_ASSOC);
		$ing_ex=$fcl1['documento_inv'];
	}

	echo "<tr>
<td>$barra -> $n</td>
<td>$con</td>
<td>$ing</td>
<td>$consumo_ex</td>
<td>$ing_ex</td>
</tr>";
if($consumo_ex!='' and $ing_ex!='')
{
	$conexion2->query("update registro set documento_inv_consumo='$consumo_ex',documento_inv_ing='$ing_ex' where barra='$barra'")or die($conexion2->error());
}


}
?>