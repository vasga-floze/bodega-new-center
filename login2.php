<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script>
	$(document).ready(function(){
	$("#u").hide();
	$("#c").hide();
	$("#con").css("margin-top","7%");
	
	});
	function ver()
	{
		if($("#usu").val()=='')
		{
			$("#u").hide(800);
		}else
		{
			$("#u").show(800);
		}
	}

	function ver1()
	{
		if($("#con").val()=='')
		{
			$("#con").css("margin-top","7%");
			$("#c").hide(800);
		}else
		{
			$("#con").css("margin-top","0%");
			$("#c").show(900);
		}
	}
</script>

</head>
<center>
<body style="background-color: #D8D8D8;">
<img src="avatar.png" width="9%" height="9%" style="margin-top: 0.9%;">
<div class="login2">
	<form method="POST">
		<br>
		<label>
			<p style="margin-bottom: 0.7%; text-align: center;" id="u">USUARIO</p>
			<input type="text" name="usu" id="usu" placeholder="USUARIO" class="text" autocomplete="off" onkeyup="ver()" style="padding-bottom: 1%; padding-top: 1%;">
		</label>

		<br>
		<label>
			<p style="margin-bottom: 0.7%; text-align: center;" id="c">CONTRASEÑA</p>
			<input type="password" name="con" id="con" placeholder="CONTRASEÑA" class="text"  onkeyup="ver1()" style="padding-bottom: 1%; padding-top: 1%;">
		</label>
		<input type="submit" name="" value="ENTRAR" class="boton2">
	</form>
</div>

</body>
</html>