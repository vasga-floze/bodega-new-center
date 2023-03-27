<form method="POST">
	<input type="text" name="usu">
	<input type="password" name="pass">
	<input type="submit" name="">
	
</form>
<?php
if($_POST)
{
	session_start();
	extract($_REQUEST);
	$pass=md5($pass);
	$conexion=new mysqli("localhost",'root','','pruebabd')or die(mysqli_error());
	$c=$conexion->query("select * from login where usuario='$usu' and contrasena='$pass'")or die(mysqli_error());
	$n=mysqli_num_rows($c);
	if($n>0)
	{
		$f=mysqli_fetch_array($c);
		$_SESSION['usuario']=$usu;
		if($f['tipo']==1)
		{
			$_SESSION['tipo']='ADMINISTRADOR';
			//redirecionas
			echo "<script>alert('SOS ADMINISTRADOR')</script>";
		}else
		{
			$_SESSION['tipo']='NO ADMINISTRADOR';
			echo "<script>alert('NO SOS ADMINISTRADOR')</script>";
		}
	}
}


?>